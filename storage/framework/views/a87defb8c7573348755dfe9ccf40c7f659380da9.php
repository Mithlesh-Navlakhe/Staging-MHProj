<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <div id="conteiner" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>"></div>

    <div class="container">
        <div class="row-fluid">
            <div class="span12 heading-top-margin">

                <div class="heading-without-datepicker">Edit User</div>
            </div>
        </div>
        <div class="row-fluid" id="login" >
            <!-- block -->
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" role="form" method="POST" action="<?php echo e(url('/user/update/' . $user->id)); ?>">
                        <?php echo e(csrf_field()); ?>


                        <div class="control-group row" >
                            <label class="control-label col-sm-2" for="focusedInput">User type</label>
                            <div class="controls col-sm-10">
                                <select name="employe" class="input-xlarge focused" id="selectTeam" style="height: 42px;" >
                                    <option><?php echo e($user->employe); ?></option>
                                    <?php if($status == 'Admin'): ?>
                                        <option>Admin</option>
                                        <option>Supervisor</option>
                                        <option>HR Manager</option>
                                    <?php endif; ?>
                                    <option>Lead</option>
                                    <option>Developer</option>
                                    <option>QA Engineer</option>
                                </select>

                            </div>
                        </div>
                        <div class="control-group row">
                            <label class="control-label col-sm-2" for="focusedInput">Name *</label>
                            <div class="controls col-sm-10">
                                <input name="name" class="input-xlarge focused" id="focusedInput" value="<?php echo e($user->name); ?>"  type="text" required />
                                <?php if($errors->has('name')): ?>
                                    <span class="help-block ">
                                            <strong style="color:#802420"><?php echo e($errors->first('name')); ?></strong>
                                        </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="control-group row" id="hourlyRate"  style="display:none;">
                            <label class="control-label col-sm-2" for="hourlyRateId">Hourly rate</label>
                            <div class="controls col-sm-10">

                                <input name="hourlyRate" class="input-xlarge focused"  id="hourlyRateId" value="<?= $user->hourly_rate ?>" type="number" step="0.01">

                                <?php if($errors->has('hourlyRate')): ?>
                                    <span class="help-block">
                                                <strong><?php echo e($errors->first('hourlyRate')); ?></strong>
                                            </span>
                                <?php endif; ?>
                            </div>

                        </div>
                        <div id="team_name" style="display:none;" class="control-group row">
                            <label class="control-label col-sm-2" for="focusedInput">Team</label>

                            <?php if( $teams == true ): ?>

                                <div class="controls col-sm-10">
                                    <select name="users_team_id" class="input-xlarge focused" style="height: 42px;">
                                        <?php if(isset($teamActive)): ?>
                                            <option value="<?php echo e($teamActive->id); ?>" selected><?php echo e($teamActive->team_name); ?></option>
                                        <?php else: ?>
                                            <option selected disabled>Please change Team</option>
                                        <?php endif; ?>

                                        <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                <!--<input name="team_name" class="input-xlarge focused" id="focusedInput"  type="textl" required> -->
                                        <option value="<?php echo e($team->id); ?>"><?php echo e($team->team_name); ?></option>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                            <option></option>

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
                            <label class="control-label col-sm-2" for=""></label>
                            <button type="submit" class="btn btn-large button-orange" formaction="">Save</button> &nbsp;&nbsp;
                            <a  href="<?php echo e(url('/user/all')); ?>" class="btn btn-large button-orange" style="font-weight: normal;" >Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /block -->
        </div>
    </div>
    <script src="/js/jquery/jquery-3.1.1.min.js"></script>
    <script src="/js/registration.js"></script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.index_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>