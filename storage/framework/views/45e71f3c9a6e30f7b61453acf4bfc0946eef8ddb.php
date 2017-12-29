<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    
	<link href="/css/jquery.multiselect.css" rel="stylesheet" type="text/css">
	<link href="/css/multipleselect.css" rel="stylesheet" type="text/css">
   
    
	<div class="modal fade" id="delete-track" role="dialog">
        <div class="modal-dialog"  >
            <!-- Modal content-->
            <div class="modal-content">
                <div id="modalConfirmDeleteTrack"></div>
            </div>
        </div>
    </div>

    <div id="conteiner" class="container" data-date=""
         data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>"
         data-token="<?php echo e(Session::token()); ?>"
         data-log-active = "<?= isset($_COOKIE['logTrackActiveLogId']) ? $_COOKIE['logTrackActiveLogId'] : ''?>"
         data-start = "<?=  isset($active['start']) ? $active['start'] : '' ?>"
         data-end = "<?=  isset($active['end']) ? $active['end'] : '' ?>">

        <div class="row margin-top-twenty">
            <div class="col-md-4 col-lg-3 btn-toolbar toolbar-span">
                <div class="daterange daterange--double mail-project picker-width"></div>
            </div>
            <div class="col-md-3 col-lg-3 padding-twenty">
                <select name="users" class=" input-xlarge focused my_input input-height" id="SelectedProjectReport" data-all="true">
					<?php if($status == 'Lead'): ?>
						<?php if(empty($active['projectId'])): ?>
							<option selected disabled value="" >Please select Project</option>
						<?php endif; ?>
						<?php if(isset($projectsList)): ?>
							<option value="all" <?= ( isset($active['projectId']) && 'all' == $active['projectId']) ? 'selected' : '' ?>>All</option>
							<?php $__currentLoopData = $projectsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
								<!-- SN 05-05-2017: added client name associated with project name -->
								<option value="<?= $key->id ?>" <?= ( isset($active['projectId']) && $key->id == $active['projectId']) ? 'selected' : '' ?>><?php echo e($key->project_name); ?> - <?php echo e($key->company_name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
						<?php endif; ?>
                    <?php elseif($status == 'Super Admin' || $status == 'Admin' || $status == 'Supervisor'): ?>
						<?php if(empty($active['projectId'])): ?>
							<option selected disabled value="" >Please select Lead</option>
						<?php endif; ?>
						<?php if(isset($projectsList)): ?>
							<?php $__currentLoopData = $projectsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
								<option value="<?= $key->id ?>" <?= ( isset($active['projectId']) && $key->id == $active['projectId']) ? 'selected' : '' ?>><?php echo e($key->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
						<?php endif; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-5 col-lg-6 padding-twenty">
                <span class="pull-right font-thirty heading-color">Email Project Report</span>
            </div>
            <!-- <h2  class="col-md-10 showDate"  id="timeTrackShowDate"></h2>-->
        </div>

        <div class="row"></div>
		<div class="row">
			<div id="email-content" class="email-content hidden">
				<?php
				$mailContent='';
				$mailBody='';
				$mailHeaderList="<p class='font-bold' >Major  Project/task in hand: </p><ol>";

				if(isset($projectReport)){
				   $prevProjId='';
				   foreach ($projectReport as $key => $task) {
					  if($prevProjId != $task->project_id){
					  if($prevProjId != ''){
						 $mailBody .= '</ul>';
					  }
					  $mailBody .= "<p class='font-bold'> <u>". isset($task->project->project_name) ? $task->project->project_name : '' ." </u></p>";
					  $mailBody .= "<ul>";
					  $mailHeaderList .= "<li>".$task->project->project_name."</li>";
					  }
					  $mailBody .= "<li>".$task->task_titly. " - ".(isset($task->user->name) ? $task->user->name : 'No Person')." - ".($task->done ==1 ? 'Done' : 'In Process')."</li>";
					  $prevProjId = $task->project_id;
				   }
				   $mailContent = $mailBody;
				}else{
				   $mailContent = "No Content";
				}
				echo $mailHeaderList .= "</ol> </br> <p class='font-bold'> Details: <u> </u></p>";
				echo $mailContent;
				?>
			</div> 
		</div>
        <div class="row">
            <div class="emailreportbutton check-box-center">
				<a href="#" class="btn btn-sm button-export button-email" id="button-email">Email Report Content</a>
			</div>
        </div>
           
		   
		 <!-----
		<div class="block-area">
			<div class="span12 top-border compressed-area">
				 <form class="form-horizontal" role="form" method="post" action="/reports/sendemailcontent" >
					<?php echo e(csrf_field()); ?>

				 <div class="control-group row">
					<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right margin-top-ten project-label">
						<span class="control-label" for="touser">To *</span>
					</div>
					<div class="controls col-xs-9 col-sm-9 col-md-9 col-lg-9 project-input">
						<select name="tousers[]" class="input-xlarge focused my_input input-height" multiple id="tousers" required>
						 <?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
							<option value="<?php echo e($key->email); ?>" ><?php echo e($key->name); ?></option>
						  <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
						</select>
					</div>
					
					
				</div>
				<div class="control-group row">
					<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right margin-top-ten project-label">
						<span class="control-label" for="ccuser">CC</span>
					</div>
					<div class="controls col-xs-9 col-sm-9 col-md-9 col-lg-9 project-input">
						<select name="ccusers[]" class="input-xlarge focused my_input input-height" multiple id="ccusers">
							<?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
								<option value="<?php echo e($key->email); ?>" ><?php echo e($key->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
						  </select>
					</div>
					
					
				</div>
				
				<div class="control-group row">
					<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right margin-top-ten project-label">
						<span class="control-label" for="tasks"></span>
					</div>
					<div class="controls col-xs-9 col-sm-9 col-md-9 col-lg-9 project-input">
						 
						 <div class="tasks full-border padding-five task-block-container mailbody" id="tasks">
							 <?php 
								/*$mailContent='';
								$mailBody='';
								$mailHeaderList="<p class='font-bold'>Major Project/Task in hand: </p><ol>";
								if(isset($projectReport)){
								   $prevProjId='';
								   $status='';
								   foreach ($projectReport as $key => $task) {    
									  if($prevProjId != $task->project_id){
									  if($prevProjId != ''){
										 $mailBody .= '</ul>';
									  }
									  $mailBody .= "<p class='font-bold'> <u>". isset($task->project->project_name) ? "<span class='font-bold'>".$task->project->project_name."</span>" : '' ." </u></p>";
									  $mailBody .= "<ul>";
									  $mailHeaderList .= "<li>".$task->project->project_name."</li>";
									  }
									  $mailBody .= "<li>".$task->task_titly. " - ". (isset($task->user->name) ? $task->user->name : 'No Person'). "</li>";
									  $prevProjId = $task->project_id;
								   }
								   $mailContent = $mailBody;
								}else{
								   $mailContent = "No Content";
								}	
								echo $mailHeaderList .= "</ol> </br> <p class='font-bold'> Details: <u> </u></p>";
								echo $mailContent; */
							 ?>
						 </div>
					</div>
					<input type="hidden" name="content" id="content" value="<?php //echo $mailHeaderList ?> <?php //echo $mailContent ?>" required />
					<input type="hidden" name="date-range" id="date-range" value="<?php //echo date("d-m-Y", strtotime($active['start'])); ?> to <?php //echo date("d-m-Y", strtotime($active['end'])); ?>"  />
				</div>
				<div class="row">
					<div  class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label class="control-label col-xs-2 col-sm-2 col-md-2 col-lg-2" for=""></label>
						<div class="controls col-xs-10 col-sm-10 col-md-10 col-lg-10 padding-five">
							<input type="submit" name="send-report" id="send-report" class="btn btn-sm button-export font-fifteen send-report" value="Send">
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
		<!----->
		   
        </div>
    </div>
<script src="/js/reports.js"></script>
<script src="/js/tasks.js"></script>
<script src="/js/jquery.multiselect.js"></script>
<script src="/js/multipleselect.js"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.index_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>