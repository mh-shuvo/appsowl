<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentAdminController extends Controller
{
    public function dashboard()
    {
        return view('AgentAdmin.dashboard');
    }  
    public function user()
    {
        return view('AgentAdmin.user');
    } 
    public function companyinfo()
    {
        return view('AgentAdmin.companyinfo');
    }
    public function accounthead()
    {
        return view('AgentAdmin.accounthead');
    }
    public function create_user()
    {
        return view('AgentAdmin.create_user');
    } 
    public function application()
    {
        return view('AgentAdmin.application');
    } 
    public function profile()
    {
        return view('AgentAdmin.profile');
    } 
    public function user_info()
    {
        return view('AgentAdmin.user_info');
    } 
    public function receive_payment()
    {
        return view('AgentAdmin.receive_payment');
    } 
    public function withdrawal_payment()
    {
        return view('AgentAdmin.withdrawal_payment');
    } 
    public function support()
    {
        return view('AgentAdmin.support');
    } 
    public function history()
    {
        return view('AgentAdmin.history');
    } 
    public function user_payment_history()
    {
        return view('AgentAdmin.user_payment_history');
    }

}
