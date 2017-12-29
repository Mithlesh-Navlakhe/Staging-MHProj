@extends('layouts.index_template')

@section('content')
<div id="conteiner" class="container" data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}">
        <div class="modal fade" id="delete-team" role="dialog">
            <div class="modal-dialog"  >
                <!-- Modal content-->
                <div class="modal-content">
                    <div id="modalConfirmDeleteTeam">

                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <h3 class="h3-my">Teams</h3>
                <a href="/team/create" class="btn btn-large button-orange margin-left-large">
                    <i class="glyphicon glyphicon-plus"></i> Add Team
				</a>
            </div>
        </div>
        <div class="row-fluid">
            <!-- block -->
            <div class="block bottom-border no-left-border no-right-border">
                <div class="block-content collapse in">
                    <div class="span12">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="usersTable">
                            <thead>
								<tr>
									<th width="300px">Teams</th>
									<th width="300px">Lead</th>
									<th width="60px">Action</th>
								</tr>
                            </thead>
								<tbody>
									@foreach( $teams as $team )
										<tr class="odd gradeX">
											<td class="check-box-center">{{ $team->team_name }}</td>
											<td class="check-box-center">{{ $team->name }}</td>
											<td class="check-box-center">
											    <a href="/team/update/{{ $team->id }}"  class="btn btn-info"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
												<button type="button" class="btn btn-danger  deleteTeam" data-url="/team/delete/{{ $team->id }}" data-element="{{ $team->team_name }}">
													<span class="glyphicon glyphicon-floppy-remove span_no_event" aria-hidden="true"></span> Delete
												</button>
											</td>
										</tr>
									@endforeach
								</tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /block -->
        </div>
    </div>
</div>

@endsection
