<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';

    protected $fillable = ['member_id', 'admin_id', 'type', 'remarks', 'amount', 'status', 'admin_bank_id', 'member_bank_id'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function adminBank()
    {
        return $this->belongsTo(UserBank::class, 'admin_bank_id', 'id');
    }

    public function memberBank()
    {
        return $this->belongsTo(MemberBank::class, 'member_bank_id', 'id');
    }
}
