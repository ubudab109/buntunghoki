<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBank extends Model
{
    use HasFactory;

    protected $table = 'user_banks';
    protected $fillable = ['user_id', 'payment_type_id', 'bank_payment_id', 'account_name', 'account_number', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id', 'id');
    }

    public function bankPayment()
    {
        return $this->belongsTo(BankPayment::class, 'bank_payment_id', 'id');
    }
}
