<?php

namespace Leo\Services\Models;

use Leo\Customers\Models\Customers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceBills extends Model
{
    use HasFactory;

    protected $table = 'service_bills';
    protected $fillable = ['id_customer', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'id_customer');
    }

    public function serviceBillsDetails()
    {
        return $this->hasMany(ServiceBillsDetails::class, 'id_bill');
    }
}
