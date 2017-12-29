<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <script type="text/javascript" src="/data/daterangepicker.js"></script>
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
         data-token="<?php echo e(Session::token()); ?>"
         data-log-active = "<?= isset($_COOKIE['logTrackActiveLogId']) ? $_COOKIE['logTrackActiveLogId'] : ''?>">


        <div class="row" style="margin-top: 20px">
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
            </div>

            <h2  class="col-md-6 showDate"  id="timeTrackShowDate"></h2>


                <div class="col-md-4" style="color: #999;text-align: right; font-size: 30px;">Dayli Report</div>


        </div>


        <div class="row-fluid">

            <!-- block -->
            <div class="block" style="border-bottom: 1px solid #ccc; border-left: none; border-right: none">

                <div class="block-content collapse in">
                    <div class="span12">


                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="usersTable">
                            <thead>
                            <tr>
                                <th width="130px">Person Name</th>
                                <th>Client</th>
                                <!--  <th>User</th> -->
                                <th>Project</th>
                                <!--  <th>Date Start</th>
                                 <th>Date Finish</th>-->
                                <th>Task</th>
                                <th>Task Type</th>
                                <th>Hours</th>
                                <th>Value</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="thFoot" width="130px"></th>
                                <th class="thFoot" ></th>
                                <!-- <th class="thFoot" >User</th>-->
                                <th class="thFoot" ></th>
                                <!--  <th class="thFoot" >Date Start</th>
                                  <th class="thFoot" >Date Finish</th>-->
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>

                            </tr>
                            </tfoot>

                            <tbody>


                            <?php if(isset($dayReport)): ?>

                                <?php $__currentLoopData = $dayReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                    <tr class="odd gradeX">
                                        <td><?php echo e($key->user->name); ?></td>
                                        <td><?php echo e($key->client->company_name); ?></td>
                                        <td><?php echo e($key->project->project_name); ?></td>

                                        <td><?php echo e($key->task_titly); ?></td>
                                        <td><?php echo e($key->task_type); ?></td>
                                        <td><?php echo e($key->total); ?></td>
                                        <td><?php echo e($key->value); ?></td>


                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <strong>Total:</strong><br>
                            <strong>Hours: <?php echo e($total['totalTime']); ?></strong> |
                        <strong>Value: <?php echo e($total['totalValue']); ?> </strong>
                    </div>
                </div>

            </div>

            <!-- /block -->
        </div>




    </div>
    <!--    <script src="/js/jquery/jquery-3.1.1.min.js"></script>-->
    <script src="/js/tasks.js"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.index_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>