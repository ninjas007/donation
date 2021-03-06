<?php

namespace App\Http\Controllers\Api;

use Midtrans\Snap;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DonationController extends Controller
{
    public function __construct()
    {
        //SET MIDTRANS CONFIGURATION
        \Midtrans\Config::$serverKey    = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized  = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds        = config('services.midtrans.is3ds');
    }

    public function index()
    {
        //GET DATA DONATIONS
        $donations = Donation::with('campaign')->where('donatur_id', auth()->guard('api')->user()->id)->latest()->paginate(5);

        $response = [
            'success'   => true,
            'message'   => 'List Data Donation : ' . auth()->guard('api')->user()->name,
            'data'      => $donations
        ];

        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        DB::transaction(function() use ($request) {

            //ALGORITMA CETAK NO INVOICE
            $panjang = 10;
            $random  = '';
            for ($i = 0; $i < $panjang; $i++) {
                $random .= rand(0,1) ? rand(0,9) : chr(rand(ord('a'), ord('z')));
            }

            $no_invoice = 'TRX-'.Str::upper($random);

            $campaign = Campaign::where('slug', $request->campaignSlug)->first();

            $donation = Donation::create([
                'invoice'       => $no_invoice,
                'campaign_id'   => $campaign->id,
                'donatur_id'    => auth()->guard('api')->user()->id,
                'amount'        => $request->amount,
                'pray'          => $request->pray,
                'status'        => 'pending,'
            ]);

            //BUAT TRANSAKSI KE MIDTRANS LALU SAVE SNAP TOKEN
            $payload = [
                'transaction_details'   => [
                    'order_id'      => $donation->invoice,
                    'gross_amount'  => $donation->amount,
                ],
                'customer_details'      => [
                    'first_name'    => auth()->guard('api')->user()->name,
                    'email'         => auth()->guard('api')->user()->email,
                ]
            ];

            //BUAT SNAP TOKEN
            $snapToken = Snap::getSnapToken($payload);
            $donation->snap_token = $snapToken;
            $donation->save();

            $this->response['snap_token'] = $snapToken;
        });

        return response()->json([
            'success'   => true,
            'message'   => 'Donasi Berhasil Dibuat!',
            $this->response
        ]);
    }

    public function notificationHandler(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . config('services.midtrans.serverKey'));

        if ($notification->signature_key != $validSignatureKey) {
            return response(['message' => 'Invalid signature'], 403);
        }

        $transaction = $notification->transaction_status;
        $type        = $notification->payment_type;
        $orderId     = $notification->order_id;
        $fraud       = $notification->fraud_status;

        //DATA DONATION
        $data_donation = Donation::where('invoice', $orderId)->first();

        if ($transaction == 'capture') {
            //UNTUK TRANSAKSI KARTU KREDIT, PERLU PENGECEKAN APAKAH TRANSAKSI DITOLAK OLEH FDS ATAU TIDAK
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $data_donation->update([
                        'status' => 'pending'
                    ]);
                } else {
                    $data_donation->update([
                        'status' => 'success'
                    ]);
                }
            }
        } elseif ($transaction == 'settlement') {
            $data_donation->update([
                'status' => 'success'
            ]);
        } elseif ($transaction == 'pending') {
            $data_donation->update([
                'status' => 'pending'
            ]);
        } elseif ($transaction == 'deny') {
            $data_donation->update([
                'status' => 'failed'
            ]);
        } elseif ($transaction == 'expire') {
            $data_donation->update([
                'status' => 'expired'
            ]);
        } elseif ($transaction == 'cancel') {
            $data_donation->update([
                'status' => 'failed'
            ]);
        }
    }
}
