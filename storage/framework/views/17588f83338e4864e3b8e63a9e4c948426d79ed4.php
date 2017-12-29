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

    <div id="conteiner" class="container" data-date="<?= isset($date)? $date : '' ?>"
         data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>"
         data-token="<?php echo e(Session::token()); ?>"
         data-log-active = "<?= isset($_COOKIE['logTrackActiveLogId']) ? $_COOKIE['logTrackActiveLogId'] : ''?>">


        <div class="row margin-top-twenty">
            <!--
            <div class="col-md-2 btn-toolbar" style="vertical-align: inherit">
                <div id="timeStep5" class="btn-group">
                    <button class="btn btn-sm calendarPrevDayReport">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </button>
                    <button class="btn btn-sm calendarNextReport">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </button>
                    <button class="btn btn-sm d5">
                        <span class="glyphicon glyphicon-th"></span>
                    </button>
                </div>
            </div> -->

            <h2  class="col-md-6 showDate"  id="timeTrackShowDate"></h2>
            <div class="col-md-6 heading-color pull-right font-thirty text-right">Status Report</div>
        </div>

        <div class="row margin-left-twenty margin-top-twenty mailbody">
    		<!--	<div id="status-content" class="status-content" style="display:block;"> -->
            <form class="form-horizontal" role="form" method="POST" action="/reports/status/<?= $date ?>" >
				<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
				<div class="col-md-12 row margin-top-twelve my_input">
				  <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 font-bold padding-top-ten label-width">To: *</div>
				  <div class="row col-xs-10 col-sm-10 col-md-10 col-lg-10">
					<select name="tousers[]" class="input-xlarge focused my_input input-height" multiple id="tousers">
					  <?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userdt): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
						<?php if(isset($toEmails) && in_array($userdt->email, $toEmails)): ?>
						  <option value="<?php echo e($userdt->email); ?>" selected="selected"><?php echo e($userdt->name); ?></option>
						<?php else: ?>
						  <option value="<?php echo e($userdt->email); ?>" ><?php echo e($userdt->name); ?></option>
						<?php endif; ?>
					  <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
					</select>
				  </div>
				</div>
				<?php if( $allUsers == true ): ?>
				  <div class="col-md-12 row margin-top-twelve my_input">
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 font-bold padding-top-ten label-width">Cc:</div>
					<div class="row col-xs-10 col-sm-10 col-md-10 col-lg-10">
					  <select name="ccusers[]" class="input-xlarge focused my_input input-height" multiple id="ccusers">
						<?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userdt): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
						  <?php if(isset($ccEmails) && in_array($userdt->email, $ccEmails)): ?>
							<option value="<?php echo e($userdt->email); ?>" selected="selected"><?php echo e($userdt->name); ?></option>
						  <?php else: ?>
							<option value="<?php echo e($userdt->email); ?>" ><?php echo e($userdt->name); ?></option>
						  <?php endif; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
					  </select>
					</div>
				  </div>
				<?php endif; ?>

				<div class="col-md-12 row margin-top-twelve my_input">
					<div class="saveemails-block" >
						<input type="checkbox" name="saveemails" > <span class="saveemails-top relative">Save TO/CC Recipient.</span>
					</div>
				</div>
				<div class="col-md-12 row mailbody margin-top-twelve my_input" >
				  <div class="row status-content-width margin-top-twenty margin-left-fifteen">
					<!-- <div id="status-content" class="status-content" style="display:block;"> -->
					  <?php

					  $mailContent='';
					  $mailBody='';
					  $tomorrowBody='';
					  //$mailHeaderList="<p style='font-weight:bold;'>Project Name   Task Title     Allocate time Track Time   </p> ";

					  if(isset($dayReport)){
						 $prevProjId='';
						 foreach ($dayReport as $key => $task) {
						  if(Auth::user()['original']['id'] == $task->assign_to){
							  if($prevProjId != $task->project_id){
							  if($prevProjId != ''){
							   $mailBody .= '</ul>';
							  }
								$mailBody .= "<p class='font-bold'> <u>". $task->project->project_name ." </u></p>";
								$mailBody .= "<ul>";
							  }
							  $mailBody .= "<li>".$task->task_titly. "&nbsp;(".(isset($task->alloceted_hours) ? $task->alloceted_hours.' Hrs' : '').")&nbsp;&nbsp;&nbsp;".(isset($task->status_time) ? $task->status_time.' Hrs' : '')."</li>";
							  $prevProjId = $task->project_id;
							 }
							 $mailContent = $mailBody;
						 }
					  }else{
						 $mailContent = "No Content";
					  }
					  //echo $mailHeaderList;
					  $mailContent .= "</ul>";
					  //$mailContent .= "<p style='font-weight:bold;'>Total Hours : ".$total['status_total']."</p>";

					  echo $mailContent;
					  ?>
					  <p class='font-bold'>Total Hours : <?php echo e($total['status_total']); ?></p>
					  <div class="col-md-12 bottom-five">
						<textarea id="extratask" class="status-content-width" name="extratask" rows="4" cols="80"># </textarea>
					  </div>
					  <div class="col-md-12 tom-task-heading">
						<span class='font-bold'> Tomorrow’s Task :</span>
						<span> Select tomorrow task from list.</span>
					  </div>
					  <?php

					  if(isset($todayTasks)){
						//$tomorrowBody .= "<p style='font-weight:bold;'> <u> Tomorrow’s Task : </u></p>";
						//echo $tomorrowBody;

						$tomorrowBody .= "<div class='col-md-10 task-container scroll'>";

						foreach ($todayTasks as $key => $task) {
							//$tomorrowBody .= "<li>".$task->task_titly."</li>";
							$tomorrowBody .= "<div class='tom-task-block'>";
							$tomorrowBody .= "<input type='checkbox' name='tomorrow_list[]' value='".$task->task_titly."'><span class='tom-task saveemails-top relative'>".$task->task_titly."</span>";
							$tomorrowBody .= "</div>";
						}
						$tomorrowBody .= "</div>";
					  }
					   echo $tomorrowBody;
					   ?>

					</div>
				  </div>
			   <!--      </div> -->


				<input type="hidden" name="message" value="<?php echo  htmlspecialchars($mailContent) ?>" />
				<input type="hidden" name="date" value="<?php echo $date ?>" />
				<input type="hidden" name="total" value="<?php echo $total['status_total'] ?>" />
				<input type="hidden" id="firstday" name="firstday" value="" />
				
				<div class="col-md-12  margin-top-twelve button-style">
				  <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 font-bold padding-top-ten label-width"></div>
				  <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 row">
					  <input type="submit" name="send" id="sendstatusmail" class="btn btn-large button-export pull-right margin-right-twenty" value="Send">
				  </div>
				</div>
				
				<div class="modal-footer"></div>
		  </form>
    	</div>
    </div>
</div>
<script src="/js/reports.js"></script>
<script src="/js/jquery.multiselect.js"></script>
<script src="/js/multipleselect.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.index_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>