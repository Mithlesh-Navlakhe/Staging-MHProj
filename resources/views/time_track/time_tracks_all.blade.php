@extends('layouts.index_template')

@section('content')
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'];
    $id = \Illuminate\Support\Facades\Auth::user()['original']['id'];
    ?>

    <div class="modal fade" id="delete-track" role="dialog">
        <div class="modal-dialog"  >
            <!-- Modal content-->
            <div class="modal-content">
                <div id="modalConfirmDeleteTrack"></div>
            </div>
        </div>
    </div>

    <div id="conteiner" class="container static-container" data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}" data-token="@if(Session::token()) {{ Session::token() }} @endif" data-date="<?= isset($date)? $date : '' ?>">
        <div class="row-fluid">
            <div class="span12">
                <h3 class="h3-my">Tracks</h3>
            </div>
			
			<div id="track-button-group" class="row col-md-12 btn-group track-button-group row col-md-12 margin-bottom-ten">
				<button class="btn btn-sm startdate-calender calender-color margin-ten" >
					<span>Start Date</span><span class="glyphicon glyphicon-th padding-left-ten"></span>
					<div><input type="text" name="start-date" id="start-date" readonly class="check-box-center"  value=""></div>
				</button>
				<button class="btn btn-sm finishtdate-calender margin-ten calender-color">
					<span>Finish Date</span><span class="glyphicon glyphicon-th padding-left-ten"></span>
					<div><input type="text" name="finish-date" id="finish-date" readonly class="check-box-center"  value=""></div>
				</button>
				<input id="date-archive" class="date-archive" type="hidden" placeholder="Select Dates" />
				<div class="button-group-width margin-top-ten">
					<button id="search-result" class="btn btn-info search-result no-curve">Search</button> 
					<button id="close-result" class="btn btn-default close-result">Clear</button> 
				</div>
           </div>
        </div>

        <div class="row-fluid">
            <!-- block -->
			<!--- Overlay -->
			<div id="myNav" class="overlay hidden">
			  <div class="overlay-content">
				<div id="loader" class="loader hidden"></div>
			  </div>
			</div>
            <div class="block bottom-border no-left-border no-right-border">
                <div class="block-content collapse in">
                    <div class="span12">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered my_input" id="usersTable">
                            <thead>
                            <tr>
								@if(($status != 'Developer') && ($status != 'QA Engineer'))
									<th></th>
								@endif
                                <th class="header-project">Project</th>
                                <th>Task</th>
                                <th>Assign To</th> 
                                <th>Duration</th>
                                <th>Total Time</th>
                                <th>Billable</th>
                                <th>Cost</th>
                                <th>Status Order</th>
                                <th>Start Date</th>
                                <th>Finish Date</th>
                                @if ($status == 'Super Admin' || $status == 'Lead' || $status == 'Admin' || $status == 'Supervisor')
                                    <th class="center track-action-header">Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
								@if(($status != 'Developer') && ($status != 'QA Engineer'))
								   <th class="thFoot checkbox-header "></th>
								@endif
                                <th class="thFoot header-project">Project</th>
                                <th class="thFoot" >Task</th>
                                <th class="thFoot" >Assign To</th>
                                <th class="thFoot" >Duration</th>
                                <th class="thFoot" >Total_time</th>
                                <th class="thFoot" >Billable</th>
                                <th class="thFoot" >Cost</th>
                                <th class="thFoot" >Status Order</th>
                                <th class="thFoot create-date removeSelect" >Start Date</th>
                                <th class="thFoot end-date removeSelect" >Finish Date</th>
                                @if ($status == 'Super Admin' || $status == 'Lead' || $status == 'Admin' || $status == 'Supervisor')
                                    <th class="removeSelect">Action</th>
                                @endif
                            </tr>
                            </tfoot>
                            <tbody class="track-body">
							<?php  date_default_timezone_set("Asia/Kolkata"); ?>
							<?php
                                  function convertToLocal($second) {
                                     $difference = bcmod($second, 3600);
                                     $hours = (int)($second/3600);
                                     $minutes = (int)($difference/60);
                                     $seconds = bcmod($difference, 60);
                                     if (strlen($hours) < 2){
                                         $hours = '0' . $hours;
                                     }
                                     if (strlen($minutes) < 2){
                                         $minutes = '0' . $minutes;
                                     }
                                     if (strlen($seconds) < 2){
                                         $seconds = '0' . $seconds;
                                     }
                                     return $hours . ':' . $minutes. ':'. $seconds;
                                 }
							?>
                            @if (isset($tracks))
                                @foreach( $tracks as $key )
                                    @if($key->task->assign_to != $id && ($status == 'QA Engineer' || $status == 'Developer'))
                                        @continue
                                    @endif
									
									@php ($trclass = '')
								    @if(!empty($trueval))
									   @if(in_array($key->id, $trueval))
											@php ($trclass = 'track-limit-more')
									   @endif
								    @endif
                                    <tr class="odd gradeX <?= $key->done == 2 ? 'done_tr' : ($key->done == 1 ? 'done_tr2' : '')?> hover-tr {{ $trclass }}" data-toggle="tooltip" title="">
                                        @if(($status != 'Developer') && ($status != 'QA Engineer'))
											<td><input type="checkbox" name="approvetrack" id="approvetrack" value="{{ $key->id }}" @if($key->done == 0) disabled @endif></td>
										@endif
										<td>{{ $key->project->project_name }}</td>
										<td class="hover-td" title="Description  : {{ $key->task->task_description ? $key->task->task_description : 'No Description' }}"><span class="task-title">{{ $key->task->task_titly }}</span></td>
                                        <?php  
											$hours = (int)($key->duration/60);
											$minutes = bcmod($key->duration, 60);
											if (strlen($hours) < 2){
												$hours = '0' . $hours;
											}
											if (strlen($minutes) < 2){
												$minutes = '0' . $minutes;
											}
                                        ?>
										<td>{{ $key->name}}</td>
                                        <td>{{ $key->duration ==null ? '-' :  $hours . ':' . $minutes }}</td>
                                        <td>{{ $key->total_time ==null ? '-' : convertToLocal($key->total_time) }}</td>
                                        <td>{{ $key->billable_time == 1 ? 'Yes' : 'No' }}</td>
                                        <!-- SN 06/29/2017: update below code for project cost -->
										<td>
										    @if($key->billable_time == 1)
												@if(isset($key->value))
													{{ $key->value }}
												@else
													-
												@endif
											@else
												0.00
											@endif
										</td>
                                        <!-- SN 04/21/2017 updated below status order-->
										<td>{{ $key->done == 0 ? 'In Process' : ($key->done == 1 ? 'Done' : ($key->done == 2 ? 'Approved' : ($key->done == 3 ? 'Rejected': ''))) }}
											@if($status == 'Developer' || $status == 'QA Engineer')
												<div class="popup-field tooltip-block popup-height scroll">
													<span style="padding-bottom: 20px;">Description  : {{ $key->task->task_description ? $key->task->task_description : 'No Description' }}</span>
												</div>
											@endif
										</td>
                                        <td><?= date("Y-m-d H:i:s", strtotime($key->created_at." UTC")); ?></td>
                                        <td><?= date("Y-m-d H:i:s", strtotime($key->finish_track." UTC")); ?></td>

                                        @if ($status == 'Super Admin' || $status == 'Lead' || $status == 'Admin' || $status == 'Supervisor')
                                            <td>
                                                <!--  SN 04/21/2017: removed check of key->done from approve and reject button and add title attribute-->
												<button  type="button" class="btn btn-success approvTrack" title="Approve" {{ $key->done == 0 ? 'disabled': ($key->done == 2 ? 'disabled': ($key->done == 3 ? 'disabled': '')) }}
														data-id="{{ $key->id }}"  data-url="/trask/approve/{{ $key->id }}" data-element="{{ $key->project->project_name . '-' . $key->task->task_titly}}">
                                                    <span class="glyphicon glyphicon-ok span_no_event" aria-hidden="true"></span>
                                                </button>

												<button  type="button" class="btn btn-warning  rejectTrack" title="Reject" {{ $key->done == 0 ? 'disabled': ($key->done == 2 ? 'disabled': ($key->done == 3 ? 'disabled': '')) }}
													data-url="/trask/reject/{{ $key->id }}" data-id="{{ $key->id }}" data-element="{{ $key->project->project_name . '-' . $key->task->task_titly}}">
													<span class="glyphicon glyphicon-remove span_no_event" aria-hidden="true"></span>
												</button>

                                                <a href="/track/update/{{ $key->id }}"  class="btn btn-info"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
												<button type="button" class="btn btn-danger deleteTrack" data-url="/track/delete/{{ $key->id }}" data-element="{{ $key->project->project_name . '-' . $key->task->task_titly}}">
													<span class="glyphicon glyphicon-floppy-remove span_no_event" aria-hidden="true"></span> Delete
												</button>
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
			<div id="instruction-block" class="instruction-block">
			  <div id="instruction-notes" class="instruction-notes font-bold ">
				<p>
					* If any task highlighted with Red Background it means that specific task got track for 3 hrs or more than 3 hrs. 
					Please ensure any task should not be continously track for 3 hrs or more than 3 hrs.
				</p>
			  </div>
		    </div>
            <!-- /block -->
        </div>
    </div>
	<?=  (date_default_timezone_set("UTC")) ? '' : '' ?>
<!-- SN 06/29/2017: added below code to hide header dropdown for start date and finish date , added js file for datepicker -->
<script type="text/javascript" src="/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/js/datefilter.js"></script>
@endsection
