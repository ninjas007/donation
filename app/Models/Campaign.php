<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'target_donation',
        'max_date',
        'description',
        'image',
        'user_id'
    ];

    public function categories() {
        return $this->belongsTo(Category::class);
    }

    public function users() {
        return $this->belongsTo(User::class);
    }

    public function donations() {
        return $this->hasMany(Donation::class);
    }

    //METHOD UNTUK MENJUMLAHKAN DONASI

    public function sumDonation() {
        return $this->hasMany(Donation::class)->selectRaw('donations.campaign_id,SUM(donations.amount) as total')->where('donations.status', 'success')->groupBy('donations.campaign_id');
    }

    //UNTUK GET IMAGE

    public function getImageAttribute($image) {
        return asset('storage/campaigns'.$image);
    }
}
