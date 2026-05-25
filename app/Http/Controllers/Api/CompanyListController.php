<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyListController extends Controller
{
    //
       public function getCompanyList()
    {
        $users = Company::get();

        return response()->json([
            'success' => true,
            'count'   => $users->count(),
            'data'    => $users
        ]);
    }
}
