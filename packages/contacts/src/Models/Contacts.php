<?php

namespace Leo\Contacts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;
    protected $table='contacts';
    protected $fillable=['id','name','email','phone','status','note','message','created_at','updated_at'];
}
