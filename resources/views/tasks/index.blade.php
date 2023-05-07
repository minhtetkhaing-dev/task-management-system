@extends('layouts.app')

@section('content')

<div class="container px-lg-5">
    <div class="d-flex justify-content-between mb-3">
        <h2 class="fw-bold">Task Lists</h2>
        <div>
            <button id="filter-btn" class="btn btn-info" type="button" data-bs-toggle="modal" data-bs-target="#filterTaskModal"><i class="bi bi-funnel-fill me-1"></i>Filter</button>
            <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                <i class="bi bi-plus-circle-fill me-1"></i>Create
            </button>
            <button id="delete-record-btn" type="submit" class="btn btn-danger"><i class="bi bi-trash3-fill me-1"></i>Delete</button>
            <form method="POST" action="{{ route('tasks.delete') }}" id="delete-record-form" class="d-none">
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
                    <input type="checkbox" class="form-check-input check-all">
                </div>
            </th>
            <th class="fw-bold">Task</th>
            <th class="fw-bold">Project</th>
            <th class="fw-bold">Start Date</th>
            <th class="fw-bold">Deadline</th>
            <th class="fw-bold">Status</th>
            <th class="fw-bold"></th>
        </tr>
        @foreach($tasks as $task)
        <tr class="">
            <td class="ps-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input check-this" data-id="{{$task->id}}">
                </div>
            </td>
            <td>{{$task->name}}</td>
            <td>{{$task->project->name}}</td>
            <td>{{$task->start_date}}</td>
            <td>{{$task->due_date}}</td>
            <td>
                <span class="rounded-pill px-2 @if($task->status == 'new') bg-primary text-white @endif @if($task->status == 'ongoing') bg-info @endif @if($task->status == 'done') bg-success text-white @endif">
                    @if($task->status == 'new')
                        New
                    @endif
                    @if($task->status == 'ongoing')
                        In Progress
                    @endif
                    @if($task->status == 'done')
                        Done
                    @endif
                </span>
            </td>
            <td align="right">
                <button class="btn btn-warning btn-edit edit-task-btn" type="button" data-bs-toggle="modal" data-bs-target="#editTaskModal" data-id="{{$task->id}}" data-name="{{$task->name}}" data-project-id="{{$task->project->id}}" data-start-date="{{$task->start_date}}" data-due-date="{{$task->due_date}}" data-status="{{$task->status}}">
                    <i class="bi bi-pencil-fill me-1"></i>Edit
                </button>
            </td>
        </tr>
        @endforeach
    </table>
</div>

{{-- Create Task --}}
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="createTaskModalLabel">Create Task</h1>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('task.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="name" class="text-md-end">Task Name</label>
                        </div>

                        <div class="col-12">
                            <input id="name" type="text" class="form-control" name="name" autofocus>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="project-id" class="text-md-end">Project</label>
                        </div>

                        <div class="col-12">
                            <select id="project-id" class="form-select" name="project_id">
                            @foreach($projects as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                            </select>
                            <input type="text" class="form-control d-none" name="timesheet_id">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="start-date" class="text-md-end">Start Date</label>
                        </div>
                        <div class="col-6">
                            <label for="due-date" class="text-md-end">Deadline</label>
                        </div>

                        <div class="col-6">
                            <input id="start-date" type="date" class="form-control" name="start_date">
                        </div>
                        <div class="col-6">
                            <input id="due-date" type="date" class="form-control" name="due_date">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="status" class="text-md-end">Status</label>
                        </div>

                        <div class="col-12">
                            <select id="status" class="form-select" name="status">
                                <option value="new" selected>New</option>
                                <option value="ongoing">On Going</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-25">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Task --}}
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="editTaskModalLabel">Create Task</h1>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('task.update')}}" enctype="multipart/form-data">
                @csrf
                <input type="text" class="d-none" id="edit-id" name="id">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="edit-name" class="text-md-end">Task Name</label>
                        </div>

                        <div class="col-12">
                            <input id="edit-name" type="text" class="form-control" name="name" autofocus>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="project-id" class="text-md-end">Project</label>
                        </div>

                        <div class="col-12">
                            <select id="edit-project-id" class="form-select" name="project_id">
                            @foreach($projects as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                            </select>
                            <input type="text" class="form-control d-none" name="timesheet_id">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="edit-start-date" class="text-md-end">Start Date</label>
                        </div>
                        <div class="col-6">
                            <label for="edit-due-date" class="text-md-end">Deadline</label>
                        </div>

                        <div class="col-6">
                            <input id="edit-start-date" type="date" class="form-control" name="start_date">
                        </div>
                        <div class="col-6">
                            <input id="edit-due-date" type="date" class="form-control" name="due_date">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="edit-status" class="text-md-end">Status</label>
                        </div>

                        <div class="col-12">
                            <select id="edit-status" class="form-select" name="status">
                                <option value="new" selected>New</option>
                                <option value="ongoing">On Going</option>
                                <option value="done">Done</option>
                            </select>
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

{{-- filter task --}}

<div class="modal fade" id="filterTaskModal" tabindex="-1" aria-labelledby="filterTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="filterTaskModalLabel">Filter Task</h1>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('tasks.filter')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="filter-id" class="text-md-end">Task Name</label>
                        </div>

                        <div class="col-12">
                            <select id="filter-id" class="form-select" name="filter_id" autofocus>
                                <option value="all">All</option>
                                @foreach($tasks as $task)
                                    <option value="{{ $task->id }}">{{ $task->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="filter-project-id" class="text-md-end">Project</label>
                        </div>

                        <div class="col-12">
                            <select id="filter-project-id" class="form-select" name="filter_project_id">
                                <option value="all">All</option>
                                @foreach($projects as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            {{-- <input type="text" class="form-control d-none" name="timesheet_id"> --}}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="filter-start-date-start" class="text-md-end">Start Date Range</label>
                        </div>
                        <div class="col-6">
                            <label for="filter-start-date-end" class="text-md-end"></label>
                        </div>
    
                        <div class="col-6">
                            <input id="filter-start-date-start" type="date" class="form-control" name="filter_start_date_start">
                        </div>
                        <div class="col-6">
                            <input id="filter-start-date-end" type="date" class="form-control" name="filter_start_date_end">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="filter-due-date-start" class="text-md-end">Deadline Date Range</label>
                        </div>
                        <div class="col-6">
                            <label for="filter-due-date-end" class="text-md-end"></label>
                        </div>
    
                        <div class="col-6">
                            <input id="filter-due-date-start" type="date" class="form-control" name="filter_due_date_start">
                        </div>
                        <div class="col-6">
                            <input id="filter-due-date-end" type="date" class="form-control" name="filter_due_date_end">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="filter-status" class="text-md-end">Status</label>
                        </div>

                        <div class="col-12">
                            <select id="filter-status" class="form-select" name="filter_status">
                                <option value="all" selected>All</option>
                                <option value="new">New</option>
                                <option value="ongoing">On Going</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="{{ route('tasks.index') }}" class="btn btn-danger">Remove Filters</a>
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
@endsection
