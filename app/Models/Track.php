<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $primaryKey = 'track_id';
    protected $fillable = [
        'vinyl_id',
        'track_number',
        'title',
        'position',
    ];

    public function vinyl()
    {
        return $this->belongsTo(Vinyl::class, 'vinyl_id');
    }
}
