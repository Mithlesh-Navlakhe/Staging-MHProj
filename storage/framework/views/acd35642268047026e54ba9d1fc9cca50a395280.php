<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>

    <div class="modal fade" id="delete-user" role="dialog">
		<div class="modal-dialog"  >
			<div class="modal-content">
				<div id="modalConfirmDeleteUser"></div>
			</div>
		</div>
	</div>


<div id="conteiner" class="container" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>">
    <div class="row-fluid">
        <div class="span12">
            <h3 class="h3-my">Task Status</h3>
            <a href="/task-status/create/"  class="btn btn-large button-orange margin-left-large">
                <i class="glyphicon glyphicon-plus"></i> Add Task Status
			</a>
        </div>
    </div>

    <div class="row-fluid">

        <!-- block -->
        <div class="block bottom-border no-left-border no-right-border">

            <div class="block-content collapse in">
                <div class="span12">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="usersTable">
                        <thead>
							<tr>
								<th class="type-header">Status</th>
								<th>Description</th>
								<th>Status-Order</th>
								<?php if($status == 'Super Admin' || $status == 'HR Manager' || $status == 'Admin'): ?>
									<th class="center type-action-header">Action</th>
								<?php endif; ?>
							</tr>
                        </thead>

						<tbody>
                         <?php $__currentLoopData = $taskstatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <tr class="odd gradeX">
                                <td class="text-center" > <?php echo e($key['name']); ?></td>
                                <td class="text-center" ><?php echo e($key['description']); ?></td>
								<td class="text-center" ><?php echo e($key['status_order']); ?></td>
                                <?php if($status == 'Super Admin' || $status == 'Admin' || $status == 'Lead' || $status == 'Supervisor'): ?>
                                    <td class="text-center">
										<a href="/taskstatus/update/<?php echo e($key['id']); ?>"  class="btn btn-info"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
										<button type="button" class="btn btn-danger  deleteUser" data-url="/taskstatus/delete/<?php echo e($key['id']); ?>" data-element="<?php echo e($key['name']); ?>">
										<span class="glyphicon glyphicon-floppy-remove span_no_event" aria-hidden="true"></span> Delete</button>
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