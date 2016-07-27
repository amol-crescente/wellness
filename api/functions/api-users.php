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
