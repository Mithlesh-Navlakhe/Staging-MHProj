<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <div class="container" id="conteiner" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>">

        <div class="row">
            <div class="row-fluid">
                <div class="heading-top-margin">
                    <div class="heading-without-datepicker"><?= isset($taskstatus) ? 'Edit' : 'Add' ?> Task Status</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row-fluid" id="login" >
                <!-- block -->
                <div class="block-content collapse in">
                    <div class="span12">
                        <form class="form-horizontal" role="form" method="post" action="<?= ( isset($taskstatus) ) ? '/task-status/update/' . $taskstatus[0]['id'] : '/task-status/create/' ; ?>">
                            <?php echo e(csrf_field()); ?>


                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                    <label class="control-label" for="ProjectNameId" style="text-align: left;">Task Status *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                    <input name="task_status" class="input-xlarge focused my_input" id="TaskStatusId"  type="text"  title="Text Value Only"
                                        value="<?= ( isset( $taskstatus[0]['name'] ) ) ? $taskstatus[0]['name'] : ((old() && old('task_status')) ? old('task_status') : ''); ?>" required
                                    />
                                    <?php if($errors->has('task_status')): ?>
                                        <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('task_status')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
							
							<div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                    <label class="control-label" for="ProjectNameId" style="text-align: left;">Status Order *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                    <input name="status_order" class="input-xlarge focused my_input" id="Status_Order"  type="text" pattern="[0-9]" title="Number Value Only" minLength="1" maxLength="10"
                                        value="<?= ( isset( $taskstatus[0]['status_order'] ) ) ? $taskstatus[0]['status_order'] : ((old() && old('status_order')) ? old('status_order') : ''); ?>" required
                                    />
                                    <?php if($errors->has('status_order')): ?>
                                        <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('status_order')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                    <label class="control-label" for="NotesProjectId" style="text-align: left;">Description</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                    <textarea name="description" class="input-xlarge focused my_input" id="Description" rows="6"  type="text" required minlength="5">
									 <?= ( isset( $taskstatus[0]['description'] ) ) ? $taskstatus[0]['description'] : '' ?>
									</textarea>
                                    <?php if($errors->has('task_type')): ?>
                                        <span class="help-block">
                                            <strong style="color:#802420"><?php echo e($errors->first('task_type')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-actions row">
                                <label class="control-label col-sm-2" for=""></label>
                                <button type="submit" class="btn btn-large button-orange" formaction="">Submit</button> &nbsp;&nbsp;
                             
                                <a  href="/task-status/all/" class="btn btn-large button-orange" style="font-weight: normal;" >Cancel</a>
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