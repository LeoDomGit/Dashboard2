<?php

namespace Leo\Post\Models;
use Leo\Post\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostCate extends Model
{
    use HasFactory;
    protected $table = 'post_collections';
    protected $fillable = ['id','name','slug','status','created_at','updated_at'];
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function posts(){
        return $this->hasMany(Post::class,'id_collection');
    }
}
