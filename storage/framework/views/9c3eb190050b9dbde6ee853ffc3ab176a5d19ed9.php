<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
        <div class="container" id="conteiner" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>">

            <div class="row">
                <div class="row-fluid">
                    <div class="heading-top-margin">

                        <div class="heading-without-datepicker">Add user</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row-fluid" id="login" >
                    <!-- block -->
                    <div class="block-content collapse in">
                        <div class="span12">
                            <form class="form-horizontal" role="form" method="POST" action="<?php echo e(url('/user/create')); ?>">
                                <?php echo e(csrf_field()); ?>

                                    <div class="control-group row" >
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                        <label class="control-label " for="focusedInput">User type *</label>
                                        </div>

                                        <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                            <select name="employe" class="input-xlarge focused my_input" id="selectTeam" style="height: 42px;" required />
                                                <option selected disabled value="">Select</option>
                                                <?php if( old('employe') ): ?>
                                                    <option><?php echo e(old('employe')); ?></option>

                                                <?php endif; ?>
                                                <?php if($status == 'Admin'): ?>
                                                <option>Admin</option>
                                                <option>Supervisor</option>
                                                <option>HR Manager</option>
                                                <?php endif; ?>
                                                <option>Lead</option>
                                                <option>Developer</option>
                                                <option>QA Engineer</option>
                                            </select>
                                            <?php if($errors->has('employe')): ?>
                                                <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('employe')); ?></strong>
                                            </span>
                                            <?php endif; ?>

                                        </div>
                                    </div>

                                    <div class="control-group row">
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                        <label class="control-label" for="focusedInput">Name  *</label>
                                        </div>
                                        <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                            <input name="name" class="input-xlarge focused my_input" id="focusedInput"  autofocus type="text" value="<?php echo e(old('name')); ?>" required>
                                            <?php if($errors->has('name')): ?>
                                                <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('name')); ?></strong>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="control-group  row">
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                        <label class="control-label" for="focusedInput">Email *</label>
                                        </div>
                                        <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">

                           <input name="email" class="input-xlarge focused my_input" value="<?php echo e(old('email')); ?>" type="email" required>

                                            <?php if($errors->has('email')): ?>
                                                <span class="help-block">
                                                <strong><?php echo e($errors->first('email')); ?></strong>
                                            </span>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                    <div class="control-group  row" id="hourlyRate"  style="display:none;">
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                        <label class="control-label" for="hourlyRateId">Hourly rate</label>
                                        </div>
                                        <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">

                                            <input name="hourlyRate" class="input-xlarge  focused my_input"  id="hourlyRateId" value=""  type="number" step="0.01">

                                            <?php if($errors->has('hourlyRate')): ?>
                                                <span class="help-block">
                                                    <strong><?php echo e($errors->first('hourlyRate')); ?></strong>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                    <div id="team_name" style="display:none;" class="control-group  row">
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                            <label class="control-label" for="focusedInput">Team</label>
                                        </div>
                                        <?php if( $teams == true ): ?>

                                            <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                            <select name="users_team_id" class="input-xlarge focused my_input" style="height: 42px">
                                                <option value="" selected>Select team</option>

                                            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                            <!--<input name="team_name" class="input-xlarge focused" id="focusedInput"  type="textl" required> -->
                                                <option value="<?php echo e($team->id); ?>"><?php echo e($team->team_name); ?></option>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                                            </select>
                                        </div>

                                        <?php endif; ?>

                                        <?php if($errors->has('team_name')): ?>
                                            <span class="help-block">
                                                <strong><?php echo e($errors->first('team_name')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-actions row">
                                        <div  class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
                                        </div>
                                        <label class="control-label " for=""></label>
                                        <div class="col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                            <button type="submit" class="btn btn-large button-orange" formaction="">Save</button> &nbsp;&nbsp;
                                            <a  href="<?php echo e(url('/user/all')); ?>" class="btn btn-large button-orange" style="font-weight: normal;" >Cancel</a>
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