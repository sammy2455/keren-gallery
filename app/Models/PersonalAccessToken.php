<?php

namespace App\Models;

use App\Traits\Uuid;
use Laravel\Sanctum\PersonalAccessToken as AccessToken;

class PersonalAccessToken extends AccessToken
{
    use Uuid;

    protected $table = 'personal_access_tokens';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public $incrementing = false;

}
