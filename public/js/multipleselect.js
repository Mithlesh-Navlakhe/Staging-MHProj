 
'use strict';

$('#tousers').multiselect({
  columns: 1,
  placeholder: 'Select Users',
  search: true
});

$('#ccusers').multiselect({
  columns: 1,
  placeholder: 'Select Users',
  search: true
});

$('#projects').multiselect({
  columns: 1,
  placeholder: 'Select Projects',
  search: true
});

//SN 05/04/2017: added code
$('.ms-options-wrap > button').css({
		'overflow': 'hidden',
		'text-overflow': 'ellipsis',
		'white-space': 'nowrap'
		//'width': '91.33% !important',
		//'height':'42px'
  });
//$('.ms-options-wrap').css('width','91.33%');
$('.ms-options-wrap > button').addClass("selectbutton input-height").css('border','1px solid #CCC !important');
$("ul > li > label").css({'padding-left':'25px', 'font-size':'15px'});
$("ul > li > label > input").css('margin-left','5px');