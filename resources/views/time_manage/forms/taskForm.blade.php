@extends('layouts.index_template')

@section('content')
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <div class="container" id="conteiner" data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}"
            data-type-action="<?= isset( $task )  ? 'edit' : 'add'?>">

        <div class="row">
            <div class="row-fluid">
                <div class="margin-left-large col-md-12">
                    <div class="heading-without-datepicker"><?= ( isset( $task ) ) ? 'Edit' : 'Add' ;?> task</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row-fluid" id="login" >
                <!-- block -->
                <div class="block-content collapse in">
                    <div class="span12">
                        <form class="form-horizontal" role="form" method="POST" action="<?= ( isset( $task ) ) ? '/project/update' . $task[0]->id : '/project/create' ;?>">
                            {{ csrf_field() }}
                            @if (isset($task))

                            @endif
                            @if( $status != 'Developer' || $status != 'Developer' )
                            <div class="control-group row">
                                @if( isset( $client ) )
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="CompanyTaskId" >Client *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                        <select name="company_id" class="input-xlarge focused my_input input-height" id="CompanyTaskId" required >

                                        @if( !isset( $task ) )
                                            {{ $setComId=""}}
                                            <option  defaul value="">Please select Client</option>
                                            @if( old() && old('company_id'))
                                                {{ $setComId = old('company_id') }}
                                            @endif
                                            @if (isset($client->company_name))
                                                <option value="{{ $client->id }}" selected>{{ $client->company_name }}</option>
                                            @endif

                                            @foreach( $client as $key )
                                                @if ($key->id == $setComId)
                                                  <option  value="{{ $key->id}}" selected="">{{ $key->company_name }}</option>
                                                @else
                                                  <option  value="{{ $key->id}}" >{{ $key->company_name }}</option>
                                                @endif
                                            @endforeach

                                        @elseif( isset( $task ) )
                                            @foreach( $client as $key )
												<option value="{{ $key->id }}" @if( $task[0]->company_id == $key->id ) selected  @endif >{{ $key->company_name }}</option>
                                                
                                            @endforeach
                                        @endif
                                        </select>

										@if ($errors->has('company_id'))
											<span class="help-block error-text">
												<strong>{{ $errors->first('company_id') }}</strong>
											</span>
										@endif
                                </div>
                                @endif
                            </div>
                            @endif
                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
                                    <label class="control-label text-left" for="taskProjectId" >Project *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    @if( $status == 'Developer' )
                                        <select name="project_id" class="input-xlarge focused my_input input-height" id="taskProjectId" required >
                                        @if( isset( $task ) )
                                            @if( isset( $projects ) )
                                                @foreach( $projects as $project )
                                                        <option value="{{ $project->id }}" @if( $task[0]->project_id == $project->id ) selected @endif >{{ $project->project_name }}</option>
                                                @endforeach
                                            @else
                                                <option>No project available</option>
                                            @endif
                                        @else
                                            @if( isset( $projects ) )
                                                <option value="" selected>Select project</option>
                                                @foreach( $projects as $project )
                                                    <option value="{{ $project->id }}" class="{{ ($project->project_name == 'Leave') ? 'hidden' : '' }}">{{ $project->project_name }}</option>
                                                @endforeach
                                            @else
                                                <option>No project available</option>
                                            @endif
                                        @endif
                                        </select>
                                    @else
                                        <select name="project_id" class="input-xlarge focused my_input input-height"  id="taskProjectId"  required>
                                            @if( !isset( $task ) )
                                              {{ $setPrjId=""}}
                                              @if( old() && old('project_id'))
                                                  {{ $setPrjId = old('project_id') }}
                                                  @foreach ($project as $key)
                                                    @if($key->id == $setPrjId)
                                                      <option value="{{ $key->id }}" selected>{{ $key->project_name }}</option>
                                                    @endif
                                                  @endforeach
                                              @endif
                                            @elseif( isset( $task ) )
                                                @foreach( $project as $key )
                                                    <option value="{{ $key->id }}" @if( $task[0]->project_id == $key->id ) selected @endif > {{ $key->project_name }}</option>
													
                                                @endforeach
                                            @endif
                                        </select>
                                    @endif

                                    @if ($errors->has('project_id'))
                                        <span class="help-block error-text">
											<strong >{{ $errors->first('project_id') }}</strong>
										</span>
                                    @endif

                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left"  for="taskTypeId">Task Type *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                  <!-- Mith: 03/29/17: Update task type data populate from database  -->
                                    <select name="task_type" class="input-xlarge focused my_input input-height"  id="taskTypeId" required />
                                    @if( !isset( $task ) )
                                        <option  defaul value="">Please select Task Type</option>
                                        
                                         @foreach( $tasktype as $key )
                                            @if( $key->id == old('task_type'))
                                              <option  value="{{ $key->id}}" selected>{{ $key->task_type }}</option>
										    @elseif($key->task_type == 'Leave')
											  <option value="{{ $key->id}}" class="hidden">{{ $key->task_type }}</option>
                                            @else
                                              <option  value="{{ $key->id}}">{{ $key->task_type }}</option>
                                            @endif
                                        @endforeach

                                    @elseif( isset( $task ) )
                                        @foreach( $tasktype as $key )
											<!-- SN 04/17/2017: updated below code to show selected value -->
											<option value="{{ $key->id }}" @if( ($task[0]->task_type == $key->id) || ($task[0]->task_type == $key->task_type) ) selected @endif >{{ $key->task_type }}</option>
                                        @endforeach
                                    @endif

                                    </select>
                                    @if ($errors->has('task_type'))
                                        <span class="help-block error-text">
											<strong>{{ $errors->first('task_type') }}</strong>
										</span>
                                    @endif
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="taskTittleId">Task Title *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">

                                    <input name="task_titly" class="input-xlarge focused my_input" id="taskTittleId"   type="text" required
                                         value="<?= ( isset( $task[0]->task_titly ) ) ? $task[0]->task_titly : (( old('task_titly') ) ? old('task_titly') : '') ;?>"
                                        >
                                    @if ($errors->has('task_titly'))
                                        <span class="help-block error-text">
                                                <strong>{{ $errors->first('task_titly') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width">
                                    <label class="control-label text-left" for="taskDescriptionId" >Description</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <textarea name="task_description" class="input-xlarge focused my_input" id="taskDescriptionId" rows="6"  type="text"
                                            ><?= ( isset( $task[0]->task_description ) ) ? $task[0]->task_description : ( old('task_description') ? old('task_description') : '' ) ;?></textarea>
                                    @if ($errors->has('task_description'))
                                        <span class="help-block error-text">
                                                <strong>{{ $errors->first('task_description') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
                                    <label class="control-label text-left" for="HourlyRateProhectId">Allocated Hours</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <!-- Mith 03/31/17: Update html5 validation check   -->
                                    <input name="alloceted_hours" class="input-xlarge focused my_input" id="HourlyRateProhectId" title="Please match the requested format HH:MM"  pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="HH:MM"  step="0.15" type="text"

                                            value="<?= ( isset( $task[0]->alloceted_hours ) ) ? str_replace('.', ':',$task[0]->alloceted_hours) : ( (old() && old('alloceted_hours')) ? old('alloceted_hours') : '' ) ;?>" />
                                    @if ($errors->has('alloceted_hours'))
                                        <span class="help-block">
                                                <strong style="color:#802420">{{ $errors->first('alloceted_hours') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width" >
                                    <label class="control-label text-left" for="AssignToId">Assign To *</label>
                                </div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width">
                                    <!-- SN 05-05-2017: added below check for developer if added task by developer -->
									@if($status == "Developer")
										<select name="assign_to" class=" input-xlarge focused my_input" id="AssignTo" style="height: 42px;" data-all="true" data-value="@if(old() && old('assign_to')) {{ old('assign_to') }} @endif" required>	
											@if( isset( $task[0]->assign_to ) )
												@if(isset($task[0]->user->name))
													<option id="username" data-id="{{ $task[0]->assign_to }}" value="{{ $task[0]->assign_to }}" selected><?= $task[0]->user->name ?></option>
												@endif
											@else
												<option id="username" data-id="@if(Auth::user()->id){{ Auth::user()->id }}@endif" value="@if(Auth::user()->id){{ Auth::user()->id }}@endif" selected>{{ Auth::user()->name }}</option>
											@endif
										</select>
									@else
										<select name="assign_to" class=" input-xlarge focused my_input input-height" id="AssignToId" data-all="true" data-value="@if(old() && old('assign_to')) {{ old('assign_to') }} @endif" required>
											@if( isset( $task[0]->assign_to ) )
												@if (isset($user->name))
													<option id="username" data-id="{{ $user->id }}" value="{{ $user->id }}" selected>{{ $user->name }}</option>
												@endif
											@else
												<option selected disabled></option>
											@endif
										</select>
									@endif
                                    @if ($errors->has('assign_to'))
                                        <span class="help-block error-text">
											<strong >{{ $errors->first('assign_to') }}</strong>
										</span>
                                    @endif
                                </div>
								<!-- SN 05/10/2017: added hidden input for task assigned by -->
								<input name="task_assign_by" class="input-xlarge focused my_input" id="taskassignbyId" type="hidden" value="@if(Auth::user()->id){{ Auth::user()->id }}@endif" >
                            </div>

                            <div class="control-group row">
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 text-right label-mob-width"></div>
                                <div class="controls col-xs-8 col-sm-6 col-md-5 col-lg-4 input-mob-width" >
                                    <label class="control-label" for="BillableId" style="">Billable</label>
                                    <input type="checkbox"  name="billable" id="BillableId" value="1"
										<?= ( isset( $track ) && $track[0]->billable == 1 ) ? ' checked' : ((old('billable') == '1') ? ' checked': '' ) ; ?>
                                        @if( isset( $task[0]->billable ) && $task[0]->billable == true )
                                            checked
                                        @endif
										>
                                    <br>
                                </div>
                            </div>

                            <div class="form-actions row col-lg-12 col-md-12 ">
                                <label class="control-label col-sm-2 col-lg-2 col-md-2 col-xs-4 label-mob-width" for=""></label>
								<div class="col-sm-10 col-lg-10 col-md-10 input-mob-widt">
									<button type="submit" class="btn btn-large button-orange" formaction="">Save</button> &nbsp;&nbsp;
									<a  href="{{ url('/task/all') }}" class="btn btn-large button-orange">Cancel</a>
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
