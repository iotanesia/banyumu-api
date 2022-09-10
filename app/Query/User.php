<?php

namespace App\Query;
use App\Models\User as Model;
use App\ApiHelper as Helper;
use App\Constants\Constants;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Constants\Group;
use App\Mail\ResetPasswordMail;
use App\Models\Auth\UserRole;
use App\Models\Auth\UsersTimAdk;
use Illuminate\Support\Facades\Mail;
use App\Query\Master\MCabangInduk;

class User {

    public static function byUsername($username)
    {
        return Model::where('username',$username)->first();
    }

    public static function getSuperadmin($username)
    {
        return Model::where('username',$username)->first();
    }

    public static function byEmail($email)
    {
        return Model::where('email',$email)->first();
    }
}
