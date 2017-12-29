<?php $__env->startSection('content'); ?>
<div id="conteiner" class="container" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>">

        <div class="modal fade" id="delete-team" role="dialog">
            <div class="modal-dialog"  >
                <!-- Modal content-->
                <div class="modal-content">
                    <div id="modalConfirmDeleteTeam">

                    </div>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <h3 class="h3-my">Teams</h3>
                <a href="/team/create" style="display:inline-block; margin-left: 25px" class="btn btn-large button-orange">
                    <i class="glyphicon glyphicon-plus"></i> Add Team</a>
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
                                <th width="300px">Teams</th>
                                <th width="300px">Lead</th>
                                <th width="60px">Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <tr class="odd gradeX">
                                    <td><?php echo e($team->team_name); ?></td>
                                    <td><?php echo e($team->name); ?></td>

                                    <td style="text-align: center">
                     <button type="button" class="btn btn-danger  deleteTeam" data-url="/team/delete/<?php echo e($team->id); ?>" data-element="<?php echo e($team->team_name); ?>">
                                            <span class="glyphicon glyphicon-floppy-remove span_no_event" aria-hidden="true"></span> Delete</button>

                                    </td>
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
</div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.index_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>