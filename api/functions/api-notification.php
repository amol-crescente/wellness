<?php
function Notification($endpoint,$action,$passedObject)
{  
    	$resultArr=array();
    	
    	$senderinfo   = db_fetch('we_users','`id`=\''.$passedObject['sender'].'\' ');
        $receiverinfo = db_fetch('we_users','`id`=\''.$passedObject['receiver'].'\' ');

        //Send email to resident
        
		$from_email   = $senderinfo[0]['email'];
		$from_name    = $senderinfo[0]['fname'].' '.$senderinfo[0]['lname'];
		$to           = $receiverinfo[0]['email'];
	    $attachment   = $passedObject['attachment'];
        
        $attachment!=''?$attachbody = $attachment:$attachbody='';
         
		$subject=$passedObject['subject'];
        
		$message_body='
			<center><h1 style="font-size:3.5em; font-family: \'Helvetica Neue\',Helvetica, Arial,sans-serif; font-weight:100">
			     Resident Wellness Portal
			</h1></center>			
		 	<p style="font-size:16px">
		   		Hi '.$receiverinfo[0]['fname'].',
		    </p>
		 	<p style="font-size:16px">
		   		'.$passedObject['message'].'
		    </p>
            
			 <br />
			';

			$signature='';

		include('libs/email_template.php');
		$message= wordwrap($message, 50);

	
		$response = send_mail($from_email,$from_name,$to,$subject,$message,$attachbody);
        
    
        if($response==1)
        {
        $resultArr['event']['type']="success";
		$resultArr['event']['message']="Message Send Successfully";
		$resultArr['notifyinfo']='1';    
        }
        else
        {
        $resultArr['event']['type']="fail";
		$resultArr['event']['message']="Message Send Failed";
		$resultArr['notifyinfo']=null;    
        }
				
        
	
	
    	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
    	return $resultArr;

}
