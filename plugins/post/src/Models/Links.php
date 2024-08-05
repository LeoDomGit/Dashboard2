<?php

namespace Leo\Post\Models;
use Leo\Post\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Links extends Model
{
    use HasFactory;
    protected $table = 'links';
    protected $fillable = ['id','id_link','id_parent','type_1','type_2','created_at','updated_at'];
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
