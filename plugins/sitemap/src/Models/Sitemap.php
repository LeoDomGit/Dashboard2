<?php

namespace Leo\Sitemap\Models;

use Leo\Customers\Models\Customers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sitemap extends Model
{
    use HasFactory;
    protected $table = 'sitemap';
    protected $fillable = ['id','page','static_page','content','url','status','created_at','updated_at'];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
