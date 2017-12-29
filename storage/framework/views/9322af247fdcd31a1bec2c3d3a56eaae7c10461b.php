<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <div class="container" id="conteiner" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>"
            data-type-action="<?= isset( $task )  ? 'edit' : 'add'?>">

        <div class="row">
            <div class="row-fluid">
                <div class="heading-top-margin">

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
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                    <label class="control-label" for="CompanyTaskId" style="text-align: left;">Client *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                        <select name="company_id" class="input-xlarge focused my_input" id="CompanyTaskId" style="height: 42px;" required >

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
                                                <?php if( $task[0]->company_id == $key->id ): ?>
                                                    <option value="<?php echo e($key->id); ?>" selected><?php echo e($key->company_name); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                        <?php endif; ?>
                                        </select>

                                            <?php if($errors->has('company_id')): ?>
                                                <span class="help-block">
                                                        <strong style="color:#802420"><?php echo e($errors->first('company_id')); ?></strong>
                                                    </span>
                                            <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                    <label class="control-label" for="taskProjectId" style="text-align: left;">Project *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                    <?php if( $status == 'Developer' ): ?>
                                        <select name="project_id" class="input-xlarge focused my_input" id="taskProjectId" style="height: 42px;" required
                                                <?= ( $status == 'Developer') ? ' disabled' : '' ?> >
                                        <?php if( isset( $projects ) ): ?>
                                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                    <option value="<?php echo e($project->id); ?>" selected><?php echo e($project->project_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                        <?php else: ?>
                                            <option>No project available</option>
                                        <?php endif; ?>
                                        <!-- </select> -->
                                    <?php else: ?>
                                        <select name="project_id" class="input-xlarge focused my_input"  id="taskProjectId" style="height: 42px;" required>
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
                                                    <?php if( $task[0]->project_id == $key->id ): ?>
                                                        <option value="<?php echo e($key->id); ?>" selected><?php echo e($key->project_name); ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    <?php endif; ?>

                                    <?php if($errors->has('project_id')): ?>
                                        <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('project_id')); ?></strong>
                                            </span>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                    <label class="control-label" for="taskTypeId" style="text-align: left;">Task Type *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                  <!-- Mith: 03/29/17: Update task type data populate from database  -->
                                    <select name="task_type" class="input-xlarge focused my_input"  id="taskTypeId" style="height: 42px;"
                                    <?= ( $status == 'Developer') ? ' disabled' : '' ?> required />
                                    <?php if( !isset( $task ) ): ?>
                                        <?php echo e($setValue=""); ?>

                                        <option  defaul value="">Please select Task Type</option>
                                        <!--
                                        <?php if(isset($tasktype->task_type)): ?>
                                            <option value="<?php echo e($client->id); ?>" selected><?php echo e($tasktype->task_type); ?></option>
                                        <?php endif; ?>  -->

                                        <?php if( old() && old('task_type')): ?>
                                            <?php echo e($setValue = old('task_type')); ?>

                                        <?php endif; ?>

                                        <?php $__currentLoopData = $tasktype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <?php if( $key->task_type == $setValue): ?>
                                              <option  value="<?php echo e($key->task_type); ?>" selected><?php echo e($key->task_type); ?></option>
                                            <?php else: ?>
                                              <option  value="<?php echo e($key->task_type); ?>"><?php echo e($key->task_type); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                                    <?php elseif( isset( $task ) ): ?>
                                        <?php $__currentLoopData = $tasktype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <?php if( $task[0]->task_type == $key->task_type ): ?>
                                                <option value="<?php echo e($key->task_type); ?>" selected><?php echo e($key->task_type); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                    <?php endif; ?>

                                    </select>
                                    <?php if($errors->has('task_type')): ?>
                                        <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('task_type')); ?></strong>
                                            </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                    <label class="control-label" for="taskTittleId" style="text-align: left;">Task Title *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">

                                    <input name="task_titly" class="input-xlarge focused my_input" id="taskTittleId"   type="text" required
                                         value="<?= ( isset( $task[0]->task_titly ) ) ? $task[0]->task_titly : (( old('task_titly') ) ? old('task_titly') : '') ;?>"
                                        <?= ( $status == 'Developer') ? ' disabled' : '' ?>>
                                    <?php if($errors->has('task_titly')): ?>
                                        <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('task_titly')); ?></strong>
                                            </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                    <label class="control-label" for="taskDescriptionId" style="text-align: left;">Description</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                    <textarea name="task_description" class="input-xlarge focused my_input" id="taskDescriptionId" rows="6"  type="text"
                                            ><?= ( isset( $task[0]->task_description ) ) ? $task[0]->task_description : ( old('task_description') ? old('task_description') : '' ) ;?></textarea>
                                    <?php if($errors->has('task_description')): ?>
                                        <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('task_description')); ?></strong>
                                            </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                    <label class="control-label" for="HourlyRateProhectId" style="text-align: left;">Allocated Hours</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                    <!-- Mith 03/31/17: Update html5 validation check  -->
                                    <input name="alloceted_hours" class="input-xlarge focused my_input" id="HourlyRateProhectId" title="Please match the requested format HH:MM"  pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="HH:MM"  step="0.15" type="text"

                                            value="<?= ( isset( $task[0]->alloceted_hours ) ) ? str_replace('.', ':',$task[0]->alloceted_hours) : '' ;?>"
                                        <?= ( $status == 'Developer') ? ' disabled' : '' ?> />
                                    <?php if($errors->has('alloceted_hours')): ?>
                                        <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('alloceted_hours')); ?></strong>
                                            </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                    <label class="control-label" for="AssignToId" style="text-align: left;">Assign To</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                    <select name="assign_to" class=" input-xlarge focused my_input "   id="AssignToId" style="height: 42px;" data-all="true">
                                        <?php if( isset( $task[0]->assign_to ) ): ?>
                                            <?php if(isset($user->name)): ?>
                                                <option id="username" data-id="<?php echo e($user->id); ?>" value="<?php echo e($user->id); ?>" selected><?php echo e($user->name); ?></option>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <option selected disabled></option>
                                        <?php endif; ?>
                                    </select>
                                    <?php if($errors->has('assign_to')): ?>
                                        <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('assign_to')); ?></strong>
                                            </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">

                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4" >
                                    <label class="control-label" for="BillableId" style="">Billable</label>
                                    <input type="checkbox"  name="billable" id="BillableId" value="1"
                                        <?php if( isset( $task[0]->billable ) && $task[0]->billable == true ): ?>
                                            checked
                                        <?php endif; ?>
                                    ><br>
                                </div>
                            </div>

                            <div class="form-actions row">
                                <label class="control-label col-sm-2" for=""></label>
                                <button type="submit" class="btn btn-large button-orange" formaction="">Save</button> &nbsp;&nbsp;
                                <a  href="<?php echo e(url('/task/all')); ?>" class="btn btn-large button-orange" style="font-weight: normal;" >Cancel</a>
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