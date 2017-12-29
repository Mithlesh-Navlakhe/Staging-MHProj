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
            <h3 class="h3-my">Task Types</h3>
            <a href="/task-type/create"  class="btn btn-large button-orange  margin-left-large">
                <i class="glyphicon glyphicon-plus"></i> Add Task Type</a>
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
                            <th class="type-header">Task Type</th>
                            <th>Description</th>
                            @if ($status == 'Super Admin' || $status == 'HR Manager' || $status == 'Admin')
                                <th class="center type-action-header">Action</th>
                            @endif
                        </tr>
                        </thead>
                        <!-- uncomment this to start drop down search functionality
                        <tfoot>
                        <tr>
                            <th width="30%" class="thFoot">Task Type</th>
                            <th class="thFoot">Description</th>
                            @if ($status == 'HR Manager' || $status == 'Admin')
                                <th  class="removeSelect">Action</th>
                            @endif
                        </tr>
                        </tfoot>
                      -->
                        <tbody>
                        @foreach( $tasksRes as $key )
                            <tr class="odd gradeX">
                                <td class="text-center">{{ $key['title'] }}</td>
                                <td class="text-center" >{{ $key['description'] }}</td>
                                @if ($status == 'Super Admin' || $status == 'Admin' || $status == 'Lead' || $status == 'Supervisor')
                                    <td class="text-center">
										<a href="/task-type/update/{{ $key['id']  }}"  class="btn btn-info"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
										<button type="button" class="btn btn-danger  deleteUser" data-url="/tasktype/delete/{{ $key['id']  }}" data-element="{{ $key['title']  }}">
										<span class="glyphicon glyphicon-floppy-remove span_no_event" aria-hidden="true"></span> Delete</button>
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
