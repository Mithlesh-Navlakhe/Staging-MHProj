<?php $__env->startSection('content'); ?>

<div class="container" id="login">
    <div class="row">
        <form class="form-signin" role="form" method="POST" action="<?php echo e(url('/login')); ?>">
            <?php echo e(csrf_field()); ?>

            <h2 class="form-signin-heading"><i class="fa-li fa fa-lock"></i> Login</h2>
            <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                <div class="usericon"><i class="fa fa-envelope"></i></div>
                <input id="email" type="email" class="input-block-level" placeholder="Email" name="email" autofocus value="<?php echo e(old('email')); ?>" required>

                <?php if($errors->has('email')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('email' ) == 'These credentials do not match our records.' ? 'These passwords don\'t match' : $errors->first('email' )); ?></strong>
                    </span>
                <?php endif; ?>

                <?php if( isset($_GET['loginStatus'])): ?>
                    <span style="color: #a94442">
                        <strong><?php echo e($_GET['loginStatus']); ?></strong>
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
                <div>
                        <a   class="" href="<?php echo e(url('/password/reset')); ?>"> <small>Forgot Password?</small></a>
                    <button style="margin-top: -2px" type="submit" class="btn btn-large btn-primary">Sign In </button>&nbsp
                    <a style="float: right; margin-top: -2px;  margin-right: 10px; border-radius: 0px" href="<?php echo e(url('auth/google')); ?>" class="btn btn-danger  btn-large  btn-social btn-google">
                        <span class="fa fa-google"></span></a>

                    </a>
                </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>