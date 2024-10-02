<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $user;
   
    public function __construct()
    { 
        if(isset($this->session)){
            return redirect()->to('/');
        }
        $this->user = session()->get('userData');

    }

    public function index(): string
    {
        $data=[
            'page' => 'Login'
        ];
        return view('forms/login-screen', $data);
    }

    public function landingPage(): string
    {
        $data=[
            'page' => 'Home',
            'user' => $this->user
        ];
        return view('front-end', $data);
    }    
}
