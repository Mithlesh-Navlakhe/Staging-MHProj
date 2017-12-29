@extends('layouts.index_template')

@section('content')

        <div id="conteiner" data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}"></div>

        <div class="container">
            <div class="row-fluid col-md-12">
                <div class="span12 heading-top-margin">
                    <div class="heading-without-datepicker"><?= isset($teams) ? 'Edit' : 'Add' ?> Team</div>
                </div>
            </div>
            <div class="row-fluid" id="login" >
                <!-- block -->
                <div class="block-content collapse in">  
                    <div class="span12">
                        <form class="form-horizontal" role="form" method="POST" action="<?= isset($teams) ? '/team/update' : '/team/create' ?>">
                            {{ csrf_field() }}
							<div class="control-group row">
								<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
									<label class="control-label" for="focusedInput">Team name *</label>
								</div>
								<div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
									<input name="team_name" class="input-xlarge focused my_input" id="focusedInput" autofocus required type="text" 
									value="<?= isset($teams->team_name) ? $teams->team_name : ((old('team_name')) ? old('team_name') : '') ?>" required />
									@if ($errors->has('team_name'))
										<span class="help-block error-text">
											<strong>{{ $errors->first('team_name') }}</strong>
										</span>
									@endif
								</div>
							</div>


                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="taskProjectId">Leads</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
									<select name="teams_lead_id" class="input-xlarge focused my_input"  id="CompanyNameProjectId" style="height: 42px;">
										<option selected disabled>Please select Lead</option>
										@foreach( $leads as $key )
											@if(isset($teams))
											  @if($key->id == $teams->teams_lead_id)
												<option value="{{ $key->id}}" selected>{{ $key->name }}</option>
											  @else
												<option  value="{{ $key->id}}">{{ $key->name }}</option>
											  @endif
											@else
											  <option  value="{{ $key->id}}">{{ $key->name }}</option>
											@endif
										@endforeach
									</select>
                                </div>
							</div>
                            </div>
							<div class="form-actions row col-lg-12 col-md-12">
								<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right"></div>
								<div class="col-sm-10 col-lg-10 col-md-10">
									<label class="control-label " for="focusedInput"></label>
									<button type="submit" class="btn btn-large button-orange" formaction="">Save</button> &nbsp;&nbsp;
									<a  href="{{ url('/team/all') }}" class="btn btn-large button-orange" >Cancel</a>
								 </div>
							</div>

                        </form>
                    </div>
                </div>
                <!-- /block -->
            </div>
        </div>
        <script src="/js/jquery/jquery-3.1.1.min.js"></script>
        <script src="/js/registration.js"></script>
@endsection
