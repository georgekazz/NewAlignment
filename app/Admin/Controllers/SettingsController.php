<?php

namespace App\Admin\Controllers;

use App\Models\Settings;
use OpenAdmin\Admin\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use App\Models\SuggestionConfigurations\SilkConfiguration;


class SettingsController extends AdminController
{
    protected $title = 'Settings';

    public function grid()
    {
        $user = Auth::guard('admin')->user();
        $providers = \App\Models\SuggestionProvider::all();

        return view('settings', ['user' => $user, 'providers' => $providers]);
    }

    public function create(\OpenAdmin\Admin\Layout\Content $content)
    {
        $input = request()->all();
        $input = array_filter($input);
        $settings = Settings::create($input);
        $settings->provider->validate($settings);

        admin_toastr('Settings Created!', 'success', ['duration' => 5000]);

        return redirect(admin_url('settings'));
    }

    public function render() {
        $file = "/app/projects/default_config.xml";
        $filename = storage_path() . $file;
        $xml = file_get_contents($filename);
        $silk = new SilkConfiguration();
        if ($silk->validateSchema($file)) {
            $result = $silk->parseXML($xml);
        } else {
            return "Validation error. Your settings file is not a valid Silk LSL settings file";
        }
        foreach ($silk->nodes as $node) {
            dd($silk->getNode($result, $node));
        }
    }
}
