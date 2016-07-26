<?php
function register($endpoint,$action,$passedObject)
{
    
	$resultArr=array();

	$email      = trim(strtolower($passedObject['email']));
	$password   = trim(strtolower($passedObject['password']));

	$regtype    = $passedObject['regtype']; 
    $fname      = $passedObject['fname']; 
    $lname      = $passedObject['lname'];
    $phone      = $passedObject['phone'];
    $address    = $passedObject['address'];
    
    $username   = $passedObject['username'];
    
    $birthdate  = $passedObject['birthdate']; 
    $gender     = $passedObject['gender'];
    $passedObject['notify']==true?$notify='1':$notify='0';
    



	

		//First verify if the email exists
		$email_ver=db_fetch('we_users',' LCASE(`email`)=\''.$email.'\'');
        
     
        
		if ((count($email_ver))!=0) 
		{
			$resultArr['event']['type']="fail";
			$resultArr['event']['message']="Email Already Exist";
			$resultArr['login']=null;
		}
        elseif (count($passedObject)<=0) 
		{
			$resultArr['event']['type']="fail";
			$resultArr['event']['message']="Please Enter Something";
			$resultArr['login']=null;
		}
		else
		{

			$verify=db_fetch('we_users',' LCASE(`username`)=\''.$email.'\'');
			if ((count($verify))==0)
			{
						$add=db_add('we_users',array(
						'`regtype`' => $regtype,
						'`fname`' => $fname,
						'`lname`' => $lname,
                        '`gender`' => $gender,
                        '`birthdate`' => date('Y-m-d',strtotime($birthdate)),
						'`address`' => $address,
						'`email`' => trim($email),
						'`phone`' => $phone,
						'`username`' => $username,
						'`password`' => md5($password),
                        '`notify`' => $notify,
						'`joindate`' => date('Y-m-d H:i:s')
					));
                    
                    //Send email with new pass
            		$from_email='support@crescente.com';
            		$from_name='Resident Wellness Portal';
            		$to=$email;
            	
            		$subject='Registration Success';
            
            		$message_body='
            			<center><h1 style="font-size:3.5em; font-family: \'Helvetica Neue\',Helvetica, Arial,sans-serif; font-weight:100">
            			     Welcome To Resident Wellness Portal
            			</h1></center>			
            		 	<p style="font-size:16px">
            		   		Hi '.$fname.',
            		    </p>
            		 	<p style="font-size:16px">
            		   		Thank you for registring with us 
            		    </p>
            			 <br />
            			';
            
            			$signature='';
            
            		include('libs/email_template.php');
            		$message= wordwrap($message, 50);
            
            	
            		$response = send_mail($from_email,$from_name,$to,$subject,$message);
                    
                    if($response==1)
                    {
                    $resultArr['event']['type']="success";
					$resultArr['event']['message']="Signup Done and Email Sent Successfully";
					$resultArr['login']=$passedObject;    
                    }
                    else
                    {
                    $resultArr['event']['type']="fail";
    				$resultArr['event']['message']="Signup Failed";
    				$resultArr['login']=null;    
                    }
					
					//------------------------------------------------

			}//if verify
			else
			{
				$resultArr['event']['type']="fail";
				$resultArr['event']['message']="User Already Exist";
				$resultArr['login']=null;
			}
		}//else email verify


	// return true;

	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;

}

function auth_user($endpoint,$action,$passedObject)
{


    $resultArr=array();
	$rowArr=array();



	$userName=$passedObject['username'];
	$passWord=$passedObject['password'];

	
    
	

		//Check company agents
		$logins=db_fetch('we_users','`username`=\''.$userName.'\' OR `email`=\''.$userName.'\'');
	       
		if(count($logins) > 0)
		{
			foreach ($logins as $key => $values)
			{
				
				if((($values['username']==$userName) && (($values['password']==md5(strtolower($passWord))) || ($values['password']==md5($passWord)))) || (($values['email']==$userName) && (($values['password']==md5(strtolower($passWord))) || ($values['password']==md5($passWord)))))
				{ 
						$rowArr['regtype']= $values['regtype']; 
						$rowArr['id']     = $values['id']; 
						$login_ID         = $values['id'];

						$login_token=md5($login_ID.time());
                        
                        $crdatetime = date('Y-m-d H:i:s');
                        											
						//---- get user details from users table
						$userArr=db_fetch('we_users','`id`=\''.$values['id'].'\'');
				
						$rowArr['fname']=$userArr[0]['fname'];
						$rowArr['lname']=$userArr[0]['lname'];
						$rowArr['email']=$userArr[0]['email'];
                        $rowArr['address']=$userArr[0]['address'];
						$rowArr['token']=$login_token;
						
						//--- ADD TOKEN IN DB -----
									$add_token=db_add('we_tokens',array(
								'`type`'  => 'login',
                                '`token`' => $login_token,
								'`time`' => $crdatetime,
								'`login_id`' => $login_ID
								
							));
				}//if
				else
				{
					$rowArr['role']='0'; 
				}
				

				//--- CHECK FOR OLD TOKEN more that 12 hrs
				$delete = db_delete('we_tokens', ' `login_id`=\''.$values['id'].'\' AND TIMESTAMPDIFF(HOUR, FROM_UNIXTIME(`time`),FROM_UNIXTIME(UNIX_TIMESTAMP())) > 12 ');
				
			}//foreach
		}//if
		else
		{
			$rowArr['regtype']='0'; 
		}



	if($rowArr['regtype']==0)
	{
		$resultArr['event']['type']="fail";
		$resultArr['event']['message']="Invalid User";
		$resultArr['auth']=$rowArr;
	}
	else
	{
		$resultArr['event']['type']="success";
		$resultArr['event']['message']="User found Successfully";
		$resultArr['auth']=$rowArr;
	}


	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);

	return $resultArr;	
}

