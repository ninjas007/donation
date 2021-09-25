<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use IlluminateFoundationAuthUser as Authenticatable;

class Donatur extends Authenticatable // ubah extends class ke authnya
{
    use HasFactory;
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function donations() {
        return $this->hasMany(Donation::class);
    }

    //UNTUK MENGUBAH AVA MENJADI NAMA USER

    public function getAvatarAttribute($avatar) {
        if ($avatar != null) {
            return asset('storage/donaturs/'.$avatar);
        } else {
            return 'https://ui-avatars.com/api/?name=' . str_replace(' ', '+', $this->name) . '&background=4e73df&color=ffffff&size=100';
        }
    }
}
