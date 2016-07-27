<?php
//ini_set('display_errors', 0);
//error_reporting(E_ERROR | E_WARNING | E_PARSE); 

$option=$_GET["option"];  

$url = "https://accounts.google.com/o/oauth2/auth";
    
$params = array(
    "response_type" => "code",
    "client_id" => "468393552680-4cmruubnffntgee6f4dbbohmdcjj74hl.apps.googleusercontent.com",
    "redirect_uri" => "http://www.wellness.dev/api/well.php",
    "scope" => "https://www.googleapis.com/auth/plus.me"
    );

$request_to = $url . '?' . http_build_query($params);

header("Location: " . $request_to);


if(isset($_GET['code'])) {
    // try to get an access token
    $code = $_GET['code'];
    $url = 'https://accounts.google.com/o/oauth2/token';
    $params = array(
        "code" => $code,
        "client_id" => "468393552680-4cmruubnffntgee6f4dbbohmdcjj74hl.apps.googleusercontent.com",
        "client_secret" => "PCcftpkjt34ZOXmUp7zKVKmm",
        "redirect_uri" => "http://www.wellness.dev/api/well.php",
        "grant_type" => "authorization_code"
    );

    $request = new HttpRequest($url, HttpRequest::METH_POST);
    $request->setPostFields($params);
    $request->send();
    $responseObj = json_decode($request->getResponseBody());
    echo "Access token: " . $responseObj->access_token;
}




switch($option)
{
	
    case "1" : $passUrl="http://localhost/well/api/well.php/logins/register";//update user
				$data=array(
					'register'=>array(
						'regtype'=>'2',
                        'username'=>'somnath',
                        'password'=>'somnath',
                        'fname'=>'somnath',
                        'lname'=>'kadam',
                        'phone'=>'9999888878',
                        'email'=>'meetsamkadam@gmail.com',
                        'birthdate'=>'22-09-1987',
                        'address'=>'Pune-15',
                        'gender'=>'1',
                        'notify'=>'true', //true or false
                        'isAdmin'=>'1'
                        )
					);
		break;
    
    case "2" : $passUrl="http://localhost/well/api/well.php/logins/login";//login user
				$data=array(
					'login'=>array(
						'username'=>'test@gmail.com',
						'password'=>'test@gmail.com'
						)
					);
		break;
	
	case "3" : $passUrl="http://localhost/well/api/well.php/users/getuser";//get user
				$data=array(
					'user'=>array(
						'id'=>'11'
						)
					);
		break;

	case "4" : $passUrl="http://localhost/well/api/well.php/users/updateuser";//update user
				$data=array(
					'updateinfo'=>array(
						'id'=>'11',
                        'regtype'=>'2',
                        'username'=>'testdemo1',
                        'password'=>md5('testdemo1'),
                        'fname'=>'first name',
                        'lname'=>'last name',
                        'phone'=>'9090282825',
                        'email'=>'demo@wellness.com',
                        'birthdate'=>'22-09-1987',
                        'address'=>'250,Welington,USA',
                        'avatar'=>'http://localhost/well/img/users/firstname.jpg',//set image as userid to identify images unique
                        'notify'=>'1',//notify 0 or 1
                        'isAdmin'=>'1'
                        )
					);
		break;

	case "5" : $passUrl="http://localhost/well/api/well.php/users/updateavatar";
				$data=array(
					'fileinfo'=>array(
						'formfield'=>'formfield',
                        'folder'=>'userid',//provide image name as userid
                        'filename'=>'userid'//provide image name as userid 
						)
					);
		break;
	

	case "6" : $passUrl="http://localhost/well/api/well.php/logins/forgotpass";
				$data=array(
					'forgotinfo'=>array(
						'email'=>'somnathk.crescente@gmail.com',
                        'reseturi'=>'http://localhost/well/#/access/forgotpass'
						)
					);
		break;

	case "7" : $passUrl="http://localhost/well/api/well.php/logins/resetpass";
				$data=array(
					'resetinfo'=>array(
						'newpassword'=>'password123',
                        'resettoken'=>'987a9ea2ec7b6c4bc1352c377642ec3c'
                        )
					);
		break;     
    
    case "8" : $passUrl="http://localhost/well/api/well.php/users/getuserlist";//get user
				$data=array(
					'userlistinfo'=>array(
						'regtype'=>'',
                        'username'=>'test',
                        'fname'=>'',
                        'gender'=>'',
                        'isAdmin'=>''
						)
					);
		break;

    /**
     * 
     * ASSESMENT SECTION START FROM HERE
     * */
    case "9" : $passUrl="http://localhost/well/api/well.php/assesment/asntypeadd";
				$data=array(
					'asntypeinfo'=>array(
						'name'=>'Demo Asn Type',
                        'desc'=>'Demo Assesment Type',
                        'status'=>'1' // 1 or 0 publish or unpublish
                        
                        )
					);
		break;        
    case "10" : $passUrl="http://localhost/well/api/well.php/assesment/asntypeupdate";
				$data=array(
					'asntypeinfo'=>array(
						'id'=>'1',
                        'name'=>'Demo Waaw',
                        'desc'=>'Demo Waaw',
                        'status'=>'0' // 1 or 0 publish or unpublish
                        
                        )
					);
		break;        
    case "11" : $passUrl="http://localhost/well/api/well.php/assesment/asntypedelete";
				$data=array(
					'asntypeinfo'=>array(
						'id'=>'1'
                        
                        )
					);
		break;
   
   //assesment add edit delete
   
   case "12" : $passUrl="http://localhost/well/api/well.php/assesment/asmtadd";
				$data=array(
					'asmtinfo'=>array(
						'as_type'=>'1', //asn type from we_asn_type table
                        'user_id'=>'11', // autofill userid in textbox
                        'as_name'=>'first demo assesment',
                        'as_date'=>'26-07-2016',
                        'as_time'=>'12:12:00',
                        'ampm'=>'PM',
                        'agreement'=>'1', // 0 or 1 yes or no
                        'as_desc'=>'First Demo Assesment',
                        'created_by'=>'16', // staff id 
                        'created_time'=>date('Y-m-d H:i:s'), // current time
                        'status'=>'1' //1 if added by staff / 2 if added by resident
                        )
					);
		break;
   case "13" : $passUrl="http://localhost/well/api/well.php/assesment/asmtupdate";
				$data=array(
					'asmtinfo'=>array(
						'id'=>'2',
                        'as_type'=>'1', //asn type from we_asn_type table
                        'user_id'=>'11', // autofill userid in textbox
                        'as_name'=>'first assesment edit',
                        'as_date'=>'26-07-2016',
                        'agreement'=>'1', // 0 or 1 yes or no
                        'as_desc'=>'First Assesment Edit',
                        'status'=>'1',
                        'created_by'=>'12' // either by resident or staff member
                        )
					);
		break;
   case "14" : $passUrl="http://localhost/well/api/well.php/assesment/asmtdelete";
				$data=array(
					'asmtinfo'=>array(
						'id'=>'1'
                        )
					);
		break;		
   case "15" : $passUrl="http://localhost/well/api/well.php/assesment/userasmtlist"; // display users assesment list by userid
				$data=array(
					'userasmtinfo'=>array(
						'user_id'=>'23',
                        'as_type'=>'1',
                        'fromdate'=>'24-07-2016',
                        'todate'=>'27-07-2016',
                        'created_by'=>'11' // either by resident or staff member
                        )
					);
		break;
   case "16" : $passUrl="http://localhost/well/api/well.php/assesment/singleasmt"; // display Single assesment by id
				$data=array(
					'singleasmtinfo'=>array(
						'id'=>'994'
                        )
					);
		break;
   case "17" : $passUrl="http://localhost/well/api/well.php/assesment/asmtapprove"; // notification for assesment approve to user
				$data=array(
					'approveinfo'=>array(
						'id'=>'6',  // assesment id created by resident
                        'status'=>'1' // 1 for accept and 0 for reject
                        )
					);
		break;
  //email notification system
  case "26" : $passUrl="http://localhost/well/api/well.php/notification/notification"; // notifications between users
				$data=array(
					'notifyinfo'=>array(
						'sender'=>'16', // user_id of logged in user
                        'receiver'=>'11', // if staff login then userid of resident  * if resident login then userid of staff * if Admin login then userid of staff
                        'subject'=>'Subject for mail',
                        'message'=>'Message',
                        'attachment'=>'http://localhost/well/img/a0.jpg' 
                        )
					);
		break;
        
}//switch

$passedObject = json_encode($data);
//print_r($passedObject); die();

$username='jktest';
$password='jk';

$ch = curl_init($passUrl);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $passedObject);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                                                    
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($passedObject))                                                                       
);                                                                                                                   
 
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

$result = curl_exec($ch);

echo $result;

?>