<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['user_id', 'name', 'description', 'price', 'category_id', 'location', 'contact_info', 'image_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }
}
