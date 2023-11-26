<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function setting()
    {
        if(Auth::user()->role == 'admin') {
        $setting = Setting::first();
        return view('settings.general', compact('setting'));
        }
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'copyright' => 'required'
        ]);

        if ($request->id) {
            $setting = Setting::findOrFail($request->id);
            $setting->name = $request->name;

            if ($request->file('logo')) {
                Storage::delete('public/setting/'.$setting->logo);
                $image = $request->file('logo');
                $imagename = time() . '.' . $request->file('logo')->getClientOriginalName();
                $image->storeAs('public/setting', $imagename);
                $setting->logo = $imagename;
            }

            $setting->copyright = $request->copyright;
            $status = $setting->save();
        }
        else {
            $setting = new Setting();
            $setting->name = $request->name;

            if ($request->file('logo')) {
                $image = $request->file('logo');
                $imagename = time() . '.' . $request->file('logo')->getClientOriginalName();
                $image->storeAs('public/setting', $imagename);
                $setting->logo = $imagename;
            }

            $setting->copyright = $request->copyright;
            $status = $setting->save();
        }

        if ($status) {
            Toastr::success('Setting saved','success');
            return redirect()->back();
        }
        else {
            Toastr::error('Setting failed to save','failed');
            return redirect()->back();
        }
    }
}
