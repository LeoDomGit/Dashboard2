<?php

namespace Leo\Renvenues\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Leo\Products\Models\Products;
use Leo\Renvenues\Models\HoaDon;
class HoaDon_Detail extends Model
{
    use HasFactory;
    protected $table='hoa_don_chi_tiet';
    protected $fillable=['id','id_hoa_don','id_product','quantity','created_at','updated_at'];

    public function product()
    {
        return $this->belongsTo(Products::class, 'id_product');
    }

    public function bill()
    {
        return $this->belongsTo(HoaDon::class, 'id_hoa_don');
    }
}
