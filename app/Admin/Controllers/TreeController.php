<?php

namespace App\Admin\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;


class TreeController extends AdminController
{

    public function grid()
    {
        return view('forcetreewelcome');
    }



    public function showmainpage()
    {
        $files = File::where('filetype', 'ttl')->get();

        return view('forcetreemain', compact('files'));
    }

    public function getTreeData()
    {
        try {
            $path = storage_path('app/public/response.json');
            $jsonData = json_decode(file_get_contents($path), true);

            // \Log::info('JSON Data: ', $jsonData);

            $graphData = $this->convertToGraphData($jsonData);

            return response()->json($graphData);

        } catch (\Exception $e) {
            \Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function convertToGraphData($data)
    {
        if (!isset($data['adjacency_matrix']) || !is_array($data['adjacency_matrix'])) {
            throw new \Exception('Invalid or missing adjacency matrix');
        }

        $nodes = [];
        $links = [];

        $nodeIndices = $data['adjacency_matrix'][0];

        foreach ($nodeIndices as $index => $node) {
            if ($index === 0)
                continue;
            $nodes[] = [
                "id" => $node,
                "name" => ucwords(str_replace('_', ' ', $node)),
                "details" => $data['node_metrics_matrix'][0][$index],
            ];
        }

        // Δημιουργία συνδέσεων
        foreach ($data['adjacency_matrix'] as $i => $row) {
            if ($i === 0)
                continue;
            foreach ($row as $j => $value) {
                if ($j === 0)
                    continue;
                if ($value !== 0) {
                    $links[] = [
                        "source" => $nodeIndices[$i],
                        "target" => $nodeIndices[$j],
                    ];
                }
            }
        }

        return ["nodes" => $nodes, "links" => $links];
    }
}
