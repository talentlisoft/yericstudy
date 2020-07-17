<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class studentAnswering implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $result_id;
    public $topic_id;
    public $answer;
    public $duration;
    public $status;
    public $training_id;
    public $traineetrainingId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($traineetrainingId, $result_id, $topic_id, $answer, $duration, $status)
    {
        $this->traineetrainingId = $traineetrainingId;
        $this->result_id = $result_id;
        $this->topic_id = $topic_id;
        $this->answer = $answer;
        $this->duration = $duration;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('training.' . $this->traineetrainingId);
    }

    public function broadcastAs()
{
    return 'trainee.answering';
}
}
