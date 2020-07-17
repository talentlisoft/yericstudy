<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class Trainee extends Model implements Authenticatable
{
    protected $table = 'trainees';
    public $primaryKey='id';
    public $timestamps = true;

    protected $rememberTokenName = 'remember_token';

    // Fields
//    public $password;

    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    public function getRememberTokenName()
    {
        return $this->rememberTokenName;
    }
}
