'use strict';
$(document).ready(function(){
    $(window).load(function(){
		
		// start date datepicker at time track page
		$(".startdate-calender").datepicker({
				autoclose: true,
				todayBtn: "linked",
				todayHighlight: true,
				showAnim: "blind"
			}
		).on('changeDate', function (e) {
				var caldate = e.format();
				caldate = moment(caldate, 'MM/DD/YYYY').format('YYYY-MM-DD');   
				$("#start-date").val(caldate);
				
				/*
				$('#usersTable').DataTable().columns(10).search('').draw();
				$('#usersTable').DataTable().columns(9).search(caldate).draw();  */
				
		});
		$('.startdate-calender').datepicker('update', new Date(moment($('#conteiner').data('date'), 'DD-MM-YYYY')));
	
		// finish date datepicker at time track page
		$(".finishtdate-calender").datepicker({
				autoclose: true,
				todayBtn: "linked",
				todayHighlight: true,
				showAnim: "blind"
			}
		).on('changeDate', function (e) {
				var caldate = e.format();  
				caldate = moment(caldate, 'MM/DD/YYYY').format('YYYY-MM-DD');		
				$("#finish-date").val(caldate);
				
				/*
				$('#usersTable').DataTable().columns(9).search('').draw();
				$('#usersTable').DataTable().columns(10).search(caldate).draw(); */
				
		});
		$('.finishtdate-calender').datepicker('update', new Date(moment($('#conteiner').data('date'), 'DD-MM-YYYY')));
	
		// start date datepicker at time track page
		$(".startdate-archive").datepicker({
				autoclose: true,
				todayBtn: "linked",
				todayHighlight: true
			}
		).on('changeDate', function (e) {
				var caldate = e.format();
				caldate = moment(caldate, 'MM/DD/YYYY').format('YYYY-MM-DD');   
				$("#start-date").val(caldate);
				/*$('#usersTable').DataTable().columns(8).search('').draw();
				$('#usersTable').DataTable().columns(7).search(caldate).draw();  */
				
		});
		$('.startdate-archive').datepicker('update', new Date(moment($('#conteiner').data('date'), 'DD-MM-YYYY')));
		
		// finish date datepicker at time track page
		$(".finishtdate-archive").datepicker({
				autoclose: true,
				todayBtn: "linked",
				todayHighlight: true
			}
		).on('changeDate', function (e) {
				var caldate = e.format();  
				caldate = moment(caldate, 'MM/DD/YYYY').format('YYYY-MM-DD');		
				$("#finish-date").val(caldate);
				/*$('#usersTable').DataTable().columns(7).search('').draw();
				$('#usersTable').DataTable().columns(8).search(caldate).draw(); */
				
		});
		$('.finishtdate-archive').datepicker('update', new Date(moment($('#conteiner').data('date'), 'DD-MM-YYYY')));
		
		$(document).on('click', '#close-result', function() { 
			$("#start-date, #finish-date, #date-archive").val("");
			//window.location = window.location.href;
			$('#usersTable').DataTable().draw(); 
		});
		
		/** datepicker filter code **/
		$("#track-button-group").on('click', '#search-result', function(){
			var startdate = $("#start-date").val();
			var enddate = $("#finish-date").val();
			$("#date-archive").val(startdate + ' to ' + enddate);
			$('#usersTable').DataTable().draw();
		});
		
		$("#cal-icon").on('apply.daterangepicker', function(ev, picker) {
			  $("#date-archive").val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
			  $('#usersTable').DataTable().draw();
		});

		$("#date-archive, #cal-icon").on('cancel.daterangepicker', function(ev, picker) {
			  $("#date-archive").val('');
			  //$('#usersTable').DataTable().draw();
			  window.location = window.location.href;
		});
		
		var status = $("#conteiner").data('status'); 
		var index = [];
		index = $('#usersTable').DataTable().columns(':contains(Date)')[0];   
		
		$.fn.dataTableExt.afnFiltering.push(
		function( oSettings, aData, iDataIndex ) {			
			
			var daterange = $("#date-archive").val();
			var results_daterange = daterange.split(" to ");
			var filterstart = results_daterange[0];
			var filterend = results_daterange[1];
				
			var startdatecol, enddatecol;
			
			if(index.length > 0){
				if(index[0]){
					startdatecol = index[0];
				}
				
				if(index[1]){
					enddatecol = index[1];  
				}else{
					enddatecol = index[0];
				}
			}   
			//SN 07/17/2017: added below check to finishdate should be greater then startdate.
			if (filterstart > filterend && filterend !== ''){
				var msg = "Finish Date should be bigger then Start Date";
			    Main.displayFilterErrorModal('#delete-track', msg, '#modalConfirmDeleteTrack');
				$("#close-result").trigger('click');
			}
			
			var tabledatestart = aData[startdatecol];
			var tabledateend = aData[enddatecol];		
			
			/***/
			/*if(tabledateend){
				tabledateend = tabledateend;     
			}else{
				tabledateend = filterend;		
			}
			/***/

			var  tabledateStart = moment(tabledatestart).format('YYYY-MM-DD');
			var  tabledateEnd = moment(tabledateend).format('YYYY-MM-DD');
    		var fltStr = false;
			if ( filterstart === "" && filterend === "" ){
				fltStr = true;
			} else if (filterstart != "" && moment(filterstart).isSame(tabledateStart)) {
				fltStr = true;
			} else if (filterend != "" && moment(filterend).isSame(tabledateEnd)) {
				fltStr = true;
			} else if (moment(filterstart).isBefore(tabledateStart) && moment(filterend).isAfter(tabledateEnd)) {
				fltStr = true;
			} else if(filterstart == undefined || filterend == undefined ) {
				fltStr = true;
			}
			return fltStr;
			
			/*
			if ( filterstart === "" && filterend === "" )
			{    
				return true;   
			}
			else if ((moment(filterstart).isSame(tabledatestart) || moment(filterstart).isBefore(tabledatestart)) && filterend === "")
			{	
				return true;
			}
			else if ((moment(filterstart).isSame(tabledatestart) || moment(filterstart).isAfter(tabledatestart)) && filterstart === "")
			{	
				return true;
			}
			else if ((moment(filterend).isSame(tabledateend) || moment(filterend).isBefore(tabledateend)) && filterstart === "")
			{
				return true;
			}
			else if ((moment(filterstart).isSame(tabledatestart) || moment(filterstart).isBefore(tabledatestart)) && (moment(filterend).isSame(tabledateend) || moment(filterend).isAfter(tabledateend)))
			{	
				return true;
			}
			else if (filterstart == undefined || filterend == undefined)
			{   
				return true;
			}
			else
			{	
				return false;
			} */
			
		}
		); 
		/***/
		
		/** Error Model open **/
		var Main = {
				displayFilterErrorModal: function(idModal, msg, appendContainer) {   
					var htmlDelete = '' +
						'<div class="modal-header">' +
							'<button type="button" class="close" data-dismiss="modal">&times;</button>' +
							'<h4 class="modal-title">Alert Message</h4>' +
						'</div>' +
						'<div class="modal-body">' +
							'<p>' + msg + '</p>' +
						'</div>' +
						'<div class="modal-footer">' +
							'<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>' +
						'</div>';

					$(appendContainer).html(htmlDelete)
					$(idModal).modal('toggle');
				}
			}
		/** Error Model close **/
		 
	});
});