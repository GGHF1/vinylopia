<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';
    protected $fillable = [
        'user_id',
        'seller_id',
        'total_amount',
        'status',
        'shipping_address',
        'payment_method',
        'created_at',
        'updated_at'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    
    public function statuses()
    {
        return $this->hasMany(OrderStatus::class, 'order_id')->orderBy('created_at', 'asc');
    }
    
    public function messages()
    {
        return $this->hasMany(OrderMessage::class, 'order_id')->orderBy('created_at', 'asc');
    }
    
    public function getOrderNumber()
    {
        return sprintf('#%08d', $this->order_id);
    }
    
    public function getStatusLabel()
    {
        $labels = [
            'pending' => 'Pending',
            'invoice_sent' => 'Invoice Sent',
            'paid' => 'Paid',
            'in_progress' => 'In Progress',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
        ];
        
        return $labels[$this->status] ?? ucfirst($this->status);
    }
    
    public function getStatusClass()
    {
        $classes = [
            'pending' => 'status-pending',
            'invoice_sent' => 'status-invoice',
            'paid' => 'status-paid', 
            'in_progress' => 'status-progress',
            'shipped' => 'status-shipped',
            'delivered' => 'status-delivered',
            'cancelled' => 'status-cancelled'
        ];
        
        return $classes[$this->status] ?? 'status-default';
    }
}