<?php

class Dashboard extends Controller
{
    public function index()
    {
        $this->view('layouts/header');
        $this->view('dashboard');
        $this->view('layouts/footer');
    }
}