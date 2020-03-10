<?php


class Error404 extends AbstractController
{

    public function index()
    {
        return Router::render('layouts/error');
    }
}