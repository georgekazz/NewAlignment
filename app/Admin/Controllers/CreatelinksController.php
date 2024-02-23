<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;
class CreatelinksController extends AdminController
{
    public function grid()
    {

        return view('createlinks');
    }
}
