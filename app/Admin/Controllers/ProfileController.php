<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Controllers\AdminController;

class ProfileController extends AdminController
{

    protected $title = 'My Profile';

    public function grid()
    {
        $nameJson = DB::table('admin_users')->pluck('name');
        $createdJson = DB::table('admin_users')->pluck('created_at');
        $avatar = DB::table('admin_users')->value('avatar');

        $nameArray = json_decode($nameJson, true);
        $name = reset($nameArray);

        $createdArray = json_decode($createdJson, true);
        $createdAt = reset($createdArray);

        return view('profile', compact('name','createdAt', 'avatar'));
    }
}
