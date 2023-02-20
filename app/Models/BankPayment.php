<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankPayment extends Model
{
    use HasFactory;

    protected $table = 'bank_payments';
    protected $fillable = ['payment_type_id', 'name', 'status'];

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id', 'id');
    }
}
