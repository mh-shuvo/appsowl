<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function dashboard()
    {
        return view('Accounts.dashboard');
    }  
    public function user()
    {
        return view('Accounts.user');
    } 
    public function companyinfo()
    {
        return view('Accounts.companyinfo');
    }
    public function accounthead()
    {
        return view('Accounts.accounthead');
    }                                 
}
