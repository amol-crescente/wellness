<?php
require_once 'PHPMailer/PHPMailerAutoload.php';

function db_connect($dbdata)
{
	
	$host=$dbdata['host'];
	$username=$dbdata['user'];
	$password=$dbdata['pass'];
	$database=$dbdata['db'];
		
	$con = mysql_connect($host,$username,$password);
										if (!$con)
										  {
										  return false;
										  }  
	
	$_SESSION['app']['db_con']=$con;
	$_SESSION['app']['database']=$database;
	db_switch($database);
	return $con;
	
	
}

//Selecting the database
function db_switch($database)
{
	
	mysql_select_db($database, $_SESSION['app']['db_con']);
	
	return true;
}
//Getting the data from a table
function db_fetch($table, $condition)
{

    $connection=$_SESSION['app']['db_con'];
	$database=$_SESSION['app']['database'];
	
    
    
    
	//composing the conditions because $condition is an array 
	if (isset($condition)) $condition_string=' where '.$condition;
	
    //echo "select * from ".$table." ".$condition_string." <br />";
	
    $result=mysql_query("select * from ".$table." ".$condition_string."", $connection);
	while ($row_result = mysql_fetch_assoc($result))
	{
		$rows[]=$row_result;
		
	}
	
	
	return $rows;
	
}	
	
function db_fetch_ll($query)
{

	$connection=$_SESSION['app']['db_con'];
	$database=$_SESSION['app']['database'];

	$result=mysql_query($query, $connection);
	while ($row_result = mysql_fetch_assoc($result))
	{
		$rows[]=$row_result;

	}


	return $rows;

}	
		
		

function db_update ($table, $fields, $condition)
{
		$connection=$_SESSION['app']['db_con'];
		$database=$_SESSION['app']['database'];
	
		if (isset($fields)){
				$fields_string = ' SET ';
				foreach ($fields as $key => $values)
				{
					$fields_string .= ' '.$key.' = \''.$values.'\', '; 
					
					
				}
		}
		
		
		$query_string="UPDATE  `".$database."`.`".$table."` ".substr($fields_string, 0, -2)." WHERE  `".$table."`.".$condition;
		
        $updatequery = mysql_query($query_string, $connection);
		
        mysql_affected_rows()>0 ? $result = true:$result = false;
                        
        return $result;
		
		
}	
	
function db_add ($table, $fields)
{
			$connection=$_SESSION['app']['db_con'];
			$database=$_SESSION['app']['database'];
	       
			if (isset($fields)){
					$fields_string = '(';
					$values_string = 'VALUES (';
					
					
					foreach ($fields as $key => $values)
					{
						$fields_string .= $key.',';
						$values_string .= '\''.$values.'\',';
					
					
					}
					
					$fields_string=substr($fields_string,0,-1);
					$values_string=substr($values_string,0,-1);
					
					$fields_string .= ') ';
					$values_string .= ');';
					
			}
		
		    //echo "INSERT INTO  `".$database."`.`".$table."` ".$fields_string.$values_string;die;
			$query_string="INSERT INTO  `".$database."`.`".$table."` ".$fields_string.$values_string;
			// echo $query_string;
			mysql_query($query_string, $connection);
			$last_id=mysql_insert_id();
			return $last_id;
			
}

function send_mail($from_email,$from_name,$to,$subject,$message)
{
    $mail = new PHPMailer;
    
    $mail->isSMTP();                                   // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                    // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                            // Enable SMTP authentication
    $mail->Username = 'somnathk.crescente@gmail.com';          // SMTP username
    $mail->Password = 'Crescente@123'; // SMTP password
    $mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                 // TCP port to connect to
    
    $mail->setFrom($from_email,$from_name);
    $mail->addReplyTo($from_email,$from_name);
    $mail->addAddress($to);   // Add a recipient
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');
    
    $mail->isHTML(true);  // Set email format to HTML
    
    $bodyContent = $message;
    
    
    $mail->Subject = $subject;
    $mail->Body    = $bodyContent;
    
    if(!$mail->send()) 
    {
        $response = 0;
    }
    else 
    {
        $response = 1;
    }
    
    return $response;
	
}// function

function db_delete ($table, $condition)
{
	$connection=$_SESSION['app']['db_con'];	
	$deletequery = mysql_query ("delete from ".$table." where ".$condition, $connection);
    
    mysql_affected_rows()>0 ? $result = true:$result = false;
    
	return $result;
	
}		
