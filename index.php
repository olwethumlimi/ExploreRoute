<?php

use Rakit\Validation\Rules\Required;

session_start();
require './route.php';



$route = new Route();

$route::Add("/",function($params){
    global $route;
    // //load image  from  this string since all files are secured
   // $file=$route::LoadSafeFile("safestorage/file.jpg");

    //load css and js
    $route::css("foundation.css");
    $route::js("jquery.js");
    $route::Render('upload.php');
    
   
});



$route::Add("/upload",function($params){
    global $route;
    ///save file from input
                    //this ->is the file name i declared;
                    //nameName -> is the name of the file

     $res=$route::saveFile("this","neljkdkldlkdlkwName");
     print_r($res);

  //method default is get
},"post");


// you can pass params
$route::Add("/home/{id}",function($params){
    print_r($params);
 });
 


$route::error(function($url){
   
    global $route;
//    $file=$route::LoadSafeFile("safestorage/file.jpg");
    
    $route::Render('404.php',['url' => $url]);
   
    
});

// $route->Remove("/home/{id}");




$route->Run();
