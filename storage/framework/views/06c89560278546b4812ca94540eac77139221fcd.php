<!-- Main Content -->
<?php $__env->startSection('content'); ?>
<div class="container">
    <?php if( isset($loginStatus)): ?>

        <strong><?php echo e($loginStatus); ?></strong>

    <?php endif; ?>
    <div class="row" id="login">
        <div class="">
            <?php if(session('status')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>
            <form class="form-signin" role="form" method="POST" style="min-height: 200px;" action="<?php echo e(url('/password/email')); ?>">
                <h2 class="form-signin-headin">Reset Password</h2>
                <?php echo e(csrf_field()); ?>

                <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                    <div class="usericon"><i class="fa fa-key"></i></div>
                    <input id="email" type="email" placeholder="E-Mail Address" class="input-block-level" name="email" value="<?php echo e(old('email')); ?>" required>

                    <?php if($errors->has('email')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('email')); ?></strong>
                        </span>
                    <?php endif; ?>

                </div>

                    <button  type="submit" class="btn btn-large btn-primary">
                        Send Password Reset Link
                    </button>

            </form>



    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>