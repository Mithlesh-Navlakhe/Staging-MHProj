/**
 * Created by naaanazar on 08.12.2016.
 */
'use strict';
$(document).ready(function(){
    $(window).load(function(){
        if ($('#additionalCost').val()) {
            if ($('#additionalCost').val().length > 0 && $("#timeDuration").val().length > 0) {
                var cost = $('#additionalCost').val() / 60 * moment.duration($("#timeDuration").val()).asMinutes(); 
                $('#insertCost').html(Math.round(cost * 100) / 100);
            }
        }

        //report calendar
        var dateStart = $('#conteiner').data('start');
        var dateEnd = $('#conteiner').data('end');
        var singleStart = $('#conteiner').data('singlestart');
		if(dateStart && dateEnd) {
            if (dateStart.length > 0 && dateEnd.length > 0) {
                $('.dr-date-start').text(moment(dateStart, 'YYYY-MM-DD').format('MMMM D, YYYY'));
                $('.dr-date-end').text(moment(dateEnd, 'YYYY-MM-DD').format('MMMM D, YYYY'));
            }
        }
		if(singleStart) {
            if (singleStart.length > 0) {
                $('.dr-date').text(moment(singleStart, 'YYYY-MM-DD').format('MMMM D, YYYY'));
            }
        }

		var todaysdate='';
        var different='';
        var i = 0;
        setInterval(function () {
            if (t) {
                var idActiveLog = $('#conteiner').data('log-active');
                var timmer_todaysdate = new Date();
                if(todaysdate != ''){
                  console.log((moment(timmer_todaysdate, "YYYY-MM-DD hh:mm:ss") - moment(todaysdate, "YYYY-MM-DD hh:mm:ss")) / 1000);
                  var different = (moment(timmer_todaysdate, "YYYY-MM-DD hh:mm:ss") - moment(todaysdate, "YYYY-MM-DD hh:mm:ss")) / 1000;
                }
                if(!different || different >= 120){
                  $.get('/get/timestart/' + idActiveLog, function (date) {
                      var duration = SecondsTohhmmss((moment(date.data.now, "YYYY-MM-DD hh:mm:ss") - moment(date.data.start, "YYYY-MM-DD hh:mm:ss")) / 1000);
                      seconds = duration.slice(6,7) == 0 ? duration.slice(7) : duration.slice(6);
                      minutes = duration.slice(3,4) == 0 ? duration.slice(4,5) : duration.slice(3,5);
                      hours = duration.slice(1,2) == 0 ? duration.slice(1,2) : duration.slice(0,2);
                  });
                  todaysdate = new Date();
                }
            }
            i++;
        },1000)

        $(".removeSelect").html('');
        if (($('#bodyData').data('msg').length > 0) && ($('#bodyData').data('theme').length > 0)) {
            $.jGrowl($('#bodyData').data('msg'), {
                theme: $('#bodyData').data('theme'),
                life: 4000,
                position:'center',
            });
        };

        if ($('#conteiner').data('msg')) {
            if (($('#conteiner').data('msg').length > 0) && ($('#conteiner').data('theme').length > 0)) {
                $.jGrowl($('#conteiner').data('msg'), {
                    theme: $('#conteiner').data('theme'),
                    life: 4000,
                    position: 'center',
                });
            }
        }
    });


    //calendar

	var dd = new Calendar({
        element: $('.track-datepicker'),
        current_date: moment(),
        format: {input: 'MMMM D, YYYY'},
        required: false,
        callback: function() {
            var start = moment($('.dr-date').text()).format('YYYY-MM-DD');
            var userId = $("#SelectAllUserTrackReport option:selected").val();
            if (userId.length > 0) {
                window.location.href = "/reports/track/" + start + '/' + userId;
            }
        }
    });
	
    var dd = new Calendar({
        element: $('.one'),
        earliest_date: 'January 1, 2000',
        latest_date: moment(),
        start_date: moment().subtract(29, 'days'),
        end_date: moment(),

        callback: function() {
            var start = moment(this.start_date).format('YYYY-MM-DD'),
                end = moment(this.end_date).format('YYYY-MM-DD');

            var userId = $("#SelectAllUserReport option:selected").val();
			if (userId.length > 0) {
				window.location.href = "/reports/people/" + start + '/' + end + '/' + userId;
            }
		}
    });

    var ds = new Calendar({
        element: $('.one2'),
        earliest_date: 'January 1, 2000',
        latest_date: moment(),
        start_date: moment().subtract(29, 'days'),
        end_date: moment(),
		callback: function() {
            var start = moment(this.start_date).format('YYYY-MM-DD'),
                end = moment(this.end_date).format('YYYY-MM-DD');
			var userId = $("#SelectAllProjectReport option:selected").val();
			if (userId.length > 0) {
				window.location.href = "/reports/project/" + start + '/' + end + '/' + userId;
            }
        }
    });

	var presetArray = [{
        label: 'This week',
        start: moment(moment()).weekday(1),
        end: moment(moment()).weekday(6)
      },{
      label: 'Last week',
      start: moment(moment()).weekday(-6),
      end: moment(moment()).weekday(-1)
    },
    {
      label: 'This month',
      start: moment(self.latest_date).startOf('month'),
      end: self.latest_date
    },
    {
      label: 'Last month',
      start: moment(moment()).subtract(1, 'month').startOf('month'),
      end: moment(moment()).subtract(1, 'month').endOf('month')
    },{
      label: 'Last 3 months',
      start: moment(moment()).subtract(3, 'month').startOf('month'),
      end: moment(moment()).subtract(1, 'month').endOf('month')
    }];	
	//Mith 05/11/2017: Add for mail project report date picker.
    var ds = new Calendar({
        element: $('.mail-project'),
        earliest_date: 'January 1, 2000',
        latest_date: moment(),
        start_date: moment().subtract(29, 'days'),
        end_date: moment(),
		presets: presetArray,
        callback: function() {
            var start = moment(this.start_date).format('YYYY-MM-DD'),
                end = moment(this.end_date).format('YYYY-MM-DD');

            var userId = $("#SelectedProjectReport option:selected").val();

            if (userId.length > 0) {
               window.location.href = "/reports/emailproject/" + start + '/' + end + '/' + userId;
            }
        }
    });

	//Mith 06/07/2017: Add for performance report date picker.
    var ds = new Calendar({
        element: $('.repo-performance'),
        earliest_date: 'January 1, 2000',
        latest_date: moment(),
        start_date: moment().subtract(29, 'days'),
        end_date: moment(),
		presets: presetArray,					 
        callback: function() {
            var start = moment(this.start_date).format('YYYY-MM-DD'),
                end = moment(this.end_date).format('YYYY-MM-DD');
            var leadId = $("#SelectedPerformance option:selected").val();
            if (leadId.length > 0) {
               window.location.href = "/reports/performance/" + start + '/' + end + '/' + leadId;
            }
        }
    }); 

	var presetTaskList = [
      {
        label: 'This month',
        start: moment(self.latest_date).startOf('month'),
        end: self.latest_date
      },
      {
        label: 'Last month',
        start: moment(moment()).subtract(1, 'month').startOf('month'),
        end: moment(moment()).subtract(1, 'month').endOf('month')
      },{
        label: 'Last 3 months',
        start: moment(moment()).subtract(3, 'month').startOf('month'),
        end: moment(moment()).subtract(1, 'month').endOf('month')
      },
      {
      label: 'Last 6 months',
      start: moment(self.latest_date).subtract(6, 'month').startOf('month'),
      end: moment(self.latest_date).subtract(1, 'month').endOf('month')
    },{
        label: 'This year',
        start: moment().startOf('year'),
      end: self.latest_date
      },{
      label: 'Last year',
      start: moment(self.latest_date).subtract(12, 'month').startOf('month'),
      end: moment(self.latest_date).subtract(1, 'month').endOf('month')
    },
      {
      label: 'All time',
      start: moment('January 1, 1900'),
      end: self.latest_date
    }];
	
    //Mith 08/24/2017: Add for task archive date picker.
    var ds = new Calendar({
        element: $('.taskarchive'),
        earliest_date: 'January 1, 2000',
        latest_date: moment(),
        start_date: moment().subtract(29, 'days'),
        end_date: moment(),
  		presets: presetTaskList,
        callback: function() {
            var start = moment(this.start_date).format('YYYY-MM-DD'),
            end = moment(this.end_date).format('YYYY-MM-DD');
            window.location.href = "/task/archive/" + start + '/' + end ;
        }
    });
	
	//Mith 08/24/2017: Add for task archive date picker.
	var ds = new Calendar({
		element: $('.tasklist'),
		earliest_date: 'January 1, 2000',
		latest_date: moment(),
		start_date: moment().subtract(29, 'days'),
		end_date: moment(),
		presets: presetTaskList,
		callback: function() {
			var start = moment(this.start_date).format('YYYY-MM-DD'),
			end = moment(this.end_date).format('YYYY-MM-DD');
			window.location.href = "/task/all/" + start + '/' + end ;
		}
	});
	
    //All reports drop down selectors

    $(document).on("change", "#SelectAllUserReport", function () {
        var userId = $("#SelectAllUserReport option:selected").val();
        var start = moment($('.dr-date-start').text(), 'MMMM D, YYYY').format('YYYY-MM-DD');
        var end = moment($('.dr-date-end').text(), 'MMMM D, YYYY').format('YYYY-MM-DD');
        window.location.href = "/reports/people/" + start + '/' + end + '/' + userId;
    });

    $(document).on("change", "#SelectAllProjectReport", function () {
        var userId = $("#SelectAllProjectReport option:selected").val();
        var start = moment($('.dr-date-start').text(), 'MMMM D, YYYY').format('YYYY-MM-DD');
        var end = moment($('.dr-date-end').text(), 'MMMM D, YYYY').format('YYYY-MM-DD');
        window.location.href = "/reports/project/" + start + '/' + end + '/' + userId;
    });

    //SN 05/12/2017: updated below code for multiple option.
	$(document).on("change", "#SelectedProjectReport", function () {
        //var userId = $("#SelectAllProjectReport option:selected").val(); 
		var userId = $("#SelectedProjectReport").val();   
		if(userId){
			var start = moment($('.dr-date-start').text(), 'MMMM D, YYYY').format('YYYY-MM-DD');
			var end = moment($('.dr-date-end').text(), 'MMMM D, YYYY').format('YYYY-MM-DD');
			window.location.href = "/reports/emailproject/" + start + '/' + end + '/' + userId;
		}
    });

	//Mith 05/12/2017: updated below code for performance option.
	$(document).on("change", "#SelectedPerformance", function () {
		var leadId = $("#SelectedPerformance").val();
		if(leadId){
			$('#button-performance').attr('disabled',true);
			$('#button-performance').css({pointerEvents: "none"});
    		var start = moment($('.dr-date-start').text(), 'MMMM D, YYYY').format('YYYY-MM-DD');
    		var end = moment($('.dr-date-end').text(), 'MMMM D, YYYY').format('YYYY-MM-DD');
    		window.location.href = "/reports/performance/" + start + '/' + end + '/' + leadId;
        }
    });
	
	//Mith 11/21/2017: updated below code for track report option.
	$(document).on("change", "#SelectAllUserTrackReport", function () {
		var userId = $("#SelectAllUserTrackReport option:selected").val();
		var start = moment($('.dr-date').text(), 'MMMM D, YYYY').format('YYYY-MM-DD');
		window.location.href = "/reports/track/" + start + '/' + userId;
	});
	
	//SN 09/08/2017: added below code for leave section
	var newdate = new Date();   
	newdate = moment(newdate, 'MM/DD/YYYY').format('YYYY-MM-DD');
	//$("#date-leave").val(newdate);
	
	//SN 09/14/2017: update current week's start date and end date.
	var curr = new Date();				
	var mondayDate = new Date();
	var friday = 1 - curr.getDay();
	mondayDate.setDate(mondayDate.getDate()+friday);
	mondayDate = moment(mondayDate, 'MM/DD/YYYY').format('YYYY-MM-DD');
	$("#firstday").val(mondayDate);
	
	$('#leave-button').on('click', function(){
		$("#leave-block").removeClass('hidden');
		$("#leave-button").attr('disabled','disabled');
		//$("#leave-button").prop('disabled',true);
	});
	
	$('#close-block').on('click', function(){
		$("#leave-block").addClass('hidden');
		$("#leave-button").prop('disabled', false);
	});
	
	$('.leave-reset').on('click', function(){
		$(".error-time").text("");
		window.location.href = "/tracking/";
	});
	/*$('#leave-submit').on('click', function(){
		var val = $("#date-leave").val(); 
		if(val){
			return true;
		}else{
			$(".error-date").text("* Date Required");
			$("#leave-submit").prop('disabled', true);
			return false;
		}
	});*/
	
	$('.users-leave').on('change', function(){
		var val = $(".users-leave option:selected").text(); 
		$(".username").val(val);
		val = val + " is on Leave";
		$(".leave_user").val(val);
	});
	$('.leaveType').on('change', function(){
		var val = $(".leaveType option:selected").val(); 
		if(val == 'spec-hrs'){
			$(".spec-time").removeClass('hidden');
			$(".from-time").removeClass('hidden');
			$(".end-leave").removeClass('hidden');
			$(".first-time").removeClass('hidden').prop('required', true);
			$(".last-time").removeClass('hidden').prop('required', true);
			$(".half-time").addClass('hidden');
			$(".error-time").text("");
		}else if(val == 'half-day'){
			$(".half-time").removeClass('hidden');
			$("#leave-submit").prop('disabled', false);
			$(".error-time").text("");
			$(".spec-time").addClass('hidden');
			$(".from-time").prop('required', false).val('');
			$(".end-leave").prop('required', false).val('');
			$(".first-time").addClass('hidden');
			$(".last-time").addClass('hidden');
		}else{
			$(".spec-time").addClass('hidden');
			$(".from-time").prop('required', false).val('');
			$(".end-leave").prop('required', false).val('');
			$(".first-time").prop('required', false).val('');
			$(".last-time").prop('required', false).val('');
			$("#leave-submit").prop('disabled', false);
			$(".half-time").addClass('hidden');
			$(".error-time").text("");
		}		
	});
	
	$("#from-time, #end-leave, #last-time, #first-time").on('click', function(){
		if($(".error-time").text().length > 0){
			$(".error-time").text('');
		}
	});
	
	$("#end-leave, #last-time").on('focusout', function(){
		var frm = $(".from-time").val();
		var end = $(".end-time").val(); 
		var frmslice = frm.split('.');
		var endslice = end.split('.');
		$("#from-time").trigger('click');
		
		if((end >= '09.30' || end >= 9) && (end >= '12.30' && end <= 12)){
			frm = frm;
			/*if(end == '09.30' || end == 9){
				$(".error-time").text("* End Time Should not be 09.30 AM");  
				$("#leave-submit").prop('disabled', true);
				return true;
			}*/
		}else if((end >= '01.00' && end <= '09.00') || (end >= 1 && end <= 9)){
			end = end + 12;					
		}else{
			end = end;	
		}
		if((frm >= '09.30' || frm >= 9) && (frm >= '12.30' && end <= 12)){
			frm = frm;
		}else if((frm >= '01.00' || frm >= 1) && (frm >= '08.30' && end <= 8)){
			frm = frm + 12;
		}else{
			frm = frm;
		}
				
		if(frm > end){
			$(".error-time").text("* End Time Should be Greater then From Time");  
			$("#leave-submit").prop('disabled', true);
		}else if((frm == end) && (frm.length && end.length)){	
			if(frmslice[1] == endslice[1]){
				$(".error-time").text("* From Time and End Time Should not be Same");
				$("#leave-submit").prop('disabled', true);
			}else{
				$("#leave-submit").prop('disabled', false);	
			}
		}else{
			$("#leave-submit").prop('disabled', false);			
		}
	});
	
	var todaydate = new Date();
	$(".leave-day").datepicker({
        autoclose: true,
        todayBtn: "linked",
        todayHighlight: true
		//startDate: todaydate   
      }
    ).on('changeDate', function (e) {
		var dateCalendar = e.format();
		dateCalendar = moment(dateCalendar, 'MM/DD/YYYY').format('YYYY-MM-DD');
		$("#date-leave").val(dateCalendar);
		
		$(".error-date").text("");
		$("#leave-submit").prop('disabled', false);
    });
	
	
	$('#first-time').timepicker({
		'minTime': '09.30 AM',
		'maxTime': '08.30 PM',
		'showDuration': false
	});
	$('#last-time').timepicker({
		'minTime': '09.30 AM',
		'maxTime': '08.30 PM',
		'showDuration': false
	});
	
	$("#first-time").on('change', function(){
		var ftime = $(this).val(); 
		var n = ftime.indexOf("a");
		var ff;
		if(n != -1){
			ff = ftime.split('a');  
		}else{
			ff = ftime.split('p');  
		}
		var ft = ff[0].split(':');
		if(ft[0] < 12){
			var n1 = ftime.indexOf("a");
			if(n1 != -1){
				var firstpart = ff[0].split(':');		
				if(firstpart[0] == '9' || firstpart[0] == 9){		
					firstpart[0] = '0' + firstpart[0];
				}else{									
					firstpart[0] = firstpart[0];
				}
				ff[0] = firstpart[0].toString() +':'+ firstpart[1];
				ff[0] = ff[0].toString().replace(':', '.');
				$("#from-time").val(ff[0]);
			}else{
				var firstpart = ff[0].split(':');		
				firstpart[0] = parseInt(firstpart[0]) + 12; 
				ff[0] = firstpart[0].toString() +':'+ firstpart[1];
				ff[0] = ff[0].toString().replace(':', '.');
				$("#from-time").val(ff[0]);
			}
		}else{
			var n1 = ftime.indexOf("a");
			ff[0] = ff[0].toString().replace(':', '.');
			if(n1 != -1){
				$("#from-time").val(ff[0]);
			}else{
				$("#from-time").val(ff[0]);
			}
		}
		
		/*var frm = $(".from-time").val();
		if(frm == '09.30' || frm == 9){
			$('#last-time').timepicker({ 'maxTime': '06.30 PM' });
		}else if(frm == '10.00' || frm == 10){
			$('#last-time').timepicker({ 'maxTime': '07.00 PM' });
		}else if(frm == '10.30' || frm == 10){
			$('#last-time').timepicker({ 'maxTime': '07.30 PM' });
		}else if(frm == '11.00' || frm == 11){
			$('#last-time').timepicker({ 'maxTime': '08.00 PM' });
		}else if(frm == '11.30' || frm == 11){
			$('#last-time').timepicker({ 'maxTime': '08.30 PM' });
		}*/
	});
	
	$("#last-time").on('change', function(){
		var ftime = $(this).val(); 
		var n = ftime.indexOf("a");
		var ff;
		if(n != -1){
			ff = ftime.split('a');  
		}else{
			ff = ftime.split('p');  
		}
		var ft = ff[0].split(':');
		if(ft[0] < 12){
			var n1 = ftime.indexOf("a");
			if(n1 != -1){
				var firstpart = ff[0].split(':');
				if(firstpart[0] == '9' || firstpart[0] == 9){
					firstpart[0] = '0' + firstpart[0];
				}else{								
					firstpart[0] = firstpart[0];
				}
				ff[0] = firstpart[0].toString() +':'+ firstpart[1];
				ff[0] = ff[0].toString().replace(':', '.');
				$("#end-leave").val(ff[0]);
			}else{
				var firstpart = ff[0].split(':');		
				firstpart[0] = parseInt(firstpart[0]) + 12; 
				ff[0] = firstpart[0].toString() +':'+ firstpart[1];
				ff[0] = ff[0].toString().replace(':', '.');
				$("#end-leave").val(ff[0]);
			}
		}else{
			var n1 = ftime.indexOf("a");
			ff[0] = ff[0].toString().replace(':', '.');
			if(n1 != -1){
				$("#end-leave").val(ff[0]);
			}else{
				$("#end-leave").val(ff[0]);
			}
		}
	});
	
	//

	//Mith 08/01/2017: Apply delay in keyup.
	$.fn.delayKeyup = function(callback, ms){
		 var timer = 0;
		 var el = $(this);
		 $(this).keyup(function(){
			 clearTimeout (timer);
			 timer = setTimeout(function(){
				 callback(el)
					 }, ms);
		 });
		 return $(this);
	};

	//Mith 08/01/2017: Call delay in keyup for search bar.
	$('#trc-search').delayKeyup(function(el){
	  var enteredValue = $('#trc-search').val();
	  if(enteredValue.length != 0 && enteredValue.length < 3) return;
	  var temp = $('#trackLogTableId > tbody  > tr').each(function() {
					var rowTr = $(this);
					//console.log("mith",rowTr);
					$(this).find('td').each (function() {
					  var srchFlag = $(this).attr('data-search-flag');
					  if(srchFlag == 'true'){
						var listValue = $(this).attr('data-search-value');
						var regex = new RegExp(enteredValue.replace(/(\W)/g, "\\$1"), "gi");
						if(listValue.toLowerCase().match(regex)){
						  rowTr.show();
						} else{
						  //To hide/show plus/minus sign before task
							if(typeof rowTr.find('.showTimelog') != 'undefined' && typeof rowTr.find('.hideTimelog') != 'undefined'){
								if(rowTr.find('.showTimelog').hasClass('hidden') ){
									rowTr.find('.showTimelog').removeClass("hidden");
									rowTr.find('.hideTimelog').addClass("hidden");
								}
							}
						  rowTr.hide();
						}
					  }
					});
					//To hide open sub task detail.
					if($(this).attr('data-subtask-flag') == 'true' && rowTr["0"].className == ''){
						rowTr.addClass("hidden");
					}
                    //console.log(rowTr["0"].id);
					})
	},1000);
	
	//Mith 08/01/2017: Search close icon.
	$('.search-cross').on('click', function(){
		$('#trc-search').val("");
		$('#trc-search').keyup();
	});
	
	/**
     *  Mith 08/16/2017: track edit form submit method.
     */
    $('#addTrackForm').on('submit',function(event){

        var changeVal = $('#totaltime').val();
        var actulaVal = $('#actualtotaltime').val();
        changeVal = moment.duration(changeVal).asSeconds();
        actulaVal = moment.duration(actulaVal).asSeconds();
		var typeid = $('#task_type_id').val();
		var id = $('#task-type').val();

		if(parseInt(typeid) !== parseInt(id)){
			if(changeVal>actulaVal){
			  event.preventDefault();

				var htmlDelete = '' +
				  '<div class="modal-header">' +
				  '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
				  '<h4 class="modal-title">Alert Message</h4>' +
				  '</div>' +
				  '<div class="modal-body">' +
				  '<p> Change time is greater than actual tracked time. </p>' +
				  '</div>' +
				  '<div class="modal-footer">' +
				  '<button type="button" class="btn btn-default close-approve" data-dismiss="modal">Close</button>' +
				  '</div>';
				  $('#modalConfirmDeleteTrack').html(htmlDelete)
				  $('#delete-track').modal('toggle');
				  return false;
			}else {
				return true;
			}
		}else {
			return true;
		}
    });
	
    //  $('#datetimepicker').datetimepicker('setInitialDate', '2016-12-31');

  /*  $('.day').on('click', function(){
        var date = setTimeout(" console.log($('#dtp_input2').val());"  , 500);

    //    window.location ='/trecking/' + date;
        window.onload=function() {
            console.log($('#dtp_input2').val());
        }


    });


    $('.form_date').datetimepicker({

        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,

    });

    $(document).on('dp.change', '.form_date', function() {
        alert('changed');
    });*/

    $(".d4").datepicker({
		autoclose: true,
		todayBtn: "linked",
		todayHighlight: true
        }
    ).on('changeDate', function (e) {
            var dateCalendar = e.format();
            dateCalendar = moment(dateCalendar, 'MM/DD/YYYY').format('DD-MM-YYYY');
            window.location.href = "/tracking/" + dateCalendar;
    });

    $('#sandbox-container .input-group.date').datepicker({
        autoclose: true
    });

    $('.d4').datepicker('update', new Date(moment($('#conteiner').data('date'), 'DD-MM-YYYY')));

    $(document).on("click", ".calendarNextDay", function(){
        var dateCalendar = moment($('#conteiner').data('date'), 'DD-MM-YYYY').add('days', 1).format('DD-MM-YYYY');
        window.location.href = "/tracking/" + dateCalendar;
    });

    $(document).on("click", ".calendarPrevDay", function(){
        var dateCalendar = moment($('#conteiner').data('date'), 'DD-MM-YYYY').add('days', -1).format('DD-MM-YYYY');
        window.location.href = "/tracking/" + dateCalendar;
    });


    //report
    /*$(".d5").datepicker({
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
    }); */
	
	/* SN: 04/06/17: Added below code for cancel button when creating task for tracking */
	$('#cancel-track').click(function(){
		$('#addTrackForm')[0].reset();
		$('#trakingTaskDescription').html('');
		$("#billableTime").removeAttr('checked');
	});

    $(document).on("change", "#trackTaskId", function () {

        var taskId = $("#trackTaskId option:selected").val();
        if (taskId) {

            var urlSend = '/track/getdesckription/' + taskId;
            $.get(urlSend, function (response) {
                $("#trakingTaskDescription").html(response.data[0].task_description);
				if(response.data[0].billable == 1){
					$("#billableTime").prop('checked',true);
        		}else{
        		    $("#billableTime").prop('checked',false);
        		}
            });
        } else {
            $("#trakingTaskDescription").html('');
        }
    });


    $(document).on('click', '.webClick', function(e){
        e.stopImmediatePropagation();
        window.open($(e.target).html(),'_blank');
    })

    //timetrack
    $(document).on('change', '#billableTime', function() {
        if(this.checked) {
            
           if($('#additionalCost').val().length >0 &&  $("#timeDuration").val().length > 0){
             var cost =  $('#additionalCost').val() / 60 * moment.duration($("#timeDuration").val()).asMinutes();
               
               $('#insertCost').html(Math.round(cost * 100) / 100);
           }
        } else {
            $('#insertCost').html('');

        }
    });

    $(document).on('mousemove', '#additionalCost', function(){

        var attr = $('#billableTime').prop('checked');
        if (typeof attr !== typeof undefined && attr !== false) { 
            if($('#additionalCost').val().length >0 &&  $("#timeDuration").val().length > 0){
                var cost =  $('#additionalCost').val() / 60 * moment.duration($("#timeDuration").val()).asMinutes();  
                $('#insertCost').html(Math.round(cost * 100) / 100);
            }
        } else {
            $('#insertCost').html('');
        }
    });


    $(document).on('mousemove', '#timeDuration', function(){

        var attr = $('#billableTime').prop('checked');
       
        if (typeof attr !== typeof undefined && attr !== false) {
            
            if(($('#additionalCost').val().length > 0) &&  ($("#timeDuration").val().length > 0)){
                var cost =  $('#additionalCost').val() / 60 * moment.duration($("#timeDuration").val()).asMinutes();
             
                $('#insertCost').html(Math.round(cost * 100) / 100);
            }
        } else {
            $('#insertCost').html('');

        }
    });

    // button now
    var dStart,
        dFinish,
        duration ;

    $(document).on('click', '#formTrackStartNow', function(){
        $.get('/tracking-getTime', function (response) {
            $('#formTrackStart').val(moment(response.data, "YYYY-MM-DD hh:mm:ss").format('HH:mm'));
            trackStart();
        });
    });

    $(document).on('click', '#formTrackFinishNow', function(){
        $.get('/tracking-getTime', function (response) {
            $('#formTrackFinish').val(moment(response.data, "YYYY-MM-DD hh:mm:ss").format('HH:mm'));
            trackFinish();
        });
    });

    //button + -
    $(document).on('click', '#formTrackStartInc', function(){
        addTime('#formTrackStart', 10);
        trackStart();
    });

    $(document).on('click', '#formTrackFinishInc', function(){
        addTime('#formTrackFinish' , 10);
        trackFinish();
    });

    $(document).on('click', '#formTrackStartDec', function(){
        addTime('#formTrackStart', -10);
        trackStart();
    });

    $(document).on('click', '#formTrackFinishDec', function(){
        addTime('#formTrackFinish' , -10);
        trackFinish();
    });
    $(document).on('click', '#resetTime', function(){
        event.preventDefault();
        $('#formTrackStart').val('');
        $('#formTrackDuration').val('');
        $('#formTrackFinishSend').val('');
        $('#formTrackStartSend').val('');
        $('#formTrackFinish').val('');
        $('#formTrackStart').val('');
        $('#timeDuration').val('');
        $('#timeDuration').removeAttr('readonly');

        dStart ='';
        dFinish = '';

    });



    $(document).on('change', '#nextDay', function() {
        if(this.checked) {
            if(!dFinish){
                dFinish = new Date();
            }
            dFinish.setDate(new Date().getDate()+ 1);

            trackFinish();
        } else {
            if(dFinish){
                dFinish.setDate(new Date().getDate());
                trackFinish();
            }
        }
    });


    function addTime(element , addMinutes){
        var timeSet = $(element).val();
        if(moment(timeSet, "HH:mm").isValid()){
            timeSet =  moment(timeSet, "HH:mm").add(addMinutes, 'minutes');
        } else {
            timeSet =  moment('00:00', 'HH:mm' ).add(addMinutes, 'minutes');
        }
        $(element).val(moment(timeSet, "HH:mm").format('HH:mm'));
    }


    //time + duratiot



    $('#formTrackStart').on('mouseleave',function(){
        trackStart();

    });

    $('#formTrackFinish').on('mouseleave',function(){
        trackFinish();
    });

    $("#timeDuration").on('mouseleave',function(){

   //     fixTime("#timeDuration");
    });

    $("#timeDuration").on('click',function(){

   //     fixTime("#timeDuration");
    });

    $(".button-orange").on('mousemove',function(){

    //    fixTime("#timeDuration");
    });




    function fixTime(element) {
        if($(element).val().length >0) {
            $(element).val();
            var fixTime = moment.duration($(element).val(), "HH:mm").format('HH:mm');
            //console.log(fixTime);
            $(element).val(fixTime);
        }
    }


    function timeDuration(dFinish,  dStart) {

        if (dFinish > dStart) {
            if (dFinish.getHours() == dStart.getHours && dFinish.getMinutes() == dStart.getMinutes()) {

                $("#timeDuration").val('00:00');

            } else {
                var duration = dFinish - dStart;

                var hours;

                if (Math.floor(duration / 60000) < 60) {
                    hours = '00';
                } else {
                    var hours = Math.floor(Math.floor(duration / 60000) / 60);
                    if (hours < 10) {
                        hours = '0' + hours;
                    }

                }

                var minuts = Math.floor(duration / 60000) % 60;

                if (minuts < 10) {
                    minuts = '0' + minuts;
                }

                $("#timeDuration").val(hours + ':' + minuts);
                $("#formTrackDuration").val(hours + ':' + minuts);

              
            }
        } else {
            $("#timeDuration").val('incorect');
            $("#formTrackFinish").val($('#formTrackStart').val());
        }
    }

    function timeDuration(dFinish,  dStart) {

        if (dFinish > dStart) {
            if (dFinish.getHours() == dStart.getHours && dFinish.getMinutes() == dStart.getMinutes()) {

                $("#timeDuration").val('00:00');

            } else {
                var duration = dFinish - dStart;

                var hours;

                if (Math.floor(duration / 60000) < 60) {
                    hours = '00';
                } else {
                    var hours = Math.floor(Math.floor(duration / 60000) / 60);
                    if (hours < 10) {
                        hours = '0' + hours;
                    }

                }

                var minuts = Math.floor(duration / 60000) % 60;

                if (minuts < 10) {
                    minuts = '0' + minuts;
                }

                $("#timeDuration").val(hours + ':' + minuts);
                $("#formTrackDuration").val(hours + ':' + minuts);

                
            }
        } else {
            $("#timeDuration").val('incorect');
            $("#formTrackFinish").val($('#formTrackStart').val());
        }
    }

    function trackStart(){
       
        if (hasValue("#formTrackStart") || hasValue("#formTrackFinish")){
            
            $("#timeDuration").attr('readonly', 'readonly');
            var dateStringStart = $("#formTrackStart").val();
            //      console.log(moment(dateString, "HH:mm").isValid() + moment(dateString, "HH:mm").format('HH:mm'));

            if(moment(dateStringStart, "HH:mm").isValid()){
                var dateString = moment(dateStringStart, "HH:mm").format('HH:mm');
                $('#formTrackStart').val(dateString);
                dStart = new Date();
                dStart.setHours(dateString.slice(0,2));
                dStart.setMinutes(dateString.slice(3));
                dStart.setSeconds('0');
                dStart.setMilliseconds('0');
                $('#formTrackStartSend').val(dStart);
            } else {
                $('#formTrackStart').val('incorect');
                $('#formTrackStartSend').val('');
            }


        } else{
            $("#timeDuration").removeAttr('readonly');
        }

        if(dFinish && dStart){

            timeDuration(dFinish,  dStart)
        }
    }

    function trackFinish(){
        if (hasValue("#formTrackStart") || hasValue("#formTrackFinish")){
            
            $("#timeDuration").attr('readonly', 'readonly');
            var dateStringFinish = $("#formTrackFinish").val();
            //      console.log(moment(dateString, "HH:mm").isValid() + moment(dateString, "HH:mm").format('HH:mm'));

            if(moment(dateStringFinish, "HH:mm").isValid()){
                var dateString = moment(dateStringFinish, "HH:mm").format('HH:mm');
                $('#formTrackFinish').val(dateString);
                if (!dFinish) {
                    dFinish = new Date();
                };
                dFinish.setHours(dateString.slice(0,2));
                dFinish.setMinutes(dateString.slice(3));
                dFinish.setSeconds('0');
                dFinish.setMilliseconds('0');
                $('#formTrackFinishSend').val(dFinish);
            } else {
                $('#formTrackFinish').val('incorect');
                $('#formTrackFinishSend').val('');
            }
        } else{
            $("#timeDuration").removeAttr('readonly');
        }
        if(dFinish && dStart){

            timeDuration(dFinish,  dStart);
        }
    }

    function timeDuration(dFinish,  dStart) {

        if (dFinish > dStart) {
            if (dFinish.getHours() == dStart.getHours && dFinish.getMinutes() == dStart.getMinutes()) {
                $("#timeDuration").val('00:00');
            } else {
                var duration = dFinish - dStart;
                var hours;
                if (Math.floor(duration / 60000) < 60) {
                    hours = '00';
                } else {
                    var hours = Math.floor(Math.floor(duration / 60000) / 60);
                    if (hours < 10) {
                        hours = '0' + hours;
                    }
                }

                var minuts = Math.floor(duration / 60000) % 60;
                if (minuts < 10) {
                    minuts = '0' + minuts;
                }

                $("#timeDuration").val(hours + ':' + minuts);
                $("#formTrackDuration").val(hours + ':' + minuts);
                //console.log(minuts + 'mm' + 'hh' + hours);
            }
        } else {
            $("#timeDuration").val('incorect');
            $("#formTrackFinish").val($('#formTrackStart').val());
        }
    }




    function hasValue(elem) {
        var valElement = $(elem).val();
        if (valElement) {
           
            return true;
        } else {
            
            return false;
        }
    }

	//Mith: 05/16/17: current date is selected or not if not fire notification.
    compareCurrentDate();
    function compareCurrentDate(){
      var sessionDate = $('#conteiner').data('date');
      var userStatus = $('#conteiner').data('status');
	  var pageName = $('#conteiner').data('pagename');
	  if(typeof pageName == 'undefined' || pageName != 'tracking' || typeof sessionDate == 'undefined' || 0 === sessionDate.length || userStatus == 'Supervisor' || userStatus == 'Admin' || userStatus == 'Super Admin'){ 		
        return;
      }
      var fit_start_time  = moment(sessionDate, 'DD-MM-YYYY');
      var fit_end_time    = moment();

      if(!(moment(fit_start_time).isSameOrAfter(fit_end_time,'day') && moment(fit_start_time).isSameOrBefore(fit_end_time,'day'))){
            myHubNotification('Select Today\'s date to start tracking your task.','trackingdate',555);
      }

    }


    function myHubNotification(myhubtitle,myhubTag,myhubtime){
              function onErrorNotification () {
                  console.error('Error showing notification. You may need to request permission.');
              }
              function onPermissionGranted () {
                  console.log('Permission has been granted by the user');
                  doNotification();
              }

              function onPermissionDenied () {
                  console.warn('Permission has been denied by the user');
              }

              function doNotification () {
                  var myNotification = new Notify('MyHub Message!', {
                      body: myhubtitle,
                      tag: myhubTag,
                      icon: '/images/notfyicon.png',
                      notifyError: onErrorNotification,
                      timeout: myhubtime
                  });

                  myNotification.show();
              }

              if (!Notify.needsPermission) {
                  doNotification();
              } else if (Notify.isSupported()) {
                  Notify.requestPermission(onPermissionGranted, onPermissionDenied);
              }
    }

    //timetrack log
     $('#timeTrackShowDate').html(moment($('#conteiner').data('date'), 'DD-MM-YYYY').format('dddd, MMMM Do YYYY'));


     var
      /*stop = document.getElementById('stop'),
     clear = document.getElementById('clear'),*/
     seconds = 0, minutes = 0, hours = 0,
     t;
	var fiveMinIncreaseTime = '';


     function add(timeSet) {

		 seconds++;
         if (seconds >= 60) {
             seconds = 0;
             minutes++;
             if (minutes >= 60) {
                 minutes = 0;
                 hours++;
             }
         }
         var timeDurationSet = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);
         $('#timeTrackSegmentDuration').html(timeDurationSet);

        var taskTitle = $('.timeTrackSegmentTotalActive').closest('tr').data('task_titly');
			taskTitle = 'Please re-visit Tracking page, your task ('+taskTitle+') is exceeded with the allocated tracked time.';
        var allocateDuration = $('.timeTrackSegmentTotalActive').closest('tr').data('duration');
        var taskAllocateDuration = moment(allocateDuration, 'hh:mm:ss');
        var trackDuration = SecondsTohhmmss((moment.duration(timeDurationSet) + (moment.duration($('.timeTrackSegmentTotalActive').data('total'))))/1000);
        trackDuration = moment(trackDuration, 'hh:mm:ss');

        $('.timeTrackSegmentTotalActive').html(SecondsTohhmmss((moment.duration(timeDurationSet) + (moment.duration($('.timeTrackSegmentTotalActive').data('total'))))/1000));
		if(taskAllocateDuration.isBefore(trackDuration)){
            if(fiveMinIncreaseTime == '' || fiveMinIncreaseTime.isBefore(trackDuration)){
                myHubNotification(taskTitle,'taskoverdue',555);
                var temp = trackDuration.add(5, 'minutes');
                fiveMinIncreaseTime = moment(temp, 'hh:mm:ss');
            }
        }
		timer();
     }

     function timer() {
     t = setTimeout(add, 1000);
     }
    // timer();
     clearTimeout(t);

	setInterval(function(){
            var userStatus = $('#conteiner').data('status');
          if(typeof t == 'undefined' ){
            if(userStatus == 'Developer' || userStatus == 'QA Engineer' || userStatus == 'Lead' || typeof userStatus == 'undefined'){
                myHubNotification('You are not tracking your task since long. Please, select appropriate task to track.','trackingnotstarted',555);
            }
          }
    }, 900000);

     $(document).on('click' , '#startTrack',  function(e){

         $.post('/create/timelog',
             { project_id: $(e.target).parents("tr").data('project_id'),
                task_id: $(e.target).parents("tr").data('task_id'),
                track_id: $(e.target).parents("tr").data('id'),
                 _token: $('#conteiner').data('token'),
                 create: true },
             function (response){
        
                 window.location ='/tracking/' + $('#conteiner').data('date');
            /* var responseDate = response.data;
             var dateStartTrack = moment(responseDate * 1000).format('HH:mm')

                 console.log( response.data[0].id);
             var id = $(e.target).parents("tr").data('id');

           
                var track_id =  $(e.target).parents("tr").data('id');
                var project_name =    $(e.target).parents("tr").data('project_name');
                var task_title =   $(e.target).parents("tr").data('task_titly');*/



               //  showStartLogBlock(moment(response.data[0].start, "YYYY-MM-DD hh:mm:ss").format('HH:mm'), response.data[0].id, track_id, project_name,  task_title, e);

           //  $('#add-' + id).show();

            // timer();
         });
     });
     //clearTimeout(t);
    var actibeTimetrackId = false;

     $(document).on('click' , '#stopTrack',  function(event){
         
         event.preventDefault();

      //   var timeSegment = $('#timeTrackSegmentDuration').text();
         clearTimeout(t);
         document.getElementById('stop-form').submit();

     });

    $(document).on('click' , '#stopTrack2',  function(event){
        
        event.preventDefault();
        clearTimeout(t);
        document.getElementById('stop-form-track').submit();

    });



    //time log show

    $(document).on('click' , '.showTimelog',  function(e){
        showTimeLog(e);
    });

    function showTimeLog(e, add) {
        var id = $(e.target).parents("tr").data('id');
		var taskId = $(e.target).parents("tr").data('task_id');
        var trackDate = moment($('#conteiner').data('date'), "DD-MM-YYYY").format('YYYY-MM-DD');
        
		//$('#add-' + id).show();						/* SN 08/09/2017: updated below code to show time log on click of plus and minus button **/
        //$('#track-' + id).find('.showTimelog').hide();
		$('#add-' + id).removeClass('hidden');
        $('#track-' + id).find('.showTimelog').addClass('hidden');
		$(e.target).parents('tr').find('.hideTimelog').removeClass('hidden');

        $.get('/track-getTimeLogById/'+ taskId + '/'+ trackDate, function (response) {
            var html;
                for (var key in response.data) {
                    if (response.data[key].finish != null) {
                    html += '' +
                        '<tr class="trackLog"  data-idTrack="' + response.data[key].track_id + '">' +
                            '<td class="">' +
                                '<span class="ng-binding"></span>' +
                                '<p class="projecttask">' + response.data[key].project.project_name + ' - ' + response.data[key].task.task_titly + '</p>' +
                            '</td>' +
                            '<td class="text-center" style="width:15%;">' +
                                '<h4 id="" style="margin: 7px 0px ">' +
                                SecondsTohhmmss((moment(response.data[key].finish, "YYYY-MM-DD hh:mm:ss") - moment(response.data[key].start, "YYYY-MM-DD hh:mm:ss")) / 1000) + '</h4>' +
                                '<p class="project" >' +
                                moment.utc(response.data[key].start, "YYYY-MM-DD hh:mm:ss").local().format('HH:mm') + ' - ' + moment.utc(response.data[key].finish, "YYYY-MM-DD hh:mm:ss").local().format('HH:mm') + '</p>' +
								'<p class="project" >' +
                                moment(response.data[key].start, "YYYY-MM-DD hh:mm:ss").format('YYYY-MM-DD') + '</p>' +
                            '</td>' +
                            '<td class="text-right table-cell-actions">' +
                                '<div class="btn-group">' +
                                    '<button type="button" class="btn btn-default deleteLog"' +
                                        'data-url="/log/delete/' + response.data[key].id + '" data-element="' + SecondsTohhmmss((moment(response.data[key].finish, "YYYY-MM-DD hh:mm:ss") - moment(response.data[key].start, "YYYY-MM-DD hh:mm:ss")) / 1000) + '">' +
                                        '<span class="glyphicon glyphicon-trash span_no_event" aria-hidden="true"></span>' +
                                    '</button>' +
                                '</div>' +
                            '</td>' +
                        '</tr>';
                    }

                    if(response.data[key].finish == null) {
                        actibeTimetrackId = response.data[key].id;

                        $.get('/tracking-getTime', function (date) {
                        
                          var duration = SecondsTohhmmss((moment(date.data, "YYYY-MM-DD hh:mm:ss") - moment(response.data[key].start, "YYYY-MM-DD hh:mm:ss")) / 1000)

                       var  html2 = '' +
                            '<tr class="trackLog activeTrack trackLogWrite" data-stop-id ="' + response.data[key].id + '"  >' +
                                '<td class="">' +
                                    '<span class="ng-binding"></span>' +
                                    '<p class="projecttask"> - ' + response.data[key].project.project_name + ' - ' + response.data[key].task.task_titly + '</p>' +
                                '</td>' +
                                '<td class="text-right">' +
                                    '<h3 id="timeTrackSegmentDuration" style="margin: 7px 0px ">' + duration + '</h3>' +
                                    '<p class="project" >' + moment(response.data[key].start, "YYYY-MM-DD hh:mm:ss").format('HH:mm') + ' - --:--</p>' +
                                '</td>' +
                                '<td class="text-right table-cell-actions">' +
                                    '<div class="btn-group">' +
                                        '<a href="#" class="btn btn-danger" id="stopTrack" >' +
                                        '<span class="glyphicon glyphicon-stop"></span>' +
                                        '</a>' +

                                        '<form id="stop-form" action="/create/timelog/" method="POST" style="display: none;">' +
                                            '<input type="hidden" name="_token" id="csrf-token" value="' + $('#conteiner').data('token') + '" />' +
                                            '<input type="hidden" name="id" value="' + response.data[key].id + '">' +
                                        '</form>' +
                                    '</div>' +
                                '</td>' +
                            '</tr>';

                            $('#add-' + id).find('table').append(html2);

                            var formFinish = '' +
                                '<form id="stop-form-track" action="/create/timelog/" method="POST" style="display: none;">' +
                                    '<input type="hidden" name="_token" id="csrf-token" value="' + $('#conteiner').data('token') + '" />' +
                                    '<input type="hidden" name="id" value="' + response.data[key].id + '">' +
                                '</form>';

                            $('#track-' + response.data[key].track_id).find('.addTrackFinishForm').html(formFinish);

                            $('#track-' + response.data[key].track_id).find('#startTrack').hide();
                            $('#track-' + response.data[key].track_id).find('#stopTrack2').show();

                            seconds = duration.slice(6,7) == 0 ? duration.slice(7) : duration.slice(6);
                                minutes = duration.slice(3,4) == 0 ? duration.slice(4,5) : duration.slice(3,5);
                                hours = duration.slice(1,2) == 0 ? duration.slice(1,2) : duration.slice(0,2);

                            if (!t) {
                                timer();
                            }
                        });
                    }
                };


           /* if (add) {
                html += add;
            }*/


            $('#add-' + id).find('table').html(html);
            //$(e.target).parents('a').hide();
            //$(e.target).parents('tr').find('.hideTimelog').show();

        });
    };

   /* function showStartLogBlock(time, id, track_id, project_name,  task_title, e)
    {
        var html = '' +
            '<tr class="trackLog activeTrack trackLogWrite" data-stop-id ="' + track_id + '"  >' +
            '<td class="">' +
            '<span class="ng-binding"></span>' +
            '<p class="projecttask"> - ' + project_name + ' - ' + task_title + '</p>' +
            '</td>' +
            '<td class="text-right">' +
            '<h3 id="timeTrackSegmentDuration" style="margin: 7px 0px ">0:00:00</h3>' +
            '<p class="project" >' + time + ' - --:--</p>' +
            '</td>' +
            '<td class="text-right table-cell-actions">' +
            '<div class="btn-group">' +
            '<a href="#" class="btn btn-danger" id="stopTrack" >' +
            '<span class="glyphicon glyphicon-stop"></span>' +
            '</a>' +

            '<form id="stop-form" action="/create/timelog/" method="POST" style="display: none;">' +
            '<input type="hidden" name="_token" id="csrf-token" value="' + $('#conteiner').data('token') + '" />' +
            '<input type="hidden" name="id" value="' + id + '">' +
            '</form>' +
            '</div>' +
            '</td>' +
            '</tr>';


        showTimeLog(e, html);
    }*/


    $(document).on('click' , '.hideTimelog',  function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        //$(e.target).parents('a').hide();
        $(e.target).parents('tr').find('.showTimelog').removeClass('hidden');
		$(e.target).parents('tr').find('.hideTimelog').addClass('hidden');
        var id =$(e.target).parents("tr").data('id');
        $('#add-'+  id).addClass('hidden');
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



    //cockies

    if(getCookie('logTrackActiveLogId')){
        timer();
    }
    // ( document.cookie );
	/*var table = $('#usersTable').DataTable();
		table.state.clear();
		table.destroy();
*/

        $('#usersTable').DataTable({
           // "order": [[ 5, "desc" ]],
              "aaSorting": [],
			  "stateSave": true,
              "stateDuration": -1,
			  "bStateSave": true,
			  //"select": true,
			  "responsive": true,
			  
			  
			    initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
					var val,newfilter,selected;
                    var select = $('<select id="alloption" onchange="updateTotal(this)"><option value="">All</option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
							val = $(this).val().replace('/[\]/', '');  //SN 06/05/2017: added this line to remove special character.
                            column
                                //.search(val ? '^' + val + '$' : '', true, false)
								.search(val ? '' + val + '' : '',0, true)
                                .draw();
                        });
					          //Mith: 04/21/17: sort the dropdown in alphabatical order.
                    var sortFunction = function (a, b) {
                                                return a.toLowerCase().localeCompare(b.toLowerCase());
                                        }
					//SN 05/22/2017: updated below code to retain dropdown value after page refreshing.
					var filter = $('#usersTable').DataTable().columns().search(); 
                    column.data().unique().sort(sortFunction).each(function (d, j) {
                        if (!(d.length < 1) ) {
                            selected = (filter.indexOf(d) > -1) ? 'selected' : '';
							select.append('<option value="' + d + '"'+selected+'>' + d + '</option>');
                        }
                    });
                });
            }
        });
	//Mith: 05/01/17: add 'state save' and 'state duration' option with 'state clear' method at time of  initialization.
    $('#usersTable tfoot tr').insertAfter($('#usersTable thead tr'))


    $('#usersTable').parent().addClass('table_container');

    //$('#usersTable').find('select').addClass('input-xlarge focused my_input');
   
	$(document).on("click", "#button-logout", function (e) {
		var table = $('#usersTable').DataTable();
		table.state.clear();
		//table.destroy();
		window.location.href = '/user/logout';
	});

    //   getProjects =

        $(document).on("click", ".getProjects", function (e) {
          var  id = $(e.target).parent('tr').data('id');
            var urlGet = '/client/projects/' + id;
            
            if (urlGet) {
                window.location.href = urlGet;
            } else {

            }

        });

    $(document).on("click", ".getTasks", function (e) {
        var  id = $(e.target).parent('tr').data('id');
        var urlGet = '/project/tasks/' + id;
       
        if (urlGet) {
            window.location.href = urlGet;
        } else {

        }

    });

    $(document).on( "click", ".deleteTeam", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var delUrl = $(e.target).data('url');
        var element = $(e.target).data('element');
        var massage = 'Do you want to remove <strong> ' + element + '</strong>?'
        Main.displayModal('#delete-team', delUrl,   massage, '#modalConfirmDeleteTeam');
    });

    $(document).on( "click", ".deleteUser", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var delUrl = $(e.target).data('url');
        var element = $(e.target).data('element');
        var massage = 'Do you want to remove <strong> ' + element + '</strong> user?'

        Main.displayModal('#delete-user', delUrl, massage, '#modalConfirmDeleteUser');
    });

    $(document).on( "click", ".deleteClient", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var delUrl = $(e.target).data('url');
        var element = $(e.target).data('element');
        var massage = 'Do you want to remove <strong> ' + element + '</strong>?'

        Main.displayModal('#delete-client', delUrl,  massage, '#modalConfirmDeleteClient');
    });

    $(document).on( "click", ".deleteProject", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var delUrl = $(e.target).data('url');
        var element = $(e.target).data('element');
        var massage = 'Do you want to remove <strong> ' + element + '</strong>?'

        Main.displayModal('#delete-project', delUrl,  massage, '#modalConfirmDeleteProject');
    });

    $(document).on( "click", ".deleteTask", function(e) {
        e.preventDefault();
        var delUrl = $(e.target).data('url');
        var element = $(e.target).data('element');
        var massage = 'Do you want to remove <strong> ' + element + '</strong>?'

        Main.displayModal('#delete-task', delUrl,  massage, '#modalConfirmDeleteTask');
    });

    $(document).on( "click", ".deleteTrack", function(e) {
        e.preventDefault();
        
    //    e.stopImmediatePropagation();
        var delUrl = $(e.target).data('url');
        var element = $(e.target).data('element');
        var massage = 'Do you want to remove track <strong> ' + element + '</strong>?'

        Main.displayModal('#delete-track', delUrl,  massage, '#modalConfirmDeleteTrack');
    });

    $(document).on( "click", ".deleteLog", function(e) {
        e.preventDefault();
        
        //    e.stopImmediatePropagation();
        var delUrl = $(e.target).data('url');
        var element = $(e.target).data('element');
        var massage = 'Do you want to remove log <strong> ' + element + '</strong>?'

        Main.displayModal('#delete-track', delUrl,  massage, '#modalConfirmDeleteTrack');
    });

	/* SN 06/29/2017: added below code for show error popup when select more than 10 checkboxes */
	 /*$(document).on( "change", "input[name=approvetrack]", function() {
		var noofcheckbox = $('#usersTable').DataTable().$("input[name=approvetrack]:checked", {"page": "all"}).length; 
		if(noofcheckbox > 10){		
			//$('#usersTable').DataTable().$("input[name=approvetrack]", {"page": "all"}).prop("checked", false);
			var msg = "More than 10 checkboxes cannot be selected!";
			Main.displayErrorMessageModal('#delete-track', msg, noofcheckbox, '#modalConfirmDeleteTrack');
			$(this).prop("checked", false);
			return false;
		}	
	 });  */
	
    //SN 06/08/2017: updated approve functionality at timetrack page
    $(document).on( "click", ".approvTrack", function(e) {
        e.preventDefault();
		var values = $('#usersTable').DataTable().$('input[name=approvetrack]:checked', {"page": "all"}).map(function() {
                               return $(this).val();
                           }).get();
		
		if(values.length>1){
			var delUrl = $(e.target).data('url');
			var element = $(e.target).data('element');
			var id = values;
			var massage = 'Do you want to approve all selected tracks?'
		}else{
			var delUrl = $(e.target).data('url');
			var element = $(e.target).data('element');
			var id = $(e.target).data('id'); 
			var massage = 'Do you want to approve track <strong> ' + element + '</strong>?'
		}
        Main.displayModalApprove('#delete-track', delUrl,  massage, id, '#modalConfirmDeleteTrack');
    });
	
	$(document).on("click", ".trackapprove", function(e){
		var id = $(this).data('id');
		var taskids = id.toString().split(",");
		
		$(".overlay").removeClass('hidden');
		$("#loader").removeClass('hidden');
		$("#bodyData").css('pointer-events','none');    
		$(".close-approve").trigger('click');
		
		if(taskids.length == 1){
		  var url = '/task/approve/'+id;
			$.get(url, function(response){
				if(response){
					window.location = '/track/all';
					$(".overlay").addClass('hidden');
					$("#loader").addClass('hidden');
					$("#bodyData").css('pointer-events','');  
				}
			});
		}else if(taskids.length > 1){
			var token = $('#conteiner').data('token');
			token = token.trim();
			var url = '/task/alltaskapprove';
			$.post(url,
			 { task_ids: taskids,
				_token: token
			   }, function (response){
			        window.location = '/track/all';
					$(".overlay").addClass('hidden');
					$("#loader").addClass('hidden');
					$("#bodyData").css('pointer-events','');
			 });
		}
	});
	

    $(document).on( "click", ".rejectTrack", function(e) {
        e.preventDefault();

        var delUrl = $(e.target).data('url');
        var element = $(e.target).data('element');
		var id = $(e.target).data('id');
		var token = $('#conteiner').data('token');
		token = token.trim();
        var massage = 'Do you want to reject track <strong> ' + element + '</strong>?'

		Main.displayModalReject('#delete-track', delUrl,  massage, id, token, '#modalConfirmDeleteTrack');
        //Main.displayModalReject('#delete-track', delUrl,  massage, '#modalConfirmDeleteTrack');
    });

	//SN 06/16/2017: added below function to select maximum 10 records at select all option of checkbox header.
	$(document).on("click", ".checkbox-header > #alloption", function(){ 
		var val = $(".checkbox-header > #alloption").text();
		if(val == 'All'){
			/*if($("input[type=checkbox]:checked").slice(0, 10).prop('checked') == true){			
				$("input[type=checkbox]").slice(0, 10).prop('checked', false);
			}else{
				if($("input[type=checkbox]:checked").length > 10){ 
					$("input[type=checkbox]").slice(0, 10).prop('checked', 'true');	
				}else{
					$("input[type=checkbox]").slice(0, 10).prop('checked', 'true');
				}
			} */
			
			if($("input[type=checkbox]:checked").slice(0, 10).prop('checked') == true){			
				$("input[type=checkbox]").slice(0, 10).prop('checked', false);
			}else{
				$("input[type=checkbox]").prop('checked', 'true'); 
			}
			if($("input[type=checkbox]:disabled")){ 
				$("input[type=checkbox]:disabled").prop('checked', '');
			}

			/* SN 06/29/2017: added below code when checked checkbox more than 10 error popup appears on clik all */
			/*var noofcheckbox = $('#usersTable').DataTable().$("input[name=approvetrack]:checked", {"page": "all"}).length;  
			if(noofcheckbox > 10){	
				var msg = "More than 10 checkboxes cannot be selected!";
				Main.displayErrorMessageModal('#delete-track', msg, noofcheckbox, '#modalConfirmDeleteTrack');
				$('#usersTable').DataTable().$("input[name=approvetrack]:checked", {"page": "all"}).prop("checked", false);
				return false;
			} */
		}
	});
	
	//SN 04/16/2017: added below for set description.
	$(window).load(function(){
		setdescription();
	});

	
	//SN 05/25/2017: added below code to get all task for approve and reject button functionality
	$(document).on("click", ".approveTaskbutton", function(e){
		e.preventDefault();
		var checkValues = $('input[name=apprtasklist]:checked').map(function() {
                               return $(this).val();
                           }).get();
        if(checkValues.length>1){
          var id = checkValues;
          var delUrl = $(e.target).data('url');
          var element = $(e.target).data('element');
          var massage = 'Do you want to approve all selected tracks?'
          Main.displayModalLeadApprove('#delete-track', id, delUrl,  massage, '#modalConfirmDeleteTrack');
        } else {
          var id = $(e.target).data('id');
          var delUrl = $(e.target).data('url');
          var element = $(e.target).data('element');
          var massage = 'Do you want to approve track <strong> ' + element + '</strong>?'
          Main.displayModalLeadApprove('#delete-track', id, delUrl,  massage, '#modalConfirmDeleteTrack');
        }

	});

	/*
	$(document).on("click", ".leadapprovetask", function(){
		var id = $(this).data('id');
		var url = '/trask/approve/'+id;
		$("#delete-track").hide();
		$(".modal-backdrop").hide();
		$(".overlay").removeClass('hidden');
		$("#loader").removeClass('hidden');
		$("#bodyData").css('pointer-events','none');    //SN 05/31/2017: made screen non clickable when click on approve button
		$.get(url, function(response){
			if(response){
				setLeadTask(); 
				$("#loader").addClass('hidden');
				$(".overlay").addClass('hidden');
				$("#bodyData").css('pointer-events','');  //SN 05/31/2017: remove css for make screen  clickable
			}
		});
	}); */

	$(document).on("click", ".leadapprovetask", function(){
		var id = $(this).data('id');
		var taskIds = id.toString().split(",");
		$(".btn-default").click();
		if(taskIds.length == 1){
		  var url = '/task/approve/'+id;
			$(".overlay").removeClass('hidden');
			$("#loader").removeClass('hidden');
			$("#bodyData").css('pointer-events','none');    //SN 05/31/2017: made screen non clickable when click on approve button
			$(".close-popup").trigger('click');
			$.get(url, function(response){
				if(response){
					setLeadTask(function(statusCompleted){
						$("#loader").addClass('hidden');
						$(".overlay").addClass('hidden');
						$("#bodyData").css('pointer-events','');  //SN 05/31/2017: remove css for make screen  clickable	
					});
				}
			});
		}else if(taskIds.length > 1){
		  $(".overlay").removeClass('hidden');
		  $("#loader").removeClass('hidden');
		  $("#bodyData").css('pointer-events','none');    //SN 05/31/2017: made screen non clickable when click on approve button
		  $(".close-popup").trigger('click');
			$.post('/task/alltaskapprove',
			 { task_ids: taskIds,
				_token: $('#dashcontainer').data('token')
			   },
			 function (response){
			   setLeadTask(function(statusCompleted){
					$("#loader").addClass('hidden');
					$(".overlay").addClass('hidden');
					$("#bodyData").css('pointer-events','');  //SN 05/31/2017: remove css for make screen  clickable
			   });
			 });
		}
	});
	
	$(document).on( "click", ".rejectTaskbutton", function(e) {
        e.preventDefault();
        var delUrl = $(e.target).data('url');
        var element = $(e.target).data('element');
		var id = $(e.target).data('id');
		var token = $('#dashcontainer').data('token');
		token = token.trim();		
        var massage = 'Do you want to reject track <strong> ' + element + '</strong>?'
		Main.displayModalLeadReject('#delete-track', delUrl,  massage, id, token, '#modalConfirmDeleteTrack');
    });

	/**
     *  Mith 05/31/2017: ajax task rejection. 
     */
     $(document).on('submit','form.leadcommentform',function(event){
        event.preventDefault();
        var $form = $( this ),
        url = $form.attr( 'action' );
        var postData = $(this).serializeArray();
			$(".btn-default").click();
    		$(".overlay").removeClass('hidden');
    		$("#loader").removeClass('hidden');
			$("#bodyData").css('pointer-events','none');    //SN 05/31/2017: made screen non clickable when click on approve button and add trigger() to remove scroll bar not appearing issue
			$(".close-popup").trigger('click');
    		$.post(url, postData, function(response){
    			if(response){
    				setLeadTask(function(statusCompleted){
						$("#loader").addClass('hidden');
						$(".overlay").addClass('hidden');
						$("#bodyData").css('pointer-events','');
					});
    			}
    		});
    });
	 
	$(document).on("change", "#CompanyTaskId", function () {

        var clientId = $("#CompanyTaskId option:selected").val();
        if (clientId) {
            var result = '<option selected disabled value="">Please select Project</option>';
            var urlSend = '/project/getProjects/' + clientId;
            $.get(urlSend, function (response) {
				//SN 09/28/2017: added check to remove project-leave from project list
                for (var key in response.data) {
                    if(response.data[key].project_name == 'Leave'){
						result += '<option value="' + response.data[key].id + '" class="hidden">' + response.data[key].project_name + '</option>';
					}else{
						result += '<option value="' + response.data[key].id + '">' + response.data[key].project_name + '</option>';
					}
                };

                $("#taskProjectId").html(result);
            });
        } else {
            $("#taskProjectId").html('');
        }

    });

    $(document).on("change", "#taskProjectId", function () {
        Main.all_users2();
    });

    var list = $('#AssignToId').data('all');

    if(list) {
        //  $(document).on("mouseenter", "#AssignToId", function () {
        
        Main.all_users2();
    }
   // });
});

//SN 05/26/2017: added below function to get lead task list need to approve
function setLeadTask(callback){
	if($("#dashcontainer")){
		var status = $("#dashcontainer").data('status'); 
		var id = $("#dashcontainer").data('activeid');  
		var html, html2;
		if(status == 'Lead'){	
			var url = '/get/getApproveTask';
			$.get(url, function(response){
				if(response){      
					for (var key in response.data.approvetask) {		
						if (response.data.approvetask[key] != null) {	
							html += '<tr>'+
									 '<td><input type="checkbox" name="apprtasklist" value='+response.data.approvetask[key].id+' /></td>'+	
									 '<td class="check-box-center">'+response.data.approvetask[key].project_name+'</td>'+
									 '<td>'+response.data.approvetask[key].task_titly+'</td>'+
									 '<td class="check-box-center">'+response.data.approvetask[key].name+'</td>'+
									 '<td class="check-box-center">'+
									 '<button type="button" class="btn btn-success approveTaskbutton" title="Approve"   data-id="'+response.data.approvetask[key].id+'"'+
										  'data-element="'+response.data.approvetask[key].task_titly+'"'+
										  'data-url="/task/approve/'+response.data.approvetask[key].id+'" > <span class="glyphicon glyphicon-ok span_no_event" aria-hidden="true"></span> '+
										  '</button>'+
									 '&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-warning rejectTaskbutton" title="Reject" data-id="'+response.data.approvetask[key].id+'"'+
										  'data-element="'+response.data.approvetask[key].task_titly+'"'+
										  'data-url="/trask/reject/'+response.data.approvetask[key].id+'" > <span class="glyphicon glyphicon-remove span_no_event" aria-hidden="true"></span> '+
										  '</button>'+
									 '</td>'+
									 '</tr>';

									
						}
					}

					
				}else{
					html = "No Task";
				}
				$("#taskTable .tasklistbody").html('');
				$("#taskTable .tasklistbody").append(html);
				$("#taskTable").css("margin-bottom","0px");
				callback('done');
			});

			
		}
	}
}

function getServerTime() {
    $.get('/tracking-getTime', function (response) {
        return response.data;
    });
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return false;
}

function checkcomment(){
	var text = $("#comment-box").val();
		text = text.trim();
	if(text.length < 5) {
		$("#error-comment").html("* Provide Minimum 5 characters");
		return false;
	}
	return true;
}

function removeerror(){
    $("#error-comment").html("");
}

function setdescription(){
	var description = $('#Description').val();
	if(description){
		description = description.trim();
		$("#Description").html(description);
	}
}

var Main = {
    displayModal: function(idModal, delUrl, massage, appendContainer) {
        var htmlDelete = '' +
            '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                '<h4 class="modal-title">Delete Confirmation</h4>' +
            '</div>' +
            '<div class="modal-body">' +
                '<p>' + massage + '</p>' +
            '</div>' +
            '<div class="modal-footer">' +
                '<a href="' + delUrl + '" type="button" class="btn btn-danger" >Delete</a>' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
                '</div>';

        $(appendContainer).html(htmlDelete)
        $(idModal).modal('toggle');
    },
	
	displayErrorMessageModal: function(idModal, msg, noofcheckbox, appendContainer) {
        var htmlDelete = '' +
            '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                '<h4 class="modal-title">Alert Message</h4>' +
            '</div>' +
            '<div class="modal-body">' +
                '<p>' + msg + '</p>' +
            '</div>' +
            '<div class="modal-footer">' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '</div>';

        $(appendContainer).html(htmlDelete)
        $(idModal).modal('toggle');
    },
	
	//SN 06/09/2017: updated below approve and cancel button for approal task.
    displayModalApprove: function(idModal, delUrl, massage, id, appendContainer) {
        var htmlDelete = '' +
            '<div class="modal-header">' +
            '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
            '<h4 class="modal-title">Approve Confirmation</h4>' +
            '</div>' +
            '<div class="modal-body">' +
            '<p class="task-title">' + massage + '</p>' +
            '</div>' +
            '<div class="modal-footer">' +
            '<a href="#" onclick="event.preventDefault();" type="button" class="btn btn-success trackapprove" data-id="'+id+'">Approve</a>' +
            '<button type="button" class="btn btn-default close-approve" data-dismiss="modal">Close</button>' +
            '</div>';

        $(appendContainer).html(htmlDelete)
        $(idModal).modal('toggle');
    },
    //displayModalReject: function(idModal, delUrl, massage, appendContainer) {
	displayModalReject: function(idModal, delUrl, massage, id, token, appendContainer) {
        var htmlDelete = '' +
            '<div class="modal-header">' +
            '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
            '<h4 class="modal-title">Reject Confirmation</h4>' +
            '</div>' +
            '<div class="modal-body">' +
            '<p>' + massage + '</p>' +

			'<div>' +
			'<form id="commentform" class="commentform" method="post" action="'+ delUrl +'" onsubmit="return checkcomment();">' +
			'<input type="hidden" name="_token" value="'+ token +'">' +
			'<input type="hidden" name="id" value="' + id + '">' +
			'<textarea id="comment-box" name="comment-box" class="comment-box" placeholder="Comments here (Required)" onfocus="return removeerror();" minlength="5" required pattern="[A-Za-z0-9 ,-:]{5}" title="Provide Minimum 5 characters" style="width:100%;min-height:100px;margin-top:20px;"></textarea>' +
			'<span class="help-block" id="error-comment" style="color:#802420;font-weight:bold;"></span>'+
            '<div class="modal-footer">' +
            '<button type="submit" class="btn btn-warning" >Reject</button>' +
			'<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
			'</div>' +
			'</div>' +
			'</form>' +
            '</div>';

        $(appendContainer).html(htmlDelete)
        $(idModal).modal('toggle');
    },
	
	//SN 05/29/2017: added approve and reject model for lead
	displayModalLeadApprove: function(idModal, id, delUrl, massage, appendContainer) {
        var htmlDelete = '' +
            '<div class="modal-header">' +
            '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
            '<h4 class="modal-title">Approve Confirmation</h4>' +
            '</div>' +
            '<div class="modal-body">' +
            '<p>' + massage + '</p>' +
            '</div>' +
            '<div class="modal-footer">' +
            '<a href="#" onclick="event.preventDefault();" type="button" class="btn btn-success leadapprovetask" data-id="'+id+'">Approve</a>' +
            '<button type="button" class="btn btn-default close-popup" data-dismiss="modal">Close</button>' +
            '</div>';

        $(appendContainer).html(htmlDelete);
        $(idModal).modal('toggle');
    },

	
	displayModalLeadReject: function(idModal, delUrl, massage, id, token, appendContainer) {		

		
        var htmlDelete = '' +
            '<div class="modal-header">' +
            '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
            '<h4 class="modal-title">Reject Confirmation</h4>' +
            '</div>' +
            '<div class="modal-body">' +
            '<p>' + massage + '</p>' +

			'<div>' +
			'<form id="leadcommentform" class="leadcommentform" method="post" action="'+delUrl+'" >' +
			'<input type="hidden" name="_token" id="token" value="'+ token +'">' +
			'<input type="hidden" name="id" id="id" value="' + id + '">' +
			'<textarea id="comment-box" name="comment-box" class="comment-box" placeholder="Comments here (Required)" onfocus="return removeerror();" minlength="5" required pattern="[A-Za-z0-9 ,-:]{5}" title="Provide Minimum 5 characters" style="width:100%;min-height:100px;margin-top:20px;"></textarea>' +
			'<span class="help-block" id="error-comment" style="color:#802420;font-weight:bold;"></span>'+
            '<div class="modal-footer">' +
            '<button type="submit" class="btn btn-warning leadrejecttask" data-id="'+id+'">Reject</button>' +
			'<button type="button" class="btn btn-default close-popup" data-dismiss="modal">Close</button>' +
			'</div>' +
			'</div>' +
			'</form>' +
            '</div>';

        $(appendContainer).html(htmlDelete);
        $(idModal).modal('toggle');
    },

    all_users: function() {

        var clientId = $("#taskProjectId option:selected").val();
        var employe = ($('#conteiner').data('status'));
        if (clientId) {

            var urlSend = '/get/team/' + clientId;
            var result = '<option selected disabled value="null">Please select Assign to</option>';

            if ('' != ($("#username").text())) {
                result = null;
            }

            var idActiveuser = null;
            if ($('#username').data('id')) {
                idActiveuser = $('#username').data('id');
            }

            $.get(urlSend, function (response) {
                
                var lead = '<optgroup label="Lead">';
                if (response.data.lead[0].hasOwnProperty('id')) {
                    
                    if ($('#conteiner').data('type-action') == 'add' || (idActiveuser !== response.data.lead[0].id)) {
                        lead += '<option value="' + response.data.lead[0].id + '">' + response.data.lead[0].name + ' - ' + response.data.lead[0].employe + '</option>';
                    }
                }
                lead += '</optgroup>';


                var team = '<optgroup label="Team">';
                if (response.data.team !== undefined) {
                    for (var i  in response.data.team) {
                        if (response.data.team[i].employe != 'Lead') {

                            if ($('#conteiner').data('type-action') == 'add' || (idActiveuser !== response.data.team[i].id)) {
                                team += '<option value="' + response.data.team[i].id + '">' + response.data.team[i].name + ' - ' + response.data.team[i].employe + '</option>';
                            }
                        }
                    }
                }
                team += '</optgroup>';

                var qa = '<optgroup label="QA Engineer">';
                for (var i  in response.data.qa) {
                    if ($('#conteiner').data('type-action') == 'add' || ( idActiveuser !== response.data.qa[i].id)) {
                        qa += '<option value="' + response.data.qa[i].id + '">' + response.data.qa[i].name + ' - ' + response.data.qa[i].employe + '</option>';
                    }
                }
                ;
                qa += '</optgroup>';

               

                if ((employe == 'Admin' || employe == 'Lead' || employe == 'Supervisor') && ($('#conteiner').data('type-action') == 'add' || (idActiveuser !== response.data.other[i].id))) {
                    var other = '<optgroup label="Other">';
                    for (var i  in response.data.other) {
                        other += '<option value="' + response.data.other[i].id + '">' + response.data.other[i].name + ' - ' + response.data.other[i].employe + '</option>';
                    }
                    ;
                    other += '</optgroup>';
                }

                $("#AssignToId").append(result + lead + team + qa + other);
            });
        } else {
            $("#AssignToId").html('');
        }

    },

        all_users2: function() {

            var clientId = $("#taskProjectId option:selected").val();
            var employe = ($('#conteiner').data('status'));

			var oldid = $('#AssignToId').data('value');
			if(!oldid){
				oldid = 0;
			}

            if (clientId) {

                var urlSend = '/get/get-users';
                var result = '<option selected disabled value="null">Please select Assign to</option>';

                if ('' != ($("#username").text())) {
                    result = null;
                }

                var idActiveuser = null;
                if ($('#username').data('id')) {
                    idActiveuser = $('#username').data('id');
                }

                if (null == $('#AssignToId').val()) {
                    $("#AssignToId").html('');
                }

                $.get(urlSend, function (response) {

                    var lead = '<optgroup label="Leads">';
                    var developer = '<optgroup label="Developers">';
                    var qa = '<optgroup label="QA Engineers">';
                    var other = '<optgroup label="Other">';
                    if (response.data.users !== undefined) {
                        for (var i  in response.data.users) {
                            if ($('#conteiner').data('type-action') == 'add' || (idActiveuser !== response.data.users[i].id)) {
                                if (response.data.users[i].employe == 'Developer') {
                                    developer += '<option value="' + response.data.users[i].id + '">' + response.data.users[i].name + ' - ' + response.data.users[i].employe + '</option>';

									if(response.data.users[i].id == oldid){
										developer += '<option value="' + oldid + '" selected>' + response.data.users[i].name + ' - ' + response.data.users[i].employe + '</option>';
									}

                            } else if (response.data.users[i].employe == 'Lead') {
                                    lead += '<option value="' + response.data.users[i].id + '">' + response.data.users[i].name + ' - ' + response.data.users[i].employe + '</option>';

									if(response.data.users[i].id == oldid){
										lead += '<option value="' + oldid + '" selected>' + response.data.users[i].name + ' - ' + response.data.users[i].employe + '</option>';
									}
                            } else if (response.data.users[i].employe == 'QA Engineer') {
                                    qa += '<option value="' + response.data.users[i].id + '">' + response.data.users[i].name + ' - ' + response.data.users[i].employe + '</option>';

									if(response.data.users[i].id == oldid){
										qa += '<option value="' + oldid + '" selected>' + response.data.users[i].name + ' - ' + response.data.users[i].employe + '</option>';
									}
                            } else {
                                    other += '<option value="' + response.data.users[i].id + '">' + response.data.users[i].name + ' - ' + response.data.users[i].employe + '</option>';

									if(response.data.users[i].id == oldid){
										other += '<option value="' + oldid + '" selected>' + response.data.users[i].name + ' - ' + response.data.users[i].employe + '</option>';
									}
							}
                        }
                        }
                    }

                    lead  += '</optgroup>';
                    developer   += '</optgroup>';
                    qa   += '</optgroup>';
                    other   += '</optgroup>';

                    $("#AssignToId").append(result + lead + developer + qa +  other);
                });
            } else {
                $("#AssignToId").html('');
            }
        }


};
