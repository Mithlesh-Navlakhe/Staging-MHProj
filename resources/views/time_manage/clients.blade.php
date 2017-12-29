@extends('layouts.index_template')

@section('content')
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>

    <div class="modal fade" id="delete-client" role="dialog">
        <div class="modal-dialog"  >
            <!-- Modal content-->
            <div class="modal-content">
                <div id="modalConfirmDeleteClient"></div>
            </div>
        </div>
    </div>

    <div id="conteiner" class="container" data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}">

        <div class="row-fluid">
            <div class="span12">
                <h3 class="h3-my">Clients</h3>
                <a href="/client/create" class="btn btn-large button-orange  margin-left-large">
                    <i class="glyphicon glyphicon-plus"></i> Add Client</a>
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
                                <th class="client-header">Company Name</th>
                                <th class="thHead">Address</th>
                                <th class="thHead">Website</th>
                                <th>Contact Person</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                              
                                @if ($status == 'HR Manager' || $status == 'Admin' || $status == 'Super Admin')
                                    <th class="center action-header">Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="thFoot" >Company Name</th>
                                <th class="thFoot thHead">Address</th>
                                <th class="thFoot thHead thFootSite">Website</th>
                                <th class="thFoot">Contact Person</th>
                                <th class="thFoot">Email</th>
                                <th class="thFoot">Phone Number</th>
                                <!--  <th style="min-width: 160px"  class="center">Created at</th> -->
                               @if ($status == 'HR Manager' || $status == 'Admin' || $status == 'Super Admin')
                                    <th class="removeSelect" >Action</th>
                                @endif
                            </tr>
                            </tfoot>
                            <tbody>


                            @foreach( $clients as $client )
                                <tr class="odd gradeX getProjects" data-id="{{ $client->id }}">
                                    <td>{{ $client->company_name }}</td>
                                    <td class="thHead">{{ $client->company_address }}</td>
                                    <td class="webClick website-color" >
                                       {{ $client->website }}
                                        </td>
                                    <td>{{ $client->contact_person }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->phone_number }}</td>
                                  <!--  <td style="text-align: center">{{ $client->created_at }}</td> -->

                                    @if ($status == 'HR Manager' || $status == 'Admin' || $status == 'Super Admin')
                                        <td>
                                            @if ($status == 'Admin' || $status == 'Super Admin' ||
                                             ($status == 'HR Manager'))

                                                <a href="/client/update/{{ $client->id }}"  class="btn btn-info"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
                                                <button type="button" class="btn btn-danger  deleteClient" data-url="/client/delete/{{ $client->id }}" data-element="{{  $client->company_name  }}">
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
