@extends('layouts.index_template')

@section('content')

    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <!--<script type="text/javascript" src="/data/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="/data/daterangepicker.css" /> -->

    <div class="modal fade" id="delete-track" role="dialog">
        <div class="modal-dialog"  >
            <!-- Modal content-->
            <div class="modal-content">
                <div id="modalConfirmDeleteTrack"></div>
            </div>
        </div>
    </div>

    <div id="conteiner" class="container" data-date="<?= isset($dates)? $dates : '' ?>"
         data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}"
         data-token="{{ Session::token() }}"
         data-log-active = "<?= isset($_COOKIE['logTrackActiveLogId']) ? $_COOKIE['logTrackActiveLogId'] : ''?>"
         data-start = "<?=  isset($active['start']) ? $active['start'] : '' ?>"
         data-end = "<?=  isset($active['end']) ? $active['end'] : '' ?>">

        <div class="row margin-top-twenty">
            <div class="span12 row">
				<div class="col-md-4 col-lg-4 btn-toolbar toolbar-span ">
					<div class="daterange daterange--double one2 picker-width margin-left-fifteen" ></div>
				</div>
				<div class="col-md-4 col-lg-4 padding-twenty">
					<div class="row col-md-12 col-sm-12 col-xs-12 project-dropdown">
						<select name="users" class=" input-xlarge focused my_input input-height"   id="SelectAllProjectReport" data-all="true">
							@if (empty($active['projectId']))
								<option selected disabled value="" >Please select Project</option>
							@endif

							@if(isset($projectsList))
								<option value="all" <?= ( isset($active['projectId']) && 'all' == $active['projectId']) ? 'selected' : '' ?>>All</option>
								@foreach($projectsList as $key)
									<!-- SN 05-05-2017: added client name associated with project name -->
									<option value="<?= $key->id ?>" <?= ( isset($active['projectId']) && $key->id == $active['projectId']) ? 'selected' : '' ?>>{{ $key->project_name }} - {{ $key->company_name }}</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
				<div class="col-md-4 col-lg-4 padding-twenty">
					<div class="row col-md-12 col-sm-12 col-xs-12 margin-ten report-text">
						<span class="pull-right font-thirty heading-color">Project Report</span>
					</div>
				</div>
           </div>
        </div>

		<!-- Mith: 04/13/17: added export to excel button.// SN 04/17/2017: updated button class as per LMZ -->
        <div class="row col-md-12">
            @if ((empty($active['start']) && empty($active['end']) && empty($active['projectId'])) || !$projectReport->count())
              <a href="#" disabled class="btn btn-sm button-export button-excel  no-click">Export To Excel</a>
            @else
              <a href="#" class="btn btn-sm button-export button-excel">Export To Excel</a>
            @endif
			
			<!-- SN 06/14/2017: added button to reordering table coloumn -->
			<button id="update-column-button" class="btn btn-sm button-export update-column-button margin-left-twenty" data-name="project-report">
                <span class="">Edit Column</span>
            </button>
        </div>

		<!--  SN 06/20/2017: added below block to contain edit column values from backend -->
		<div class="row">
			<div class="column-fields hidden">
				<div class="column-content">
					@if(isset($column))
					  <span class="column-value">@if($column->column_id){{ $column->column_id }}@endif</span>
					@endif
				</div>
			</div>
		</div>
		
		<!---  SN 07/13/2017: added date-picker ranges to filter task according to two dates -->
		<div class="row col-md-12">			
			<div id="track-button-group" class="btn-group track-button-group row col-md-12 margin-bottom-ten">
				<button class="btn btn-sm startdate-calender margin-ten calender-color" >
					<span>Start Date</span><span class="glyphicon glyphicon-th padding-left-ten"></span>
					<div><input type="text" name="start-date" id="start-date" readonly class="check-box-center"  value=""></div>
				</button>
				<button class="btn btn-sm finishtdate-calender margin-ten calender-color">
					<span>Finish Date</span><span class="glyphicon glyphicon-th padding-left-ten"></span>
					<div><input type="text" name="finish-date" id="finish-date" readonly class="check-box-center"  value=""></div>
				</button>
				<input id="date-archive" class="date-archive" type="hidden" placeholder="Select Dates"/>
				<div class="button-group-width margin-top-ten">
					<button id="search-result" class="btn btn-info search-result no-curve" >Search</button> 
					<button id="close-result" class="btn btn-default close-result">Clear</button>
				</div> 	
			</div>
		</div>
		
        <div class="row-fluid">
            <!-- block -->
            <div class="block bottom-border no-left-border no-right-border">
                <div class="block-content collapse in">
                    <div class="span12">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered my_input" id="usersTable">
                            <thead>
                            <tr>
                                <th class="header-project">Person</th>
                                <th>Task</th>
                                <th>Task Type</th>
								<?= ( isset($active['projectId']) && 'all' == $active['projectId']) ?  '<th>Project</th>' : '' ?>
								<th>Hours</th>
                                <th>Billable</th>
                                <th>Value</th>
                                <th>Cost</th>
                                <th>Economy</th>
								<th>Start Date</th>
                                <th>Finish Date</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="thFoot header-project"></th>
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>
								<?= ( isset($active['projectId']) && 'all' == $active['projectId']) ? "<th class='thFoot' ></th>" : '' ?>
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>
                                <th class="thFoot" ></th>
								<th class="thFoot removeSelect" ></th>
                                <th class="thFoot removeSelect" ></th>

                            </tr>
                            </tfoot>

                            <tbody>

                            @if (isset($projectReport))
                                @foreach( $projectReport as $key => $task )
                                    <tr class="odd gradeX">
                                        <td>{{ isset($task->user->name) ? $task->user->name : 'No Person' }}</td>
                                        <td>{{ isset($task->task_titly) ? $task->task_titly : 'No task title'}}</td>
                                        <td>{{ isset($task->task_type) ? $task->task_type : 'No task type' }}</td>
										@if(isset($active['projectId']) && $active['projectId'] == 'all')
                                          <td>{{ isset($task->project->project_name) ? $task->project->project_name : 'No project' }}</td>
                                        @endif
                                        <td>{{ isset($task->hours) ? $task->hours : 'No time' }}</td>
                                        <td>{{ isset($task->billable) ? (($task->billable == 1) ? 'YES' : 'NO') : '' }}</td>
                                        <td>{{ isset($task->value) ? $task->value : 'No value' }}</td>
                                        <td>{{ isset($task->cost) ? $task->cost : 'No cost' }}</td>
                                        <td>{{ isset($task->economy) ? $task->economy : 'No economy' }}</td>
										<td class="">{{ isset($task->track_date) ? $task->track_date : ''  }}</td>
									    <td class="">{{ isset($task->finish_track) ? date('Y-m-d', strtotime($task->finish_track)) : '' }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>
                    <div class="row">
						<div class="info-block padding-left-fifteen">
							@if( isset( $total ) )
								<strong>Total Hours: <?= $total['totalTime'] ?> </strong><br>
								<strong>Value: <?= $total['totalValue'] ?> </strong> |
								<strong>Cost: <?= $total['totalCost'] ?> </strong> |
								<strong>Economy: <?= $total['totalEconomy'] ?> </strong>
							@else
								<strong>Total Hours: 0</strong><br>
								<strong>Value: 0 </strong> |
								<strong>Cost: 0 </strong> |
								<strong>Economy: 0 </strong>
							@endif
						</div>
                    </div>
                </div>

            </div>

            <!-- /block -->
        </div>
        <div id="projectexport" >   </div>

    </div>
	
	<script type="text/javascript" src="/js/datefilter.js"></script>
    <!--    <script src="/js/jquery/jquery-3.1.1.min.js"></script>-->
    <script type="text/javascript">
       $(document).ready(function() {
         $('.button-excel').click(function() {
             var table = $('#usersTable').DataTable();
             var rowdata = table.rows( { search:'applied' } ).data();
             var selectedValue = $("#SelectAllProjectReport option:selected").val();
            var tableHtml = "<table cellpadding='0' cellspacing='0' border='0' >";
                tableHtml += "<thead>";
                tableHtml += "<tr>";
                tableHtml += "<th width='130px'>Person</th><th>Task</th><th>Task Type</th>";
                if(selectedValue == 'all'){tableHtml += "<th>Project</th>";}
                tableHtml += "<th>Hours</th><th>Billable</th><th>Value</th><th>Cost</th><th>Economy</th><th>Started at</th><th>Finish at</th>";
                tableHtml += "</tr></thead>";
            //var rowdata = table.rows( {page:'all'} ).data();
            var totalHour=0;
            var totalvalue=0;
			      var totalcost=0;
			      var totaleconomy=0;
            if(rowdata.length == 0) return;
            for(i=0;i<rowdata.length;i++){
                tableHtml += "<tr class='gradeX odd' role='row'>";
				        var collength = rowdata[i].length;
                var taskvalue=0;
                var taskcost=0;
                for(j=0;j<rowdata[i].length;j++){
                    tableHtml += "<td>"+rowdata[i][j]+"</td>";
                    if(j == ((selectedValue == 'all')?4:3)){
                      totalHour += moment.duration(rowdata[i][j], "hh:mm").asSeconds();
                    }
                    if(j == ((selectedValue == 'all')?6:5)){
                      taskvalue = parseInt(rowdata[i][j]);
                      totalvalue += taskvalue;
                    }
        	        if(j == ((selectedValue == 'all')?7:6)){
                      taskcost = parseInt(rowdata[i][j]);
                      totalcost += taskcost;
                    }
                }
                tableHtml += "</tr>";
            }
            totaleconomy = totalvalue - totalcost;
            tableHtml += "<tr class='gradeX odd' role='row'>";
            tableHtml += "<td></td></tr>";
            tableHtml += "<tr class='gradeX odd' role='row'>";
            tableHtml += "<td>Total Hours: </td><td>"+SecondsTohhmmss(totalHour)+"</td></tr>";
            tableHtml += "<tr class='gradeX odd' role='row'>";
            tableHtml += "<td>Value: </td><td>"+totalvalue+"</td></tr>";
			      tableHtml += "<tr class='gradeX odd' role='row'>";
            tableHtml += "<td>Cost: </td><td>"+totalcost+"</td></tr>";
            tableHtml += "<tr class='gradeX odd' role='row'>";
            tableHtml += "<td>Economy: </td><td>"+totaleconomy+"</td></tr>";
            tableHtml += "</table>";
			var fileName = "projectReport-"+todayDate();
            $('#projectexport').html(tableHtml);
            $('#projectexport').tableExport({type:'xlsx', excelstyles:['border-bottom', 'border-top', 'border-left', 'border-right'],fileName: fileName,worksheetName: 'projectReport'});
            $('#projectexport').html('');
         });
		 var todayDate = function(){
              var today = new Date();
              var dd = today.getDate();
              var mm = today.getMonth()+1; //January is 0!
              var yyyy = today.getFullYear();
              if(dd<10) {
               dd='0'+dd
              }
              if(mm<10) {
               mm='0'+mm
              }
              today = yyyy+'-'+mm+'-'+dd;
              return today;
         }
       });
       var SecondsTohhmmss = function(totalSeconds) {
           var hours   = Math.floor(totalSeconds / 3600);
           var minutes = Math.floor((totalSeconds - (hours * 3600)) / 60);
           var seconds = totalSeconds - (hours * 3600) - (minutes * 60);

           // round seconds
           seconds = Math.round(seconds * 100) / 100

           var result = (hours < 10 ? "0" + hours : hours);
           result += ":" + (minutes < 10 ? "0" + minutes : minutes);
           result += ":" + (seconds  < 10 ? "0" + seconds : seconds);
           return result;
       }
    </script>
	<script src="/js/reports.js"></script>
    <script src="/js/tasks.js"></script>

@endsection
