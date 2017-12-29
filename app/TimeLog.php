<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TimeTrack;
use App\Task;

class TimeLog extends Model
{
    protected $table = 'time_log';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'task_id',
        'track_id',
        'start',
        'finish',
		'task_status'
    ];

    public function time_track()
    {
        return $this->belongsTo('App\TimeTrack', 'track_id', 'id');
    }

    public function task()
    {
        return $this->belongsTo('App\Task', 'task_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_id', 'id');
    }

    /*
     * count total time in trask log
     * */
    public function totalTime($data)
    {
        $start = $this::where('id', '=', $data['id'])
            ->select(['track_id', 'start'])
            ->first();

        $data['total_time'] = strtotime($data['finish']) - strtotime($start['start']);

        $this->where('id', '=', $data['id'])
            ->update( $data );

        $this->totalTimeTrack($start['track_id']);

        return ;
    }

    /*
     * count total tim in trask
     * */
    public function totalTimeTrack($id)
    {

        $logs = TimeLog::where('track_id', '=', $id)
            ->get();

        $count = 0;
        foreach( $logs as $log ) {
            $count += $log['attributes']['total_time'];
        }

        $taskId = TimeTrack::where('id', '=', $id)
            ->with('task')
            ->first();

        $projectHourleRate = Task::where('id', '=', $taskId['original']['task_id'])
            ->with('project')
            ->first()['relations']['project']['attributes']['hourly_rate'];

        TimeTrack::where('id', '=', $id)
            ->update([
                'total_time' => (int)$count,
                'value' => (new Task())->value($count, $projectHourleRate),
                'finish_track' => date('Y-m-d H:i:s')
            ]);

        return;
    }
}