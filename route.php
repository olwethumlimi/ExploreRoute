<?php
class Route{
   private static  $_PATH=[];
   private static  $_functions=[];
   private static $_error=[];
   private static $_getUrlParams=[];
   private static $_method_Type=[];
   private static $_removePath=[];
   private static $_trackPath=[];
  

   public static  function css($file){
     
    if(file_exists("static/css")){
        print("<style>");
        require "static/css/".$file;
        print("</style>");
    }
    return "";
       
   }
   public static  function js($file){
    if(file_exists("static/js")){
        print("<script>");
        require "static/js/".$file;
        print("</script>");
    }
    return "";
    
}
   public static  function saveFile($name,$newName,$mimes=["image/jpeg","image/png","application/pdf"],$storage="safestorage"){
    
    if(!isset($_FILES[$name]["tmp_name"])){
        return ["error"=>"no file"];
        exit;
    }
    $uploaded=$_FILES[$name]["tmp_name"];
    $file_name=$_FILES[$name]["name"];
    $f= new finfo(FILEINFO_MIME);
    $ext=$f->file($_FILES[$name]["tmp_name"]);
    $ext_=explode(";",$ext);
    $mime=mime_content_type($uploaded);
    $real_mime=$ext_[0];

 
    
    if(!in_array(strtolower($real_mime),$mimes)){
        return ["error"=>"file not listed","original"=>$ext_[0]];
        exit;
    }
  
    if(strtolower($real_mime)==="text/x-php"){
         return ["error"=>"file not allowed","original"=>$ext_[0]];
         exit;
    }else{
        $extension=pathinfo($file_name,PATHINFO_EXTENSION);
        move_uploaded_file($uploaded,$storage."/".$newName.".".$extension);
        return ["uploaded"=>true];
    }


    //   move_uploaded_file()
   }
    public static  function LoadSafeFile($filePath)
    {

        if(file_exists($filePath))
        {
            $mime=mime_content_type($filePath);
            $type="data:".$mime.";base64,";
        $data=base64_encode(file_get_contents($filePath));
        $imageUrl=$type.$data;
        return $imageUrl;
        }
    }
   public static function Render($filePath, $variables = array(),$errorFile="404.php",$print=true)
   {
    // define("SECURE",1);
    $output = NULL;
    $filePath="templates/".$filePath;
    if(file_exists($filePath)){
        // Extract the variables to a local namespace
        extract($variables);
        

        // Start output buffering
        ob_start();
       
       
        // Include the template file
        include $filePath;

        // End buffering and return its contents
        $output = ob_get_clean();
        
    }
    if ($print) {
        print $output;
    }
  
    return $output;
    exit;

}

   public static function  Add($url,$_function="none",$method="get")
    { 
        Route::$_trackPath[]=$url;
        Route::$_method_Type[]=$method;

        $pure_url=$_SERVER["REQUEST_URI"];

        $file=explode("\\",dirname(__FILE__))[sizeof(explode("\\",dirname(__FILE__)))-1];
        $url_clean=strtolower(str_replace("/$file/","","/".$pure_url));
        

        $regex='/\/{(.*?)}/';
        //match  curly braces from the url
        preg_match_all($regex,$url,$getParams);

        //emove the curly braces from the url
        $_main_url=preg_replace($regex,"",$url);

        //use the lengh of the url and match with the params urls, and fit the value from right to left
        $emp=array_filter(explode("/",$url_clean));



        
        $JoinParams="";
        $values=[];

        


       
        
        $allparams=array_reverse($getParams[1]);
        for ($i=0; $i < sizeof( $allparams); $i++) { 
            $val=isset($emp[sizeof($emp)-$i])?$emp[sizeof($emp)-$i]:"";
            //append and create a path with the params value -> example1/eample2/example3
           $JoinParams.="/".$val;
           //store all the url variables
           $values[$allparams[$i]]=$val;
        }
        
        
    
        $newURLParams="/".join("/",array_reverse(array_filter(explode("/",$JoinParams))));
       
        //check; if we have params 
        if(sizeof($getParams[0])==0){
            
            //store empty params to avoid index error
            Route::$_getUrlParams[]=[];
            Route::$_functions[]=$_function;
            Route::$_PATH[]=strtolower($url);
            
        }else{

            if($getParams[0]>1 and substr( $newURLParams,-1)==="/"){
                
                Route::$_getUrlParams[]=[];
                Route::$_functions[]=$_function;
                Route::$_PATH[]=strtolower($url);
            }else{
               
                Route::$_getUrlParams[]=$values;
                Route::$_functions[]=$_function;
                Route::$_PATH[]=strtolower($_main_url."".$newURLParams);
            }
            

        }
    }
   public static  function Remove($url_remove="")
    {
       Route::$_removePath[]=$url_remove;

       //store the value of the path 
        $pos=array_search($url_remove,Route::$_trackPath);
        if($pos){
            unset(  Route::$_PATH[$pos]);
        }

    }


   public static function error($_function)
    {
 
        Route::$_error[]=$_function;
    }
   public static  function Run()
    {

 
        


        $url=$_SERVER["REQUEST_URI"];

        $file=explode("\\",dirname(__FILE__))[sizeof(explode("\\",dirname(__FILE__)))-1];
        $url_clean=strtolower(str_replace("/$file/","","/".$url));



       $url_trim=substr($url_clean,-1)==="/"?substr($url_clean,0,-1):$url_clean;



        if(!in_array($url_clean,Route::$_PATH)){
            if(sizeof(Route::$_error)==0){
                http_response_code(404);
                print "Not found  ".$url_trim;
                exit;    
            }
            http_response_code(404);
            Route::$_error[0]($url=$url_trim); 
           exit;
            
        }
    
        $pos=array_search($url_clean,Route::$_PATH);

        
        if(strtolower($_SERVER["REQUEST_METHOD"])!=strtolower(Route::$_method_Type[$pos]) ){
            $code=405;

           header("X-PHP-Response-Code: ".$code,true,$code);
           exit;
        }


        if(Route::$_functions[$pos]==="none"){
            exit;
        }else{
            

            $params=Route::$_getUrlParams[$pos];
            Route::$_functions[$pos]($params=$params);
            exit;
        }
        
    
    }
}



