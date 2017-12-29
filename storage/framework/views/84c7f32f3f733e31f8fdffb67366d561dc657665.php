<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>

    <div class="modal fade" id="delete-user" role="dialog">
    <div class="modal-dialog"  >
        <!-- Modal content-->
        <div class="modal-content">
            <div id="modalConfirmDeleteUser"></div>
        </div>
    </div>
</div>

<div id="conteiner" class="container" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>">
    <div class="row-fluid">
        <div class="span12">
            <h3 class="h3-my">Users</h3>
            <a href="/user/create" style="display:inline-block; margin-left: 25px" class="btn btn-large button-orange">
                <i class="glyphicon glyphicon-plus"></i> Add User</a>
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
                            <th width="130px">Users</th>
                            <th>Email</th>
                            <th>Team</th>
                            <th>Hourly rate</th>
                            <th>User type</th>
                            <th style="min-width: 160px"  class="center">Created at</th>

                            <?php if($status == 'HR Manager' || $status == 'Admin'): ?>
                                <th style="min-width: 140px; width: 140px;" class="center">Action</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th width="130px" class="thFoot">Users</th>
                            <th class="thFoot">Email</th>
                            <th class="thFoot">Team</th>
                            <th class="thFoot">Hourly rate</th>
                            <th class="thFoot">User type</th>
                            <th style="min-width: 160px"  class="center thFoot">Created at</th>
                            <?php if($status == 'HR Manager' || $status == 'Admin'): ?>
                                <th  class="removeSelect">Action</th>
                            <?php endif; ?>

                        </tr>
                        </tfoot>
                        <tbody>

                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <tr class="odd gradeX">
                                <td><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td><?php echo e($user->team_name ? $user->team_name : ''); ?></td>
                                <td  style="text-align: center" class="center"><?php echo e($user->hourly_rate); ?></td>
                                <td><?php echo e($user->employe); ?></td>
                                <td style="text-align: center"><?php echo e($user->created_at); ?></td>

                                <?php if($status == 'HR Manager' || $status == 'Admin'): ?>
                                    <td>
                                        <?php if($status == 'Admin' ||
                                         ($status == 'HR Manager' &&
                                         ($user->employe == "Developer" || $user->employe == "QA Engineer" || $user->employe == "Lead"))): ?>
                                            <a href="/user/update/<?php echo e($user->id); ?>"  class="btn btn-info"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
                                            <button type="button" class="btn btn-danger  deleteUser" data-url="/user/delete/<?php echo e($user->id); ?>" data-element="<?php echo e($user->name); ?>">
                                                <span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span> Delete</button>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

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