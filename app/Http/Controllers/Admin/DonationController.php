<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(){
        return view('admin.donation.index');
    }

    public function filter(Request $request){
        $this->validate($request, [
            'date_from'     => 'required',
            'date_to'       => 'required'
        ]);

        $date_from = $request->date_from;
        $date_to   = $request->date_to;

        //GET DATA DONATION BY RANGE DATE
        $donations = Donation::where('status', 'success')->whereDate('created_at', '>=', $request->date_from)->whereDate('created_at', '<=', $request->date_to)->get();

        //GET TOTAL DONATION BY RANGE DATE
        $total = Donation::where('status', 'success')->whereDate('created_at', '>=', $request->date_from)->whereDate('created_at', '<=', $request->date_to)->sum('amount');

        return view('admin.donation.index', compact('donations', 'total'));
    }
}
