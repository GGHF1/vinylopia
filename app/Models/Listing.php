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
        'status',
        'created_at',
        'updated_at'
    ];

    // Define status constants
    const STATUS_LISTED = 'listed';
    const STATUS_PLACED = 'placed';
    const STATUS_SOLD = 'sold';

    public function vinyl()
    {
        return $this->belongsTo(Vinyl::class, 'vinyl_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    // Check if listing is available for purchase
    public function isAvailable()
    {
        return $this->status === self::STATUS_LISTED;
    }

    // Get status label for display
    public function getStatusLabel()
    {
        $labels = [
            self::STATUS_LISTED => 'Available',
            self::STATUS_PLACED => 'Order Pending',
            self::STATUS_SOLD => 'Sold'
        ];
        
        return $labels[$this->status] ?? ucfirst($this->status);
    }
}