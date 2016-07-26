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
        $agreement     = $passedObject['agreement'];
        $as_desc       = $passedObject['as_desc'];
        $created_by    = $passedObject['created_by'];
        $created_time  = $passedObject['created_time'];
        $status        = $passedObject['status'];
        
        //First verify if the email exists
		$type_ver=db_fetch('we_assesment',' LCASE(`user_id`)=\''.$user_id.'\' AND `as_type`=\''.$as_type.'\' AND `as_date`=\''.$as_date.'\' ');
        
        
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
                '`agreement`'    => $agreement,
                '`as_desc`'      => $as_desc,
                '`created_by`'   => $created_by,
                '`created_time`' => $created_time,
                '`status`'       => $status
    		));
            
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