@extends('layouts.index_template')

@section('content')
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>

<div class="modal fade" id="delete-user" role="dialog">
    <div class="modal-dialog"  >
        <!-- Modal content-->
        <div class="modal-content">
            <div id="modalConfirmDeleteUser"></div>
        </div>
    </div>
</div>

<div id="conteiner" class="container" data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}">
    <div class="row-fluid">
        <div class="span12">
            <h3 class="h3-my">Users</h3>
            <a href="/user/create" class="btn btn-large button-orange margin-left-large">
                <i class="glyphicon glyphicon-plus"></i> Add User</a>
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
                            <th class="header-project">Users</th>
                            <th>Email</th>
                            <!-- <th>Team</th> -->
							<th>Lead</th>
                            <th>Hourly rate</th>
                            <th>User type</th>
                            <th class="center date-area">Created Date</th>

                            @if ($status == 'HR Manager' || $status == 'Admin' || $status == 'Super Admin')
                                <th class="center action-header">Action</th>
                            @endif
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="thFoot header-project">Users</th>
                            <th class="thFoot">Email</th>
                            <!-- <th class="thFoot">Team</th> -->
							<th class="thFoot">Lead</th>
                            <th class="thFoot">Hourly rate</th>
                            <th class="thFoot">User type</th>
                            <th class="center thFoot date-area">Created Date</th>
                            @if ($status == 'HR Manager' || $status == 'Admin' || $status == 'Super Admin')
                                <th  class="removeSelect">Action</th>
                            @endif

                        </tr>
                        </tfoot>
                        <tbody>

                        @foreach( $users as $user )
                            <tr class="odd gradeX">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <!-- SN 05/04/2017: updated below to show lead name associated with user -->
								<td>
								@foreach($leads as $lead)
									@if($user->users_team_id == $lead->id)
										{{ $lead->name }}
									@endif
								@endforeach 
								</td>
                                <td class="check-box-center">{{ $user->hourly_rate }}</td>
                                <td>{{ $user->employe }}</td>
                                <td class=" check-box-center">{{ $user->created_at }}</td>
                                @if ($status == 'HR Manager' || $status == 'Admin' || $status == 'Super Admin')
                                    <td>
                                        @if ($status == 'Admin' || $status == 'Super Admin' ||
                                         ($status == 'HR Manager' &&
                                         ($user->employe == "Developer" || $user->employe == "QA Engineer" || $user->employe == "Lead")))
                                            <a href="/user/update/{{ $user->id }}"  class="btn btn-info"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
                                            <button type="button" class="btn btn-danger  deleteUser" data-url="/user/delete/{{ $user->id }}" data-element="{{ $user->name }}">
                                                <span class="glyphicon glyphicon-floppy-remove span_no_event" aria-hidden="true"></span> Delete</button>
                                        @endif
                                    </td>
                                @endif
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

@endsection
