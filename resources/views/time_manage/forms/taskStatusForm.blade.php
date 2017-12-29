@extends('layouts.index_template')

@section('content')
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <div class="container" id="conteiner" data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}">

        <div class="row">
            <div class="row-fluid col-md-12">
                <div class="margin-left-large">
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
                            {{ csrf_field() }}

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
                                    <label class="control-label text-left" for="ProjectNameId">Task Status *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <input name="task_status" class="input-xlarge focused my_input" id="TaskStatusId"  type="text"  title="Text Value Only"
                                        value="<?= ( isset( $taskstatus[0]['name'] ) ) ? $taskstatus[0]['name'] : ((old() && old('task_status')) ? old('task_status') : ''); ?>" required
                                    />
                                    @if ($errors->has('task_status'))
                                        <span class="help-block error-text">
                                                <strong >{{ $errors->first('task_status') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
							
							<div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="ProjectNameId">Status Order *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <input name="status_order" class="input-xlarge focused my_input" id="Status_Order"  type="text" pattern="[0-9]" title="Number Value Only" minLength="1" maxLength="10"
                                        value="<?= ( isset( $taskstatus[0]['status_order'] ) ) ? $taskstatus[0]['status_order'] : ((old() && old('status_order')) ? old('status_order') : ''); ?>" required
                                    />
                                    @if ($errors->has('status_order'))
                                        <span class="help-block error-text">
                                                <strong >{{ $errors->first('status_order') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="NotesProjectId">Description</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <textarea name="description" class="input-xlarge focused my_input" id="Description" rows="6"  type="text" required minlength="5">
									 <?= ( isset( $taskstatus[0]['description'] ) ) ? $taskstatus[0]['description'] : '' ?>
									</textarea>
                                    @if ($errors->has('task_type'))
                                        <span class="help-block error-text">
                                            <strong>{{ $errors->first('task_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-actions row col-lg-12 col-md-12">
                                <label class="control-label col-sm-2 col-lg-2 col-md-2 col-xs-4 label-mob-width" for=""></label>
								<div class="col-sm-10 col-lg-10 col-md-10">
									<button type="submit" class="btn btn-large button-orange save-butto" formaction="">Submit</button> &nbsp;&nbsp;
									<a  href="/task-status/all/" class="btn btn-large button-orange" style="font-weight: normal;" >Cancel</a>
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
@endsection
