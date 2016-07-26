<?php

function followlist($endpoint,$action,$passedObject)
{
	$resultArr=array();
	$rowArr=array();
	$tempArr=array();

	 

	$usersArr=db_fetch('we_users',' 1 order by `id` desc');
	if(count($usersArr) > 0)
	{
		foreach($usersArr as $arr)
		{
			$rowArr['id']     = $arr['id'];
			$rowArr['fname']  = $arr['fname'];
			$rowArr['lname']  = $arr['lname'];
			$rowArr['avatar'] = $arr['avatar'];
			
			$tempArr[]=$rowArr;
		}//foreach

		
	}
	else
	{
		$tempArr[]='';
	}
	

	
	$resultArr=json_encode($tempArr,JSON_NUMERIC_CHECK);
	return $resultArr;
}