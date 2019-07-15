<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topics extends Model
{
    protected $table = 'topics';
    public $primaryKey='id';
    public $timestamps = true;
}
