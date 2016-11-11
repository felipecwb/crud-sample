<?php

namespace CrudSample\Http;

class Home extends Controller
{
    public function index()
    {
        return $this->getTwig()->render('home.html');
    }
}
