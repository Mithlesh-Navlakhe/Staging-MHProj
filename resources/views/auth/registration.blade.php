@extends('layouts.index_template')

@section('content')
    <link href="/css/jquery.multiselect.css" rel="stylesheet" type="text/css">
    <link href="/css/multipleselect.css" rel="stylesheet" type="text/css">
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
        <div class="container" id="conteiner" data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}">

            <div class="row col-md-12">
                <div class="row-fluid col-md-12">
                    <div class="heading-top-margin">
                        <div class="heading-without-datepicker">Add user</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row-fluid col-md-12" id="login" >
                    <!-- block -->
                    <div class="block-content collapse in">
                        <div class="span12">
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/create') }}">
                                {{ csrf_field() }}
                                    <div class="control-group row" >
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
											<label class="control-label " for="focusedInput">User type *</label>
                                        </div>

                                        <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                            <select name="employe" class="input-xlarge focused my_input input-height" id="selectTeam" required />
                                                <option selected disabled value="">Select</option>
                                                @if( old('employe') )
                                                    <option>{{ old('employe') }}</option>
                                                @endif
                                                @if ($status == 'Super Admin')
													<option>Super Admin</option>
													<option>Admin</option>
													<option>Supervisor</option>
													<option>HR Manager</option>
                                                @endif
                                                <option>Lead</option>
                                                <option>Developer</option>
                                                <option>QA Engineer</option>
                                            </select>
                                            @if ($errors->has('employe'))
                                                <span class="help-block error-text">
													<strong>{{ $errors->first('employe') }}</strong>
												</span>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="control-group row">
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
											<label class="control-label" for="focusedInput">Name  *</label>
                                        </div>
                                        <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                            <input name="name" class="input-xlarge focused my_input" id="focusedInput"  autofocus type="text" value="{{ old('name') }}" required>
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                <strong style="color:#802420">{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="control-group  row">
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
											<label class="control-label" for="focusedInput">Email *</label>
                                        </div>
                                        <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
											<input name="email" class="input-xlarge focused my_input" value="{{ old('email') }}" type="email" required>
                                            @if ($errors->has('email'))
                                                <span class="help-block error-text">
													<strong>{{ $errors->first('email') }}</strong>
												</span>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="control-group  row" id="hourlyRate"  style="display:none;">
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
											<label class="control-label" for="hourlyRateId">Hourly rate</label>
                                        </div>
                                        <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                            <input name="hourlyRate" class="input-xlarge  focused my_input"  id="hourlyRateId" value=""  type="number" step="0.01">
                                            @if ($errors->has('hourlyRate'))
                                                <span class="help-block error-text">
                                                    <strong>{{ $errors->first('hourlyRate') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                    <!-- Mith: 05/05/2017: uncomment to show team dropdown. 
									<div id="team_name" style="display:none;" class="control-group  row">
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                                            <label class="control-label" for="focusedInput">Team</label>
                                        </div>
                                        @if( $teams == true )
                                            <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4">
                                              <select name="users_team_id" class="input-xlarge focused my_input" style="height: 42px">
                                                <option value="" selected>Select team </option>
                                                  @foreach( $teams as $team )
                                                <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                                  @endforeach
                                              </select>
                                            </div>
                                        @endif

                                        @if ($errors->has('team_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('team_name') }}</strong>
                                            </span>
                                        @endif
                                    </div> -->
                                    <div id="lead_name" style="display:none;" class="control-group  row">
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                            <label class="control-label" for="focusedInput">Lead</label>
                                        </div>
                                        @if( $leads == true )
                                            <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                              <select name="lead_id" class="input-xlarge focused my_input input-height">
                                                <option value="" selected>Select lead</option>
                                                @foreach( $leads as $lead )
													<!--<input name="team_name" class="input-xlarge focused" id="focusedInput"  type="textl" required> -->
													<option value="{{ $lead->id }}">{{ $lead->name }}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                        @endif

                                        @if ($errors->has('lead_name'))
                                            <span class="help-block error-text">
                                                <strong>{{ $errors->first('lead_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div id="project_name" style="display:none;" class="control-group  row">
                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" style="text-align: right;">
                                            <label class="control-label" for="focusedInput">Project</label>
                                        </div>
                                        @if( $projects == true )
                                            <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                              <select name="projects[]" class="input-xlarge focused my_input" multiple id="projects">
                                                @foreach( $projects as $projt )
                                                  <option value="{{ $projt->id }}" >{{ $projt->project_name }}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                        @endif
                                    </div>
                                    
									<div class="form-actions row col-lg-12 col-md-12 ">
										<label class="control-label col-sm-2 col-lg-2 col-md-2 col-xs-4 label-mob-width" for=""></label>
										<div class="col-sm-10 col-lg-10 col-md-10 input-mob-widt">
											<button type="submit" class="btn btn-large button-orange" formaction="">Save</button> &nbsp;&nbsp;
                                            <a  href="{{ url('/user/all') }}" class="btn btn-large button-orange" style="font-weight: normal;" >Cancel</a>
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
<script src="/js/jquery.multiselect.js"></script>
<script src="/js/multipleselect.js"></script>
@endsection
