<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use EasyRdf\RdfNamespace;
use EasyRdf\Graph;

trait RDFTrait
{
    use HasFactory;

    public static function setNamespaces()
    {
        $namespaces = \App\Models\rdfnamespace::where('added', '=', '1')->get();
        foreach ($namespaces as $namespace) {
            RdfNamespace::set($namespace->prefix, $namespace->uri);
        }
        return 0;
    }

    public static function uknownNamespace($uri)
    {
        $tempnamespace = new \EasyRdf\Resource($uri);
        $local = $tempnamespace->localName();

        $namespace = mb_substr($uri, 0, -mb_strlen($local));
        $existing = \App\Models\rdfnamespace::where('uri', '=', $namespace)->get();
        if ($existing->isEmpty()) {
            \App\Models\rdfnamespace::create([
                'prefix' => 'null',
                'uri' => $namespace,
                'added' => 0
            ]);
        }
        return $uri;
    }

    public static function mergeGraphs(Graph $graph1, Graph $graph2)
    {
        $data1 = $graph1->toRdfPhp();
        $data2 = $graph2->toRdfPhp();
        $merged = array_merge_recursive($data1, $data2);
        unset($data1, $data2);
        return new Graph('urn:easyrdf:merged', $merged, 'php');
    }

    public static function label(Graph $graph, $uri)
    {
        $label_properties =
            \App\Models\LabelExtractor::where('enabled', '=', '1')
                ->orderBy('priority', 'asc')
                ->get();
        $label = null;
        $locale = Cookie::get('locale');
        foreach ($label_properties as $property) {
            if ($label == null) {
                $label = $graph
                    ->getLiteral(
                        $uri,
                        new \EasyRdf\Resource($property->property),
                        $locale
                    );
            } else {
                break;
            }
            if ($label == null) {
                //get default label in English. This should be configurable on .env
                $label = $graph
                    ->getLiteral(
                        $uri,
                        new \EasyRdf\Resource($property->property),
                        'en'
                    );
            }
            if ($label == null) {
                //if no english label found try a label in any language
                $label = $graph
                    ->getLiteral(
                        $uri,
                        new \EasyRdf\Resource($property->property)
                    );
            }
        }
        if ($label == null) {
            $label = RdfNamespace::shorten($uri, true);
        }
        return $label;
    }
}
