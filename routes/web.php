<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => ['permision']], function () {

    // User Controller

    Route::get('/user/create', 'UsersController@create');
    Route::post('/user/create', 'UsersController@create');
    Route::get('/user/delete/{id}', 'UsersController@delete')->where(['id' => '[0-9]+']);
    Route::get('/user/update/{id}', 'UsersController@update')->where(['id' => '[0-9]+']);
    Route::post('/user/update/{id}', 'UsersController@update')->where(['id' => '[0-9]+']);
    Route::get('/user/all', 'TimeManageController@all');
    Route::get('/user/all/{team}', 'TimeManageController@all');
    Route::get('/user/all-json', 'TimeManageController@allUsersJson');

    // TimeManage Controller

    //home
    Route::get('/', 'TimeManageController@index');
    Route::get('/home', 'TimeManageController@index');
	
	//SN 05/25/2017: added below line od code for get lead task
	Route::get('/dashboard', 'TimeManageController@index');
	Route::get('/get/getApproveTask/', 'TimeManageController@getApproveTask');

    // client
    Route::get('/client/create', 'TimeManageController@create_client');
    Route::post('/client/create', 'TimeManageController@create_client');
    Route::get('/client/update/{id}', 'TimeManageController@update_client')->where(['id' => '[0-9]+']);
    Route::post('/client/update/{id}', 'TimeManageController@update_client')->where(['id' => '[0-9]+']);
    Route::get('/client/delete/{id}', 'TimeManageController@delete_client')->where(['id' => '[0-9]+']);
    Route::get('/client/all', 'TimeManageController@all_client');

    // project
    Route::get('/project/create', 'TimeManageController@create_project');
    Route::post('/project/create', 'TimeManageController@create_project');
    Route::get('/project/update/{id}', 'TimeManageController@update_project')->where(['id' => '[0-9]+']);
    Route::post('/project/update/{id}', 'TimeManageController@update_project')->where(['id' => '[0-9]+']);
    Route::get('/project/delete/{id}', 'TimeManageController@delete_project')->where(['id' => '[0-9]+']);
    Route::get('/project/all', 'TimeManageController@all_project');
    Route::get('/project/getProjects/{client_id}', 'TimeManageController@getProjects');
    Route::get('/client/projects/{id}', 'TimeManageController@client_projects')->where(['id' => '[0-9]+']);

    //task
    Route::get('/task/create', 'TimeManageController@create_task');
    Route::post('/task/create', 'TimeManageController@create_task');
    Route::get('/task/update/{id}', 'TimeManageController@update_task')->where(['id' => '[0-9]+']);
    Route::post('/task/update/{id}', 'TimeManageController@update_task')->where(['id' => '[0-9]+']);
    Route::get('/task/delete/{id}', 'TimeManageController@delete_task')->where(['id' => '[0-9]+']);
    Route::get('/get/team/{id}', 'TimeManageController@get_team')->where(['id' => '[0-9]+']);
    Route::get('/get/get-users/', 'TimeManageController@get_users');

    //task type
    Route::get('/task-type/all/{msg?}/{theme?}', 'TimeManageController@all_task_types');
    Route::get('/tasktype/delete/{id}', 'TimeManageController@delete_task_type')->where(['id' => '[0-9]+']);
    Route::get('/task-type/create', 'TimeManageController@create_task_type');
    Route::post('/task-type/create', 'TimeManageController@create_task_type');
    Route::get('/task-type/update/{id}', 'TimeManageController@update_task_type')->where(['id' => '[0-9]+']);
    Route::post('/task-type/update/{id}', 'TimeManageController@update_task_type')->where(['id' => '[0-9]+']);

    //Route::get('/task/all/{msg?}/{theme?}', 'TimeManageController@all_tasks');
    Route::get('/task/all/{dateStart?}/{dateFinish?}', 'TimeManageController@all_tasks');
	
    Route::get('/project/tasks/{id}', 'TimeManageController@get_project_tasks')->where(['id' => '[0-9]+']);
    Route::get('/client/tasks/{id}', 'TimeManageController@get_client_tasks')->where(['id' => '[0-9]+']);
    Route::get('/task/done/{id}', 'TimeManageController@taskDone')->where(['id' => '[0-9]+']);
    Route::get('/task/start/{id}', 'TimeManageController@taskReturnToWork')->where(['id' => '[0-9]+']);

    // team
    Route::get('/team/create/', 'TimeManageController@create_team');
    Route::post('/team/create', 'TimeManageController@create_team');
    Route::get('/team/delete/{id}', 'TimeManageController@delete_team')->where(['id' => '[0-9]+']);
    Route::get('/team/all', 'TimeManageController@team_all');
	Route::get('/team/update/{id}', 'TimeManageController@update_team')->where(['id' => '[0-9]+']);
    Route::post('/team/update/{id?}', 'TimeManageController@update_team')->where(['id' => '[0-9]+']);

    //TimeTrack controller

    //trecking time & trecking log
    Route::get('/tracking/{date?}', 'TimeTrackController@trecking');
    Route::post('/tracking/{date?}', 'TimeTrackController@trecking');
    Route::post('/create/timelog/{id?}', 'TimeTrackController@create_time_log')->where(['id' => '[0-9]+']);
    Route::get('/tasks/get/{project_id}', 'TimeTrackController@getTasks')->where(['id' => '[0-9]+']);
    Route::get('/trecking/getTime', 'TimeTrackController@getTimeNow');
    Route::get('/trecking-getTime', 'TimeTrackController@getTimeNow');
    Route::get('/track/all', 'TimeTrackController@all_track');
    Route::get('/track/update/{id}', 'TimeTrackController@update_track')->where(['id' => '[0-9]+']);
    Route::post('/track/update/{id}', 'TimeTrackController@update_track')->where(['id' => '[0-9]+']);
    Route::get('/track/delete/{id}', 'TimeTrackController@delete_track')->where(['id' => '[0-9]+']);
    Route::get('/track-getTimeLogById/{id}/{trackDate?}', 'TimeTrackController@getTimeLogById')->where(['id' => '[0-9]+']);
    Route::get('/log/delete/{id}', 'TimeTrackController@deleteTraskLog')->where(['id' => '[0-9]+']);
    Route::get('/task/approve/{id}', 'TimeTrackController@approveTask')->where(['id' => '[0-9]+']);
	Route::post('/task/alltaskapprove/', 'TimeTrackController@allTaskApproveTask');
    //Mith 05/30/2017 : added above for group task approvel.
	
	//SN 04/21/2017 : commented and updated below line of code
	Route::post('/trask/reject/{id}', 'TimeTrackController@rejectTrask')->where(['id' => '[0-9]+']);
		
	Route::get('/trask/done/{id}', 'TimeTrackController@trackDone')->where(['id' => '[0-9]+']);
    Route::get('/trask/start/{id}', 'TimeTrackController@trackReturnToWork')->where(['id' => '[0-9]+']);
    Route::get('/track/getdesckription/{id}', 'TimeTrackController@getTaskDescription')->where(['id' => '[0-9]+']);

	// TaskStatus

	Route::get('/task-status/all/', 'TimeManageController@all_task_status');
	Route::get('/task-status/create/', 'TimeManageController@create_task_status');
	Route::post('/task-status/create/', 'TimeManageController@create_task_status');
	Route::get('/taskstatus/delete/{id}', 'TimeManageController@delete_task_status')->where(['id' => '[0-9]+']);
	Route::post('/taskstatus/update/{id}', 'TimeManageController@update_task_status')->where(['id' => '[0-9]+']);
	Route::get('/taskstatus/update/{id}', 'TimeManageController@update_task_status')->where(['id' => '[0-9]+']);

	//SN 05/31/2017: added route of task archive
	//Route::get('/task/archive', 'TimeTrackController@all_archive');
    Route::get('/task/archive/{dateStart?}/{dateFinish?}', 'TimeTrackController@all_archive');
    // ReportsController

    // reports
	//Mith: 07/14/17: added for status report.
    Route::post('/reports/status/{day?}', 'ReportsController@sendStatusReport');
	Route::get('/reports/status/{day?}', 'ReportsController@statusReport');
    //Mith: 04/13/17: added export key work for check for excel creation.
	Route::get('/reports/daily/{day?}/{export?}', 'ReportsController@dailyReport');
    Route::get('/reports/project/{dateStart?}/{dateFinish?}/{projectId?}/{export?}', 'ReportsController@projectReport');
    Route::get('/reports/people/{dateStart?}/{dateFinish?}/{userId?}/{export?}', 'ReportsController@peopleReport');
	//Mith: 05/10/17: added route for email generation reports.
    Route::get('/reports/emailproject/{dateStart?}/{dateFinish?}/{projectId?}/{export?}', 'ReportsController@mailProjectReport');
	Route::get('/reports/performance/{dateStart?}/{dateFinish?}/{leadId?}', 'ReportsController@performanceReport');
	
	//SN 07/13/2017: added below route to send email
	Route::post('/reports/sendemailcontent', 'ReportsController@sendemailcontent');

	//SN 06/15/2017: added below path for save column update
	Route::post('/reports/setupdatecolumn', 'ReportsController@setupdatecolumn');
	
	//SN 06/15/2017: added below path for get column update
	Route::get('/reports/getcolumnupdate/', 'ReportsController@getcolumnupdate');
	
	//SN 09/19/2017 : created for email send to user to less 40 hrs. added this route
	Route::post('/leave/create', 'TimeManageController@setleavetask'); 
	Route::get('/user/report', 'ReportsController@sendUserReport');  
	
    // for ajax
    Route::get('/get/timestart/{id}', 'TimeTrackController@getTimeStartLogById');

    // forbidden
    Route::get('/register', 'TimeManageController@index');
    Route::get('/user/logout', 'TimeManageController@logout');

    //testing
    Route::get('/test', 'TimeTrackController@test');
});

// Auth
Auth::routes();

// google login

Route::get('auth/google', 'Auth\LoginController@redirectToProvider');
Route::get('auth/google/callback', 'Auth\LoginController@handleProviderCallback');
//Route::get('auth/google/callback', 'TestController@test');
