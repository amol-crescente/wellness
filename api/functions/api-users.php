<?php
//------------------------------------------------
function get_user($endpoint,$action,$passedObject)
{
    
	$resultArr=array();
	$rowArr=array();

	// $user_id=$par1;
	$user_id=trim($passedObject['id']); 

	
		$userdata=db_fetch('we_users','`id`=\''.$user_id.'\' ');
		if(count($userdata) > 0)
		{
			$rowArr['id']       = $userdata[0]['id'];
			$rowArr['regtype']  = $userdata[0]['regtype'];
            $rowArr['fname']    = $userdata[0]['fname'];
            $rowArr['lname']    = $userdata[0]['lname'];
            $rowArr['email']    = $userdata[0]['email'];
            $rowArr['address']  = $userdata[0]['address'];
            $rowArr['gender']   = $userdata[0]['gender'];
            $rowArr['phone']    = $userdata[0]['phone'];
            $rowArr['avatar']   = $userdata[0]['avatar'];
            
			
                        
			$resultArr['event']['type']="success";
			$resultArr['event']['message']="User found successfully";
			$resultArr['user']=$rowArr;
		}
		else
		{
			$resultArr['event']['type']="fail";
			$resultArr['event']['message']="User Not Found";
			$resultArr['user']=$rowArr;
		}
	
	
	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;
}
function get_userlist($endpoint,$action,$passedObject)
{
    
	$resultArr=array();
	$rowArr=array();
    $tempArr=array();
    
    $regtype    = trim($passedObject['regtype'])!=''?' `regtype`=\''.trim($passedObject['regtype']).'\' AND ':''; 
    $username   = trim($passedObject['username'])!=''?' `username`LIKE  \'%'.trim($passedObject['username']).'%\' AND ':'';
    $fname      = trim($passedObject['fname'])!=''?' `fname` LIKE \'%'.trim($passedObject['fname']).'%\' AND ':'';
    $gender     = trim($passedObject['gender'])!=''?' `gender`=\''.trim($passedObject['gender']).'\' AND ':'';
    
    
    $isAdmin    = trim($passedObject['isAdmin'])!=''?' `isAdmin`=\''.trim($passedObject['isAdmin']).'\' AND ':'';    

    $userdata=db_fetch('we_users',' '.$regtype.' '.$username.'  '.$fname.'  '.$gender.'  '.$isAdmin.'  1');



	
	if(count($userdata) > 0)
	{
	   foreach($userdata as $arr)
		{
		$rowArr['id']       = $arr['id'];
		$rowArr['regtype']  = $arr['regtype'];
        $rowArr['fname']    = $arr['fname'];
        $rowArr['lname']    = $arr['lname'];
        $rowArr['email']    = $arr['email'];
        $rowArr['address']  = $arr['address'];
        $rowArr['gender']   = $arr['gender'];
        $rowArr['phone']    = $arr['phone'];
        $rowArr['avatar']   = $arr['avatar'];
        $tempArr[]          = $rowArr;
		}
                    
		$resultArr['event']['type']="success";
		$resultArr['event']['message']="Users found successfully";
		$resultArr['userlistinfo']=$tempArr;
	}
	else
	{
		$resultArr['event']['type']="fail";
		$resultArr['event']['message']="Users Not Found";
		$resultArr['userlistinfo']=null;
	}
	
	
	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;
}
//-------------------------------------------------------------------------------------------------------------
function update_user($endpoint,$action,$passedObject)
{
    
    
    
	$resultArr=array();
	$inputArray=array();
	
	$user_id=trim($passedObject['id']); 
	

	if(trim($passedObject['regtype'])!="")      {    $inputArray['`regtype`']     = trim($passedObject['regtype']);  }
    if(trim($passedObject['username'])!="")     {    $inputArray['`username`']    = trim($passedObject['username']);  }
    if(trim($passedObject['password'])!="")     {    $inputArray['`password`']    = trim($passedObject['password']);  }
    if(trim($passedObject['fname'])!="")        {    $inputArray['`fname`']       = trim($passedObject['fname']);  }
	if(trim($passedObject['lname'])!="")        {    $inputArray['`lname`']       = trim($passedObject['lname']);  }
	if(trim($passedObject['email'])!="")        {    $inputArray['`email`']       = trim($passedObject['email']);  }
	if(trim($passedObject['phone'])!="")        {    $inputArray['`phone`']       = trim($passedObject['phone']);  }
	if(trim($passedObject['address'])!="")      {    $inputArray['`address`']     = trim($passedObject['address']);}
    if(trim($passedObject['birthdate'])!="")    {    $inputArray['`birthdate`']   = trim(date('Y-m-d',strtotime($passedObject['birthdate'])));}
    if(trim($passedObject['avatar'])!="")       {    $inputArray['`avatar`']      = trim($passedObject['avatar']);}
    if(trim($passedObject['notify'])!="")       {    $inputArray['`notify`']      = trim($passedObject['notify']);}
    if(trim($passedObject['isAdmin'])!="")      {    $inputArray['`isAdmin`']     = trim($passedObject['isAdmin']);}
	

		
		$updateArr=db_fetch('we_users','`id`=\''.$user_id.'\' ');
		if(count($updateArr) > 0)
		{
			//--- update into DB ---
			if (count($inputArray) > 0)
			{
				$add=db_update('we_users',$inputArray,'`id`='.$user_id);	

			}
			$updateArr=db_fetch('we_users','`id`=\''.$user_id.'\' ');
			
			$resultArr['event']['type']="success";
			$resultArr['event']['message']="User Details Updated Successfully";
			$resultArr['user']=$updateArr[0];
		}
		else
		{
			$resultArr['event']['type']="fail";
			$resultArr['event']['message']="User Details Not Found";
			$resultArr['user']=null;
		}
	
	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;

}
//Upload file function
//$form_field_name,$folder,$file_prefix
function upload_file($endpoint,$action,$passedObject)
{
        
        $form_field_name = $passedObject['formfield'];
        $folder          = $passedObject['folder'];
        $file_prefix     = $passedObject['filename'];   
        $resultArr       = array();
    	
		//This function reads the extension of the file. It is used to determine if the file  is an image by checking the extension.
		 function getExtension($str) {
		         $i = strrpos($str,".");
		         if (!$i) { return ""; }
		         $l = strlen($str) - $i;
		         $ext = substr($str,$i+1,$l);
		         return $ext;
		 }

		 
		// if(isset($_POST['Submit'])) 
		// {
		 	//reads the name of the file the user submitted for uploading
		 	
			$image=$_FILES[$form_field_name]['name'];
			
		 	//if it is not empty
		 	if ($image) 
		 	{
		 		
		 		$filename = stripslashes($_FILES[$form_field_name]['name']);
		  		$extension = getExtension($filename);
		 		$extension = strtolower($extension);
				
				$image_name=$file_prefix.$extension;
                
                $path    = 'img/'.$folder;
                
                if (!file_exists($path)) 
                {
                    mkdir($path, 0777, true);
                }
                
                $newname = $path.$image_name;
				
                if (file_exists($newname)) 
                {
                    unlink($newname);
                }
                
                $copied = copy($_FILES[$form_field_name]['tmp_name'], $newname);
                
                $resultArr['event']['type']="success";
    			$resultArr['event']['message']="File Uploaded Successfully";
    			$resultArr['fileurl']=$newname;
			}
            else
            {
                $resultArr['event']['type']="fail";
    			$resultArr['event']['message']="File Upload Error";
    			$resultArr['fileurl']='';
            }
		// } //submit
		$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	    return $resultArr;
}
function forgot_pass($endpoint,$action,$passedObject)
{  
	$resultArr=array();
	
	$email = trim($passedObject['email']); 
    $fturi = trim($passedObject['reseturi']);
	$crdatetime = date('Y-m-d H:i:s');
    
	$forgotArr=db_fetch('we_users','`email`=\''.$email.'\' ');
	if(count($forgotArr) > 0)
	{
	    
        $reset_token=md5($forgotArr[0]['id'].time());	
        
        //---- SENd EMAIL HERE -----
		//Send email with new pass
		$from_email='support@crescente.com';
		$from_name='crescente Password Reset Service';
		$to=$forgotArr[0]['email'];
	
		$subject='Your reset Password Link';

		$message_body='
			<center><h1 style="font-size:3.5em; font-family: \'Helvetica Neue\',Helvetica, Arial,sans-serif; font-weight:100">
			     Welcome to our title quote app
			</h1></center>			
		 	<p style="font-size:16px">
		   		Hi '.$users[0]['fname'].',
		    </p>
		 	<p style="font-size:16px">
		   		We\'ve just reset your password, as per your request. Below you\'ll find your new login credentials. We advise you to change your password when you login, to a password of your choice.
		    </p>
		 	<p style="font-size:16px">
		   		<b>Your Reset Password Link:</b><br />
				<a href="'.$fturi.'&reset='.$reset_token.'">Click Here To Reset Your Password</a>
		    </p>
			 <br />
			';

			$signature='';

		include('libs/email_template.php');
		$message= wordwrap($message, 50);

	
		$response = send_mail($from_email,$from_name,$to,$subject,$message);
        
        //--- ADD TOKEN IN DB FOR RESET PASSWORD-----
		$add_token=db_add('we_tokens',array(
			'`tktype`'  => 'reset',
            '`token`' => $reset_token,
			'`time`' => $crdatetime,
			'`login_id`' => $forgotArr[0]['id']
			
		));
        if($response==1)
        {
        $resultArr['event']['type']="success";
		$resultArr['event']['message']="Password Reset Link Sent Successfully to Your Email Id";
		$resultArr['forgotinfo']=$passedObject;    
        }
        else
        {
        $resultArr['event']['type']="fail";
		$resultArr['event']['message']="Password Reset Link Not Sent";
		$resultArr['forgotinfo']='null';    
        }
				
        
	}
	else
	{
		$resultArr['event']['type']="fail";
		$resultArr['event']['message']="User Details Not Found";
		$resultArr['user']=null;
	}
	
	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;

}

function reset_pass($endpoint,$action,$passedObject)
{  
	$resultArr=array();
	
	$newpassword = trim($passedObject['newpassword']); 
    $resettoken  = trim($passedObject['resettoken']);
	
    
	$resetArr=db_fetch('we_tokens','`token`=\''.$resettoken.'\' ');
	if(count($resetArr) > 0)
	{
	    
        $loginid = $resetArr[0]['login_id'];
        
        $userArr=db_fetch('we_users','`id`=\''.$loginid.'\' ');
        
        $update = db_update('we_users',array('`password`' => md5($newpassword)),'`id` = '.$loginid);
        
        //---- SENd EMAIL HERE -----
		//Send email with new pass
		$from_email='support@crescente.com';
		$from_name='crescente Password Reset Service';
		$to=$userArr[0]['email'];
	
		$subject='Password Reset Successfully';

		$message_body='
			<center><h1 style="font-size:3.5em; font-family: \'Helvetica Neue\',Helvetica, Arial,sans-serif; font-weight:100">
			     Resident Wellness Portal
			</h1></center>			
		 	<p style="font-size:16px">
		   		Hi '.$userArr[0]['fname'].',
		    </p>
		 	<p style="font-size:16px">
		   		Your Password Reset Successfully.Now Login with your new password.
		    </p>
			 <br />
			';

			$signature='';

		include('libs/email_template.php');
		$message= wordwrap($message, 50);

	
		$response = send_mail($from_email,$from_name,$to,$subject,$message);
        
        //--- ADD TOKEN IN DB FOR RESET PASSWORD-----
		$add_token=db_delete('we_tokens'," `login_id`=".$loginid." AND `tktype`='reset'");
        if($response==1)
        {
        $resultArr['event']['type']="success";
		$resultArr['event']['message']="Password Reset Done";
		$resultArr['resetinfo']='1';    
        }
        else
        {
        $resultArr['event']['type']="fail";
		$resultArr['event']['message']="Password Reset Failed";
		$resultArr['resetinfo']=null;    
        }
				
        
	}
	else
	{
		$resultArr['event']['type']="fail";
		$resultArr['event']['message']="Invalid Reset Token";
		$resultArr['resetinfo']=null;
	}
	
	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;

}