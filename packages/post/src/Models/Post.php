<?php

namespace Leo\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Leo\Post\Models\PostCate;
class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $fillable = ['id','title','slug','summary','image','id_collection','content','status','highlight','created_at','updated_at',];
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function scopeHighlight($query)
    {
        return $query->where('status', 1)->where('highlight',1);
    }
    public function cates(){
        return $this->belongsTo(PostCate::class,'id_collection');
    }
}
