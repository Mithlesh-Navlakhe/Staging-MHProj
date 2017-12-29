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

    <div id="conteiner" class="container" data-date=""
         data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>"
         data-token="<?php echo e(Session::token()); ?>"
         data-log-active = "<?= isset($_COOKIE['logTrackActiveLogId']) ? $_COOKIE['logTrackActiveLogId'] : ''?>"
         data-start = "<?=  isset($active['start']) ? $active['start'] : '' ?>"
         data-end = "<?=  isset($active['end']) ? $active['end'] : '' ?>">

        <div class="row" style="margin-top: 20px">
            <span class="col-md-4 col-lg-3   btn-toolbar" style="vertical-align: inherit; font-size: large ">

                <div class="daterange daterange--double one" style=""></div>

            </span>
            <div class="col-md-3 col-lg-3" style=" padding: 20px 20px">


                <select name="users" class=" input-xlarge focused my_input "   id="SelectAllUserReport" style="height: 42px; " data-all="true">
                    <?php if(empty($active['userId'])): ?>
                    <option selected disabled value="">Please select Person</option>
                    <?php endif; ?>
                    <?php if(isset($users)): ?>

                        <optgroup label="Lead">
                            <?php if(isset($users['Lead'])): ?>
                                <?php $__currentLoopData = $users['Lead']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <option value="<?= $key->id ?>" <?= ( isset($active['userId']) && $key->id == $active['userId']) ? 'selected' : '' ?>><?= $key->name ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>

                        </optgroup>
                        <optgroup label="Developer">
                            <?php if(isset($users['Developer'])): ?>
                                <?php $__currentLoopData = $users['Developer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <option value="<?= $key->id ?>" <?= ( isset($active['userId']) && $key->id == $active['userId']) ? 'selected' : '' ?>><?= $key->name ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>

                        </optgroup>
                        <optgroup label="QA Engineer">
                            <?php if(isset($users['QA Engineer'])): ?>
                                <?php $__currentLoopData = $users['QA Engineer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <option value="<?= $key->id ?>" <?= ( isset($active['userId']) && $key->id == $active['userId']) ? 'selected' : '' ?>><?= $key->name ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>

                        </optgroup>
                        <optgroup label="Supervisor">
                            <?php if(isset($users['Supervisor'])): ?>
                                <?php $__currentLoopData = $users['Supervisor']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <option value="<?= $key->id ?>" <?= ( isset($active['userId']) && $key->id == $active['userId']) ? 'selected' : '' ?>><?= $key->name ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>

                        </optgroup>
                        <optgroup label="Admin">
                            <?php if(isset($users['Admin'])): ?>
                                <?php $__currentLoopData = $users['Admin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <option value="<?= $key->id ?>" <?= ( isset($active['userId']) && $key->id == $active['userId']) ? 'selected' : '' ?>><?= $key->name ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>

                        </optgroup>


                    <?php endif; ?>



                </select>

            </div>
            <div class="col-md-5 col-lg-6" style=" padding: 20px 20px">
                <span style="font-size: 30px; float:right; color: #999;">People Report</span>
                </div>

           <!-- <h2  class="col-md-10 showDate"  id="timeTrackShowDate"></h2>-->
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
                                <th>Project</th>
                                <!--  <th>User</th> -->

                                <!--  <th>Date Start</th>
                                 <th>Date Finish</th>-->
                                <th>Task</th>
                                <th>Task Type</th>
                                <th>Hours</th>
                                <th>Value</th>
                                <th>Cost</th>
                                <th>Economy</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="thFoot" width="130px"></th>
                                <th class="thFoot" ></th>
                                <!-- <th class="thFoot" >User</th>-->

                                <!--  <th class="thFoot" >Date Start</th>
                                  <th class="thFoot" >Date Finish</th>-->
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>

                            </tr>
                            </tfoot>

                            <tbody>


                            <?php if(isset($peopleReport)): ?>

                                <?php $__currentLoopData = $peopleReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                    <tr class="odd gradeX">
                                        <td><?php echo e($key->user->name); ?></td>
                                        <td><?php echo e($key->project->project_name); ?></td>
                                        <td><?php echo e($key->task_titly); ?></td>
                                        <td><?php echo e($key->task_type); ?></td>
                                        <td><?php echo e($key->hours); ?></td>
                                        <td><?php echo e($key->value); ?></td>
                                        <td><?php echo e($key->cost); ?></td>
                                        <td><?php echo e($key->economy); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="row">

                        <strong>Total:</strong><br>
                        <strong>Value: <?= $total['totalValue'] ?> </strong> |
                        <strong>Cost: <?= $total['totalCost'] ?> </strong> |
                        <strong>Economy: <?= $total['totalEconomy'] ?> </strong>

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