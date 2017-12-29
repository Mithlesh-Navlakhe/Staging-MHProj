<?php $__env->startSection('content'); ?>

    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <div class="container" id="conteiner" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>">

        <div class="row">
            <div class="row-fluid col-md-12">
                <div class="margin-left-large">
                    <div class="heading-without-datepicker"><?= isset($project) ? 'Edit' : 'Add' ?> Project</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row-fluid" id="login" >
                <!-- block -->
                <div class="block-content collapse in">
                    <div class="span12">
                        <form class="form-horizontal" role="form" method="POST" action="<?= ( isset($project) ) ? '/project/update/' . $project[0]->id : '/project/create/' ; ?>">
                            <?php echo e(csrf_field()); ?>


                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="CompanyNameProjectId">Company *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <select name="company_id" class="input-xlarge focused my_input input-height"  id="CompanyNameProjectId" required>
                                        <?php if(isset($client->company_name)): ?>
                                        <option value="<?php echo e($client->id); ?>" selected><?php echo e($client->company_name); ?></option>
                                        <?php endif; ?>

                                        <?php if( isset( $project[0]->client_id ) && $project[0]->client_id == $project_client[0]->id ): ?>
                                            <option  value="<?php echo e($project[0]->client_id); ?>" selected><?php echo e($project_client[0]->company_name); ?></option>
                                        <?php else: ?>
                                            <option selected disabled value="">Select</option>
                                        <?php endif; ?>

                                        <?php $__currentLoopData = $client; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <?php if( isset( $project[0]->client_id ) &&  $project[0]->client_id == $key->id ): ?>

                                            <?php else: ?>
                                                <option  value="<?php echo e($key->id); ?>"><?php echo e($key->company_name); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                    </select>
                                    <?php if($errors->has('company_id')): ?>
                                        <span class="help-block error-text">
											<strong><?php echo e($errors->first('company_id')); ?></strong>
										</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="ProjectNameId">Project Name *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <input name="project_name" class="input-xlarge focused my_input" id="ProjectNameId"  type="text"
                                        value="<?= ( isset( $project[0]->project_name ) ) ? $project[0]->project_name : '' ?>" required
                                    />
                                    <?php if($errors->has('project_name')): ?>
                                        <span class="help-block error-text">
                                                <strong><?php echo e($errors->first('project_name')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="HourlyRateProhectId" >Hourly Rate</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <input name="hourly_rate" class="input-xlarge focused my_input" id="HourlyRateProhectId"  type="number" step="0.01"
                                           value="<?= ( isset( $project[0]->hourly_rate ) ) ? $project[0]->hourly_rate : '' ?>"
                                    >
                                    <?php if($errors->has('hourly_rate')): ?>
                                        <span class="help-block error-text">
											<strong ><?php echo e($errors->first('hourly_rate')); ?></strong>
										</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="NotesProjectId">Notes</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <textarea name="notes" class="input-xlarge focused my_input" id="NotesProjectId" rows="6"  type="text"><?= ( isset( $project[0]->notes ) ) ? $project[0]->notes : '' ?></textarea>
                                    <?php if($errors->has('notes')): ?>
                                        <span class="help-block error-text">
											<strong ><?php echo e($errors->first('notes')); ?></strong>
										</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
                                    <label class="control-label text-left" for="TeamLeadProjectId">Team Lead</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <select name="lead_id" class="input-xlarge focused my_input input-height"  id="TeamLeadProjectId">
                                        <?php if( isset( $lead[0] ) ): ?>
                                            <option selected value="<?php echo e($lead[0]->id); ?>"><?php echo e($lead[0]->name); ?></option>
                                        <?php else: ?>
                                            <option selected value="0">Please select team</option>
                                        <?php endif; ?>

                                        <?php if(isset($leads->company_name)): ?>
                                            <option value="<?php echo e($leads->id); ?>" selected><?php echo e($leads->company_name); ?></option>
                                        <?php endif; ?>

                                        <?php $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                <?php if( isset( $lead[0]) && $lead[0]->id == $key->id): ?>
                                                <?php else: ?>
                                                    <option  value="<?php echo e($key->id); ?>"><?php echo e($key->name); ?></option>
                                                <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-actions row col-lg-12 col-md-12 ">
                                <label class="control-label col-sm-2 col-lg-2 col-md-2 col-xs-4 label-mob-width" for=""></label>
                                <div class="col-sm-10 col-lg-10 col-md-10">
									<button type="submit" class="btn btn-large button-orange save-button" formaction="">Save</button> &nbsp;&nbsp;
									<a  href="<?php echo e(url('/project/all')); ?>" class="btn btn-large button-orange">Cancel</a>
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