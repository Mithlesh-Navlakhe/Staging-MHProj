@extends('layouts.index_template')

@section('content')
<?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>

<div class="modal fade" id="delete-track" role="dialog">
	<div class="modal-dialog"  >
		<!-- Modal content-->
		<div class="modal-content">
			<div id="modalConfirmDeleteTrack"></div>
		</div>
	</div>
</div>

<div id="conteiner" class="container" data-date="<?= isset($date)? $date : '' ?>"
	 data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}"
	 data-token="{{ Session::token() }}"
	 data-log-active = "<?= isset($_COOKIE['logTrackActiveLogId']) ? $_COOKIE['logTrackActiveLogId'] : ''?>">
	@if(Session::has('flash_message'))
		<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{!! session('flash_message') !!}
		</div>
	@endif

	<div class="row margin-top-twenty">
		<div class="span12 row">
			<div class="col-md-4 col-lg-4 col-xs-10 col-sm-4 btn-toolbar toolbar-span">
			   <div class="btn-toolbar margin-left-fifteen">
				<div id="timeStep5" class="btn-group">
					<button class="btn btn-sm calendarPrevDayReport calender-color">
						<span class="glyphicon glyphicon-chevron-left"></span>
					</button>
					<button class="btn btn-sm calendarNextReport calender-color">
						<span class="glyphicon glyphicon-chevron-right"></span>
					</button>
					<button class="btn btn-sm d5 calender-color">
						<span class="glyphicon glyphicon-th"></span>
					</button>
				</div>
			</div>
			</div>
			<div class="col-md-4 col-lg-4 daily-picker">
				<h2 class="margin-left-ten showDate"  id="timeTrackShowDate"></h2>
			</div>
			<div class="col-md-4 col-lg-4">
				<div class=" col-md-12 col-sm-12 col-xs-12 col-lg-12 report-text">
					<div class=" text-right font-thirty heading-color">Daily Report</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Mith: 04/13/17: added export to excel button. // SN 04/17/2017: updated button class as per LMZ-->
	<div class="row col-md-12">
		@if(empty($date) || !$dayReport->count())
		   <a href="" disabled="disabled" class="btn btn-sm button-export button-excel button-field no-click"> Export To Excel</a>
		@else
		   <a href="#" class="btn btn-sm button-export button-excel button-field">
				 Export To Excel
			</a>
		@endif
		
		<!-- SN 06/14/2017: added button to reordering table coloumn -->
		<button id="update-column-button" class="btn btn-sm button-export update-column-button margin-left-twenty button-field" data-name="daily-report">
			<span class="">Edit Column</span>
		</button>
		
		<?php
			$flag = 0;
			if(isset($dayReport)){
			   $prevProjId='';
			   foreach ($dayReport as $key => $task) {
				if(Auth::user()['original']['id'] == $task->assign_to){
				  $flag = 1;
				  break;
				}
			  }
			}
		?>
		
		@if($status == 'Lead' || $status == 'Developer' || $status == 'QA Engineer')
			@if(empty($date) || !$dayReport->count() || $flag == 0)
				<a href="" disabled class="btn btn-sm button-export status-report margin-left-twenty no-click button-field" id="status-report">
				   Status Report </a>
			@else
				<a href="/reports/status/<?= (isset($date)) ? $date : '' ?>" class="btn btn-sm button-export status-report margin-left-twenty button-field" id="status-report">
				Status Report </a>
			@endif
		@endif
		
	</div>
		<!--
		<div class="row">
			<div id="status-content" class="status-content" style="display:none;"> -->
				<?php
				/* Uncomment below code to show status report on popup
				/*
				$mailContent='';
				$mailBody='';
				//$mailHeaderList="<p style='font-weight:bold;'>Project Name   Task Title     Allocate time Track Time   </p> ";

				if(isset($dayReport)){
				   $prevProjId='';
				   foreach ($dayReport as $key => $task) {
					if(Auth::user()['original']['id'] == $task->assign_to){
						if($prevProjId != $task->project_id){
								  if($prevProjId != ''){
									 $mailBody .= '</ul>';
								  }
									  $mailBody .= "<p style='font-weight:bold;'> <u>". $task->project->project_name ." </u></p>";
									  $mailBody .= "<ul>";
								  }
								  $mailBody .= "<li>".$task->task_titly. "&nbsp;(".(isset($task->alloceted_hours) ? $task->alloceted_hours : '').")&nbsp;&nbsp;&nbsp;".(isset($task->status_time) ? $task->status_time : '')."</li>";
								  $prevProjId = $task->project_id;
							   }
							   $mailContent = $mailBody;
				   }
				}else{
				   $mailContent = "No Content";
				}
				//echo $mailHeaderList;
				$mailContent .= "</ul>";
				$mailContent .= "<p style='font-weight:bold;'>Total Hours : ".$total['status_total']."</p>";
				//$mailContent .= "<u>".$total['totalTime']."</u>";
						echo $mailContent;
				*/
				?>
				<!--
			</div>
		</div> -->
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
	
	<div class="row-fluid">
		<!-- block -->
		<div class="block bottom-border no-left-border no-right-border">
			<div class="block-content collapse in">
				<div class="span12">
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered my_input" id="usersTable">
						<thead>
						<tr>
							<th class="header-project">Person Name</th>
							<th>Client</th>
							<th>Project</th>
							<th>Task</th>
							<th>Task Type</th>
							<th>Billable</th>
							<th>Hours</th>
							<th>Value</th>
						</tr>
						</thead>
						<tfoot>
						<tr>
							<th class="thFoot header-project"></th>
							<th class="thFoot" ></th>
							<!-- <th class="thFoot" >User</th>-->
							<th class="thFoot" ></th>                                
							<th class="thFoot" ></th>
							<th class="thFoot" ></th>
							<th class="thFoot" ></th>
							<th class="thFoot" ></th>
							<th class="thFoot" ></th>

						</tr>
						</tfoot>
						<tbody>
						@if (isset($dayReport))
							@foreach( $dayReport as $key )
								<tr class="odd gradeX">
									<td>{{ $key->user->name }}</td>
									<td>{{ $key->client->company_name }}</td>
									<td>{{ $key->project->project_name }}</td>
									<td>{{ $key->task_titly }}</td>
									<td>{{ $key->task_type }}</td>
									<td class="check-box-center">{{ isset($key->billable) ? (($key->billable == 1) ? 'YES' : 'NO') : '' }}</td>
									<td class="check-box-center">{{ $key->total }}</td>
									<td class="check-box-center">{{ $key->value }}</td>
								</tr>
							@endforeach
						@endif
						</tbody>
					</table>
				</div>
				<div class="row">
					<div class="info-block padding-left-fifteen">
						<strong>Total</strong>
						<strong>Hours: <span id="total-hour">{{ $total['totalTime'] }}</span></strong> |
						<strong>Value: <span id="total-value">{{ $total['totalValue'] }}</span> </strong>
					</div>
				</div>
			</div>

		</div>

		<!-- /block -->
	</div>
	<div id="dailyexport">
	</div>

</div>
<!--    <script src="/js/jquery/jquery-3.1.1.min.js"></script>-->
<script type="text/javascript">
   $(document).ready(function() {
	 $('.button-excel').click(function() {
		var table = $('#usersTable').DataTable();
		var tableHtml = "<table cellpadding='0' cellspacing='0' border='0' >";
			tableHtml += "<thead>";
			tableHtml += "<tr>";
			tableHtml += "<th width='130px'>Person Name</th><th>Client</th><th>Project</th><th>Task</th><th>Task Type</th><th>Billable</th><th>Hours</th><th>Value</th>";
			tableHtml += "</tr></thead>";

		//var rowdata = table.rows( {page:'all'} ).data();
		var totalHour=0;
		var totalvalue=0;
		var rowdata = table.rows( { search:'applied' } ).data();
		if(rowdata.length == 0) return;
		for(i=0;i<rowdata.length;i++){
			tableHtml += "<tr class='gradeX odd' role='row'>";
			for(j=0;j<rowdata[i].length;j++){
				var temp = "<td>"+rowdata[i][j]+"</td>";
				tableHtml += temp;
				if(j == 6){
				  totalHour += moment.duration(rowdata[i][j], "hh:mm").asSeconds();
				}
				if(j == 7){
				  totalvalue += parseInt(rowdata[i][j]);
				}
			}
			tableHtml += "</tr>";
		}
		tableHtml += "<tr class='gradeX odd' role='row'>";
		tableHtml += "<td>Hours: </td><td>"+SecondsTohhmmss(totalHour)+"</td></tr>";
		tableHtml += "<tr class='gradeX odd' role='row'>";
		tableHtml += "<td>Value: </td><td>"+totalvalue+"</td></tr>";
		tableHtml += "</table>";
		var fileName = "dailyReport-"+todayDate();
		$('#dailyexport').html(tableHtml);
		$('#dailyexport').tableExport({type:'xlsx', excelstyles:['border-bottom', 'border-top', 'border-left', 'border-right'],fileName: fileName,worksheetName: 'dailyReport'});
		$('#dailyexport').html('');
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
