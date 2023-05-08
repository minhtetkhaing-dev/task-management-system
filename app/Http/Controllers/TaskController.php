<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
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
        $tasks = Task::with('project','timesheets')->get();
        // $tasks = Task::all();
        $projects = Project::pluck('name', 'id')->toArray();
        // $timesheets = Timesheet::pluck('name', 'id')->toArray();
        $timesheets = Timesheet::all();
        $filters = [
            'filter_project_id' => 'all',
            'filter_status' => 'all',
            'filter_start_date_start' => null,
            'filter_start_date_end' => null,
            'filter_due_date_start' => null,
            'filter_due_date_end' => null
        ];
        
        return view('tasks.index', compact(['tasks','projects','timesheets','filters']));
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
        DB::beginTransaction();
        try{
            Task::create($data);
            DB::commit();
            return redirect()->route('tasks.index');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->all();
        unset($data['_token']);
        $id = $data['id'];
        unset($data['id']);
        // dd($data);
        DB::beginTransaction();
        try{
            Task::where('id', $id)->update($data);
            DB::commit();
            return redirect()->route('tasks.index');
        } catch(Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }

    public function delete(Request $request)
    {       
        $ids = $request->input('ids');
        $rows = Task::whereIn('id', json_decode($ids,true))->get();
        
        foreach($rows as $row)
        {
            $row->delete();
        }
        return redirect()->route('tasks.index');
    }

    public function filter(Request $request)
    {
        $projects = Project::pluck('name', 'id')->toArray();
        $timesheets = Timesheet::all();
        $data = $request->all();
        $filterProjectId = $data['filter_project_id'];
        $filterStatus = $data['filter_status'];
        $filterStartDateStart = $data['filter_start_date_start'];
        $filterStartDateEnd = $data['filter_start_date_end'];
        $filterDueDateStart = $data['filter_due_date_start'];
        $filterDueDateEnd = $data['filter_due_date_end'];
        $filters = [
            'filter_project_id' => $filterProjectId,
            'filter_status' => $filterStatus,
            'filter_start_date_start' => $filterStartDateStart,
            'filter_start_date_end' => $filterStartDateEnd,
            'filter_due_date_start' => $filterDueDateStart,
            'filter_due_date_end' => $filterDueDateEnd
        ];
        $tasks = Task::when($filterStatus != 'all', function($query) use ($filterStatus) {
            return $query->where('status', '=', $filterStatus);
        })->when($filterProjectId != 'all', function($query) use ($filterProjectId) {
            return $query->where('project_id', '=', $filterProjectId);
        })->when($filterStartDateStart != null, function($query) use ($filterStartDateStart) {
            return $query->where('start_date', '>=', $filterStartDateStart);
        })->when($filterStartDateEnd != null, function($query) use ($filterStartDateEnd) {
            return $query->where('start_date', '<=', $filterStartDateEnd);
        })->when($filterDueDateStart != null, function($query) use ($filterDueDateStart) {
            return $query->where('due_date', '>=', $filterDueDateStart);
        })->when($filterDueDateEnd != null, function($query) use ($filterDueDateEnd) {
            return $query->where('due_date', '<=', $filterDueDateEnd);
        })->with('project','timesheets')->get();

        return view('tasks.index', compact('tasks','projects','timesheets','filters'));
    }
}
