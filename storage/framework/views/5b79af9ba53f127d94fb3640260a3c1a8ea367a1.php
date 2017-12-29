<?php $__env->startSection('content'); ?>

<?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'];
$idActiveUser = \Illuminate\Support\Facades\Auth::user()['original']['id']?>
<script type="text/javascript" src="/data/daterangepicker.js" xmlns="http://www.w3.org/1999/html"></script>
<link rel="stylesheet" type="text/css" href="/data/daterangepicker.css" />

<div class="modal fade" id="delete-track" role="dialog">
	<div class="modal-dialog"  >
		<!-- Modal content-->
		<div class="modal-content">
			<div id="modalConfirmDeleteTrack"></div>
		</div>
	</div>
</div>

<div id="conteiner" class="container" data-date="<?= isset($date)? $date : '' ?>"
	data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>"
	data-idactiveuser="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['id']); ?>"
	data-token="<?php echo e(Session::token()); ?>"
	data-log-active = "<?= isset($_COOKIE['logTrackActiveLogId']) ? $_COOKIE['logTrackActiveLogId'] : ''?>"
	data-pagename = "<?= (isset( $track )) ? '' : 'tracking' ; ?>">

	<?php if(Session::has('flash_message')): ?>
		<div class="alert alert-danger alert-dismissible margin-top-ten" role="alert">
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  <?php echo session('flash_message'); ?>

		</div>
	<?php endif; ?>
	
	<!-- leave block -->
	 <div id="leave-block" class="row margin-top-twenty hidden">
		<div class="row my_row">
		  <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">  
			<div class="bs-calltoaction bs-calltoaction-default">
			  <div class="row">
				<div id="close-block" class="close-block">
				  <span class="glyphicon glyphicon-remove close-icon"></span>
				</div>
				<div class="col-md-9 cta-contents">
				   <div class="span12 margin-top-fifteen">
					 <form class="form-horizontal" role="form" method="POST" action="/leave/create">
						<?php echo e(csrf_field()); ?>	
						
						<div class="control-group row">
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 label-mob-width text-right">
								<label class="control-label text-left" for="taskTypeId">User *</label>
							</div>  
							<div class="controls col-xs-6 col-sm-6 col-md-6 col-lg-6 input-mob-width">
								<select name="assign_to" class="input-xlarge focused my_input input-height users-leave" id="users-leave" required />
								  <?php if($userlist): ?>
									<option default  value="">Please select User</option>
									<?php $__currentLoopData = $userlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
										<option value="<?php echo e($key->id); ?>"><?php echo e($key->name); ?></option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
								  <?php endif; ?>																		
								</select>
								<?php if($errors->has('task_type')): ?>
								  <span class="help-block error-text">
									<strong><?php echo e($errors->first('leave_type')); ?></strong>
								  </span>
								<?php endif; ?>
							</div>
							<input name="task_assign_by" class="input-xlarge focused my_input leave_assign_by" id="leave_assign_by" type="hidden" value="<?php if(Auth::user()->id): ?><?php echo e(Auth::user()->id); ?><?php endif; ?>" >
							<input name="task_titly" class="input-xlarge focused my_input leave_user" id="leave_user" type="hidden" value="<?php if(Auth::user()->id): ?><?php echo e(Auth::user()->name); ?> is on Leave.<?php endif; ?>" >
							<input name="task_type" class="input-xlarge focused my_input task-type" id="task-type" type="hidden" value="<?php if($tasktype): ?><?php echo e($tasktype->id); ?> <?php endif; ?>" >
							<input name="client_id" class="input-xlarge focused my_input task-type" id="task-type" type="hidden" value="<?php if($clientnproj): ?><?php echo e($clientnproj->client->id); ?> <?php endif; ?>" >
							<input name="project_id" class="input-xlarge focused my_input task-type" id="task-type" type="hidden" value="<?php if($clientnproj): ?><?php echo e($clientnproj->id); ?> <?php endif; ?>" >
							<input name="username" class="input-xlarge focused my_input username" id="username" type="hidden" value="" >
						</div>
						<div class="control-group row">
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 label-mob-width text-right">
								<label class="control-label text-left" for="taskTypeId">Leave Type *</label>
							</div>
							<div class="controls col-xs-6 col-sm-6 col-md-6 col-lg-6 input-mob-width">
								<select name="leave_type" class="input-xlarge focused my_input input-height leaveType" id="leaveType" required />
									<option defaul value="">Please select Leave Type</option>
									<option value="full-day">Full Day</option>
									<option value="half-day">Half Day</option>
									<option value="spec-hrs">Specific Hours</option>									
								</select>
								<?php if($errors->has('task_type')): ?>
								  <span class="help-block error-text">
									<strong><?php echo e($errors->first('leave_type')); ?></strong>
								  </span>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="control-group row half-time hidden">
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 label-mob-width text-right">
								<label class="control-label text-left" for="leavelabel"></label>
							</div>
							<div class="controls col-xs-6 col-sm-6 col-md-6 col-lg-6 input-mob-width">
								<select name="half_day_type" class="input-xlarge focused my_input input-height half_day_type" id="half_day_type" />
									<option value="Morning">Morning</option>
									<option value="Afternoon">Afternoon</option>									
								</select>
							</div>
						</div>
						
						<div class="control-group row spec-time hidden">
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 label-mob-width text-right">
								<label class="control-label text-left" for="leavelabel"></label>
							</div>
							<div class="controls col-xs-8 col-sm-8 col-md-8 col-lg-8 input-mob-width">
								<div class="input-group pull-left from-time-block"> 
								  <input type="text" class="form-control input-height first-time button-group-width text-center no-curve" name="first-time" id="first-time" placeholder="From" value="" />
								  <input type="hidden" class="form-control input-height from-time button-group-width text-center no-curve" name="from_time" id="from-time" value="" />
								  <span class="margin-ten pull-left">To</span>
								  <input type="hidden" class="form-control input-height end-time button-group-width text-center no-curve" name="end_time" id="end-leave" value="" />
								  <input type="text" class="form-control input-height last-time button-group-width text-center no-curve" name="last-time" id="last-time" placeholder="End"	value=""  />
							  
								  <span class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 error-time help-block font-bold error-text"></span>
								</div>
							</div>
						</div>
						
						<div class="control-group row">
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 label-mob-width text-right">
								<label class="control-label text-left" for="leavelabel">Select Leave Date *</label>
							</div>
							<div class="controls col-xs-6 col-sm-6 col-md-6 col-lg-6 input-mob-width">
								<input type="text" required  class="inputTrackPadding focused my_input padding-ten input-hours no-click input-height date-leave" name="date-leave"
								id="date-leave" placeholder="Select Date" value="" />
								<button class="btn btn-sm leave-day calender-color">
									<span class="glyphicon glyphicon-th"></span>
								</button>
								
								<span class="row col-md-12 col-lg-12 error-date help-block font-bold error-text"></span>
							</div>
						</div>
						<div class="row col-md-12 cta-button">
						   <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 label-mob-width text-right margin-top-twenty">
							 <label class="control-label labelTrack" for=""></label>
						   </div>
						   <div class="controls col-xs-9 col-sm-9 col-md-9 col-lg-9">
							 <button type="submit" id="leave-submit" class="btn button-orange">Submit</button>
							 <button type="reset" id="leave-reset" class="btn button-orange margin-left-twenty leave-reset">Cancel</button>
						   </div>
						</div>
					 </form>
				  </div>			
				</div>
			  </div>
			</div>
		  </div>
		</div>
	 </div>
	 <!----->
	
	<div class="row margin-top-twenty">
		<div class="col-md-2 btn-toolbar" >
			<div id="timeStep5" class="btn-group">
				<button class="btn btn-sm calendarPrevDay calender-color">
					<span class="glyphicon glyphicon-chevron-left"></span>
				</button>
				<button class="btn btn-sm calendarNextDay calender-color">
					<span class="glyphicon glyphicon-chevron-right"></span>
				</button>
				<button class="btn btn-sm d4 calender-color">
					<span class="glyphicon glyphicon-th"></span>
				</button>
			</div>
		</div>
		<h2  class="col-md-8 showDate"  id="timeTrackShowDate"></h2>
		
		<div class="col-md-2">
			<button type="button" id="leave-button" class="btn btn-success leave-button pull-right">Apply Leave</button>
		</div>
	</div>

	<!-- <div class="row margin-top-twenty heading-color font-eighteen">
		<div class="col-md-6" >
			Add track log
		</div>
		<div class="col-md-6">
			Tracked
		</div>
	</div> -->


	<div class="row ">
	   <div class="col-sm-6 col-md-6 col-lg-6 ">
		 <div class="row margin-top-twenty heading-color font-eighteen bottom-border padding-left-twenty">Add track log</div>
		   <div class="row right-border">
			<form  class=" margin-top-twenty" method="POST" action="<?= (isset( $track )) ? '/track/update/' . $track[0]->id : '/tracking' ;?>" id="addTrackForm">
				<?php echo e(csrf_field()); ?>


				<div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
					<div class="col-xs-2 col-sm-4 col-md-3 col-lg-3 text-right">
						<label class="control-label labelTrack" for="trackProjectId">Project *</label>
					</div>
					<div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">
						<select name="project_id" class="inputTrackPadding focused my_input input-height"  id="trackProjectId" required>
							<option selected disabled value="">Select project</option>

							<?php if( old() && old('project_id') &&  $projects ): ?>
								<?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
								   <?php if( $project['id'] && $project['id'] == old('project_id') ): ?>
									   <option value="<?php echo e(old('project_id')); ?>" selected><?php echo e($project['project_name']); ?></option>
								   <?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
							<?php endif; ?>

							<!-- SN 05/05/2017: added client name associate with project name -->
							<?php if(isset($track)): ?>
								<?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>	
									<option value="<?php echo e($task->id); ?>" <?php if($track[0]->project->id == $task->id): ?> selected <?php endif; ?> ><?php echo $task['project_name']." - ".$task['client']['company_name']; ?></option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
							<?php else: ?>
								<?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>	
									<option value="<?php echo e($task->id); ?>" class="<?php if($task->project_name == 'Leave'): ?> hidden <?php endif; ?>" ><?php echo e($task->project_name); ?> - <?php echo e($task->client->company_name); ?></option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>									

							<?php endif; ?> 
						</select>
						<?php if($errors->has('project_id')): ?>
							<span class="help-block error-text">
								<strong><?php echo e($errors->first('project_id')); ?></strong>
							</span>
						<?php endif; ?>
					</div>
				</div>

				<div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
					<div class="col-xs-2 col-sm-4 col-md-3 col-lg-3 text-right">
						<label class="control-label labelTrack" for="trackTaskId">Task *</label>
					</div>
					<div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">
						<select name="task_id" class="inputTrackPadding focused my_input input-height"  id="trackTaskId" required >

							<?php if( old() && old('task_id') &&  $projects ): ?>
								<?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
									<?php if( $project['id'] && $project['id'] == old('project_id') ): ?>
										<?php $__currentLoopData = $project['task']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
											<?php if( isset($task['id']) && $task['id'] == old('task_id') ): ?>
												<option value="<?php echo e(old('task_id')); ?>" selected><?php echo e($task['task_titly']); ?></option>
											<?php endif; ?>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
									<?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
							<?php endif; ?>

							<?php if( isset( $track ) ): ?>
								<option value="<?php echo e($track[0]->task->id); ?>" selected><?php echo e($track[0]->task->task_titly); ?></option>
							<?php endif; ?>
						</select>
						<?php if($errors->has('task_id')): ?>
							<span class="help-block error-text">
								<strong><?php echo e($errors->first('task_id')); ?></strong>
							</span>
						<?php endif; ?>
					</div>
				</div>


				<div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
					<div class="col-xs-2 col-sm-4 col-md-3 col-lg-3 text-right">
						<label class="control-label labelTrack" for="trakingTaskDescription">Description</label>
					</div>
					<div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">
					   <textarea class="inputTrackPadding focused my_input" readonly
								 rows="7" name="description" id="trakingTaskDescription"><?=
						   isset($track)  ? $track[0]->task->task_description : ''  ?></textarea>
					</div>
				</div>



			   <!-- <div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
					<div class="col-xs-2 col-sm-4 col-md-3 col-lg-3" style="text-align: right;">
						<label class="control-label labelTrack" for="formTrackStart" >Start</label>
					</div>
					<div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">
						<div class="col-md-4 col-lg-3" style="padding: 0px">
						<span class="input-group" >
							<input type="text" value="<?= ( isset($data['start']) ) ? $data['start'] : ((old() && old('date_start') ? ltrim(explode(':', explode(' ', old('date_start'))[4])[0], '0') : '')) ; ?>"
								   style="width: 57%" class="inputTrackPadding form-control" id="formTrackStart" placeholder="HH:MM"/>

							<span class="input-group-btn" style=" float:left ">
								<button type="button" class="btn btn-default" id="formTrackStartNow" style="padding:6px 1px">now</button>
								<button type="button" class="btn btn-default" id="formTrackStartInc" style="padding:6px 3px">+</button>
								<button type="button" class="btn btn-default" id="formTrackStartDec" style="padding:6px 3px">-</button>
							</span>
						</span>
						</div>

						<div class="col-sm-12 col-md-2 col-lg-2" style="padding: 0px; text-align: right;">
							<label class="labelTrack" for="formTrackFinish">Finish</label>
						</div>

						<div class="col-md-4 col-lg-3" style="padding: 0px">
						<span class="input-group" >
							<input type="text" value="<?= ( isset( $data['finish'] ) ) ? $data['finish'] : ((old() && old('date_finish') ? ltrim(explode(':', explode(' ', old('date_finish'))[4])[0], '0') : '')) ; ?>"
								   style="width: 57%; " class="inputTrackPadding form-control"  id="formTrackFinish" placeholder="HH:MM">
							<span class="input-group-btn" style=" float:left ">
								<button type="button" class="btn btn-default" id="formTrackFinishNow" style="padding:6px 1px">now</button>
								<button type="button" class="btn btn-default" id="formTrackFinishInc" style="padding:6px 3px">+</button>
								<button type="button" class="btn btn-default" id="formTrackFinishDec" style="padding:6px 3px">-</button>
							</span>
						</span>
						</div>

						<input id="formTrackStartSend"  type="hidden" name="date_start">
						<input id="formTrackFinishSend" type="hidden" name="date_finish" >


						<div class="col-md-2 col-lg-3 col-lg-offset-1" style="padding: 0px">
						   <span class="" style="display: inline-block">
							<label class = "labelTrack">
								<input type="checkbox" id="nextDay" name="nextDate"
									   <?php  (isset( $track)) ? $duration = explode(":", $track[0]->duration) : ''; ?>
								<?= isset($track)  ? (floor((strtotime( $track[0]->date_finish) - strtotime( $track[0]->date_start)) / (60 * 60 * 24)) == 1 ||  $duration[0] >  24 ? 'checked' : '' ) :
											   ((old() && old('nextDate') == 'on') ? ' checked': '' ) ?>
										> Next Day
							</label>
						 </span>
						</div>
						<?php if($errors->has('date_start')): ?>
							<span class="help-block">
											<strong style="color:#802420"><?php echo e($errors->first('date_start')); ?></strong>
										</span>
						<?php endif; ?>
						<?php if($errors->has('date_finish')): ?>
							<span class="help-block">
											<strong style="color:#802420"><?php echo e($errors->first('date_finish')); ?></strong>
										</span>
						<?php endif; ?>

					</div>
				</div>-->

				<input id="formTrackDate"  type="hidden" name="track_date" value="<?= isset($date) ?  $date : ''?>" >
				<input id="formTrackDuration" type="hidden" name="date_duration" >

				<div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
					<div class="col-xs-2 col-sm-4 col-md-3 col-lg-3 text-right">
						<label class="control-label labelTrack text-left padding-top-ten" for="timeDuration">Hours *</label>
					</div>
					<div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">
						<input type="text" required  class="inputTrackPadding focused my_input padding-ten input-hours input-height" name="duration" id="timeDuration" placeholder="HH:MM"
								value="<?= ( isset( $track ) ) ? $track[0]->duration : ((old() && old('duration')) ? old('duration') : '') ; ?>"
							   pattern="(0[0-9]|1[0-9]|2[0-9])(:[0-5][0-9]){1}"	title="Please match the requested format HH:MM"/>
						<span class="">
						<label  class="labelTrack" for="billableTime">
							Billable <input type="checkbox" name="billable_time" value="1" id="billableTime"
							<?= ( isset( $track ) && $track[0]->billable_time == 1 ) ? ' checked' : ((old('billable_time') == '1') ? ' checked': '' ) ;?>>
						</label>
					 </span>
					</div>
					
					<input type="hidden" name="task_type_id" id="task_type_id" class="task_type_id" value="<?php echo e(( isset($track )) ? $track[0]->task->task_type : ''); ?>" />
				</div>
				
				<!-- Mith 16/08/2017: track hours field at time of lead edit.  -->
				<?php if($status == 'Lead' && isset( $track )): ?>
				<div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
					<div class="col-xs-2 col-sm-4 col-md-3 col-lg-3" style="text-align: right;">
						<label class="control-label labelTrack" for="timeDuration" style="text-align: left; padding-top: 10px">Tracked Hours</label>
					</div>
					<div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">
					   <input type="text" class="my_input input-height inputTrackPadding" id="totaltime" name="totaltime" id="timeDuration" placeholder="HH:MM"
							   value="<?= ( isset( $track ) ) ? $track[0]->total_time : ((old() && old('totaltime')) ? old('totaltime') : '') ; ?>"
							  pattern="(0[0-9]|1[0-9]|2[0-9])(:[0-5][0-9]){1}"
							   title="Please match the requested format HH:MM"/>
					   <input type="hidden" id="actualtotaltime" name="actualtotaltime" value="<?= ( isset( $track ) ) ? $track[0]->total_time : ((old() && old('totaltime')) ? old('totaltime') : '') ; ?>" />
					</div>
				</div>
				<?php endif; ?>			
				
				<div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
					<div class="col-xs-2 col-sm-4 col-md-3 col-lg-3 text-right">
						<label class="control-label labelTrack labelStatus" for="task_status">Task Status</label>
					</div>
					<div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">
						<select name="done" class="inputTrackPadding focused my_input input-height" id="task_status" <?php if(!isset($track)): ?> disabled <?php endif; ?>>
							<option disabled value="">Select Task Status</option>
							<!-- SN 04/18/2017:  added check if form is in edit mode else add formdata mode -->
							<?php if(isset( $track )): ?>
								<?php $__currentLoopData = $taskstatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
									<option value="<?php echo e($key['status_order']); ?>" <?php if($track[0]->done == $key['status_order']): ?> selected <?php endif; ?> ><?php echo e($key['name']); ?></option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
							<?php else: ?>
								<?php $__currentLoopData = $taskstatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
									<option value="<?php echo e($key['status_order']); ?>" <?php if($key['name'] == "In Process"): ?> selected <?php endif; ?> ><?php echo e($key['name']); ?></option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
							<?php endif; ?>
						</select>
						<?php if($errors->has('done')): ?>
							<span class="help-block error-text">
								<strong><?php echo e($errors->first('done')); ?></strong>
							</span>
						<?php endif; ?> 
					</div>
				</div>

				<!--    <div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
						<div class="col-xs-2 col-sm-4 col-md-3 col-lg-3" style="text-align: right;">
							<label class="control-label labelTrack" for="additionalCost">Additional Cost</label>
						</div>
						<div class="controls col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="input-group">
								<div class="input-group-btn" >
									<input value="<?= ( isset($track) ) ? $track[0]->additional_cost : ((old() && old('additional_cost')) ? old('additional_cost') : '') ; ?>"
										   type="number" steep="0.01" style="padding: 10px; max-width: 83%" class="inputTrackPadding focused my_input form-control " name="additional_cost" id="additionalCost">
									<span class="input-group-addon" style="padding: 9px 12px">$</span>
								</div>
							</div>
							<?php if($errors->has('additional_cost')): ?>
								<span class="help-block">
									<strong style="color:#802420"><?php echo e($errors->first('additional_cost')); ?></strong>
								</span>
							<?php endif; ?>
						</div>
						<div class="controls col-xs-12 col-sm-3 col-md-3 col-lg-3">
						<span class="" style="display: inline-block">
							<label  class="labelTrack" for="billableTime">
							   Billable Time <input type="checkbox" name="billable_time" value="1" id="billableTime"
								<?= ( isset( $track ) && $track[0]->billable_time == 1 ) ? ' checked' : ((old('billable_time') == '1') ? ' checked': '' ) ;?>>
							</label>
						 </span>
						</div>
					</div> -->


				<div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
					<div class="col-xs-2 col-sm-4 col-md-3 col-lg-3 text-right">
						<label class="control-label labelTrack" for="trackDescription">Comments</label>
					</div>
					<div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">
					   <textarea class="inputTrackPadding focused my_input"
								 rows="7" name="description" id="trackDescription"><?=
						   ( old('description') ) ? old('description') : ( isset($track)  ? $track[0]->description : '')  ?></textarea>
					</div>
					<?php if($errors->has('description')): ?>
						<span class="help-block error-text">
							<strong><?php echo e($errors->first('description')); ?></strong>
						</span>
					<?php endif; ?>
				</div>



				<div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
					<div class="col-xs-2 col-sm-4 col-md-3 col-lg-3 text-right margin-top-twenty">
						<label class="control-label labelTrack" for=""></label>
					</div>
					<div class="controls col-xs-3 col-sm-3 col-md-3 col-lg-2 margin-top-twenty">
					  <button type="submit" id="" class="btn button-orange">Submit</button>
					</div>
					<div class="controls col-xs-3 col-sm-3 col-md-3 col-lg-2 margin-left-ten margin-top-twenty">
						<!-- SN: 04/06/17: Update below code for different functionality of cancel on add and update form. --> 
						<?php if( isset($track) ): ?>
							<button type="reset" id="cancel" class="btn button-orange cancel" onclick="event.preventDefault(); window.location = '/tracking' ">Cancel</button>
						<?php else: ?>
							<button type="reset" id="cancel-track" class="btn button-orange cancel-track">Cancel</button>
						<?php endif; ?>
					</div>
				</div>
			</form>
		   </div>
		</div>
		 <div class="col-sm-6 col-md-6 col-lg-6 padding-top-twenty">
		  <div class="row heading-color font-eighteen bottom-border padding-left-twenty">Tracked</div>
			 <!-- Mith: 08/01/17: Search bar to search tracking according to entered text. -->
			<div class="row right-border">
			  <!-- <div class="row margin-twenty"> -->
				<div class="margin-twenty">
				<div class="col-md-12 no-padding bottom-fifteen">
				  <input type="search" name="search" id="trc-search" class="col-md-12 my_input input-height" placeholder="Search">
				  <div class="search-cross">
					  <span class="glyphicon glyphicon-remove search-icon" ></span>
				  </div>
				</div>	
				
				<div class="col-md-12 no-padding scroll-bar">
					<table class="col-md-12 my_input trackLogTable" class="display" id="trackLogTableId">
					<tbody>
						<?php $__currentLoopData = $tracks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
							<?php
							/**
								Mith 05/26/2017: Below check is for showing current active tracking.
							*/
							$todayDate = date("Y-m-d");
							$lastTrackStartDate ='';
							$lastTrackEndDate ='';
							$activeTask = false;

							foreach($key['relations']['timeLog'] as $keys){
								$lastTrackStartDate = $keys['start'];
								$lastTrackEndDate = $keys['finish'];
							}

							if(strtotime($lastTrackStartDate) >= strtotime($todayDate) && $lastTrackEndDate == '') {
							  $activeTask = true;
							}

							
							if($status == 'Developer' && $idActiveUser !== $key->task->assign_to || $status == 'QA Engineer' && $idActiveUser !== $key->task->assign_to){
								continue;
							}
							/**
								Mith 05/26/2017: Below check is to show total time spend on task previously.
							*/
							$previousTime='';
							foreach ($timespend as $keys) {
								  if($keys['task_id'] == $key->task_id){
									$previousTime += $keys['total_time'];
								  }
							}

							$totalTime = '';
							$totalTime = $previousTime + $key->total_time;
							if($totalTime != null){
							if($status == 'Developer' || $status == 'QA Engineer' || $status == 'Lead')
								if(!isset($hours)) {
									$hours = 0;
								}


								$hours = floor($totalTime / 3600);
								$minutes = floor(($totalTime / 60) % 60);
								$seconds = $totalTime % 60;
								
								if(strlen($hours) == 1) {
								  $hours = "0".$hours;
								}

								if(strlen($minutes) == 1) {
								  $minutes = "0".$minutes;
								}

								if(strlen($seconds) == 1) {
								  $seconds = "0".$seconds;
								}			
								
								$totalTime =  "$hours:$minutes:$seconds";
							}
							?>

							<!-- SN 04/21/2017 -->
							<tr class="trackLog trackLogFirst <?= $key->done == 1 ? 'done_tr' : ($key->done == 1 ? 'done_tr2' : '')?> <?= $activeTask == true ? 'done_tr2 1' : '' ?>" id="track-<?= $key->id ?>"
								data-id ="<?= $key->id ?>"
								data-project_name ="<?= $key->project->project_name  ?>"
								data-project_id ="<?= $key->project->id  ?>"
								data-task_titly ="<?= $key->task->task_titly ?>"
								data-task_id ="<?= $key->task->id ?>"
								data-total_time ="<?= ($key->total_time == null) ? '00:00:00' : $totalTime  ?>"
								data-duration ="<?= ($key->duration == null) ? '00:00' : date('H:i',  mktime(0,$key->duration)) ?>"
								data-date_start ="<?= date('H:i', strtotime($key->date_start))?>"
								data-date_start = "<?= date('H:i', strtotime($key->date_finish)) ?>">
								<td>
									<a href="#"  class="showTimelog"> <span class="glyphicon glyphicon-plus"></span></a>
									<a href="#"  class="hideTimelog hidden"> <span class="glyphicon glyphicon-minus" ></span> </a>
								</td>
								<td data-search-flag="true" data-search-value="<?php echo e($key->name); ?> - <?php echo e($key->project->project_name); ?> - <?php echo e($key->task->task_titly); ?>">
									<span class="ng-binding"></span>
									<p class="projecttask"><?php echo e($key->name); ?> - <?php echo e($key->project->project_name); ?> - <?php echo e($key->task->task_titly); ?></p>
								</td>
								<td class="text-right">
									<h3 id="timeTrackSegmentTotal"
										class="timeTrackSegmentTotal <?= isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id ? 'timeTrackSegmentTotalActive' : '' ?>"
										
										data-total="<?= $totalTime ?>">
											<?php echo e(($totalTime == null) ? '00:00:00' : $totalTime); ?>

									</h3>
									<?php if($key->date_start == null || $key->date_start == null): ?>
										<?php  $hours = (int)($key->duration/60);
									   $minutes = bcmod($key->duration, 60);
												if (strlen($hours) < 2){
													$hours = '0' . $hours;
												}
												if (strlen($minutes) < 2){
													$minutes = '0' . $minutes;
												}
										?>
										<p class="project" >  <?php echo e(($key->duration == null) ? '00:00' : $hours . ':' . $minutes); ?></p>
									<?php else: ?>
										 <p class="project" > <?php echo e(date('H:i', strtotime($key->date_start))); ?> - <?php echo e(date('H:i', strtotime($key->date_finish))); ?></p>
									<?php endif; ?>
								</td>
								<td class="text-right table-cell-actions padding-left-te" valign="bottom">
									<div class="btn-group">
										<span class="stop-start-button">
											<?php if($key->done == 0): ?>
												<a  href='/trask/done/<?= $key->id ?>' <?= $key->task->assign_to != Auth::user()['original']['id'] ? 'disabled' : "" ?> class="btn btn-success <?php echo e($key->task->assign_to != Auth::user()['original']['id'] ? 'no-click' : ""); ?>" id="doneTrack" style="<?= isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id ? 'display:none' : '' ?>" >
													<span class="glyphicon glyphicon-ok"></span>Submit
												</a>
											<?php else: ?>
												<a  href='#'  class="btn btn-warning no-click" id="doneReject" disabled style="<?= isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id ? 'display:none' : '' ?>" >
													<span class="glyphicon glyphicon-ok"></span>Approval Pending
												</a>
											<?php endif; ?>
											<?php if($key->done == 0): ?>
												<button class="btn btn-default" id="startTrack" title="Start Tracking" <?= $key->task->assign_to != Auth::user()['original']['id'] ? 'disabled' : "" ?> style="<?= isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id ? 'display:none' : '' ?>" >
													<span class="glyphicon glyphicon-play"></span>
												</button>
											<?php endif; ?>
											<button href="#" class="btn btn-danger" id="stopTrack2" title="Stop Tracking" style="<?= isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id ? '' : 'display:none' ?>">
												<span class="glyphicon glyphicon-stop"></span>
											</button>
											<span class="addTrackFinishForm">
											<?php if(isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id): ?>
												<form id="stop-form-track" action="/create/timelog" method="POST" class="hidden">
													<?php echo e(csrf_field()); ?>

													<input type="hidden" id="stop-form-track-id" name="id" value="<?= $_COOKIE['logTrackActiveLogId'] ?>">
												 </form>
											<?php endif; ?>
											</span>

										</span>
										<span>
											<?php if($key->done == 0): ?>
												<a href="/track/update/<?= $key->id ?>" class="btn btn-default" id="editTrack">
													<span class="glyphicon glyphicon-pencil span_no_event"></span>
												</a>
											<?php endif; ?>
											<button type="button" class="btn btn-default deleteTrack <?=(isset($track[0]->id) && $track[0]->id == $key->id) ? 'disabled' : ''  ?>" <?=(isset($track[0]->id) && $track[0]->id == $key->id) ? 'disabled' : ''  ?>
													 data-url="/track/delete/<?php echo e($key->id); ?>" data-element="<?php echo e($key->project->project_name); ?> - <?php echo e($key->task->task_titly); ?>">
												 <span class="glyphicon glyphicon-trash span_no_event" aria-hidden="true"></span>
											</button>

										</span>
									</div>
								</td>
							</tr>

							<tr  class="hidden" data-subtask-flag="true" id ="add-<?= $key->id ?>">
								<td colspan="4" class="padding-left-thirty">
									<table class="my_input"> </table>
								</td>
							</tr>

						<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
					 </tbody>
				   </table>
				</div>
				 <?php if(sizeof($tracks) > 0): ?> 
					<div class="row col-md-12 padding-twenty">
						<div class="font-bold">Total Tracked Hours: <?php echo e((isset($totaltracktime)) ? $totaltracktime : '00.00'); ?> hours</div>
					</div>					
				<?php endif; ?>
			  </div>
		   </div>
		</div>
	</div>
</div>
<!--    <script src="/js/jquery/jquery-3.1.1.min.js"></script>-->
<script src="/js/tasks.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.index_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>