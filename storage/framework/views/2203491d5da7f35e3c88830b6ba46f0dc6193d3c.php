<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <div class="container" id="conteiner" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>"
            data-type-action="<?= isset( $task )  ? 'edit' : 'add'?>">

        <div class="row">
            <div class="row-fluid">
                <div class="margin-left-large col-md-12">
                    <div class="heading-without-datepicker"><?= ( isset( $task ) ) ? 'Edit' : 'Add' ;?> task</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row-fluid" id="login" >
                <!-- block -->
                <div class="block-content collapse in">
                    <div class="span12">
                        <form class="form-horizontal" role="form" method="POST" action="<?= ( isset( $task ) ) ? '/project/update' . $task[0]->id : '/project/create' ;?>">
                            <?php echo e(csrf_field()); ?>

                            <?php if(isset($task)): ?>

                            <?php endif; ?>
                            <?php if( $status != 'Developer' || $status != 'Developer' ): ?>
                            <div class="control-group row">
                                <?php if( isset( $client ) ): ?>
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="CompanyTaskId" >Client *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                        <select name="company_id" class="input-xlarge focused my_input input-height" id="CompanyTaskId" required >

                                        <?php if( !isset( $task ) ): ?>
                                            <?php echo e($setComId=""); ?>

                                            <option  defaul value="">Please select Client</option>
                                            <?php if( old() && old('company_id')): ?>
                                                <?php echo e($setComId = old('company_id')); ?>

                                            <?php endif; ?>
                                            <?php if(isset($client->company_name)): ?>
                                                <option value="<?php echo e($client->id); ?>" selected><?php echo e($client->company_name); ?></option>
                                            <?php endif; ?>

                                            <?php $__currentLoopData = $client; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                <?php if($key->id == $setComId): ?>
                                                  <option  value="<?php echo e($key->id); ?>" selected=""><?php echo e($key->company_name); ?></option>
                                                <?php else: ?>
                                                  <option  value="<?php echo e($key->id); ?>" ><?php echo e($key->company_name); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                                        <?php elseif( isset( $task ) ): ?>
                                            <?php $__currentLoopData = $client; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
												<option value="<?php echo e($key->id); ?>" <?php if( $task[0]->company_id == $key->id ): ?> selected  <?php endif; ?> ><?php echo e($key->company_name); ?></option>
                                                
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                        <?php endif; ?>
                                        </select>

										<?php if($errors->has('company_id')): ?>
											<span class="help-block error-text">
												<strong><?php echo e($errors->first('company_id')); ?></strong>
											</span>
										<?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
                                    <label class="control-label text-left" for="taskProjectId" >Project *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <?php if( $status == 'Developer' ): ?>
                                        <select name="project_id" class="input-xlarge focused my_input input-height" id="taskProjectId" required >
                                        <?php if( isset( $task ) ): ?>
                                            <?php if( isset( $projects ) ): ?>
                                                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                        <option value="<?php echo e($project->id); ?>" <?php if( $task[0]->project_id == $project->id ): ?> selected <?php endif; ?> ><?php echo e($project->project_name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                            <?php else: ?>
                                                <option>No project available</option>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if( isset( $projects ) ): ?>
                                                <option value="" selected>Select project</option>
                                                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                    <option value="<?php echo e($project->id); ?>" class="<?php echo e(($project->project_name == 'Leave') ? 'hidden' : ''); ?>"><?php echo e($project->project_name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                            <?php else: ?>
                                                <option>No project available</option>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        </select>
                                    <?php else: ?>
                                        <select name="project_id" class="input-xlarge focused my_input input-height"  id="taskProjectId"  required>
                                            <?php if( !isset( $task ) ): ?>
                                              <?php echo e($setPrjId=""); ?>

                                              <?php if( old() && old('project_id')): ?>
                                                  <?php echo e($setPrjId = old('project_id')); ?>

                                                  <?php $__currentLoopData = $project; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                    <?php if($key->id == $setPrjId): ?>
                                                      <option value="<?php echo e($key->id); ?>" selected><?php echo e($key->project_name); ?></option>
                                                    <?php endif; ?>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                              <?php endif; ?>
                                            <?php elseif( isset( $task ) ): ?>
                                                <?php $__currentLoopData = $project; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                    <option value="<?php echo e($key->id); ?>" <?php if( $task[0]->project_id == $key->id ): ?> selected <?php endif; ?> > <?php echo e($key->project_name); ?></option>
													
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    <?php endif; ?>

                                    <?php if($errors->has('project_id')): ?>
                                        <span class="help-block error-text">
											<strong ><?php echo e($errors->first('project_id')); ?></strong>
										</span>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left"  for="taskTypeId">Task Type *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                  <!-- Mith: 03/29/17: Update task type data populate from database  -->
                                    <select name="task_type" class="input-xlarge focused my_input input-height"  id="taskTypeId" required />
                                    <?php if( !isset( $task ) ): ?>
                                        <option  defaul value="">Please select Task Type</option>
                                        
                                         <?php $__currentLoopData = $tasktype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <?php if( $key->id == old('task_type')): ?>
                                              <option  value="<?php echo e($key->id); ?>" selected><?php echo e($key->task_type); ?></option>
										    <?php elseif($key->task_type == 'Leave'): ?>
											  <option value="<?php echo e($key->id); ?>" class="hidden"><?php echo e($key->task_type); ?></option>
                                            <?php else: ?>
                                              <option  value="<?php echo e($key->id); ?>"><?php echo e($key->task_type); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                                    <?php elseif( isset( $task ) ): ?>
                                        <?php $__currentLoopData = $tasktype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
											<!-- SN 04/17/2017: updated below code to show selected value -->
											<option value="<?php echo e($key->id); ?>" <?php if( ($task[0]->task_type == $key->id) || ($task[0]->task_type == $key->task_type) ): ?> selected <?php endif; ?> ><?php echo e($key->task_type); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                    <?php endif; ?>

                                    </select>
                                    <?php if($errors->has('task_type')): ?>
                                        <span class="help-block error-text">
											<strong><?php echo e($errors->first('task_type')); ?></strong>
										</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="taskTittleId">Task Title *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">

                                    <input name="task_titly" class="input-xlarge focused my_input" id="taskTittleId"   type="text" required
                                         value="<?= ( isset( $task[0]->task_titly ) ) ? $task[0]->task_titly : (( old('task_titly') ) ? old('task_titly') : '') ;?>"
                                        >
                                    <?php if($errors->has('task_titly')): ?>
                                        <span class="help-block error-text">
                                                <strong><?php echo e($errors->first('task_titly')); ?></strong>
                                            </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="taskDescriptionId" >Description</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <textarea name="task_description" class="input-xlarge focused my_input" id="taskDescriptionId" rows="6"  type="text"
                                            ><?= ( isset( $task[0]->task_description ) ) ? $task[0]->task_description : ( old('task_description') ? old('task_description') : '' ) ;?></textarea>
                                    <?php if($errors->has('task_description')): ?>
                                        <span class="help-block error-text">
                                                <strong><?php echo e($errors->first('task_description')); ?></strong>
                                            </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
                                    <label class="control-label text-left" for="HourlyRateProhectId">Allocated Hours</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <!-- Mith 03/31/17: Update html5 validation check   -->
                                    <input name="alloceted_hours" class="input-xlarge focused my_input" id="HourlyRateProhectId" title="Please match the requested format HH:MM"  pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="HH:MM"  step="0.15" type="text"

                                            value="<?= ( isset( $task[0]->alloceted_hours ) ) ? str_replace('.', ':',$task[0]->alloceted_hours) : ( (old() && old('alloceted_hours')) ? old('alloceted_hours') : '' ) ;?>" />
                                    <?php if($errors->has('alloceted_hours')): ?>
                                        <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('alloceted_hours')); ?></strong>
                                            </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
                                    <label class="control-label text-left" for="AssignToId">Assign To *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <!-- SN 05-05-2017: added below check for developer if added task by developer -->
									<?php if($status == "Developer"): ?>
										<select name="assign_to" class=" input-xlarge focused my_input" id="AssignTo" style="height: 42px;" data-all="true" data-value="<?php if(old() && old('assign_to')): ?> <?php echo e(old('assign_to')); ?> <?php endif; ?>" required>	
											<?php if( isset( $task[0]->assign_to ) ): ?>
												<?php if(isset($task[0]->user->name)): ?>
													<option id="username" data-id="<?php echo e($task[0]->assign_to); ?>" value="<?php echo e($task[0]->assign_to); ?>" selected><?= $task[0]->user->name ?></option>
												<?php endif; ?>
											<?php else: ?>
												<option id="username" data-id="<?php if(Auth::user()->id): ?><?php echo e(Auth::user()->id); ?><?php endif; ?>" value="<?php if(Auth::user()->id): ?><?php echo e(Auth::user()->id); ?><?php endif; ?>" selected><?php echo e(Auth::user()->name); ?></option>
											<?php endif; ?>
										</select>
									<?php else: ?>
										<select name="assign_to" class=" input-xlarge focused my_input input-height" id="AssignToId" data-all="true" data-value="<?php if(old() && old('assign_to')): ?> <?php echo e(old('assign_to')); ?> <?php endif; ?>" required>
											<?php if( isset( $task[0]->assign_to ) ): ?>
												<?php if(isset($user->name)): ?>
													<option id="username" data-id="<?php echo e($user->id); ?>" value="<?php echo e($user->id); ?>" selected><?php echo e($user->name); ?></option>
												<?php endif; ?>
											<?php else: ?>
												<option selected disabled></option>
											<?php endif; ?>
										</select>
									<?php endif; ?>
                                    <?php if($errors->has('assign_to')): ?>
                                        <span class="help-block error-text">
											<strong ><?php echo e($errors->first('assign_to')); ?></strong>
										</span>
                                    <?php endif; ?>
                                </div>
								<!-- SN 05/10/2017: added hidden input for task assigned by -->
								<input name="task_assign_by" class="input-xlarge focused my_input" id="taskassignbyId" type="hidden" value="<?php if(Auth::user()->id): ?><?php echo e(Auth::user()->id); ?><?php endif; ?>" >
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width"></div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width" >
                                    <label class="control-label" for="BillableId" style="">Billable</label>
                                    <input type="checkbox"  name="billable" id="BillableId" value="1"
										<?= ( isset( $track ) && $track[0]->billable == 1 ) ? ' checked' : ((old('billable') == '1') ? ' checked': '' ) ; ?>
                                        <?php if( isset( $task[0]->billable ) && $task[0]->billable == true ): ?>
                                            checked
                                        <?php endif; ?>
										>
                                    <br>
                                </div>
                            </div>

                            <div class="form-actions row col-lg-12 col-md-12 ">
                                <label class="control-label col-sm-2 col-lg-2 col-md-2 col-xs-4 label-mob-width" for=""></label>
								<div class="col-sm-10 col-lg-10 col-md-10 input-mob-widt">
									<button type="submit" class="btn btn-large button-orange" formaction="">Save</button> &nbsp;&nbsp;
									<a  href="<?php echo e(url('/task/all')); ?>" class="btn btn-large button-orange">Cancel</a>
								</div>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- /block -->
            </div>
        </div>
    </div>
    <script src="/js/jquery/jquery-3.1.1.min.js"></script>
    <script src="/js/registration.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.index_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>