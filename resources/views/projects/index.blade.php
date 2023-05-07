@extends('layouts.app')

@section('content')
<div class="container px-lg-5">
    <div class="d-flex justify-content-between mb-3">
        <h2 class="fw-bold">Project Lists</h2>
        <div>
            <button id="filter-btn" class="btn btn-info" type="button" data-bs-toggle="modal" data-bs-target="#filterProjectModal"><i class="bi bi-funnel-fill me-1"></i>Filter</button>
            <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                <i class="bi bi-plus-circle-fill me-1"></i>Create
            </button>
            <button id="delete-record-btn" type="submit" class="btn btn-danger"><i class="bi bi-trash3-fill me-1"></i>Delete</button>
            <form method="POST" action="{{ route('projects.delete') }}" id="delete-record-form" class="d-none">
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
                    <input type="checkbox" class="form-check-input check-all" name="all-projects">
                </div>
            </th>
            <th>Project</th>
            <th>Description</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th></th>
        </tr>
        @foreach($projects as $project)
        <tr>
            <td class="ps-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input check-this" name="project" data-id="{{$project->id}}">
                </div>
            </td>
            <td>{{$project->name}}</td>
            <td>{{$project->description}}</td>
            <td>{{$project->start_date}}</td>
            <td>{{$project->due_date}}</td>
            <td>
                <span class="rounded-pill px-2 @if($project->status == 'new') bg-primary text-white @endif @if($project->status == 'ongoing') bg-info @endif @if($project->status == 'testing') bg-warning text-dark @endif @if($project->status == 'production') bg-success text-white @endif">
                    @if($project->status == 'new')
                        New
                    @endif
                    @if($project->status == 'ongoing')
                        On Going
                    @endif
                    @if($project->status == 'testing')
                        Testing
                    @endif
                    @if($project->status == 'production')
                        Production
                    @endif
                </span>
            </td>
            <td align="right">
                <button class="btn btn-warning edit-project-btn" type="button" data-bs-toggle="modal" data-bs-target="#editProjectModal" data-id="{{$project->id}}" data-name="{{$project->name}}" data-description="{{$project->description}}" data-start-date="{{$project->start_date}}" data-due-date="{{$project->due_date}}" data-status="{{$project->status}}">
                    <i class="bi bi-pencil-fill me-1"></i>Edit
                </button>
            </td>
        </tr>
        @endforeach
    </table>
</div>

{{-- Create Project Modal --}}
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
        <div class="modal-header">
            <h1 class="modal-title fs-5 fw-bold" id="createProjectModalLabel">Create Project</h1>
            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="{{ route('project.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="name" class="text-md-end">Project Name</label>
                    </div>

                    <div class="col-12">
                        <input id="name" type="text" class="form-control" name="name" autofocus required>
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
                    <div class="col-6">
                        <label for="start-date" class="text-md-end">Start Date</label>
                    </div>
                    <div class="col-6">
                        <label for="due-date" class="text-md-end">Deadline</label>
                    </div>

                    <div class="col-6">
                        <input id="start-date" type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="col-6">
                        <input id="due-date" type="date" class="form-control" name="due_date" required>
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
                            <option value="testing">Testing</option>
                            <option value="production">Production</option>
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

{{-- Edit Project Modal --}}
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content shadow">
          <div class="modal-header">
              <h1 class="modal-title fs-5 fw-bold" id="editProjectModalLabel">Edit Project</h1>
              <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="{{ route('project.update')}}" enctype="multipart/form-data">
              @csrf
              <input type="text" class="d-none" id="edit-id" name="id">
              <div class="modal-body">
                  <div class="row mb-3">
                      <div class="col-12">
                          <label for="edit-name" class="text-md-end">Project Name</label>
                      </div>
  
                      <div class="col-12">
                          <input id="edit-name" type="text" class="form-control" name="name" autofocus>
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
                              <option value="testing">Testing</option>
                              <option value="production">Production</option>
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


{{-- Filter Project Modal --}}
<div class="modal fade" id="filterProjectModal" tabindex="-1" aria-labelledby="filterProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="filterProjectModalLabel">Filter Project</h1>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('projects.filter')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
    
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
                                <option value="testing">Testing</option>
                                <option value="production">Production</option>
                            </select>
                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <a href="{{ route('projects.index') }}" class="btn btn-danger">Remove Filters</a>
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
        updateFilters();
    });

    var filters = @json($filters) 

    function updateFilters(){
        console.log(filters)
        $('#filter-start-date-start').val(filters.filter_start_date_start)
        $('#filter-start-date-end').val(filters.filter_start_date_end)
        $('#filter-due-date-start').val(filters.filter_due_date_start)
        $('#filter-due-date-end').val(filters.filter_due_date_end)
        $('#filter-status').val(filters.filter_status)
    }
</script>
@endsection