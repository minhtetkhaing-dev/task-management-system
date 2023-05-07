@extends('layouts.app')

@section('content')
<div class="container px-lg-5">
    <div class="d-flex justify-content-between mb-3">
        <h2 class="fw-bold">Timesheets</h2>
        <div>
            <button id="filter-btn" class="btn btn-info" type="button" data-bs-toggle="modal" data-bs-target="#filterTimesheetModal"><i class="bi bi-funnel-fill me-1"></i>Filter</button>
            <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#createTimesheetModal">
                <i class="bi bi-plus-circle-fill me-1"></i>Create
            </button>
            <button id="delete-record-btn" type="submit" class="btn btn-danger"><i class="bi bi-trash3-fill me-1"></i>Delete</button>
            <form method="POST" action="{{ route('timesheets.delete') }}" id="delete-record-form" class="d-none">
                @csrf
                <input type="text" name="ids" id="delete-ids">
                <button id="delete-record-submit" type="submit" class="btn btn-danger"><i class="bi bi-trash3-fill me-1"></i>Delete</button>
            </form>
        </div>
    </div>
    <table class="table table-info table-striped border border-primary shadow">
        <tr>
            <th class="ps-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input check-all" name="all-timesheets">
                </div>
            </th>
            <th>Date</th>
            <th>Project</th>
            <th>Task</th>
            <th>Description</th>
            <th>Hour Spent</th>
            <th></th>
        </tr>
        @foreach($timesheets as $timesheet)
        <tr>
            <td class="ps-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input check-this" name="timesheet" data-id="{{$timesheet->id}}">
                </div>
            </td>
            <td>{{$timesheet->date}}</td>
            <td>{{$timesheet->project->name}}</td>
            <td>{{$timesheet->task->name}}</td>
            <td>{{$timesheet->description}}</td>
            <td>{{$timesheet->hour}}</td>
            <td align="right">
                <button class="btn btn-warning edit-timesheet-btn" type="button" data-bs-toggle="modal" data-bs-target="#editTimesheetModal" data-id="{{$timesheet->id}}" data-date="{{$timesheet->date}}" data-project-id="{{$timesheet->project->id}}" data-task-id="{{$timesheet->task->id}}" data-description="{{$timesheet->description}}" data-hour="{{$timesheet->hour}}">
                    <i class="bi bi-pencil-fill me-1"></i>Edit
                </button>
            </td>
        </tr>
        @endforeach
    </table>
</div>

{{-- Create Project Modal --}}
<div class="modal fade" id="createTimesheetModal" tabindex="-1" aria-labelledby="createTimesheetModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
        <div class="modal-header">
            <h1 class="modal-title fs-5 fw-bold" id="createTimesheetModalLabel">Create Timesheet</h1>
            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="{{ route('timesheet.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="date" class="text-md-end">Date</label>
                    </div>

                    <div class="col-12">
                        <input id="date" type="date" class="form-control" name="date" autofocus>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="project-id" class="text-md-end">Project</label>
                    </div>

                    <div class="col-12">
                        <select id="project-id" class="form-select project-id" name="project_id">
                        @foreach($projects as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="task-id" class="text-md-end">Task</label>
                    </div>

                    <div class="col-12">
                        <select id="task-id" class="form-select task-id" name="task_id">
                        {{-- @foreach($tasks as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach --}}
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="description" class="text-md-end">Description</label>
                    </div>

                    <div class="col-12">
                        <input id="description" type="text" class="form-control" name="description">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="hour" class="text-md-end">Hour Spent</label>
                    </div>

                    <div class="col-12">
                        <input id="hour" type="number" class="form-control" name="hour">
                    </div>
                </div>

                {{-- <input type="text" id="user-id" class="d-none" name="user_id" value="{{ Auth::user()->id }}"> --}}
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success w-25">Create</button>
            </div>
        </form>
    </div>
  </div>
</div>

{{-- Edit Project Modal --}}
<div class="modal fade" id="editTimesheetModal" tabindex="-1" aria-labelledby="editTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="editTimesheetModalLabel">Edit Timesheet</h1>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('timesheet.update') }}" enctype="multipart/form-data">
                @csrf
                <input type="text" class="d-none" id="edit-id" name="id">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="edit-date" class="text-md-end">Date</label>
                        </div>

                        <div class="col-12">
                            <input id="edit-date" type="date" class="form-control" name="date" autofocus>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="edit-project-id" class="text-md-end">Project</label>
                        </div>

                        <div class="col-12">
                            <select id="edit-project-id" class="form-select project-id" name="project_id">
                            @foreach($projects as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="edit-task-id" class="text-md-end">Task</label>
                        </div>

                        <div class="col-12">
                            <select id="edit-task-id" class="form-select task-id" name="task_id">
                            
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="edit-description" class="text-md-end">Description</label>
                        </div>

                        <div class="col-12">
                            <input id="edit-description" type="text" class="form-control" name="description">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="edit-hour" class="text-md-end">Hour Spent</label>
                        </div>

                        <div class="col-12">
                            <input id="edit-hour" type="number" class="form-control" name="hour">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-25">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- filter timesheets --}}
<div class="modal fade" id="filterTimesheetModal" tabindex="-1" aria-labelledby="filterTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="filterTimesheetModalLabel">Filter Timesheet</h1>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('timesheets.filter') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="filter-date-start" class="text-md-end">Date Range</label>
                        </div>

                        <div class="col-6">
                            <label for="filter-date-end" class="text-md-end"></label>
                        </div>
                        
                        <div class="col-6">
                            <input id="edit-date-start" type="date" class="form-control" name="filter_date_start" autofocus>
                        </div>

                        <div class="col-6">
                            <input id="filter-date-end" type="date" class="form-control" name="filter_date_end">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="edit-project-id" class="text-md-end">Project</label>
                        </div>

                        <div class="col-12">
                            <select id="filter-project-id" class="form-select project-id" name="filter_project_id">
                                <option value="all">All</option>
                                @foreach($projects as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="edit-task-id" class="text-md-end">Task</label>
                        </div>

                        <div class="col-12">
                            <select id="filter-task-id" class="form-select task-id" name="filter_task_id">
                                <option value="all">All</option>
                                @foreach($tasks as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="filter-hour" class="text-md-end">Hour Spent</label>
                        </div>

                        <div class="col-12">
                            <input id="filter-hour" type="number" class="form-control" name="filter_hour">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="{{ route('timesheets.index') }}" class="btn btn-danger">Remove Filters</a>
                    <button type="submit" class="btn btn-success w-25">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('message.delete_error')
@include('message.confirm_delete')


@endsection
@section('script')
    <script>
        $(document).ready(function () {
            updateTasks();
            updateEditTasks();
        });
        var pj = @json($projects);
        var tasks = @json($tasks);
        var tasksProject = @json($tasks_project);

        console.log(tasks)
        $('#project-id').change(function() {
            updateTasks()
        });

        function updateTasks(){
            $('#task-id').empty();
            var selectedValue = $('#project-id').val();
            console.log('Selected Project',selectedValue)
            $.each(tasksProject, function(index, value) {
                console.log(index,value.project_id)
                if (value.project.id == selectedValue) {
                    $('#task-id').append(`<option value="${value.id}">${value.name}</option>`);
                }
            });
        }

        $('#edit-project-id').change(function() {
            console.log("Changed")
            updateEditTasks()
        });

        function updateEditTasks(){
            $('#edit-task-id').empty();
            var selectedValue = $('#edit-project-id').val();
            console.log('Selected Project',selectedValue)
            $.each(tasksProject, function(index, value) {
                console.log(index,value.project_id)
                if (value.project.id == selectedValue) {
                    $('#edit-task-id').append(`<option value="${value.id}">${value.name}</option>`);
                }
            });
        }
    </script>
@endsection