<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try{
            Project::create($data);
            DB::commit();
            return redirect()->route('projects.index');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        
        $ids = $request->input('ids');
        $rows = Project::whereIn('id', json_decode($ids,true))->get();
        
        foreach($rows as $row)
        {
            $row->delete();
        }
        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $data = $request->all();
        unset($data['_token']);
        $id = $data['id'];
        unset($data['id']);
        // dd($data);
        DB::beginTransaction();
        try{
            Project::where('id', $id)->update($data);
            DB::commit();
            return redirect()->route('projects.index');
        } catch(Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }

    public function filter(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $filterId = $data['filter_id'];
        $filterStatus = $data['filter_status'];
        $filterStartDateStart = $data['filter_start_date_start'];
        $filterStartDateEnd = $data['filter_start_date_end'];
        $filterDueDateStart = $data['filter_due_date_start'];
        $filterDueDateEnd = $data['filter_due_date_end'];
        $projects = Project::when($filterId != 'all', function($query) use ($filterId) {
            return $query->where('id', '=', $filterId);
        })->when($filterStatus != 'all', function($query) use ($filterStatus) {
            return $query->where('status', '=', $filterStatus);
        })->when($filterStartDateStart != null, function($query) use ($filterStartDateStart) {
            return $query->where('start_date', '>=', $filterStartDateStart);
        })->when($filterStartDateEnd != null, function($query) use ($filterStartDateEnd) {
            return $query->where('start_date', '<=', $filterStartDateEnd);
        })->when($filterDueDateStart != null, function($query) use ($filterDueDateStart) {
            return $query->where('start_date', '>=', $filterDueDateStart);
        })->when($filterDueDateEnd != null, function($query) use ($filterDueDateEnd) {
            return $query->where('start_date', '<=', $filterDueDateEnd);
        })->get();

        // dd($projects);
        return view('projects.index', compact('projects'));
    }
}
