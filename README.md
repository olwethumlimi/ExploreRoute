# THIS IS A SIMPLE ROUTING SYSTEM

where all folder and files are secured
you can access files via secured function

## How does it work? i'll start with the folders

<ol>
    <li>the static fo store your css and js files and static images</li>
    <li>the templates will store your php files</li>
    <li>safestorage will store  files such as user data</li>
</ol>

## IMOPRT IT  BY USING

  <mark>
    require './route.php';
  $route = new Route();

  </mark>
## Now the look at the functions

<b>Add(PATH ,FUNCTION,METHOD="GET")</b>
    <ol>
        <li>the defualt method is GET</li>
        <li>to use ## PARAMS # use the curly braces and wrap it with the id eg /student/{id}</li>
        <li> $params -> return the params</li>  
    </ol>

<b>.Render(FILE_NAME_TO_RENDER,DICTIONARY)</b>
     - FILE_NAME_TO_RENDER -> index.php , home.php , about.php
     -DICTIONARY -> this are the value you wanna pass to the file -> ["name"=>"easyRoute"]

$route::Add("/",function($params){
    $route::Render('home.php', ["name"=>"easyRoute"]);
});

## Note don't include and local css or use you can load it from a url

to load css or js local  use  function

js(Filename)
css(filename)

$route::Add("/",function($params){
    global $route;
    $route::css("foundation.css");
    $route::css("form.css");
    $route::js("jquery.js");

});

## to loadfiles use

LoadSafeFile("path/File")

$route::Add("/",function($params){
    global $route;
    $file=$route::LoadSafeFile("safestorage/file.jpg");
});

## PAGE NOT FOUND USE THIS FUNCTION

$route::error(function($url){
    global $route;    
    $route::Render('404.php',['file' => ""]);
})

## To run routes use

.Run()

## To remove routes use

 $route->Remove("/")
 $route->Remove("/home")
 $route->Remove("/home/{id}")

## To upload files use

 saveFile(OBJECTNAME, SET_FILENAME)
 use  the saveFile -> this return a res as Upload or Error

 $route::Add("/upload",function($params){
    global $route;
     $res=$route::saveFile("this","neljkdkldlkdlkwName");
     print_r($res);
},"post");


##  <h1>  </h1>