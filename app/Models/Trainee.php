<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainee extends Model
{
    protected $table = 'trainees';
    public $primaryKey='id';
    public $timestamps = true;
}
