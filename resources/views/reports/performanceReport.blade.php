@extends('layouts.index_template')
@section('content')
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <!--<script type="text/javascript" src="/data/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="/data/daterangepicker.css" /> -->

	<div class="modal fade" id="delete-track" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="modalConfirmDeleteTrack"></div>
            </div>
        </div>
    </div>

    <div id="conteiner" class="container" data-date=""
         data-status="{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}"
         data-token="{{ Session::token() }}"
         data-log-active = "<?= isset($_COOKIE['logTrackActiveLogId']) ? $_COOKIE['logTrackActiveLogId'] : ''?>"
         data-start = "<?=  isset($active['start']) ? $active['start'] : '' ?>"
         data-end = "<?=  isset($active['end']) ? $active['end'] : '' ?>">

        <div class="row margin-top-twenty">
            <div class="col-md-4 col-lg-4   btn-toolbar toolbar-span">
                <div class="daterange daterange--double repo-performance picker-width" ></div>
            </div>
            <div class="col-md-4 col-lg-4 padding-twenty">
                <select name="users" class=" input-xlarge focused my_input input-height" id="SelectedPerformance" data-all="true">
                    @if (empty($active['leadId']))
                        @if ($status == "Lead")
                          <option selected disabled value="" >Please select Member</option>  
                        @else
                          <option selected disabled value="" >Please select Lead</option>
                        @endif
                    @endif
                    @if(isset($leads))
						@foreach($leads as $key)
							<option value="<?= $key->id ?>" <?= ( isset($active['leadId']) && $key->id == $active['leadId']) ? 'selected' : '' ?>>{{ $key->name }} </option>
						@endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-4 col-lg-4 padding-twenty">
                <span class="pull-right font-thirty heading-color">Performance Report</span>
            </div>
        </div>
        <div class="row">

         <div class="performancereportbutton check-box-center">
			 @if(empty($report)|| $report == '')
			   <a href="" disabled class="btn btn-sm button-export no-click button-performance" id="button-performance" >Export to Excel</a>
			 @else
			   <a href="#"  class="btn btn-sm button-export button-performance" id="button-performance" >Export to Excel</a>
			 @endif
        </div>
      
		</div>

		<div class="row">
			<div class="col-md-12">
				<div id="performance-content" class="performance-block scroll-bar full-border hidden margin-bottom-large">
					<table cellpadding="0" cellspacing="0" border="0" class="table  table-fixed full-border no-margin-bottom" id="taskTable">
					  {!! $report !!}
					</table>
				</div>
			</div>

		</div>
    <div id="performanceexport">
    </div>
    </div>
    <script type="text/javascript">
       $(document).ready(function() {
		   /*SN 06/20/2017: commented below code and updated at reports.js*/
           /*$('#button-performance').click(function() {
             var htmlString = $("#performance-content").html();
			 var fileName = "performanceReport-"+todayDate();
             $('#performanceexport').html(htmlString);
             $('#performanceexport').tableExport({type:'xlsx', excelstyles:['border-bottom', 'border-top', 'border-left', 'border-right'],fileName: fileName,worksheetName: 'performanceReport'});
             $('#performanceexport').html('');
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
			}*/
       });
    </script>
	<script src="/js/reports.js"></script>
    <script src="/js/tasks.js"></script>
@endsection
