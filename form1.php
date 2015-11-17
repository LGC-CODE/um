<?php 

$mailto = 'luis constante:_:luisconstante@yahoo.com,francisco bautista:_:universalmagazine@yahoo.com';
$emailSubject = 'New UM Msg';
$headers = '';
$emailFrom = '';
$longDate = 1;
$submissionTime = 1;
$referringPage = 0;
$ipAddress = 0;


if(isset($_POST["name"])){

do{

if($err = validateInputs('name', '', '')){
					     
						 echo $err ;
						 break;
					 }
if($err = validateInputs('name', 'textonly', '')){
						 echo $err ;
						 break;
					 }
if($err = validateInputs('Email', '', '')){
					     
						 echo $err ;
						 break;
					 }
if($err = validateInputs('Email', 'validateemail', '')){
						 echo $err ;
						 break;
					 }
if($err = validateInputs('message', '', '')){
					     
						 echo $err ;
						 break;
					 }
}while(0);

}

if(!$err){
			
			 $inputField = "name:_:Email:_:message:_:";
			 $inputFieldExplode = explode(':_:',$inputField);
			 $message = '';	
			 $messageVar = "|NAME|
|EMAIL|
|MESSAGE|";

			 $i = 0;
			  
			  while($inputFieldExplode[$i]){
                 $labelNameVar = ucfirst($inputFieldExplode[$i]);
				$messageVar =  str_ireplace('|'.$inputFieldExplode[$i].'|',
				 '<br clear=\"all\">'.$labelNameVar.' : '.$_POST[$inputFieldExplode[$i]].'',$messageVar);
				 
				 $i++;
			  }
			  
			  $message .= $messageVar; 
 
			  $message .= "<br clear=\"all\"><br clear=\"all\">";

			  $message .= "----------------------------------------------";

			  
			  if($longDate == 1){
			   $message .= "<br clear=\"all\">Subission Date :&nbsp;".date("m-d-y")."<br clear=\"all\">" ;

			  }
			  
			   if($submissionTime == 1){
			   $message .= "<br clear=\"all\">Subission Time :&nbsp;".date("h:i:s")."<br clear=\"all\">" ;

			  }
			  
			  if($referringPage == 1){
			   $message .= "<br clear=\"all\">Referrer URL :&nbsp;".$_SERVER["HTTP_REFERER"]."<br clear=\"all\">" ;

			  }
			  
			 if($ipAddress == 1){
			   $message .= "<br clear=\"all\">IP Address :&nbsp;".$_SERVER["REMOTE_ADDR"]."<br clear=\"all\">" ;

			  }

		  	  $confirmationURL = "http://universalmagazine.net/confirmation.html";

			  
			  if(empty($emailFrom)) $emailFrom = $_REQUEST["Email"];
			  else $emailFrom = $emailFrom;

			  
			  $toExplode = explode(",", $mailto);
              $headers .= "From: <".$emailFrom.">". "\r\n";
			  $z = 0;
			  
			  while($toExplode[$z]){

				$toEmailListsExplode = explode(":_:", $toExplode[$z]);

				if(sendEmail($toEmailListsExplode[1], $emailSubject , $message, $headers )){  
					$status = 1;
				}

                $z++;
			  }
			  
			  if($status == 1) {
				   
				  if(!empty($confirmationURL)){
					  
					  header("location:".$confirmationURL);  
				  }
			  }else {
				
				echo "An error occured please try again later";  
			  }
			  
		  }

?>
<?php
		   
	  function validateInputs($labelName, $validation = '', $eqv = '')
	  {
		  
		if(empty($validation)){
			
			if(empty($_POST[$labelName])) $msg = displayMessage("Please Enter your ".ucfirst($labelName)."");
			else $msg = false;
		}
		else {
			
		
		  switch($validation){
			  
			  case 'numberOnly':
				  if(!is_numeric($_POST[$labelName]))
				  $msg = displayMessage("Please Enter a Numeric values");
				 else $msg = false;	
			  break ;
			  
			  case 'textonly':
			  
				if( preg_match('([a-zA-Z].*[0-9]|[0-9].*[a-zA-Z])', $_POST[$labelName]) || preg_match('/^\d+$/',$_POST[$labelName]) ) 
				  $msg = displayMessage("Please ".ucfirst($labelName)." Should be only a string&nbsp;");
				else $msg = false;	
			  break;
			  
			  case 'validateemail':
				 if(!isValidEmail($_POST[$labelName]))
				   $msg = displayMessage("Please Enter Valid Email Address");
				 else $msg = false;	 
			  break;
			  
			  case 'eqtoval':
				  if($_POST[$labelName] != $eqv)
				   $msg = displayMessage("".ucfirst($labelName)." Equal to value is not matching");
				  else $msg =  false;  
			  break;
		  }
		}
		  
		  return $msg;
	  
	  }
		
	  function isValidEmail($email)
	  {
		  if (function_exists('filter_var')) {
			  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				  return true;
			  } else
				  return false;
		  } else
			  return preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $email);
	  } 
	  
	  function displayMessage($msg)
	  {
		return $msg;  
	  }
	  
	  function sendEmail($mailto , $subject , $message, $header )
	  {
		  $headers = '';
		  $headers .= 'MIME-Version: 1.0'."\r\n";
          $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
		  $headers .=  $header;
		  
		  return (mail($mailto, $subject, $message, $headers)) ?  1 : 0;
	  }
		
?>
