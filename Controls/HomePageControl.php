<?php


class HomePageControl
{
    public function index(){
        readfile("public/index.html");
    }
}