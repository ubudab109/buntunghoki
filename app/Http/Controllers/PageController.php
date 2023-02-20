<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{   

    public function __construct()
    {
        $this->middleware('can:role-management-list', ['only' => 'roles']);
        $this->middleware('can:admin-management-list', ['only' => 'admin']);
        $this->middleware('can:payment-type-list', ['only' => 'paymentType']);
        $this->middleware('can:bank-payment-list', ['only' => 'bank']);
        $this->middleware('can:company-setting-list', ['only' => 'setting']);
    }

    // PAGE DASHBOARD
    public function dashboard()
    {
        return view('pages.dashboard');
    }

    // PAGE ROLE
    public function roles()
    {
        return view('pages.role-list');
    }

    // PAGE ADMIN
    public function admin()
    {
        return view('pages.admin-list');
    }

    // PAGE PAYMENT
    public function paymentType()
    {
        return view('pages.payment-type-list');
    }

    // BANK LIST
    public function bank()
    {
        $paymentTypes = PaymentType::where('status', 1)->get();
        return view('pages.bank-list', compact('paymentTypes'));
    }

    // ADMIN BANK
    public function adminBank(Request $request)
    {
        $paymentTypes = PaymentType::select('id','name')->get();
        $admins = User::select('id','fullname')->get();
        
        return view('pages.admin-bank-list', compact('paymentTypes', 'admins'));
    }

    // COMPANY SETTING
    public function setting()
    {
        $companyName = CompanySetting::where('slug', 'company_name')->first();
        $companyLogo = CompanySetting::where('slug', 'company_logo')->first();

        return view('pages.company-setting', compact('companyName', 'companyLogo'));
    }
}
