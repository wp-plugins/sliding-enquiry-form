<?php


	$to = $_POST['serveremailid'];	
	
	$name = $_POST['name'];		
	$email = $_POST['email'];
	$number = $_POST['number'];	
	$message = $_POST['message'];
	$security_code1 = $_POST['captcha'];
	
	$subject = "Sliding Form Enquiry plugin";	
	
	$message = "Dear Sir/Mam,".	
				"\n\n".$subject.	
				"\n\nUser Name:".$name.		
				"\n\nUser Email Id:".$email.
				"\n\nUser Contact Number:".$number.	
				"\n\nUser Message:".$message.	
				"\n\nThank You"; 
				
	$from  = "from:".$email;	
	
	$headers = 'From: sliding-enquiry@example.com ' . "\r\n"  . 'X-Mailer: PHP/' . phpversion();			
	$m = mail($to,$subject,$message,$headers,'-fwebmaster@example.com');	
	
	if($m)
	{  
		echo 0;	
	}	
	else
	{
		echo 1;
	}

?>