<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::paginate(10);
        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:4',
        ]);

        Region::create($data);
        return redirect()->route('admin.regions.index')->with('success', 'Region created successfully');
    }

    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:4',
        ]);

        $region->update($data);
        return redirect()->route('admin.regions.index')->with('success', 'Region updated successfully');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('admin.regions.index')->with('success', 'Region deleted successfully');
    }
}
