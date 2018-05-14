<?php

error_reporting(E_ALL ^ E_NOTICE);

$g_mail = "m.zakuan.efandi@gmail.com";
$from_email = "";
$continue = "https://zakuanefandi.github.io/";

$errors = array();

// Remove $_COOKIE elements from $_REQUEST.

if(count($_COOKIE)){
	foreach(array_keys($_COOKIE) as $value){
		unset($_REQUEST[$value]);
	}
}

// Validate email field.

$req_email = $_REQUEST['email'];

if(isset($req_email) && !empty($req_email)){
	$req_email = trim($req_email);
	if(substr_count($req_email,"@") != 1 || stristr($req_email," ")){
		$errors[] = "Email address is invalid";
	}else{
		$exploded_email = explode("@",$req_email);
		if(empty($exploded_email[0]) || strlen($exploded_email[0]) > 64 || empty($exploded_email[1])){
			$errors[] = "Email address is invalid";
		}else{
			if(substr_count($exploded_email[1],".") == 0){
				$errors[] = "Email address is invalid";
			}else{
				$exploded_domain = explode(".",$exploded_email[1]);
				if(in_array("",$exploded_domain)){
					$errors[] = "Email address is invalid";
				}else{
					foreach($exploded_domain as $value){
						if(strlen($value) > 63 || !preg_match('/^[a-z0-9-]+$/i',$value)){
							$errors[] = "Email address is invalid"; 
							break;
						}
					}
				}
			}
		}
	}
}

// Check referrer is from same site.

if(!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],"zakuanefandi.github.io"))){
	$errors[] = "You must enable referrer logging to use the form";
}

// Check for a blank form.

function recursive_array_check_blank($element_value)
{
	global $set;
	if(!is_array($element_value)){
		if(!empty($element_value)){
			$set = 1;
		}
	}else{
		foreach($element_value as $value){
			if($set){
				break;
			}
		recursive_array_check_blank($value);
		}
	}

}

recursive_array_check_blank($_REQUEST);

if(!$set){
	$errors[] = "You cannot send a blank form";
}

unset($set);

// Display any errors and exit if errors exist.

if(count($errors)){
	foreach($errors as $value){
		print "$value<br>";
	}
	exit;
}

if(!defined("PHP_EOL")){
	define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "" : "");
}

// Build message.

function build_message($request_input){
	if(!isset($message_output)){
		$message_output ="";
	}
	if(!is_array($request_input)){
		$message_output = $request_input;
	}else{
		foreach($request_input as $key => $value){
			if(!empty($value)){
				if(!is_numeric($key)){
					$message_output .= str_replace("_"," ",ucfirst($key)).": ".build_message($value).PHP_EOL.PHP_EOL;
				}else{
					$message_output .= build_message($value).", ";
				}
			}
		}
	}
	return rtrim($message_output,", ");
}

$message = build_message($_REQUEST);

$message = $message . PHP_EOL.PHP_EOL."-- ".PHP_EOL."Thank you for using our contact form.";

$message = stripslashes($message);

$subject = "Contact Form";

$subject = stripslashes($subject);

if($from_email){
	$headers = "From: " . $from_email;
	$headers .= PHP_EOL;
	$headers .= "Reply-To: " . $req_email;
}else{
	$from_name = "";
	if(isset($_REQUEST['name']) && !empty($_REQUEST['name'])){$from_name = stripslashes($_REQUEST['name']);}
	$headers = "From: {$from_name} <{$req_email}>";
}

mail($g_mail,$subject,$message,$headers);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Just some title</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#ffffff" text="#000000">
<div>
<center>
<b>Thank you
	<?php 
	if(isset($_REQUEST['name'])){
		print stripslashes($_REQUEST['name']);
	}
	?>
</b>
<br>Your message has been sent
<p><a href="<?php print $continue; ?>">Click here to continue</a></p>
</center>
</div>
</body>
</html>