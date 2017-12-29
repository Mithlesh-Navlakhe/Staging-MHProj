<?php $__env->startSection('content'); ?>
    <link href="/css/jquery.multiselect.css" rel="stylesheet" type="text/css">
    <style>
      ul,li { margin:0; padding:0; list-style:none;}
      .label { color:#000; font-size:16px;}
	  <!---  SN 05/04/2017: added below css  -->
	  .ms-options-wrap .selectbutton{width: 91.66% !important;height: 42px; font-size: 14px;border-right: 1px solid #CCC!important;border-top: 1px solid #CCC!important;border-left: 1px solid #CCC!important;border-bottom: 1px solid #CCC!important;}
	  .ms-options {max-width: 100%;margin: 0 !important;border-right: 1px solid #CCC!important;border-top: 1px solid #CCC!important;border-left: 1px solid #CCC!important;border-bottom: 1px solid #CCC!important;}
	  .selectbutton{height: 42px; border: 1px solid #CCC !important; font-size: 15px; padding-left: 15px;}
    </style>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <div id="conteiner" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>"></div>

    <div class="container">
        <div class="row-fluid">
            <div class="span12 heading-top-margin">

                <div class="heading-without-datepicker">Edit User</div>
            </div>
        </div>
        <div class="row-fluid" id="login" >
            <!-- block -->
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" role="form" method="POST" action="<?php echo e(url('/user/update/' . $user->id)); ?>">
                        <?php echo e(csrf_field()); ?>


                        <div class="control-group row" >
                            <label class="control-label col-sm-2" for="focusedInput">User type</label>
                            <div class="controls col-sm-10">
                                <select name="employe" class="input-xlarge focused" id="selectTeam" style="height: 42px;" >
                                    <?php $users = ['Super Admin','Admin', 'Supervisor', 'HR Manager', 'Lead', 'Developer', 'QA Engineer']; ?>
                                    <option><?php echo e($user->employe); ?></option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                        <?php if( $user->employe != $val ): ?>
                                            <?php if($status == 'Admin' || $status == 'Super Admin'): ?>
                                                <option><?= $val?></option>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                </select>

                            </div>
                        </div>
                        <div class="control-group row">
                            <label class="control-label col-sm-2" for="focusedInput">Name *</label>
                            <div class="controls col-sm-10">
                                <input name="name" class="input-xlarge focused" id="focusedInput" value="<?php echo e($user->name); ?>"  type="text" required />
                                <?php if($errors->has('name')): ?>
                                    <span class="help-block ">
                                            <strong style="color:#802420"><?php echo e($errors->first('name')); ?></strong>
                                        </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="control-group row" id="hourlyRate"  style="display:none;">
                            <label class="control-label col-sm-2" for="hourlyRateId">Hourly rate</label>
                            <div class="controls col-sm-10">

                                <input name="hourlyRate" class="input-xlarge focused"  id="hourlyRateId" value="<?= $user->hourly_rate ?>" type="number" step="0.01">

                                <?php if($errors->has('hourlyRate')): ?>
                                    <span class="help-block">
                                                <strong><?php echo e($errors->first('hourlyRate')); ?></strong>
                                            </span>
                                <?php endif; ?>
                            </div>

                        </div>
						<!-- Mith: 05/05/2017: uncomment to show team dropdown. 
                        <div id="team_name" style="display:none;" class="control-group row">
                            <label class="control-label col-sm-2" for="focusedInput">Team</label>

                            <?php if( $teams == true ): ?>
                                <div class="controls col-sm-10">
                                    <select name="users_team_id" class="input-xlarge focused" style="height: 42px;">
                                        <?php if(isset($teamActive)): ?>
                                            <option value="<?php echo e($teamActive->id); ?>" selected><?php echo e($teamActive->team_name); ?></option>
                                        <?php else: ?>
                                            <option selected disabled>Please change Team</option>
                                        <?php endif; ?>
                                        <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <?php if( isset( $teamActive ) && $teamActive->id == $team->id ): ?>

                                            <?php else: ?>
                                                <option value="<?php echo e($team->id); ?>"><?php echo e($team->team_name); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                                    </select>
                                </div>

                            <?php endif; ?>

                            <?php if($errors->has('team_name')): ?>
                                <span class="help-block">
                                            <strong><?php echo e($errors->first('team_name')); ?></strong>
                                        </span>
                            <?php endif; ?>
                        </div> -->
                        <div id="lead_name" style="display:none;" class="control-group row">
                            <label class="control-label col-sm-2" for="focusedInput">Lead</label>
                            <?php if( $leads == true ): ?>
                                <div class="controls col-sm-10">
                                    <select name="lead_id" class="input-xlarge focused" style="height: 42px;">
                                        <?php if(isset($leadActive)): ?>
                                            <option value="<?php echo e($leadActive->id); ?>" selected><?php echo e($leadActive->name); ?></option>
                                        <?php else: ?>
                                            <option selected disabled>Please change Lead</option>
                                        <?php endif; ?>
                                        <?php $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <?php if( isset( $leadActive ) && $leadActive->id == $lead->id ): ?>

                                            <?php else: ?>
                                                <option value="<?php echo e($lead->id); ?>"><?php echo e($lead->name); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <?php if($errors->has('lead_name')): ?>
                                <span class="help-block">
                                    <strong><?php echo e($errors->first('lead_name')); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div id="project_name" style="display:none;" class="control-group  row">
                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
								<label class="control-label" for="focusedInput">Project</label>
							</div>
                            <?php if( $projects == true ): ?>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 ">
                                  <select name="projects[]" class="input-xlarge focused my_input " multiple id="projects" style="height: 42px;">
                                    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $projt): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                      <?php if(isset($projectActive) && in_array($projt->id, $projectActive)): ?>
                                        <option value="<?php echo e($projt->id); ?>" selected="selected"><?php echo e($projt->project_name); ?></option>
                                      <?php else: ?>
                                        <option value="<?php echo e($projt->id); ?>" ><?php echo e($projt->project_name); ?></option>
                                      <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                  </select>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-actions row">
                            <label class="control-label col-sm-2" for=""></label>
                            <button type="submit" class="btn btn-large button-orange" formaction="">Save</button> &nbsp;&nbsp;
                            <a  href="<?php echo e(url('/user/all')); ?>" class="btn btn-large button-orange" style="font-weight: normal;" >Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /block -->
        </div>
    </div>
    <script src="/js/jquery/jquery-3.1.1.min.js"></script>
    <script src="/js/registration.js"></script>
    <script src="/js/jquery.multiselect.js"></script>
    <script>
          $('#projects').multiselect({
              columns: 1,
              placeholder: 'Select projects',
              search: true
          });
		  
		  //SN 05/04/2017: added code
		  $('.ms-options-wrap > button').css({
              'overflow': 'hidden',
              'text-overflow': 'ellipsis',
              'white-space': 'nowrap',
			  'width': '91.33% !important',
			  'height':'42px'
          });
		  $('.ms-options-wrap').css('width','91.33%');
		  $('.ms-options-wrap > button').addClass("selectbutton").css('border','1px solid #CCC !important');
		  $("ul > li > label").css({'padding-left':'25px', 'font-size':'15px'});
		  $("ul > li > label > input").css('margin-left','5px');
   </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.index_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>