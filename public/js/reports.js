/*
*
*  js code for report pages. created at 06/20/2017
*
*/

'use strict';
$(document).ready(function(){
	$(window).load(function(){
	
	//SN : 05/11/2017 added below code to show email content
	$(document).on( "click", "#button-email", function(e) {
        e.preventDefault();
		var delUrl = $("#email-content").html();
			delUrl = delUrl.trim();		
        var massage = 'Email Format not found <strong> </strong>?';
        Main.displayEmailModal('#delete-track', delUrl,   massage, '#modalConfirmDeleteTrack');
    });
	//Mith : 06/30/2017 added below code to show status report.
  	$(document).on( "click", "#sendstatusmail", function(e) {
		e.stopImmediatePropagation();
        var options = $('#tousers > option:selected');
        if(options.length == 0){
            var delUrl = 'Please enter email address in To (recipient)';
            var massage = 'Status report not found <strong> </strong>?';
            Main.displayStatusModal('#delete-track', delUrl,   massage, '#modalConfirmDeleteTrack');
            return false;
        }  
      });
	//SN 06/15/2017: added below code for edit column button click functionality
	$(document).on( "click", ".update-column-button", function(e) {
        e.preventDefault();
        var delUrl = '';
		var reportname = $(this).data('name');		
        var massage = 'Select checkbox to update table column'; 
		var value = $('#usersTable').DataTable().columns().context[0].aoColumns.length;
		var title = [], checked = [];
		var i;
		var SelectedFillter = [];
		for(i=0; i<value; i++){ 
			var columsTitle = $('#usersTable').DataTable().columns().context[0].aoColumns[i].sTitle;
			var columsVisibility =  $('#usersTable').DataTable().columns().context[0].aoColumns[i].bVisible;	
			title.push(columsTitle);
			SelectedFillter.push({title:columsTitle,visibility:columsVisibility});	
		}
	
		var columnvalue = $(".column-value").text();  
		for(i=0; i<value; i++){ 
			var name =  $('#usersTable').DataTable().columns().context[0].aoColumns[i].bVisible;  
			checked.push(name);  
		}   
		Main.displayReorderModal('#delete-track', delUrl, title, massage, SelectedFillter, reportname, columnvalue, '#modalConfirmDeleteTrack');
    });
	
	/*SN 06/15/2017: added below code to update column in datatable */
	 $(document).on("click", ".update-button-field", function (event) {
		event.preventDefault();
		$(".close-column").trigger('click');
		var values = []; 
		var checkedFields = [];	
		var order,i;
		$("input[name=selectreorder]:not(:checked)").each(function() {
			order = $(this).attr('order');
			values.push(order);
		});
		$("input[name=selectreorder]:checked").each(function() {
			order = $(this).attr('order');
			checkedFields.push(order);
		});			
		var reportname = $("input[name=selectreorder]").attr('reportname');
		
		for(i=0; i<values.length; i++){
			var column = $('#usersTable').DataTable().column(values[i]);
			column.visible(false);
		}
		for(i=0; i<checkedFields.length; i++){
			var column = $('#usersTable').DataTable().column(checkedFields[i]);
			column.visible(true);
		}
		
		if(values.length == 0){
			values == "";
		}
		
		
		var token = $("#conteiner").data('token').trim();		
		$.post('/reports/setupdatecolumn',
			{ values: values, reportname: reportname, _token: token },
			function (response){
				$('#usersTable').DataTable().columns.adjust().draw();
		});
		
	 });
	 
	 $('#button-performance').click(function() { 							
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
		}
		
		
	/*****/
	$("#extratask").keyup(function(e){
		if((e.keyCode || e.which) == 13) { //Enter keycode
		  this.value = this.value+"# ";
		}
	  });
	
	/*****/
	
	/****/
		$(".d5").datepicker({
            autoclose: true,
            todayBtn: "linked",
            todayHighlight: true
        }
		).on('changeDate', function (e) {     
				var dateCalendar = e.format();
				dateCalendar = moment(dateCalendar, 'MM/DD/YYYY').format('DD-MM-YYYY');
				window.location.href = "/reports/daily/" + dateCalendar;
		});

		$('#sandbox-container .input-group.date').datepicker({
			autoclose: true
		});

		$('.d5').datepicker('update', new Date(moment($('#conteiner').data('date'), 'DD-MM-YYYY')));
		
		$(document).on("click", ".calendarNextReport", function(){  
			var dateCalendar = moment($('#conteiner').data('date'), 'DD-MM-YYYY').add('days', 1).format('DD-MM-YYYY');
			window.location.href = "/reports/daily/" + dateCalendar;
		});

		$(document).on("click", ".calendarPrevDayReport", function(){  
			var dateCalendar = moment($('#conteiner').data('date'), 'DD-MM-YYYY').add('days', -1).format('DD-MM-YYYY');
			window.location.href = "/reports/daily/" + dateCalendar;
		});
	  /****/
});

	//SN 06/19/2017: added below code to check checkboxes according to column values is exist at backend.
	$(window).on('load', function(){
		var but = $("#update-column-button");     
		if(but){
			var name = but.data('name');  
			if(name == 'project-report'){
				settablecolumn();
			}
			if(name == 'daily-report'){
				settablecolumn();
			}
			if(name == 'people-report'){ 	
				settablecolumn();
			}
		}
	});

//SN 06/19/2017: added below function to checked checkboxes according to backend values bydefault
function settablecolumn(){
	var value = $(".column-value").text().trim();	   
	var i,collen;
	collen = $('#usersTable').DataTable().columns().context[0].aoColumns.length;
	if(value){							
		value = value.split(',');
		for(i=0; i<value.length; i++){	
			var col = $('#usersTable').DataTable().column(value[i]);  
			col.visible(false);	   
		}
	}else{
		for(i=0; i<collen; i++){	
			var col = $('#usersTable').DataTable().columns(i);  
			col.visible(true);	   
		}
	}
}
	 
var Main = {
	
	displayEmailModal: function(idModal, delUrl, massage, appendContainer) {			
        var htmlDelete = '' +
            '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                '<h4 class="modal-title">Email Content</h4>' +
            '</div>' +
            '<div class="modal-body">' +
            '<div class="mailbody my_input container-size margin-top-twenty scroll">'+delUrl+'</div>'+
			'</div>' +
            '<div class="modal-footer"></div>';
               
        $(appendContainer).html(htmlDelete);
        $(idModal).modal('toggle');		
    },
    displayStatusModal: function(idModal, delUrl, massage, appendContainer) {


          var htmlDelete = '' +
              '<div class="modal-header">' +
                  '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                  '<h4 class="modal-title">Alert Message</h4>' +
              '</div>' +
              '<div class="modal-body">' +
  			//'<textarea id="emaildata-box" name="emaildata-box" class="emaildata-box" placeholder="Email Content" contenteditable minlength="5"  style="width:100%;min-height:400px;margin-top:20px;">'+ delUrl +'</textarea>' +
              '<p>'+delUrl+'</p>'+
  			'</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-success close-popup" data-dismiss="modal">Close</button>' +
        '</div>';

        $(appendContainer).html(htmlDelete);
        $(idModal).modal('toggle');
      },
	
	displayReorderModal: function(idModal, delUrl, title, massage, checked, reportname, columnvalue, appendContainer) {  
	   var disable = '',i=0,check = '',order;
	   var output = $.map(title, function(value) {
			if((value == "Person Name") || (value == "Person")){  
			   disable = "disabled";
			}else{
			   disable = "";
			}
			
			return('<div draggable="true" class="reorderfieldrow" >'+
			'<div class="reorder-data">'+
			'<input type="checkbox" name="selectreorder" id="selectreorder" class="selectreorder"'+ disable +'  value="'+value+'" order="'+ i++ +'" reportname="'+ reportname +'">'+
			'<span class="padding-left-ten relative saveemails-top">' + value + '</span>'+
			'</div>'+
			'</div>');
		});	
	   
	   var htmlDelete = '' +
            '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                '<h4 class="modal-title">Update Column</h4>' +
            '</div>' +
            '<div class="modal-body"><p style="margin-left: 0em;">'+massage+'</p>'+output.join("")+'</div>' +
            '<div class="modal-footer">' +
                '<a href="" type="button" class="btn btn-success update-button-field" >Update</a>' +
                '<button type="button" class="btn btn-default close-column" data-dismiss="modal">Close</button>' +
            '</div>';
		
        $(appendContainer).html(htmlDelete)
        $(idModal).modal('toggle');
		$(".reorderfieldrow").css("height","35px");
		$(".modal-content").css({"width":"60%","margin":"30px auto"});
		var column = columnvalue.split(',');  
		
		$("input[name=selectreorder]").each(function() {   
			for(i=0;i<checked.length;i++){
				if($(this).val() == checked[i].title){
					$(this).prop("checked",checked[i].visibility);
				}
			}
			
		}); 
    }
   };
});