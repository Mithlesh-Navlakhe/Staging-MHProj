@extends('layouts.index_template')

@section('content')

    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'];
    $idActiveUser = \Illuminate\Support\Facades\Auth::user()['original']['id']?>
    <script type="text/javascript" src="/data/daterangepicker.js" xmlns="http://www.w3.org/1999/html"></script>
    <link rel="stylesheet" type="text/css" href="/data/daterangepicker.css" />
	
    <div class="modal fade  modal-popup" id="delete-track" role="dialog">
        <div class="modal-dialog"  >
            <!-- Modal content-->
            <div class="modal-content">
                <div id="modalConfirmDeleteTrack"></div>
            </div>
        </div>
    </div>

	
    <div id="dashcontainer" class="container dashcontainer" data-date="<?= isset($date)? $date : '' ?>"
         data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}"
         data-activeid="{{\Illuminate\Support\Facades\Auth::user()['original']['id']}}"
         data-token="{{ Session::token() }}" >

        <div class="row margin-top-twenty">
			<div class="col-md-12 row">
				<div class="col-md-6 col-xs-6 col-sm-6">
					<h2 class="dash-title showDate"  id="dash-title">Dashboard</h2> 
				</div>
				<div class="col-md-6 col-xs-6 col-sm-6">
					<h2 class="showDate pull-right">{{$pageDate}}</h2>
				</div>
			</div>
        </div>

		
		@if(Auth::User()->employe == 'Lead')
		  <div class="row col-md-12">  
			<div class="heading-color padding-top-large font-eighteen"><span>Task to be Approve</span></div>
	      </div>
		  <div class="row col-md-12 dash-table">
		   <div class="padding-top-ten">
			<div class="approvetasklist scroll-bar full-border background-white container-size" id="approvetasklist">
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered no-margin-bottom" id="taskTable">
					<thead>
						<tr>
							<th></th>
							<th>Project</th>
							<th>Unapproved Task</th>
							<th>Assign To</th>
							<th class="center track-action-header">Action</th>
						</tr>
					</thead>
					<tbody class="tasklistbody">
					
					@if (isset($approvetask))
						@foreach( $approvetask as $key )
							<tr class="odd gradeX">
								<td><input type="checkbox" name="apprtasklist" value="{{ $key->id }}" /></td>
								<td class="check-box-center">{{ $key->project->project_name }}</td>
								<td>{{ $key->task->task_titly }}</td>
								<td class="check-box-center">{{ $key->name }}</td>
								<td class="check-box-center"> 
									<button type="button" class="btn btn-success approveTaskbutton" title="Approve"   data-id="{{ $key->id }}"
											 data-url="/trask/approve/{{ $key->id }}" data-element="{{ $key->project->project_name . '-' . $key->task->task_titly}}">
										<span class="glyphicon glyphicon-ok span_no_event" aria-hidden="true"></span> 
									</button>
									<button type="button" class="btn btn-warning rejectTaskbutton" title="Reject" data-id="{{ $key->id }}"
										  data-element="{{ $key->project->project_name . '-' .$key->task->task_titly}}"
										  data-url="/trask/reject/{{ $key->id }}" > <span class="glyphicon glyphicon-remove span_no_event" aria-hidden="true"></span> 
									</button>
								</td>
							</tr>
						@endforeach
					 @endif
					</tbody>
				</table>
				<!-- SN 05/31/2017: updated below code -->
				@if(empty($approvetask))
				<div class="my_input">
					 <div class="odd gradeX setHeight">
					 <div class="no-task-height"></div>
						<div class="no-task check-box-center" width="">No Unapproved Task</div>
					 </div>
				</div>
				@endif
				
				<!--- Overlay -->
				<div id="myNav" class="overlay hidden">
				  <div class="overlay-content">
					<div id="loader" class="loader hidden"></div>
				  </div>
				</div>
			 </div>
		    </div>
		  </div>
		@endif

        <div class="row margin-top-twenty">
            <!-- <div class="col-md-12 heading-color font-eighteen">
				<div class="col-md-6" >
					<span class="setLeft" style="">Tasks to Work Today</span>
				</div>
				<div class="col-md-6 padding-left-thirty">
					<div class="row" style="">Pending Tasks</div>
				</div>
			</div> -->
        </div>

        <div class="row margin-top-twenty" style=""> 
		  <div class="col-md-12 today-task-block">
			<div class="col-sm-6 col-md-6 col-lg-6 row" id="todaytaskblock">
			<div class="col-md-12 row heading-color font-eighteen">
				<span class="setLef">Tasks to Work Today</span>
			</div>
			<div class="col-md-12 scroll-bar full-border setBlockHeight padding-top-twenty">
              <table cellpadding="0" cellspacing="0" border="0" class="table table-striped col-md-12 trackLogTable" class="display" id="trackLogTableId">
                  <tbody>
				  @if(sizeof($todayTasks) > 0) 
					 @foreach( $todayTasks as $key)
                         <!-- <a href="/tracking"> -->
						  <tr class="odd gradeX tracktr today-task-list" data-task_id ="<?= $key->id ?>" > 
							  <td></td>
                              <td class="half-block-area">
								  <a href="/tracking" class="removeUnderline">
									  <p class="projecttask">{{$key->name}} - {{ $key->project->project_name }} - {{ $key->task_titly }} </p>
								  </a>
                              </td>
                         </tr>
						<!-- </a> -->
                     @endforeach
				  @else
					  <div class="row">
						<div class="task-block-height"></div>
						<div class="no-task check-box-center">
						 No Tasks For Today
						</div>
					  </div>
				  @endif
                 </tbody>
              </table>
            </div>
            </div>
			
			<!-- SN 05/31/2017: updated below code -->
			<div class="col-sm-6 col-md-6 col-lg-6 pendingtask" id="pendingtaskblock">
				<div class="col-md-12 row padding-left-thirty heading-color font-eighteen">
					<div class="row col-md-12">Pending Tasks</div>
				</div>
				<div class="row col-md-12 col-lg-12 scroll-bar pull-right full-border setBlockHeight padding-top-twenty BlockHeight">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped col-md-12 pendingtaskTable" class="display" id="pendingtaskTable">
                    <tbody>
					@if(sizeof($tracks) > 0) 
                      @foreach( $tracks as $key)
					     <tr class="odd gradeX tracktr pending-task-list" id="pending-task-list">
						   <td>	 </td>
						   <td class="half-block-area">
							   <a href="/tracking/<?= (isset( $key->track_date )) ? date('d-m-Y', strtotime($key->track_date)) : '' ; ?>" aria-hidden="true" class="removeUnderline">	
									<p class="projecttask my_input">{{$key->name}} - {{ $key->project->project_name }} - {{ $key->task->task_titly }}</p>
							   </a>
						   </td> 
					     </tr>
                      @endforeach
					@else
					  <div class="row ">  
						<div class="task-block-height"></div>
						<div class="no-task check-box-center">
						 No Pending Tasks 
						</div>
					  </div>
					@endif
                   </tbody>
                </table>
            </div>
            </div>
          </div>
		</div>
    </div>	
<!--    <script src="/js/jquery/jquery-3.1.1.min.js"></script>-->
    <script src="/js/tasks.js"></script>
@endsection
