<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vinyl extends Model
{
    use HasFactory;

    protected $primaryKey = 'vinyl_id';

    protected $fillable = [
        'title',
        'artist',
        'genre',
        'style',
        'year',
        'label',
        'cover',
        'LP',
        'feat',
    ];

    public function tracks()
    {
        return $this->hasMany(Track::class, 'vinyl_id');
    }
}