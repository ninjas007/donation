<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(){
        //get data campaigns
        $campaigns = Campaign::with('user', 'sumDonation')->when(request()->q, function($campaigns){
            $campaigns = $campaigns->where('title', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);

        $response = [
            'success'   => true,
            'message'   => 'List Data Campaign',
            'data'      => $campaigns,
        ];

        return response()->json($response, 200);
    }

    public function show($slug){
        $campaign = Campaign::with('user', 'sumDonation')->where('slug', $slug)->first();

        $donations = Donation::with('donatur')
            ->where('campaign_id', $campaign->id)
            ->where('status', 'success')
            ->latest()->get();

        if ($campaign) {
            $response = [
                'success'   => true,
                'message'   => 'Detail Data Campaign : '. $campaign->title,
                'data'      => $campaign,
                'donations' => $donations
            ];

            return response()->json($response, 200);
        }

        return response()->json([
            'success'   => false,
            'message'   => 'Data Campaign Tidak Ditemukan',
        ], 404);
    }
}
