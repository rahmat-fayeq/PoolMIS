<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Http\Requests\StoreSalaryRequest;
use App\Http\Requests\UpdateSalaryRequest;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $salaries = Salary::query()
            ->when($request->has('search'), function ($query) use ($request) {
                $searchTerm = $request->search;
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('full_name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('job', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('submit_date', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('amount', '=', $searchTerm);
                });
            })
            ->latest()
            ->paginate()
            ->withQueryString();

        return view('salary.index', [
            'salaries' => $salaries
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('salary.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalaryRequest $request)
    {
        Salary::create($request->validated());
        return to_route('salary.index')->with('success', 'Salary Added Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salary $salary)
    {
        return view('salary.edit', [
            'salary' => $salary
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalaryRequest $request, Salary $salary)
    {
        $salary->update($request->validated());
        return to_route('salary.index')->with('success', 'Salary Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        $salary->delete();
        return to_route('salary.index')->with('success', 'Salary Deleted Successfully');
    }
}
