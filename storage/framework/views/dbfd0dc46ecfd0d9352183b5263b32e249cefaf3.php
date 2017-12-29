<?php $__env->startSection('content'); ?>
<div class="container" id="login">
    <div class="row">
		<div class="form-signin" style="min-height: 23rem;margin-bottom: 10em;">
			<form class="form-signin1" role="form" method="POST" action="<?php echo e(url('/login')); ?>">
				<?php echo e(csrf_field()); ?>

				<h2 class="form-signin-heading col-md-12"><i class="fa-li fa fa-lock"></i> Login</h2>
				<div class="form-grou<?php echo e($errors->has('email') ? ' has-error' : ''); ?> col-md-12">
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon" style="background-color: #CCCCCC;padding: 0;"><i class="fa fa-envelope" style="margin-top: 0px;"></i></span>
						<input id="email" type="email" class=" form-control" placeholder="Email" name="email" autofocus value="<?php echo e(old('email')); ?>" required style="height: 38px;margin: 0;z-index: 0;">
					</div>
				
					<?php if($errors->has('email')): ?>
						<span class="help-block">
							<strong><?php echo e($errors->first('email' ) == 'These credentials do not match our records.' ? $errors->first('email' ) : 'These passwords don\'t match'); ?></strong>
						</span>
					<?php endif; ?>

					<?php if( isset($_GET['loginStatus'])): ?>
						<span style="color: #a94442">
							<strong><?php echo e($_GET['loginStatus']); ?></strong>
						</span>
					<?php endif; ?>
				</div>
				<div class="form-grou<?php echo e($errors->has('password') ? ' has-error' : ''); ?> col-md-12">
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon" style="background-color: #CCCCCC;padding: 0;"><i class="fa fa-key" style="margin-top: 0px;"></i></span>
						<input id="password" type="password" class=" form-control" placeholder="Password" name="password" required style="height: 38px;margin: 0;z-index: 0;">						
					</div>
					<?php if($errors->has('password')): ?>
						<span class="help-block">
							<strong><?php echo e($errors->first('password')); ?></strong>
						</span>
					<?php endif; ?>
				</div>
				<div class="col-md-12 col-lg-12 col-xs-12">
					<div class=" row-fluid">
						<button type="submit" class="btn btn-large btn-primary login-button" style=" margin-top: -2px;">Sign In </button>&nbsp
						<a style="float: right; margin-top: -2px;  margin-right: 10px; border-radius: 0px" href="<?php echo e(url('auth/google')); ?>" class="btn btn-danger  btn-large  btn-social btn-google">
							<span class="fa fa-google"></span></a>
						</a>
					</div>
					<div class="col-md-12 col-lg-12 col-xs-12 forget-block" style="">
						<a class="" href="<?php echo e(url('/password/reset')); ?>"> <small>Forgot Password?</small></a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

<style>
	.button-block{ max-width: 93%; }
	@media  only screen and (max-width: 340px){
		.login-button{ height: 43px;width: 50%; font-size: 16px; padding: 0; }
	}
	
	@media  only screen and (max-width: 766px){
		.navbar-inner{margin-left: -20px;}
		.forget-block{ margin-top: 20px; }
	}
	@media (max-width: 979px){
		.navbar-fixed-top, .navbar-fixed-bottom { position: sticky; } 
	}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>