<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Alert;

class CompanySettingController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
        ]);

        if (isset($request->company_logo)) {
            if ($request->hasFile('company_logo')) {
                $file = $request->file('company_logo');
                $imageName = storeImages('public/images/companyLogo/', $file);
                $data['company_logo'] = URL::to('storage/images/companyLogo/' . $imageName);
                CompanySetting::where('slug', 'company_logo')->first()->update(['value' => $data['company_logo']]);
            }
        }

        if (isset($request->company_name)) {
            if ($request->has('company_name')) {
                $data['company_name'] = $request->input('company_name');
                CompanySetting::where('slug', 'company_name')->first()->update(['value' => $data['company_name']]);
            }
        }


        Alert::success('Success', 'Company Data Updated Successfully');
        return redirect()->back();
        
    }
}
