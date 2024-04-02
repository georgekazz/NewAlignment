<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;
use EasyRdf\Graph;
use Illuminate\Support\Facades\Auth;
class LinkTypeController extends AdminController
{
    public function getInstances($group)
    {
        $inputs = \App\Models\LinkType::where('group', '=', $group)
                ->where( function($query){
                    $user = Auth::guard('admin')->user();
                    $query->where('public', '=', 'true')
                          ->orWhere('user_id', '=', $user);
                }
                )
                ->get();
        return $inputs;
    }
    
    public function updateForm(Request $request){
        $group = $request->group;
        if(!\Illuminate\Support\Facades\Cache::has($group. '.ontology')){
            $graph = new Graph;
            $graph->parseFile(storage_path() . "/app/ontologies/" . mb_strtolower($group) . '.rdf', 'rdfxml');
            \Illuminate\Support\Facades\Cache::put($group. '.ontology', $graph);
        }
        else{
            $graph = \Illuminate\Support\Facades\Cache::get($group. '.ontology');
        }
        $instances = $this->getInstances($group);
        return view('createlinks.partials.linkinput',["instances" => $instances, "graph" => $graph]);
    }
}
