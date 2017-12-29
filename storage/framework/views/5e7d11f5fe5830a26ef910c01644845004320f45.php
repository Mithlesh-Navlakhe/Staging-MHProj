<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <div class="container" id="conteiner" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>">

        <div class="row">
            <div class="row-fluid col-md-12">
                <div class="margin-left-large">
                    <div class="heading-without-datepicker"><?= isset($taskType) ? 'Edit' : 'Add' ?> Task Type</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row-fluid" id="login" >
                <!-- block -->
                <div class="block-content collapse in">
                    <div class="span12">
                        <form class="form-horizontal" role="form" method="POST" action="<?= ( isset($taskType) ) ? '/task-type/update/' . $taskType[0]->id : '/task-type/create/' ; ?>">
                            <?php echo e(csrf_field()); ?>


                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="ProjectNameId">Task Type *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <input name="task_type" class="input-xlarge focused my_input" id="TaskTypeId"  type="text"
                                        value="<?= ( isset( $taskType[0]->task_type ) ) ? $taskType[0]->task_type : '' ?>" required
                                    />
                                    <?php if($errors->has('task_type')): ?>
                                        <span class="help-block error-text">
                                                <strong ><?php echo e($errors->first('task_type')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
                                    <label class="control-label text-left" for="NotesProjectId" >Description</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <textarea name="description" class="input-xlarge focused my_input" id="DescriptionId" rows="6"  type="text"><?= ( isset( $taskType[0]->description ) ) ? $taskType[0]->description : '' ?></textarea>
                                    <?php if($errors->has('task_type')): ?>
                                        <span class="help-block error-text">
											<strong ><?php echo e($errors->first('task_type')); ?></strong>
										</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-actions row col-lg-12 col-md-12">
                                <label class="control-label  col-sm-2 col-lg-2 col-md-2 col-xs-4 label-mob-width" for=""></label>
								<div class="col-sm-10 col-lg-10 col-md-10 ">
									<button type="submit" class="btn btn-large button-orange save-button" formaction="">Save</button> &nbsp;&nbsp;
									<a  href="/task-type/all" class="btn btn-large button-orange">Cancel</a>
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