<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticsProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'sku',
        'pcs_per_carton',
        'dimension_unit',
        'carton_length',
        'carton_width',
        'carton_height',
        'weight_unit',
        'carton_gross_weight',
        'cbm_per_carton',
        'cbm_per_piece',
        'weight_per_piece',
        'is_manual_cbm',
        'shipping_rate_per_cbm',
        'shipping_cost_per_carton',
        'shipping_cost_per_piece',
    ];
}