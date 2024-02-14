<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;

class AboutController extends AdminController
{
    protected $title = 'About';
    public function grid()
    {

        return view('about');
    }
}
