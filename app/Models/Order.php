<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 
        'client_name',      // 🌟 Added for custom invoices
        'client_company',   // 🌟 Added for custom invoices
        'order_number', 
        'status', 
        'payment_status', 
        'payment_method', 
        'currency',
        'exchange_rate',
        'sub_total', 
        'vat', 
        'discount', 
        'grand_total', 
        'shipping_cost', 
        'shipping_amount', 
        'shipping_method', 
        'shipping_address',
        'installation_fee',
        'phone',            
        'transaction_id',   
        'notes'
    ];

    /**
     * The staff or client associated with the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The individual products inside this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}