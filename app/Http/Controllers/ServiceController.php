<?php

namespace App\Http\Controllers;

use App\Models\MemberService;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::query()
            ->when($request->has('search'), function ($query) use ($request) {
                $searchTerm = $request->search;
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('price', '=', $searchTerm);
                });
            })
            ->latest()
            ->get();

        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        Service::create($request->only('name', 'price'));
        return redirect('/services')->with('success', 'Service created successfully!');
    }

    public function edit(Service $service)
    {
        return view('services.edit', [
            'service' => $service
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $service->update($request->only('name', 'price'));
        return redirect('/services')->with('success', 'Service updated successfully!');
    }

    public function destroy(Service $service)
    {
        if ($service->members()->exists()) {
            return redirect('/services')->with('error', 'Cannot delete this service because it has related records.');
        }

        $service->delete();
        return redirect('/services')->with('success', 'Service deleted successfully!');
    }

    public function details($id)
    {
        $service = Service::with(['members'])->findOrFail($id);
        $serviceRecords = MemberService::where('service_id', $id)->get();
        return view('services.details', compact('service', 'serviceRecords'));
    }
}
