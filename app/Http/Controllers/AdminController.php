<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of staff members.
     */
    public function index()
    {
        $staff = Staff::all();
        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)
                        ->orderBy('name', 'asc')
                        ->get();
        return view('admin.staff.create', compact('departments'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'position' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:20',
            'department' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'department' => $request->department,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('staff.index')
            ->with('success', 'Staff berhasil ditambahkan');
    }

    /**
     * Display the specified staff member.
     */
    public function show(string $id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(string $id)
    {
        $staff = Staff::findOrFail($id);
        $departments = Department::where('is_active', true)
                        ->orderBy('name', 'asc')
                        ->get();
        return view('admin.staff.edit', compact('staff', 'departments'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, string $id)
    {
        $staff = Staff::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $id,
            'position' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:20',
            'department' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'department' => $request->department,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('staff.index')
            ->with('success', 'Staff berhasil diperbarui');
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(string $id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Staff berhasil dihapus');
    }
}
