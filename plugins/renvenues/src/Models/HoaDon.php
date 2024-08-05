<?php

namespace Leo\Renvenues\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'hoa_don';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'transaction_id',
        'note',
        'status',
        'created_at',
        'updated_at',
    ];
}
