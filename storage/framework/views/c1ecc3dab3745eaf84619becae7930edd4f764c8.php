<?php $__env->startSection('content'); ?>
<?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
<div class="container" id="conteiner" data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>">

    <div class="row">
        <div class="row-fluid">
            <div class="heading-top-margin">
                <div class="heading-without-datepicker"><?= isset($client) ? 'Edit' : 'Add' ?> Client</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="row-fluid" id="login" >
            <!-- block -->
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" role="form" method="POST" action="<?php echo e(url('/client/create')); ?>">
                        <?php echo e(csrf_field()); ?>


                        <div class="control-group row">
                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                <label class="control-label" for="companeMameId" style="text-align: left;">Company Name *</label>
                            </div>
                            <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">

                                <input name="company_name" class="input-xlarge focused my_input" id="companeMameId"  autofocus type="text"
                                        value="<?= isset($client->company_name) ? $client->company_name : ((old('company_name')) ? old('company_name') : '') ?>" required/>

                                <?php if($errors->has('company_name')): ?>
                                    <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('company_name')); ?></strong>
                                            </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group row">
                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                <label class="control-label" for="AddressId" style="text-align: left;">Address</label>
                            </div>

                            <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                <input name="company_address"  class="input-xlarge focused my_input" id="AddressId"   type="text"
                                       value="<?= isset($client->company_address) ? $client->company_address : ((old('company_address')) ? old('company_address') :'') ?>"/>
                                <?php if($errors->has('company_address')): ?>
                                    <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('company_address')); ?></strong>
                                            </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group row">
                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                <label class="control-label" for="WebsiteId" style="text-align: left;">Website</label>
                            </div>
                            <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                <input name="website" class="input-xlarge focused my_input" id="WebsiteId"  type="text"
                                       value="<?= isset($client->website) ? $client->website : ((old('website')) ? old('website') :'') ?>"/>
                                <?php if($errors->has('website')): ?>
                                    <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('website')); ?></strong>
                                            </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group row">
                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                <label class="control-label" for="ContactPersonId" style="text-align: left;">Contact Person *</label>
                            </div>
                            <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">

                                <input name="contact_person" class="input-xlarge focused my_input" id="ContactPersonId"   type="text"
                                       value="<?= isset($client->contact_person) ? $client->contact_person : ((old('contact_person')) ? old('contact_person') :'') ?>" required/>

                                <?php if($errors->has('contact_person')): ?>
                                    <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('contact_person')); ?></strong>
                                            </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group row">
                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                <label class="control-label" for="emailClientId" style="text-align: left;">Email</label>
                            </div>
                            <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                <input type="email" name="email" class="input-xlarge focused my_input" id="emailClientId"
                                       value="<?= isset($client->email) ? $client->email : ((old('email')) ? old('email') :'') ?>"/>
                                <?php if($errors->has('email')): ?>
                                    <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('email')); ?></strong>
                                            </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group row">
                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                <label class="control-label" for="PhoneNumberId" style="text-align: left;">Phone Number</label>
                            </div>
                            <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                <input name="phone_number" class="input-xlarge focused my_input" id="PhoneNumberId"   type="text"
                                value="<?= isset($client->phone_number) ? $client->phone_number : ((old('phone_number')) ? old('phone_number') :'') ?>">
                                <?php if($errors->has('phone_number')): ?>
                                    <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('phone_number')); ?></strong>
                                            </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-actions row">
                            <label class="control-label col-sm-2" for=""></label>
                            <button type="submit" class="btn btn-large button-orange" formaction="">Save</button> &nbsp;&nbsp;
                            <a  href="<?php echo e(url('/client/all')); ?>" class="btn btn-large button-orange" style="font-weight: normal;" >Cancel</a>
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