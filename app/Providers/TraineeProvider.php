<?php


namespace App\Providers;


use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\Trainee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TraineeProvider implements \Illuminate\Contracts\Auth\UserProvider
{

    /**
     * @inheritDoc
     */
    public function retrieveById($identifier)
    {
        return Trainee::find($identifier);
    }

    /**
     * @inheritDoc
     */
    public function retrieveByToken($identifier, $token)
    {
        $trainee = Trainee::find($identifier);

        return $trainee && $trainee->getRememberToken() && hash_equals($trainee->getRememberToken(), $token)
            ? $trainee : null;
    }

    /**
     * @inheritDoc
     */
    public function updateRememberToken(Authenticatable $trainee, $token)
    {
        DB::table('trainees')
            ->where($trainee->getAuthIdentifierName(), $trainee->getAuthIdentifier())
            ->update([$trainee->getRememberTokenName() => $token]);
    }

    /**
     * @inheritDoc
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))) {
            return null;
        }

        if (array_key_exists('name', $credentials)) {
            return Trainee::where('name', $credentials['name'])->first();
        } else {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function validateCredentials(Authenticatable $trainee, array $credentials)
    {
        return Hash::check(
            $credentials['password'], $trainee->getAuthPassword()
        );
    }
}
