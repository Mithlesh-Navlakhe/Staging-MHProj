<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>

    <div class="modal fade" id="delete-task" role="dialog">
        <div class="modal-dialog"  >
            <!-- Modal content-->
            <div class="modal-content">
                <div id="modalConfirmDeleteTask"></div>
            </div>
        </div>
    </div>

    <div id="conteiner" class="container" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>" data-start = "<?=  isset($active['start']) ? $active['start'] : '' ?>"
		data-end = "<?=  isset($active['end']) ? $active['end'] : '' ?>" >
        <?php if(!isset($tasksForProject)): ?>
       
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
		
        <?php else: ?>
            <div class="row-fluid">
                
            </div>
            <br>
            <div class="row my_row">
                <div class = "col-lg-7 col-md-7 col-sm-8 col-xs-12">

                    <div class="bs-calltoaction bs-calltoaction-default">
                        <div class="row">
                            <div class="col-md-9 cta-contents">
                                <div class="span12 add-border-bottom">
                                    <h1 class="h3-my">Project: <strong><?php echo e($project->project_name); ?></strong></h1>
                                </div>
                                <div class="cta-desc margin-top-twenty">
                                    <div class="row ">
                                        <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 text-right" >
                                            <label class="control-label text-left" for="ProjectNameId" >Company</label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
                                            <?php echo e($project->company_name); ?>

                                        </div>
                                    </div>
                                    <div class="row my_row">
                                        <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 text-right">
                                            <label class="control-label text-left" for="ProjectNameId" >Project</label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
                                            <?php echo e($project->project_name); ?>

                                        </div>
                                    </div>
                                    <div class="row my_row">
                                        <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 text-right" >
                                            <label class="control-label text-left" for="ProjectNameId">Hourly rate</label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7" >
                                            <?php echo e($project->hourly_rate); ?>

                                        </div>
                                    </div>
                                    <div class="row my_row">
                                        <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 text-right" >
                                            <label class="control-label text-left" for="ProjectNameId" >Lead</label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
                                            <?php echo e($project->name); ?>

                                        </div>
                                    </div>

                                </div>
                                <div class="row my_row">
                                    <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 text-right" >
                                        <label class="control-label text-left" for="ProjectNameId">Notes</label>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7" >
                                        <?php echo e($project->notes); ?>

                                    </div>
                                </div>

                            </div>


                        <div class="col-md-3 cta-button">
                            <?php if($status == 'Admin' || $status == 'Lead' || $status == 'Supervisor' || $status == 'Super Admin'): ?>
                                <a href="/project/update/<?php echo e($project->id); ?>" class="btn btn-info margin-left-large">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit Project
                                </a>
                            <?php endif; ?>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
    <div class="row my_row"></div>
    <?php endif; ?>
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
                                <?php if($status == 'Admin' || $status == 'Lead' || $status == 'Supervisor' || $status == 'Developer' ||  $status == 'QA Engineer' || $status == 'Super Admin'): ?>
                                    <th class="center action-header">Action</th>
                                <?php endif; ?>
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
                                <?php if($status == 'Super Admin' || $status == 'Admin' || $status == 'Lead' || $status == 'Supervisor' || $status == 'Developer' ||  $status == 'QA Engineer'): ?>
                                    <th  class="removeSelect">Action</th>
                                <?php endif; ?>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php if(isset($tasksRes)): ?>
                                <?php $__currentLoopData = $tasksRes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <tr class="odd gradeX">
                                        <td><span class="task-title"><?php echo e($key['title']); ?></span></td>
                                        <td><?php echo e($key['company']); ?></td>
                                        <td><?php echo e($key['project_name']); ?></td>
                                        <td><?php echo e($key['type']); ?></td>
                                        <td><?php echo e($key['alloceted_hours']); ?></td>
                                        <td><?php echo e($key['user_name']); ?></td>
                                        <td class="check-box-center" > <?= ( $key['billable'] == '1') ? 'Yes' : 'No' ?> </td>
                                        <td class="check-box-center"><?php echo e($key['created_at']); ?></td>

                                        <?php if($status == 'Super Admin' || $status == 'Admin' || $status == 'Lead' || $status == 'Supervisor' || $status == 'Developer' ||  $status == 'QA Engineer'): ?>
                                            <td class="check-box-center">
                                                <!-- SN 05/10/2017: updated below code for edit developer task itself -->
												<?php if($status == 'Developer'): ?>
													<?php if($key['task_assign_by'] != Auth::user()->id): ?>
														<a href="#" class="btn btn-info" disabled >
													<?php elseif($key['type'] == 'Leave'): ?>
														<a href="#" class="btn btn-info no-click" disabled >
													<?php else: ?>
														<a href="/task/update/<?php echo e($key['id']); ?>"  class="btn btn-info" >
													<?php endif; ?>
												<?php else: ?>
													<a href="/task/update/<?php echo e($key['id']); ?>"  class="btn btn-info <?php echo e(( $key['type'] == 'Leave') ? 'no-click' : ''); ?>" <?php echo e(( $key['type'] == 'Leave') ? 'disabled' : ''); ?>>
												<?php endif; ?>
													<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit
												</a>

                                                <?php if($status == 'Super Admin' || $status == 'Admin' || $status == 'Lead' || $status == 'Supervisor'): ?>
                                                    <button type="button" class="btn btn-danger  deleteTask" data-url="/task/delete/<?php echo e($key['id']); ?>" data-element="<?php echo e($key['title']); ?>">
                                                        <span class="glyphicon glyphicon-floppy-remove span_no_event" aria-hidden="true"></span> Delete</button>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /block -->
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.index_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>