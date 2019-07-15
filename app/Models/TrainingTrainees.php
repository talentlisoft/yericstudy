<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTrainees extends Model
{
    protected $table = 'trainee_trainings';
    public $primaryKey='id';
    public $timestamps = true;
}
