<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;
use EasyRdf\Graph;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LinkTypeController extends AdminController
{
    public function getInstances($group)
    {
        $inputs = \App\Models\LinkType::where('group', '=', $group)
            ->where(
                function ($query) {
                    $user = Auth::guard('admin')->user();
                    $query->where('public', '=', 'true')
                        ->orWhere('user_id', '=', $user);
                }
            )
            ->get();
        return $inputs;
    }

    public function updateForm(Request $request)
    {
        $group = $request->group;

        $graph = $this->getOntologyFromCacheOrParse($group);
        $instances = $this->getInstances($group);

        return view('createlinks.partials.linkinput', [
            'instances' => $instances,
            'graph' => $graph
        ]);
    }

    /**
     * Ανάκτηση Οντολογίας από την Cache
     *
     * @param string $group
     * @return \EasyRdf\Graph
     */
    private function getOntologyFromCacheOrParse($group)
    {
        $cacheKey = $group . '.ontology';

        if (!Cache::has($cacheKey)) {
            $graph = new Graph;
            $filePath = storage_path('app/ontologies/' . mb_strtolower($group) . '.rdf');
            $graph->parseFile($filePath, 'rdfxml');
            Cache::put($cacheKey, $graph);
        } else {
            $graph = Cache::get($cacheKey);
        }

        return $graph;
    }

}
