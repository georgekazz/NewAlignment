<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Layout\Content;

class HomeController extends Controller
{

    public function index(Content $content)
    {
        $content->body(view('dashboard'));
        return $content
            ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
            ->title('Dashboard');

            
    }
}
