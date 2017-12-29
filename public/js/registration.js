'use strict';
$(document).ready(function(){
    Registration.display($('#conteiner').data('status'));
    $('#selectTeam').change(function() {
        $( "#selectTeam option:selected" ).each(function() {
            Registration.selectTeam($( this ).text());
        });
    });
    $('#selectTeam').change(function() {
        $( "#selectTeam option:selected" ).each(function() {
            Registration.hourlyRate($( this ).text());
        });
    });

    $( "#selectTeam option:selected" ).each(function() {
        Registration.hourlyRate($( this ).text());
        Registration.selectTeam($(this).text());
    });
});

var Registration = {
    display: function($status) {
        if( $status == 'HR Manager' ) {
            //$('#team_name').show();
        }
    },

    selectTeam: function(status){
        if(status == 'Lead' || status == 'Developer') {
            //Mith: 05/03/2017: show project field on select of developer.
            if(status == 'Developer'){
                setTimeout(function(){
                  $('#project_name').show();
                  $('#lead_name').show();
                  //$('#team_name').hide();
                }, 500);	
            }else {
                $('#project_name').hide();
                $('#lead_name').hide();
                //$('#team_name').show();
            }
        } else {
            //$('#team_name').hide();
            $('#project_name').hide();
            $('#lead_name').hide();
        }
    },

    hourlyRate:function(status){
        //if((status == 'Lead' || status == 'Developer' || status == 'QA Engineer' || status == 'Supervisor') && $('#conteiner').data('status') == 'Admin') {
		if((status == 'Lead' || status == 'Developer' || status == 'QA Engineer' || status == 'Supervisor')) {	
            $('#hourlyRate').show();
        } else {
            $('#hourlyRate').hide();
            //$('#project_name').hide();
            //$('#lead_name').hide();
        }
    },
};
