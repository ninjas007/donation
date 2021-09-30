<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index() {
        $campaigns = Campaign::latest()->when(request()->q, function($campaigns){
            $campaigns = $campaigns->where('title', 'like', '%'. request()->q .'%');
        })->paginate(10);

        return view('admin.campaign.index', compact('campaigns'));
    }

    public function create() {
        $categories = Category::latest()->get();
        return view('admin.campaign.create', compact('categories'));
    }

    public function store(Request $request) {

        $this->validate($request, [
            'image'             => 'required|image|mimes:png,jpg,jpeg',
            'title'             => 'required',
            'category_id'       => 'required',
            'target_donation'   => 'required|numeric',
            'max_date'          => 'required',
            'description'       => 'required'
        ]);

        //UPLOAD IMAGE
        $image = $request->file('image');
        $image->storeAs('public/campaigns', $image->hashName());

        $campaign = Campaign::create([
            'title'             => $request->title,
            'slug'              => Str::slug($request->title, '-'),
            'category_id'       => $request->category_id,
            'target_donation'   => $request->target_donation,
            'max_date'          => $request->max_date,
            'description'       => $request->description,
            'user_id'           => auth()->user()->id,
            'image'             => $image->hashName()
        ]);

        if ($campaign) {
            return redirect()->route('admin.campaign.index')->with(['success'   =>  'Data Berhasil Disimpan!']);
        } else {
            return redirect()->route('admin.campaign.index')->with(['error' =>  'Data Gagal Disimpan']);
        }
    }

    public function edit(Campaign $campaign){
        $categories = Category::latest()->get();
        return view('admin.campaign.create', compact('categories', 'campaign'));
    }

    public function update(Request $request, Campaign $campaign){
        $this->validate($request, [
            'title'             => 'required',
            'category_id'       => 'required',
            'target_donation'   => 'required|numeric',
            'max_date'          => 'required',
            'description'       => 'required'
        ]);

        //CEK JIKA IMAGE KOSONG
        if ($request->file('image') == '') {
        
            //UPDATE DATA TANPA IMAGE
            $campaign = Campaign::findOrFail($campaign->id);
            $campaign->update([
                'title'             => $request->title,
                'slug'              => Str::slug($request->title, '-'),
                'category_id'       => $request->category_id,
                'target_donation'   => $request->target_donation,
                'max_date'          => $request->max_date,
                'description'       => $request->description,
                'user_id'           => auth()->user()->id,
            ]);
        } else {
            //DELETE IMAGE LAMA
            Storage::disk('local')->delete('public/campaigns/'.basename($campaign->image));

            //UPLOAD IMAGE BARU
            $image = $request->file('image');
            $image->storeAs('public/campaigns', $image->hashName());

            //UPDATE DATA DENGAN IMAGE BARU
            $campaign = Campaign::findOrFail([
                'title'             => $request->title,
                'slug'              => Str::slug($request->title, '-'),
                'category_id'       => $request->category_id,
                'target_donation'   => $request->target_donation,
                'max_date'          => $request->max_date,
                'description'       => $request->description,
                'user_id'           => auth()->user()->id,
                'image'             => $image->hashName()
            ]);
        }

        if ($campaign) {
            return redirect()->route('admin.campaign.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            return redirect()->route('admin.campaign.index')->with(['error' => 'Data Gagal Diupdate!']);
        }

    }
        public function destroy($id) {
            $campaign = Campaign::findOrFail($id);
            Storage::disk('local')->delete('public/campaigns'.basename($campaign->image));
            $campaign->delete();
        
            if ($campaign) {
                return response()->json([
                    'status'    => 'success'
                ]);
            } else {
                return response()->json([
                    'status'    => 'error'
                ]);
            }
        }
}