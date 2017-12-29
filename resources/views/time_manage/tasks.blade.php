@extends('layouts.index_template')

@section('content')
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>

    <div class="modal fade" id="delete-task" role="dialog">
        <div class="modal-dialog"  >
            <!-- Modal content-->
            <div class="modal-content">
                <div id="modalConfirmDeleteTask"></div>
            </div>
        </div>
    </div>

    <div id="conteiner" class="container" data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}" data-start = "<?=  isset($active['start']) ? $active['start'] : '' ?>"
		data-end = "<?=  isset($active['end']) ? $active['end'] : '' ?>" >
        @if (!isset($tasksForProject))
       
	    <div class="row-fluid">
            <div class="span12">
                <h3 class="h3-my">Tasks</h3>
                <a href="/task/create" class="btn btn-large button-orange margin-left-large">
                    <i class="glyphicon glyphicon-plus"></i> Add Task
				        </a>
                <div class="btn-toolbar toolbar-span pagination">
                  <div class="daterange daterange--double tasklist date-picker-width pull-right" ></div>
                </div>
            </div>
        </div> 
		
        @else
            <div class="row-fluid">
                
            </div>
            <br>
            <div class="row my_row">
                <div class = "col-lg-7 col-md-7 col-sm-8 col-xs-12">

                    <div class="bs-calltoaction bs-calltoaction-default">
                        <div class="row">
                            <div class="col-md-9 cta-contents">
                                <div class="span12 add-border-bottom">
                                    <h1 class="h3-my">Project: <strong>{{  $project->project_name }}</strong></h1>
                                </div>
                                <div class="cta-desc margin-top-twenty">
                                    <div class="row ">
                                        <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 text-right" >
                                            <label class="control-label text-left" for="ProjectNameId" >Company</label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
                                            {{ $project->company_name }}
                                        </div>
                                    </div>
                                    <div class="row my_row">
                                        <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 text-right">
                                            <label class="control-label text-left" for="ProjectNameId" >Project</label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
                                            {{  $project->project_name }}
                                        </div>
                                    </div>
                                    <div class="row my_row">
                                        <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 text-right" >
                                            <label class="control-label text-left" for="ProjectNameId">Hourly rate</label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7" >
                                            {{  $project->hourly_rate }}
                                        </div>
                                    </div>
                                    <div class="row my_row">
                                        <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 text-right" >
                                            <label class="control-label text-left" for="ProjectNameId" >Lead</label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
                                            {{ $project->name }}
                                        </div>
                                    </div>

                                </div>
                                <div class="row my_row">
                                    <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 text-right" >
                                        <label class="control-label text-left" for="ProjectNameId">Notes</label>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7" >
                                        {{ $project->notes }}
                                    </div>
                                </div>

                            </div>


                        <div class="col-md-3 cta-button">
                            @if ($status == 'Admin' || $status == 'Lead' || $status == 'Supervisor' || $status == 'Super Admin')
                                <a href="/project/update/{{ $project->id }}" class="btn btn-info margin-left-large">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit Project
                                </a>
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
            </div>
    <div class="row my_row"></div>
    @endif
        <div class="row-fluid">
            <!-- block -->
            <div class="block bottom-border no-left-border no-right-border">
                <div class="block-content collapse in">
                    <div class="span12">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="usersTable">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Company</th>
                                <th>Project</th>
                                <th>Task Type</th>
                                <th>Allocated Hours</th>
                                <th>Assign To</th>
                                <th>Billable</th>
                                <th>Created Date</th>
                                @if ($status == 'Admin' || $status == 'Lead' || $status == 'Supervisor' || $status == 'Developer' ||  $status == 'QA Engineer' || $status == 'Super Admin')
                                    <th class="center action-header">Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="thFoot">Title</th>
                                <th class="thFoot">Company</th>
                                <th class="thFoot">Project</th>
                                <th class="thFoot">Task Type</th>
                                <th class="thFoot">Allocated Hours</th>
                                <th class="thFoot">Assign To</th>
                                <th class="thFoot">Billable</th>
                                <th class="thFoot">Created Date</th>
                                @if ($status == 'Super Admin' || $status == 'Admin' || $status == 'Lead' || $status == 'Supervisor' || $status == 'Developer' ||  $status == 'QA Engineer')
                                    <th  class="removeSelect">Action</th>
                                @endif
                            </tr>
                            </tfoot>
                            <tbody>
                            @if (isset($tasksRes))
                                @foreach( $tasksRes as $key )
                                    <tr class="odd gradeX">
                                        <td><span class="task-title">{{ $key['title'] }}</span></td>
                                        <td>{{ $key['company'] }}</td>
                                        <td>{{ $key['project_name'] }}</td>
                                        <td>{{ $key['type'] }}</td>
                                        <td>{{ $key['alloceted_hours'] }}</td>
                                        <td>{{ $key['user_name'] }}</td>
                                        <td class="check-box-center" > <?= ( $key['billable'] == '1') ? 'Yes' : 'No' ?> </td>
                                        <td class="check-box-center">{{ $key['created_at'] }}</td>

                                        @if ($status == 'Super Admin' || $status == 'Admin' || $status == 'Lead' || $status == 'Supervisor' || $status == 'Developer' ||  $status == 'QA Engineer')
                                            <td class="check-box-center">
                                                <!-- SN 05/10/2017: updated below code for edit developer task itself -->
												@if($status == 'Developer')
													@if($key['task_assign_by'] != Auth::user()->id)
														<a href="#" class="btn btn-info" disabled >
													@elseif($key['type'] == 'Leave')
														<a href="#" class="btn btn-info no-click" disabled >
													@else
														<a href="/task/update/{{ $key['id']  }}"  class="btn btn-info" >
													@endif
												@else
													<a href="/task/update/{{ $key['id']  }}"  class="btn btn-info {{ ( $key['type'] == 'Leave') ? 'no-click' : '' }}" {{ ( $key['type'] == 'Leave') ? 'disabled' : '' }}>
												@endif
													<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit
												</a>

                                                @if ($status == 'Super Admin' || $status == 'Admin' || $status == 'Lead' || $status == 'Supervisor')
                                                    <button type="button" class="btn btn-danger  deleteTask" data-url="/task/delete/{{ $key['id'] }}" data-element="{{  $key['title'] }}">
                                                        <span class="glyphicon glyphicon-floppy-remove span_no_event" aria-hidden="true"></span> Delete</button>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /block -->
        </div>
    </div>

@endsection
