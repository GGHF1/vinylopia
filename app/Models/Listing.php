<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    protected $primaryKey = 'listing_id';
    protected $fillable = [
        'price',
        'vinyl_condition',
        'cover_condition',
        'comments',
        'user_id',
        'vinyl_id',
        'created_at',
        'updated_at'
    ];

    public function vinyl()
    {
        return $this->belongsTo(Vinyl::class, 'vinyl_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
