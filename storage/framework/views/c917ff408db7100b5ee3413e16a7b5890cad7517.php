<?php $__env->startSection('content'); ?>
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

    <div id="conteiner" class="container" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>">
        <div class="row-fluid">
            <div class="span12">
                <h3 class="h3-my">Tracks</h3>
              <!--  <a href="/user/create" style="display:inline-block; margin-left: 25px" class="btn btn-large button-orange">
                    <i class="glyphicon glyphicon-plus"></i> Add User</a> -->
            </div>

        </div>

        <div class="row-fluid">

            <!-- block -->

            <div class="block" style="border-bottom: 1px solid #ccc; border-left: none; border-right: none">

                <div class="block-content collapse in">
                    <div class="span12">


                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="usersTable">
                            <thead>
                            <tr>
                                <th width="130px">Project</th>
                                <th>Task</th>
                                <!--  <th>User</th> -->
                                <th>Approve</th>
                               <!--  <th>Date Start</th>
                                <th>Date Finish</th>-->
                                <th>Duration</th>
                                <th>Total Time</th>
                                <th>Billable</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th>Started at</th>
                                <th>Finish at</th>
                                <?php if($status == 'Lead' || $status == 'Admin' || $status == 'Supervisor'): ?>
                                    <th style="min-width:250px; width: 250px;" class="center">Action</th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="thFoot" width="130px">Project</th>
                                <th class="thFoot" >Task</th>
                               <!-- <th class="thFoot" >User</th>-->
                                <th class="thFoot" >Approv</th>
                              <!--  <th class="thFoot" >Date Start</th>
                                <th class="thFoot" >Date Finish</th>-->
                                <th class="thFoot" >Duration</th>
                                <th class="thFoot" >Total_time</th>
                                <th class="thFoot" >Billable</th>
                                <th class="thFoot" >Cost</th>
                                <th class="thFoot" >Status</th>
                                <th class="thFoot" >Created at</th>
                                <th class="thFoot" >Finish at</th>
                                <?php if($status == 'Lead' || $status == 'Admin' || $status == 'Supervisor'): ?>
                                    <th  class="removeSelect">Action</th>
                                <?php endif; ?>

                            </tr>
                            </tfoot>
                            <tbody>

                            <?php if(isset($tracks)): ?>
                                <?php $__currentLoopData = $tracks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <?php if($key->task->assign_to != $id && ($status == 'QA Engineer' || $status == 'Developer')): ?>

                                        <?php continue; ?>
                                    <?php endif; ?>
                                    <tr class="odd gradeX <?= $key->approve == 1 ? 'done_tr' : ($key->done == 1 ? 'done_tr2' : '')?>">
                                        <td><?php echo e($key->project->project_name); ?></td>
                                        <td><?php echo e($key->task->task_titly); ?></td>
                                        <td><?php echo e($key->approve == 1 ? 'Yes' : 'No'); ?></td>
                                        <?php  $hours = (int)($key->duration/60);
                                        $minutes = bcmod($key->duration, 60);
                                        if (strlen($hours) < 2){
                                            $hours = '0' . $hours;
                                        }
                                        if (strlen($minutes) < 2){
                                            $minutes = '0' . $minutes;
                                        }
                                        ?>
                                        <td><?php echo e($key->duration ==null ? '-' :  $hours . ':' . $minutes); ?></td>
                                        <td><?php echo e($key->total_time ==null ? '-' : date('H:i:s', $key->total_time)); ?></td>
                                        <td><?php echo e($key->billable_time == 1 ? 'Yes' : 'No'); ?></td>
                                        <td><?php echo e($key->additional_cost); ?></td>
                                        <td><?php echo e($key->done == 1 ? 'Done' : 'In proccess'); ?></td>
                                        <td><?php echo e($key->created_at); ?></td>
                                        <td><?php echo e($key->finish_track); ?></td>

                                        <?php if($status == 'Lead' || $status == 'Admin' || $status == 'Supervisor'): ?>
                                            <td>
                                                <?php if($key->approve == 0 ): ?>
                                                <button  type="button" class="btn btn-success approvTrack"
                                                         <?= $key->done == 0 ? 'disabled' : '' ?>
                                                         data-url="/trask/approve/<?php echo e($key->id); ?>" data-element="<?php echo e($key->project->project_name . '-' . $key->task->task_titly); ?>">
                                                    <span class="glyphicon glyphicon-ok span_no_event" aria-hidden="true"></span> Approve
                                                </button>
                                                <?php endif; ?>
                                                <?php if($key->approve == 1): ?>
                                                    <button  type="button" class="btn btn-warning  rejectTrack" data-url="/trask/reject/<?php echo e($key->id); ?>" data-element="<?php echo e($key->project->project_name . '-' . $key->task->task_titly); ?>">
                                                        &nbsp&nbsp<span class="glyphicon glyphicon-remove span_no_event" aria-hidden="true"></span> Reject &nbsp
                                                    </button>
                                                <?php endif; ?>

                                                <a href="/track/update/<?php echo e($key->id); ?>"  class="btn btn-info"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
                                                    <button type="button" class="btn btn-danger deleteTrack" data-url="/track/delete/<?php echo e($key->id); ?>" data-element="<?php echo e($key->project->project_name . '-' . $key->task->task_titly); ?>">
                                                        <span class="glyphicon glyphicon-floppy-remove span_no_event" aria-hidden="true"></span> Delete</button>
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