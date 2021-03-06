<?php
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE); 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

//ini_set('display_errors', '1');
require_once ('libs/db-tools.php');

//--- include api files ----
require_once ('functions/api-logins.php');
require_once ('functions/api-users.php');
require_once ('functions/api-lists.php');
require_once ('functions/api-asmt.php');

$db1=array('host'=>'localhost','user'=>'root','pass'=>'','db'=>'cre_wellness');

// $db1=array('host'=>'124.135.353.192','user'=>'root','pass'=>'yzfkodhtntxa','db'=>'crescente20');
$conn1=db_connect($db1);
db_switch('cre_wellness');

//Optional (maybe not used anywhere, who knows :))
$img_root="";

$method = $_SERVER['REQUEST_METHOD'];

$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

//Define the API parameters
$endpoint=$request[0];
$action=$request[1];
// $par1=$request[2];

if($method=="POST")
{
	 $postdata = file_get_contents("php://input"); //We need to use this instead of $_POST because $http sends a application/json header and PHP doesn't know	
	 $passedObject = json_decode($postdata, true);
     
     
     //echo ($postdata);die;
    
	 
}

if($method=="GET")
{
	 //$postdata = file_get_contents("php://input"); 
	 //$passedObject = json_decode($postdata, true);

	 $passedObject =  $_REQUEST;

}

switch($method) 
{
  
  case 'POST':
	 
    $resultArr=rest_post($endpoint,$action,$passedObject); 
	// echo "OK";
	// echo $resultArr;
    //echo "<pre>";
    print_r($resultArr);
	
    break;

  case 'GET':
    $resultArr=rest_get($endpoint,$action,$passedObject);  

    	//echo "<pre>";
    	//print_r($resultArr);

		echo $resultArr;
    break;
  
  default:
    echo rest_error($request);  
    break;
}//switch
//------------------------------------------------------------------------------------------------
function rest_get($endpoint,$action,$passedObject)
{

	switch($endpoint)
	{
		case 'logins' : // echo "login";
			break;

		case 'auth' : 
						switch($action)
						{
							case 'login' : $resultArr=auth_user($endpoint,$action,$passedObject['auth']);
								break;
						}
			break;

		case 'users' : 
						switch($action)
						{
							case 'getuser' : $resultArr=get_user($endpoint,$action,$passedObject['user']);
								break;
                            case 'getuserlist' : $resultArr=get_userlist($endpoint,$action,$passedObject['userlistinfo']);
								break;
						}
			break;

	}
	
	return $resultArr;
}//function

//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------
function rest_post($endpoint,$action,$passedObject)
{
	switch($endpoint)
	{
		
		case 'logins' :
				switch($action)
				{
					case 'reset-pass': $resultArr=reset_pass($endpoint,$action,$passedObject['login']);
						break;

					case 'register'  : $resultArr=register($endpoint,$action,$passedObject['register']);
						break;
                    
                    case 'login'     : $resultArr=auth_user($endpoint,$action,$passedObject['login']);
						break;
					
				}
				break;
       case 'lists':
                switch($action)
				{
					case 'followlist': $resultArr=followlist($endpoint,$action,$passedObject['login']);
						break;
                }
                break;
      case 'users' : 
				switch($action)
				{
					case 'getuser'      : $resultArr=get_user($endpoint,$action,$passedObject['user']);
						break;
					case 'updateuser'   : $resultArr=update_user($endpoint,$action,$passedObject['updateinfo']);
						break;
                    case 'updateavatar' : $resultArr=upload_file($endpoint,$action,$passedObject['fileinfo']);
						break;
                    case 'forgotpass'   : $resultArr=forgot_pass($endpoint,$action,$passedObject['forgotinfo']);
						break;
                    case 'resetpass'    : $resultArr=reset_pass($endpoint,$action,$passedObject['resetinfo']);
						break; 
                    case 'getuserlist' : $resultArr=get_userlist($endpoint,$action,$passedObject['userlistinfo']);
						break;      
				}
			break;
       
       //assesment API Starts
       
       case 'assesment':
                switch($action)
				{
				    //assesment type add edit delete
					case 'asntypeadd'   : $resultArr=create_asntype($endpoint,$action,$passedObject['asntypeinfo']); // create types of assesment we_asntype
						break;
                    case 'asntypeupdate': $resultArr=update_asntype($endpoint,$action,$passedObject['asntypeinfo']); // update types of assesment we_asntype
						break;
                    case 'asntypedelete': $resultArr=delete_asntype($endpoint,$action,$passedObject['asntypeinfo']); // delete types of assesment we_asntype
						break;
                    
                    
                    //assesment add edit delete
                    case 'asmtadd'   : $resultArr=create_asmt($endpoint,$action,$passedObject['asmtinfo']); // create types of assesment we_asntype
						break;
                    case 'asmtupdate': $resultArr=update_asmt($endpoint,$action,$passedObject['asmtinfo']); // update types of assesment we_asntype
						break;
                    case 'asmtdelete': $resultArr=delete_asmt($endpoint,$action,$passedObject['asmtinfo']); // delete types of assesment we_asntype
						break;
                    
                    //assesment list userwise
                    case 'userasmtlist': $resultArr=get_asmt_userwise($endpoint,$action,$passedObject['userasmtinfo']); // delete types of assesment we_asntype
						break;
                    case 'singleasmt': $resultArr=get_asmt_row($endpoint,$action,$passedObject['singleasmtinfo']); // delete types of assesment we_asntype
						break;    
                }
                break;
        
	}
	return $resultArr;
}


//------------------------------------------------------------------------------------------------
function rest_error()
{
	return false;
	
}