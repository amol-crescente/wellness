<?php

function create_asntype($endpoint,$action,$passedObject)
{

        $resultArr=array();

    	$name       = $passedObject['name']; 
        $desc       = $passedObject['desc'];
        $status     = $passedObject['status'];
        
		//First verify if the email exists
		$type_ver=db_fetch('we_asn_type',' LCASE(`name`)=\''.$name.'\'');
        
     
        
		if ((count($type_ver))!=0) 
		{
			$resultArr['event']['type']="fail";
			$resultArr['event']['message']="Type Already Exist";
			$resultArr['asntypeinfo']=null;
		}
        else
		{
			$add=db_add('we_asn_type',array(
    			'`name`' => $name,
    			'`desc`' => $desc,
    			'`status`' => $status
    		));
            
            $resultArr['event']['type']="success";
			$resultArr['event']['message']="Assesment Type Added Successfully";
			$resultArr['asntypeinfo']=$passedObject;
		}//else email verify

	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;
}

function update_asntype($endpoint,$action,$passedObject)
{
        
        
        $resultArr=array();

    	$id         = $passedObject['id'];
        $name       = $passedObject['name']; 
        $desc       = $passedObject['desc'];
        $status     = $passedObject['status'];
        
		//First verify if the email exists
		
        
     
        $update = db_update('we_asn_stype',array('`name`' => $name, '`desc`'=>$desc, '`status`'=>$status),"`id` = ".$id);
		
        
        
        if($update!='')
        {
        $resultArr['event']['type']="success";
		$resultArr['event']['message']="Assesment Type Updated Successfully";
		$resultArr['asntypeinfo']=$passedObject;
		}
        else
        {
        $resultArr['event']['type']="fail";
		$resultArr['event']['message']="Assesment Type Not Update";
		$resultArr['asntypeinfo']=null;
		    
        }
        

	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;
}
function delete_asntype($endpoint,$action,$passedObject)
{
        $resultArr=array();
    	$id         = $passedObject['id'];
        //First verify if the email exists
        $delete=db_delete('we_asn_type'," `id`=".$id);

        if($delete!='')
        {
        $resultArr['event']['type']="success";
		$resultArr['event']['message']="Assesment Type Delete Successfully";
		$resultArr['asntypeinfo']=$passedObject;
		}
        else
        {
        $resultArr['event']['type']="fail";
		$resultArr['event']['message']="Assesment Type Not Delete";
		$resultArr['asntypeinfo']=null;
		    
        }
        

	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;
}

/**
 * 
 * ************* ASSESMENT ADD EDIT DELETE ************ 
 * 
*/
function create_asmt($endpoint,$action,$passedObject)
{

        $resultArr=array();

    	$as_type       = $passedObject['as_type'];
        $user_id       = $passedObject['user_id'];
        $as_name       = $passedObject['as_name'];
        $as_date       = date('Y-m-d',strtotime($passedObject['as_date']));
        $as_time       = date('H:i:s',strtotime($passedObject['as_time']));
        $ampm          = $passedObject['ampm'];
        $agreement     = $passedObject['agreement'];
        $as_desc       = $passedObject['as_desc'];
        $created_by    = $passedObject['created_by'];
        $created_time  = $passedObject['created_time'];
        $status        = $passedObject['status'];
        
        //First verify if the email exists
		$type_ver=db_fetch('we_assesment',' LCASE(`user_id`)=\''.$user_id.'\' AND `as_type`=\''.$as_type.'\' AND `as_date`=\''.$as_date.'\' AND `as_time`=\''.$as_time.'\' AND `ampm`=\''.$ampm.'\' ');
        
        
		if ((count($type_ver))!=0) 
		{
			$resultArr['event']['type']="fail";
			$resultArr['event']['message']="Assesment Already Exists For this Type";
			$resultArr['asntypeinfo']=null;
		}
        else
		{
		
        	$add=db_add('we_assesment',array(
    			'`as_type`'      => $as_type,
    			'`user_id`'      => $user_id,
                '`as_name`'      => $as_name,
                '`as_date`'      => $as_date,
                '`as_time`'      => $as_time,
                '`ampm`'         => $ampm,
                '`as_date`'      => $as_date,
                '`agreement`'    => $agreement,
                '`as_desc`'      => $as_desc,
                '`created_by`'   => $created_by,
                '`created_time`' => $created_time,
                '`status`'       => $status
    		));
            
            
            
            //Send email with new pass
    		if($status==1)
            {
                $createdinfo  = db_fetch_ll("SELECT * FROM `we_users` WHERE `id`=$created_by");
                
                $userinfo     = db_fetch_ll("SELECT * FROM `we_users` WHERE `id`=$user_id");
                
                $from_email   = $createdinfo[0]['email'];
        		$from_name    = $createdinfo[0]['fname'].' '.$createdinfo[0]['lname'];
        		$to           = $userinfo[0]['email'];
        		$subject      = 'New Assesment Added';
        		$message_body = '
                        			<center>
                                        <h1 style="font-size:3.5em; font-family: \'Helvetica Neue\',Helvetica, Arial,sans-serif; font-weight:100">Resident Wellness Portal</h1>
                                    </center>			
                        		 	<p style="font-size:16px">
                        		   		Hi '.$userinfo[0]['fname'].',
                        		    </p>
                        		 	<p style="font-size:16px">
                        		   		Your New Assesment <strong>'.$as_name.'</strong> Generated Please Login to your profile for more details. 
                        		    </p>
                        			<br />
        			            ';
        
     			$signature='';
        
        		include('libs/email_template.php');
        		$message= wordwrap($message, 50);
                
                //echo $from_email.'-'.$from_name.'-'.$to.'-'.$subject.'-'.$message;die;
            }
            else
            {
                $createdinfo  = db_fetch_ll("SELECT * FROM `we_users` WHERE `id`=$created_by");
                $userinfo     = db_fetch_ll("SELECT * FROM `we_users` WHERE `id`=$user_id");
                
                $from_email   = $userinfo[0]['email'];
        		$from_name    = $userinfo[0]['fname'].' '.$userinfo[0]['lname'];
        		$to           = $createdinfo[0]['email'];
        		$subject      = 'New Assesment Request';
        		$message_body = '
                        			<center>
                                        <h1 style="font-size:3.5em; font-family: \'Helvetica Neue\',Helvetica, Arial,sans-serif; font-weight:100">Resident Wellness Portal</h1>
                                    </center>			
                        		 	<p style="font-size:16px">
                        		   		Hi '.$createdinfo[0]['fname'].',
                        		    </p>
                        		 	<p style="font-size:16px">
                        		   		I Have Requested New Assesment <strong>'.$as_name.'</strong> Please Approve it. 
                        		    </p>
                        			<br />
        			            ';
        
     			$signature='';
        
        		include('libs/email_template.php');
        		$message= wordwrap($message, 50);
                
                //echo $from_email.'-'.$from_name.'-'.$to.'-'.$subject.'-'.$message;die;
            }
                $response = send_mail($from_email,$from_name,$to,$subject,$message);
            
            
            
            
            $resultArr['event']['type']="success";
			$resultArr['event']['message']="Assesment Added Successfully";
			$resultArr['asmtinfo']=$passedObject;
		}

	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;
}
function update_asmt($endpoint,$action,$passedObject)
{
    $resultArr=array();
        
    $id            = $passedObject['id']; 
	$as_type       = $passedObject['as_type'];
    $user_id       = $passedObject['user_id'];
    $as_name       = $passedObject['as_name'];
    $as_date       = date('Y-m-d',strtotime($passedObject['as_date']));
    $agreement     = $passedObject['agreement'];
    $as_desc       = $passedObject['as_desc'];
    $created_by    = $passedObject['created_by'];
    $status        = $passedObject['status'];
    
    //First verify if the email exists
	
	$add=db_update('we_assesment',array(
		'`as_type`'   => $as_type,
		'`user_id`'   => $user_id,
        '`as_name`'   => $as_name,
        '`as_date`'   => $as_date,
        '`agreement`' => $agreement,
        '`as_desc`'   => $as_desc,
        '`created_by`'   => $created_by,
        '`status`'   => $status
	),"`id` = ".$id);
    
    $resultArr['event']['type']="success";
	$resultArr['event']['message']="Assesment Updated Successfully";
	$resultArr['asmtinfo']=$passedObject;
	
	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;
}
function delete_asmt($endpoint,$action,$passedObject)
{
        $resultArr=array();
    	$id         = $passedObject['id'];
        //First verify if the email exists
        $delete=db_delete('we_assesment'," `id`=".$id);

        if($delete!='')
        {
        $resultArr['event']['type']="success";
		$resultArr['event']['message']="Assesment Delete Successfully";
		$resultArr['asmtinfo']=$passedObject;
		}
        else
        {
        $resultArr['event']['type']="fail";
		$resultArr['event']['message']="Assesment Not Delete";
		$resultArr['asmtinfo']=null;
		    
        }
        

	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;
}

/**
 *  get list of all assesment of logged in user or selected user datewise
 * */
function get_asmt_userwise($endpoint,$action,$passedObject)
{
	$resultArr=array();
	$rowArr=array();
	$tempArr=array();

	$user_id  = trim($passedObject['user_id']); 
    
    $as_type  = trim($passedObject['as_type']);
    
    $userclause = $user_id!=''?'`user_id`=\''.$user_id.'\' AND ':'';
    
    $typeclause = $as_type!=''?'`as_type`=\''.$as_type.'\' AND ':'';
    
    $fromdate = $passedObject['fromdate']!=''?trim(date('Y-m-d',strtotime($passedObject['fromdate']))):trim(date('Y-m-d'));
    $todate   = $passedObject['todate']!=''?trim(date('Y-m-d',strtotime($passedObject['todate']))):trim(date('Y-m-d'));

	
    $assesArr=db_fetch('we_assesment',''.$userclause.' '.$typeclause.'  `as_date` BETWEEN \''.$fromdate.'\' AND \''.$todate.'\' order by `as_date` desc');
    
    
	if(count($assesArr) > 0)
	{
		foreach($assesArr as $arr)
		{
			$rowArr['id']=$arr['id'];
            $rowArr['as_type']=$arr['as_type'];
            $rowArr['user_id']=$arr['user_id'];
			$rowArr['as_name']=$arr['as_name'];
			$rowArr['as_date']=$arr['as_date'];
			$rowArr['agreement']=$arr['agreement'];
			$rowArr['as_desc']=$arr['as_desc'];
			$rowArr['status']=$arr['status'];
			
			$tempArr[]=$rowArr;
		}//foreach

		$resultArr['event']['type']="success";
		$resultArr['event']['message']="Assesments found Successfully";
		$resultArr['userasmtinfo']=$tempArr;
	}
	else
	{
		$resultArr['event']['type']="fail";
		$resultArr['event']['message']="Assesments not Found";
		$resultArr['userasmtinfo']=null;
	}
	

	
	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;
}

function get_asmt_row($endpoint,$action,$passedObject)
{
	$resultArr=array();
	$rowArr=array();

	$id=trim($passedObject['id']); 

	$asmtArr=db_fetch('we_assesment','`id`=\''.$id.'\'');
	if(count($asmtArr) > 0)
	{
	    $rowArr['id']                  = $asmtArr[0]['id'];
    
		
        $rowArr['as_type']             = $asmtArr[0]['as_type'];
        $rowArr['user_id']             = $asmtArr[0]['user_id'];
		$rowArr['as_name']             = $asmtArr[0]['as_name'];
		$rowArr['as_date']             = $asmtArr[0]['as_date'];
		$rowArr['agreement']           = $asmtArr[0]['agreement'];
		$rowArr['as_desc']             = $asmtArr[0]['as_desc'];
		$rowArr['status']              = $asmtArr[0]['status'];
        $rowArr['created_by']          = $asmtArr[0]['created_by'];
        $rowArr['created_time']        = $asmtArr[0]['created_time'];
        
		$resultArr['event']['type']    = "success";
		$resultArr['event']['message'] = "Assesment found Successfully";
		$resultArr['singleasmtinfo']   = $rowArr;
	}
	else
	{
		$resultArr['event']['type']    = "fail";
		$resultArr['event']['message'] = "Assesment not Found";
		$resultArr['singleasmtinfo']   = null;
	}
	
	
	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
	return $resultArr;
}
function get_asmt_approve($endpoint,$action,$passedObject)
{
	   $resultArr=array();
    	$id         =   trim($passedObject['id']); 
        $status     =   trim($passedObject['status']);
        
        $statustext = $status=='1'?'Accepted':'Rejected';
    
        $asmtArr=db_fetch('we_assesment','`id`=\''.$id.'\'');
	
        $userinfo  = db_fetch('we_users','`id`=\''.$asmtArr[0]['user_id'].'\'');
        
        $staffinfo = db_fetch('we_users','`id`=\''.$asmtArr[0]['created_by'].'\'');
        
        $update=db_update('we_assesment',array('`status`'   => $status),"`id` = ".$id);
        
        if($update==true)
        {
            $from_email   = $staffinfo[0]['email'];
    		$from_name    = $staffinfo[0]['fname'].' '.$staffinfo[0]['lname'];
    		$to           = $userinfo[0]['email'];
    		$subject      = 'Assesment Status Notification';
    		$message_body = '
                    			<center>
                                    <h1 style="font-size:3.5em; font-family: \'Helvetica Neue\',Helvetica, Arial,sans-serif; font-weight:100">Resident Wellness Portal</h1>
                                </center>			
                    		 	<p style="font-size:16px">
                    		   		Hi '.$userinfo[0]['fname'].',
                    		    </p>
                    		 	<p style="font-size:16px">
                    		   		Your Request For Assesment <strong>'.$asmtArr[0]['as_name'].' is '.$statustext.'</strong>
                                    Please Login to Portal for more details.
                                    Thank You
                    		    </p>
                    			<br />
    			            ';
    
 			$signature='';
    
    		include('libs/email_template.php');
    		$message= wordwrap($message, 50);
            $response = send_mail($from_email,$from_name,$to,$subject,$message);
            //echo $from_email.'-'.$from_name.'-'.$to.'-'.$subject.'-'.$message;die;
            $resultArr['event']['type']    = "success";
    		$resultArr['event']['message'] = "Request Proccessed Successfully";
    		$resultArr['approveinfo']      = '1';
        }
        else
    	{
    		$resultArr['event']['type']        = "fail";
    		$resultArr['event']['message']     = "Request Not Proccessed";
    		$resultArr['approveinfo']          = null;
    	}
            
        	$resultArr=json_encode($resultArr,JSON_NUMERIC_CHECK);
        	return $resultArr;
}