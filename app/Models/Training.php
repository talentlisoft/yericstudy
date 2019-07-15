<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $table = 'trainnings';
    public $primaryKey='id';
    public $timestamps = true;
}
