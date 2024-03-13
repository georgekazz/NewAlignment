<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;
use App\Models\rdfnamespace;
use Session;
class rdfnamespaceController extends AdminController
{
    public function grid()
    {
        $rdfnamespace = rdfnamespace::paginate(15);

        return view('rdfnamespace.index', compact('rdfnamespace'));
    }

    public function create()
    {
        return view('rdfnamespace.create');
    }

    public function store(Request $request)
    {
        
        rdfnamespace::create($request->all());

        Session::flash('flash_message', 'rdfnamespace added!');

        return redirect('rdfnamespace');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
        $rdfnamespace = rdfnamespace::findOrFail($id);

        return view('rdfnamespace.show', compact('rdfnamespace'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
        $rdfnamespace = rdfnamespace::findOrFail($id);

        return view('rdfnamespace.edit', compact('rdfnamespace'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        
        $rdfnamespace = rdfnamespace::findOrFail($id);
        $rdfnamespace->update($request->all());

        Session::flash('flash_message', 'rdfnamespace updated!');

        return redirect('rdfnamespace');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        rdfnamespace::destroy($id);

        Session::flash('flash_message', 'rdfnamespace deleted!');

        return redirect('rdfnamespace');
    }
}
