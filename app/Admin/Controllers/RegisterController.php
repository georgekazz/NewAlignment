<?php

namespace App\Admin\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenAdmin\Admin\Controllers\AdminController;
use Illuminate\Support\Facades\Hash;


class RegisterController extends AdminController
{
    public function register(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admin_users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create new admin user
        $adminUser = new AdminUser();
        $adminUser->name = $request->name;
        $adminUser->username = $request->username;
        $adminUser->password = Hash::make($request->password);
        $adminUser->save();
        admin_toastr('Your register completed successfully!', 'success', ['duration' => 5000]);

        return redirect()->route('register')->with('success', 'Εγγραφήκατε επιτυχώς!');
    }
}
