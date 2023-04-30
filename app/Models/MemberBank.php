<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberBank extends Model
{
    use HasFactory;
    protected $table = 'member_banks';

    public function bankPayment()
    {
        return $this->belongsTo(BankPayment::class, 'bank_payment_id', 'id');
    }
}
