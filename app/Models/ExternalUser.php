<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ExternalUser extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';  // 2nd DB
    protected $table      = 'users';
    public    $timestamps = false;

  
 

    public static function getUsersWithCompanyAndDepartment()
        {
             Session::put('company_id',3);
             $user = Auth::user();
           //dd($user->department_id);
            return self::select(
                    'users.emp_id',
                    'users.first_name',
                    'users.middle_name',
                    'users.last_name',
                    'users.name',
                    'users.email',
                    'oms_companies.code',
                    'oms_departments.department'
                )
                ->leftJoin('oms_companies', 'oms_companies.id', '=', 'users.company_id')
                ->leftJoin('oms_departments', 'oms_departments.id', '=', 'users.department_id')
                ->orderBy('users.first_name')
                ->where('users.status', 1)
                ->where('oms_departments.department', 'like', '%sales%')
                ->where('users.company_id', session('company_id'))
                ->where('oms_departments.id', $user->department_id) 
                ->where('users.group_id', $user->group_id) 
                ->get();
        }
}