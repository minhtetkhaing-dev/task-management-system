<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimesheetRequest;
use App\Http\Requests\UpdateTimesheetRequest;
use App\Models\Timesheet;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimesheetController extends Controller
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
        $timesheets = Timesheet::with('project','task')->get();
        // $timesheets = Timesheet::all();
        $tasks_project = Task::select('id', 'name', 'project_id')->with('project:id,name')->get();
        $projects = Project::pluck('name', 'id')->toArray();
        $tasks = Task::pluck('name', 'id')->toArray();
        // dd($tasks);
        return view('timesheets.index', compact('timesheets','projects','tasks','tasks_project'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        DB::beginTransaction();
        try{
            Timesheet::create($data);
            DB::commit();
            return redirect()->route('timesheets.index');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Timesheet $timesheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timesheet $timesheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timesheet $timesheet)
    {
        $data = $request->all();
        unset($data['_token']);
        $id = $data['id'];
        unset($data['id']);
        // dd($data);
        DB::beginTransaction();
        try{
            Timesheet::where('id', $id)->update($data);
            DB::commit();
            return redirect()->route('timesheets.index');
        } catch(Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timesheet $timesheet)
    {
        //
    }

    public function delete(Request $request)
    {       
        $ids = $request->input('ids');
        $rows = Timesheet::whereIn('id', json_decode($ids,true))->get();
        
        foreach($rows as $row)
        {
            $row->delete();
        }
        return redirect()->route('timesheets.index');
    }

    public function filter(Request $request)
    {
        $tasks_project = Task::select('id', 'name', 'project_id')->with('project:id,name')->get();
        $projects = Project::pluck('name', 'id')->toArray();
        $tasks = Task::pluck('name', 'id')->toArray();
        $data = $request->all();
        $filterProjectId = $data['filter_project_id'];
        $filterTaskId = $data['filter_task_id'];
        $filterDateStart = $data['filter_date_start'];
        $filterDateEnd = $data['filter_date_end'];
        $filterHour = $data['filter_hour'];
        $timesheets = Timesheet::when($filterProjectId != 'all', function($query) use ($filterProjectId) {
            return $query->where('project_id', '=', $filterProjectId);
        })->when($filterTaskId != 'all', function($query) use ($filterTaskId) {
            return $query->where('task_id', '=', $filterTaskId);
        })->when($filterDateStart != null, function($query) use ($filterDateStart) {
            return $query->where('date', '>=', $filterDateStart);
        })->when($filterDateEnd != null, function($query) use ($filterDateEnd) {
            return $query->where('date', '<=', $filterDateEnd);
        })->when($filterHour != null, function($query) use ($filterHour) {
            return $query->where('hour', '>=', $filterHour);
        })->with('project','task')->get();

        return view('timesheets.index', compact('timesheets','projects','tasks','tasks_project'));
    }
}
