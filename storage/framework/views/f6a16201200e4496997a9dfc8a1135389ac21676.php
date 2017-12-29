<?php $__env->startSection('content'); ?>
    <?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
    <script type="text/javascript" src="/data/daterangepicker.js" xmlns="http://www.w3.org/1999/html"></script>
    <link rel="stylesheet" type="text/css" href="/data/daterangepicker.css" />

    <div class="modal fade" id="delete-track" role="dialog">
        <div class="modal-dialog"  >
            <!-- Modal content-->
            <div class="modal-content">
                <div id="modalConfirmDeleteTrack"></div>
            </div>
        </div>
    </div>



    <div id="conteiner" class="container" data-date="<?= isset($date)? $date : '' ?>"
         data-status="<?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?>"
         data-token="<?php echo e(Session::token()); ?>"
            data-log-active = "<?= isset($_COOKIE['logTrackActiveLogId']) ? $_COOKIE['logTrackActiveLogId'] : ''?>">

        <div class="row" style="margin-top: 20px">
            <div class="col-md-2 btn-toolbar" style="vertical-align: inherit">
                <div id="timeStep5" class="btn-group">
                    <button class="btn btn-sm calendarPrevDay">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </button>
                    <button class="btn btn-sm calendarNextDay">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </button>
                    <button class="btn btn-sm d4">
                        <span class="glyphicon glyphicon-th"></span>
                    </button>

                </div>
            </div>

            <h2  class="col-md-10 showDate"  id="timeTrackShowDate"></h2>
        </div>
        <div class="row" style="margin-top: 20px;     color: #999; font-size: 18px">
            <div class="col-md-6" >
                Add track log
            </div>
            <div class="col-md-6">
                Tracked
            </div>
        </div>


        <div class="row" style=" border-top: 1px solid #ccc;">

            <div class="col-sm-6 col-md-6 col-lg-6" style="border-right: 1px solid #ccc; padding-top: 20px">

                <form  method="POST" action="<?php (isset( $track )) ? '/track/update/' . $track[0]->id : '/trecking' ;?>" id="addTrackForm">
                    <?php echo e(csrf_field()); ?>

                    <div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                        <div class="col-xs-2 col-sm-4 col-md-3 col-lg-3" style="text-align: right;">
                            <label class="control-label labelTrack" for="trackProjectId">Project *</label>
                        </div>
                        <div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">

                                <select name="project_id" class="inputTrackPadding focused my_input"  id="trackProjectId" style="height: 35px;" required>
                                    <option selected disabled value="">Select project</option>

                                    <?php if( old() && old('project_id') &&  $projects ): ?>
                                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                           <?php if( $project['id'] && $project['id'] == old('project_id') ): ?>
                                               <option value="<?php echo e(old('project_id')); ?>" selected><?php echo e($project['project_name']); ?></option>
                                           <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                    <?php endif; ?>

                                    <?php if( isset( $track ) ): ?>
                                        <option value="<?php echo e($track[0]->project->id); ?>" selected><?php echo e($track[0]->project->project_name); ?></option>
                                    <?php endif; ?>
                                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                        <option value="<?php echo e($task->id); ?>"><?php echo e($task->project_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                </select>
                                <?php if($errors->has('project_id')): ?>
                                    <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('project_id')); ?></strong>
                                            </span>
                                <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                        <div class="col-xs-2 col-sm-4 col-md-3 col-lg-3" style="text-align: right;">
                            <label class="control-label labelTrack" for="trackTaskId">Task *</label>
                        </div>
                        <div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">

                            <select name="task_id" class="inputTrackPadding focused my_input"  id="trackTaskId"  style="height: 35px;" required >

                                <?php if( old() && old('task_id') &&  $projects ): ?>
                                    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                        <?php if( $project['id'] && $project['id'] == old('project_id') ): ?>
                                            <?php $__currentLoopData = $project['task']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                <?php if( isset($task['id']) && $task['id'] == old('task_id') ): ?>
                                                    <option value="<?php echo e(old('task_id')); ?>" selected><?php echo e($task['task_titly']); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                <?php endif; ?>

                                <?php if( isset( $track ) ): ?>
                                    <option value="<?php echo e($track[0]->task->id); ?>" selected><?php echo e($track[0]->task->task_titly); ?></option>
                                <?php endif; ?>
                            </select>
                            <?php if($errors->has('task_id')): ?>
                                <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('task_id')); ?></strong>
                                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                        <div class="col-xs-2 col-sm-4 col-md-3 col-lg-3" style="text-align: right;">
                            <label class="control-label labelTrack" for="formTrackStart" >Start</label>
                        </div>

                        <div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">
                            <div class="col-md-4 col-lg-4" style="padding: 0px">
                            <span class="input-group" >
                                <input type="text" value="<?= ( isset($data['start']) ) ? $data['start'] : ((old() && old('date_start') ? ltrim(explode(':', explode(' ', old('date_start'))[4])[0], '0') : '')) ; ?>"
                                       style="width: 60%" class="inputTrackPadding form-control" id="formTrackStart" placeholder="HH:MM"/>

                                <span class="input-group-btn" style=" float:left ">
                                    <button type="button" class="btn btn-default" id="formTrackStartNow" style="padding:6px 1px">now</button>
                                    <button type="button" class="btn btn-default" id="formTrackStartInc" style="padding:6px 3px">+</button>
                                    <button type="button" class="btn btn-default" id="formTrackStartDec" style="padding:6px 3px">-</button>
                                </span>
                            </span>
                            </div>

                            <div class="col-sm-12 col-md-2 col-lg-2" style="padding: 0px; text-align: center;">
                                <label class="labelTrack" for="formTrackFinish">Finish</label>
                            </div>

                            <div class="col-md-4 col-lg-4" style="padding: 0px">
                            <span class="input-group" >
                                <input type="text" value="<?= ( isset( $data['finish'] ) ) ? $data['finish'] : ((old() && old('date_finish') ? ltrim(explode(':', explode(' ', old('date_finish'))[4])[0], '0') : '')) ; ?>"
                                       style="width: 60%; " class="inputTrackPadding form-control"  id="formTrackFinish" placeholder="HH:MM">
                                <span class="input-group-btn" style=" float:left ">
                                    <button type="button" class="btn btn-default" id="formTrackFinishNow" style="padding:6px 1px">now</button>
                                    <button type="button" class="btn btn-default" id="formTrackFinishInc" style="padding:6px 3px">+</button>
                                    <button type="button" class="btn btn-default" id="formTrackFinishDec" style="padding:6px 3px">-</button>
                                </span>
                            </span>
                            </div>

                            <input id="formTrackStartSend"  type="hidden" name="date_start">
                            <input id="formTrackFinishSend" type="hidden" name="date_finish" >
                            <input id="formTrackDate"  type="hidden" name="track_date" value="<?= isset($date) ?  $date : ''?>" >
                            <input id="formTrackDuration" type="hidden" name="date_duration" >



                            <div class="col-md-2 col-lg-2" style="padding: 0px">
                               <span class="" style="display: inline-block">
                                <label class = "labelTrack">
                                    <input type="checkbox" id="nextDay" name="nextDate"
                                           <?php  (isset( $track)) ? $duration = explode(":", $track[0]->duration) : ''; ?>
                                    <?= isset($track)  ? (floor((strtotime( $track[0]->date_finish) - strtotime( $track[0]->date_start)) / (60 * 60 * 24)) == 1 ||  $duration[0] >  24 ? 'checked' : '' ) :
                                                   ((old() && old('nextDate') == 'on') ? ' checked': '' ) ?>
                                            > Next Day
                                </label>
                             </span>
                            </div>
                            <?php if($errors->has('date_start')): ?>
                                <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('date_start')); ?></strong>
                                            </span>
                            <?php endif; ?>
                            <?php if($errors->has('date_finish')): ?>
                                <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('date_finish')); ?></strong>
                                            </span>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                        <div class="col-xs-2 col-sm-4 col-md-3 col-lg-3" style="text-align: right;">
                            <label class="control-label labelTrack" for="timeDuration" style="text-align: left; padding-top: 10px">Duration *</label>
                        </div>
                        <div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">
                            <input type="text" style="padding: 10px; max-width: 65%;" required  class="inputTrackPadding focused my_input" name="duration" id="timeDuration" placeholder="HH:MM"
                                    value="<?= ( isset( $track ) ) ? $track[0]->duration : ((old() && old('duration')) ? old('duration') : '') ; ?>"
                                   pattern="(0[0-9]|1[0-9]|2[0-9])(:[0-5][0-9]){1}"
                                    title="Please match the requested format HH:MM"/>
                            <button class="btn btn-default btn-small"  id="resetTime" style="padding-bottom: 4px; padding-top: 5px; margin-bottom: 2px;"><span class="glyphicon glyphicon-repeat" ></span></button>


                            <label class="labelTrack" for="" style="padding-top: 10px">Value($) <span id="insertCost"></span></label>
                            <?php if($errors->has('duration')): ?>
                                <span class="help-block">
                                    <strong style="color:#802420"><?php echo e($errors->first('duration')); ?></strong>
                                    </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                        <div class="col-xs-2 col-sm-4 col-md-3 col-lg-3" style="text-align: right;">
                            <label class="control-label labelTrack" for="additionalCost">Additional Cost</label>
                        </div>
                        <div class="controls col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="input-group">
                                <div class="input-group-btn" >
                                    <input value="<?= ( isset($track) ) ? $track[0]->additional_cost : ((old() && old('additional_cost')) ? old('additional_cost') : '') ; ?>"
                                           type="number" steep="0.01" style="padding: 10px; max-width: 89%" class="inputTrackPadding focused my_input form-control " name="additional_cost" id="additionalCost">
                                    <span class="input-group-addon" style="padding: 9px 12px">$</span>
                                </div>
                            </div>
                            <?php if($errors->has('additional_cost')): ?>
                                <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('additional_cost')); ?></strong>
                                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="controls col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <span class="" style="display: inline-block">
                            <label  class="labelTrack" for="billableTime">
                               Billable Time <input type="checkbox" name="billable_time" value="1" id="billableTime"
                                <?= ( isset( $track ) && $track[0]->billable_time == 1 ) ? ' checked' : ((old('billable_time') == '1') ? ' checked': '' ) ;?>>
                            </label>
                         </span>
                        </div>

                    </div>

                    <div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                        <div class="col-xs-2 col-sm-4 col-md-3 col-lg-3" style="text-align: right;">
                            <label class="control-label labelTrack" for="trackDescription">Description</label>
                        </div>
                        <div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9">
                           <textarea class="inputTrackPadding focused my_input"
                                     rows="7" name="description" id="trackDescription"><?=
                               ( old('description') ) ? old('description') : ( isset($track)  ? $track[0]->description : '')  ?></textarea>
                        </div>
                        <?php if($errors->has('description')): ?>
                            <span class="help-block">
                                                <strong style="color:#802420"><?php echo e($errors->first('description')); ?></strong>
                                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group form-group-edit col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                        <div class="col-xs-2 col-sm-4 col-md-3 col-lg-3" style="text-align: right; margin-top: 20px">
                            <label class="control-label labelTrack" for=""></label>
                        </div>
                        <div class="controls col-xs-12 col-sm-8 col-md-9 col-lg-9"  style="margin-top: 20px">
                          <button type="submit" id="" class="btn button-orange">Submit</button>
                        </div>
                    </div>


                </form>


            </div>
            <div class="col-sm-6 col-md-6 col-lg-6"  style="border-right: 1px solid #ccc; padding-top: 20px">

                <table class="col-md-12 trackLogTable" class="display" id="trackLogTableId">

                    <tbody>
                    <?php $__currentLoopData = $tracks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <?php
                        $totalTime = '';
                        if($key->total_time != null){

                            $hours = floor($key->total_time / 3600);
                            $minutes = floor(($key->total_time / 60) % 60);
                            $seconds = $key->total_time % 60;

                            $totalTime =  "$hours:$minutes:$seconds";
                        }
                        ?>

                        <tr class="trackLog trackLogFirst <?= $key->approve == 1 ? 'done_tr' : ($key->done == 1 ? 'done_tr2' : '')?>" id="track-<?= $key->id ?>"
                            data-id ="<?= $key->id ?>"
                            data-project_name ="<?= $key->project->project_name  ?>"
                            data-project_id ="<?= $key->project->id  ?>"
                            data-task_titly ="<?= $key->task->task_titly ?>"
                            data-task_id ="<?= $key->task->id ?>"
                            data-total_time ="<?= ($key->total_time == null) ? '00:00:00' : date('H:i:s', strtotime($totalTime))  ?>"
                            data-duration ="<?= ($key->duration == null) ? '00:00' : date('H:i',  mktime(0,$key->duration)) ?>"
                            data-date_start ="<?= date('H:i', strtotime($key->date_start))?>"
                            data-date_start = "<?= date('H:i', strtotime($key->date_finish)) ?>">
                            <td>
                                <a href="#"  class="showTimelog"> <span class="glyphicon glyphicon-plus"></span>
                                </a>

                                <a href="#"  class="hideTimelog" style="display: none;"> <span class="glyphicon glyphicon-minus" ></span>
                                </a>
                            </td>
                            <td class="">
                                <span class="ng-binding"></span>
                                <p class="projecttask"> - <?php echo e($key->project->project_name); ?> - <?php echo e($key->task->task_titly); ?></p>
                            </td>
                            <td class="text-right">
                                <h3 id="timeTrackSegmentTotal"
                                    class="timeTrackSegmentTotal <?= isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id ? 'timeTrackSegmentTotalActive' : '' ?>"
                                    style="margin: 7px 0px "
                                    data-total="<?= date('H:i:s', strtotime($totalTime)) ?>">
                                        <?php echo e(($key->total_time == null) ? '00:00:00' : date('H:i:s', strtotime($totalTime))); ?>

                                </h3>
                                <?php if($key->date_start == null || $key->date_start == null): ?>
                                    <?php  $hours = (int)($key->duration/60);
                                   $minutes = bcmod($key->duration, 60);
                                            if (strlen($hours) < 2){
                                                $hours = '0' . $hours;
                                            }
                                            if (strlen($minutes) < 2){
                                                $minutes = '0' . $minutes;
                                            }
                                    ?>
                                    <p class="project" >  <?php echo e(($key->duration == null) ? '00:00' : $hours . ':' . $minutes); ?></p>
                                <?php else: ?>
                                     <p class="project" > <?php echo e(date('H:i', strtotime($key->date_start))); ?> - <?php echo e(date('H:i', strtotime($key->date_finish))); ?></p>
                                <?php endif; ?>
                            </td>
                            <td class="text-right table-cell-actions">
                                <div class="btn-group">
                                    <span class="stop-start-button">
                                        <?php if($key->done == 0): ?>
                                             <a  href='/trask/done/<?= $key->id ?>'  class="btn btn-success" id="doneTrack" style="<?= isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id ? 'display:none' : '' ?>" >
                                                 <span class="glyphicon glyphicon-ok"></span> Done
                                             </a>
                                        <?php else: ?>
                                            <a  href='/trask/start/<?= $key->id ?>'  class="btn btn-warning" id="doneReject" style="<?= isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id ? 'display:none' : '' ?>" >
                                                <span class="glyphicon glyphicon-ok"></span> In process
                                            </a>
                                        <?php endif; ?>
                                        <?php if($key->done == 0): ?>
                                            <button class="btn btn-default" id="startTrack" style="<?= isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id ? 'display:none' : '' ?>" >
                                                <span class="glyphicon glyphicon-play"></span>
                                            </button>
                                        <?php endif; ?>
                                        <button href="#" class="btn btn-danger" id="stopTrack2"  style="<?= isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id ? '' : 'display:none' ?>">
                                            <span class="glyphicon glyphicon-stop"></span>
                                        </button>
                                        <span class="addTrackFinishForm">
                                        <?php if(isset($_COOKIE['logTrackActiveTrackId']) && $_COOKIE['logTrackActiveTrackId'] == $key->id): ?>
                                            <form id="stop-form-track" action="/create/timelog/" method="POST" style="display: none;">
                                                <?php echo e(csrf_field()); ?>

                                                <input type="hidden" id="stop-form-track-id" name="id" value="<?=  $_COOKIE['logTrackActiveLogId'] ?>">
                                             </form>
                                        <?php endif; ?>
                                        </span>

                                    </span>
                                    <span>
                                    <?php if($key->done == 0): ?>
                                        <a href="/track/update/<?= $key->id ?>" class="btn btn-default" id="editTrack">
                                            <span class="glyphicon glyphicon-pencil span_no_event"></span>
                                        </a>
                                    <?php endif; ?>

                                         <button type="button" class="btn btn-default deleteTrack" data-url="/track/delete/<?php echo e($key->id); ?>" data-element="<?php echo e($key->project->project_name); ?> - <?php echo e($key->task->task_titly); ?>">
                                             <span class="glyphicon glyphicon-trash span_no_event" aria-hidden="true"></span></button>

                                        </span>
                                </div>
                            </td>
                        </tr>

                        <tr  style="display:none"  id ="add-<?= $key->id ?>">


                            <td colspan="4" style="    padding-left: 30px;">
                            <table width="100%">


                            </table>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                   </tbody>
                </table>



            </div>



</div>
    </div>
<!--    <script src="/js/jquery/jquery-3.1.1.min.js"></script>-->
    <script src="/js/tasks.js"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.index_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>