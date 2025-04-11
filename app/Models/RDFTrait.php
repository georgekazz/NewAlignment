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
        // Αν το γράφημα είναι άδειο ή το URI δεν υπάρχει, επιστρέφουμε το URI
        if (!$graph || !$uri) {
            return basename($uri);
        }

        // Λίστα πιθανών properties που μπορεί να περιέχουν ετικέτα
        $labelProperties = [
            'http://www.w3.org/2000/01/rdf-schema#label',
            'http://www.w3.org/2004/02/skos/core#prefLabel',
            'http://purl.org/dc/terms/title',
            'http://xmlns.com/foaf/0.1/name',
            'https://schema.org/name',
            'http://purl.org/dc/elements/1.1/title'
        ];

        $label = null;
        $locale = Cookie::get('locale');

        // Έλεγχος για κάθε property στη λίστα
        foreach ($labelProperties as $property) {
            $label = $graph->getLiteral($uri, new \EasyRdf\Resource($property), $locale)
                ?? $graph->getLiteral($uri, new \EasyRdf\Resource($property), 'en')
                ?? $graph->getLiteral($uri, new \EasyRdf\Resource($property));

            if ($label !== null) {
                break;
            }
        }

        // Αν δεν βρεθεί label, παίρνουμε το τελευταίο κομμάτι του URI
        return $label ? $label->getValue() : basename($uri);
    }

}
