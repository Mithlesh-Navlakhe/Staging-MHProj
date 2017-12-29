<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row" id = "login">



                <div class="panel-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <form class="form-signin" role="form" method="POST" action="<?php echo e(url('/password/reset')); ?>" style="padding-bottom: 46px;">
                        <h2 class="form-signin-heading"><i class="fa-li fa"></i>Reset Password</h2>
                        <?php echo e(csrf_field()); ?>


                     <!--   <input type="hidden" name="token" ">-->

                        <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">



                                <div class="usericon"><i class="fa fa-envelope"></i></div>
                                <input id="email" type="email" class="input-block-level" placeholder="E-Mail Address" name="email" value="<?php echo e(isset($email) ? $email : old('email')); ?>" required autofocus>

                                <?php if($errors->has('email')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                                <?php endif; ?>

                        </div>

                        <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">



                                <div class="usericon"><i class="fa fa-key"></i></div>
                                <input id="password" type="password" class="input-block-level" placeholder="Password" name="password" required>

                                <?php if($errors->has('password')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                                <?php endif; ?>

                        </div>

                        <div class="form-group<?php echo e($errors->has('password_confirmation') ? ' has-error' : ''); ?>">


                                <div class="usericon"><i class="fa fa-key"></i></div>
                                <input id="password-confirm" type="password" class="input-block-level" placeholder="Confirm Password" name="password_confirmation" required>

                                <?php if($errors->has('password_confirmation')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('password_confirmation')); ?></strong>
                                    </span>
                                <?php endif; ?>

                                <button type="submit" class="btn btn-large btn-primary">
                                    Reset Password
                                </button>

                        </div>
                    </form>
                </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>