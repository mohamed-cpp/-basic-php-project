<?php


class NamesControl
{

    public function index(){
        //echo $_GET['id'];
        echo "asdasd";
    }
    public function show($id){
        //echo $_GET['id'];
        echo $id;
    }
    public function create($name){
        //echo $_GET['id'];
        echo json_encode(["created2",$name]);
    }

}