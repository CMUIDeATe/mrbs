<?Php
//***************************************
// This is downloaded from www.plus2net.com //
/// You can distribute this code with the link to www.plus2net.com ///
//  Please don't  remove the link to www.plus2net.com ///
// This is for your learning only not for commercial use. ///////
//The author is not responsible for any type of loss or problem or damage on using this script.//
/// You can use it at your own risk. /////
//*****************************************

require_once "Mail/mail.php";

require_once "Mail/sendmail.php";
include "include/session.php";

include "config.php"; // database connection details stored here
//////////////////////////////
$email=$_POST['email'];
// Change the URL below to match your site
$site_url="https://resources.ideate.cmu.edu/reservations/";
?>
<!doctype html public "-//w3c//dtd html 3.2//en">

<html>

<head>
<title>Forgot Password</title>
</head>

<body>
<?Php
$status = "OK";
$msg="";
//error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR);
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){ 
$msg="Your email address is not correct<BR>"; 
$status= "NOTOK";}


echo "<br><br>";
if($status=="OK"){  
$count=$dbo->prepare("SELECT `email`,`name`, `id`, `level`, `Affiliation`, `grad_year` FROM `mrbs_users` WHERE `email` = '$email'");
$count->execute();
$row = $count->fetch(PDO::FETCH_OBJ);
$no=$count->rowCount();
//echo " No of records = ".$no; 

$em=$row->email;// email is stored to a variable
if ($no == 0) {  echo "<center><font face='Verdana' size='2' color=red><b>No Password</b><br> Sorry Your address is not there in our database . You can signup and login to use our site. <BR><BR><input type='button' value='Retry' onClick='history.go(-1)'> . <a href='signup.php'> Sign UP </a> </center>"; exit;}

/// check if activation is pending /////
//$tm=time() - 86400; // Time in last 24 hours
//$count=$dbo->prepare("SELECT userid FROM plus_key WHERE userid = '$row->userid' and time > $tm and status='pending'");
//$count->execute();
//$no=$count->rowCount();
//echo " No of records = ".$no; 
//if($no==1){
//echo "<center><font face='Verdana' size='2' color=red><b>Your password activation Key is already posted to your email address, please check your Email address & Junk mail folder. "; 
//exit;
//}

/////////////// Let us send the email with key /////////////
/// function to generate random number ///////////////
function random_generator($digits){
srand ((double) microtime() * 10000000);
//Array of alphabets
$input = array ("A", "B", "C", "D", "E","F","G","H","I","J","K","L","M","N","O","P","Q",
"R","S","T","U","V","W","X","Y","Z");

$random_generator="";// Initialize the string to store random numbers
for($i=1;$i<$digits+1;$i++){ // Loop the number of times of required digits

if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet
// Add one random alphabet 
$rand_index = array_rand($input);
$random_generator .=$input[$rand_index]; // One char is added

}else{

// Add one numeric digit between 1 and 10
$random_generator .=rand(1,10); // one number is added
} // end of if else

} // end of for loop 

return $random_generator;
} // end of function


$key=random_generator(10);
$key=($key);
//echo "insert into plus_key(userid, pkey,time,status) values('$row->userid','$key','$tm','pending'";
$sql=$dbo->prepare("insert into mrbs_users(name, password, email) values('$row->name','$key', '$row->email' )");
$sql->execute();
//print_r($sql->errorInfo()); 

$headers4="admin@sitename.com";         ///// Change this address within quotes to your address ///
$headers.="Reply-to: $headers4\n";
$headers .= "From: $headers4\n"; 
$headers .= "Errors-to: $headers4\n"; 
//$headers = "Content-Type: text/html; charset=iso-8859-1\n".$headers;// for html mail un-comment this line
$site_url=$site_url."activepassword.php?ak=$key&userid=$row->userid";
 //echo $site_url;
if(mail("$em","Your Request for login details","This is in response to your request for login detailst at site_name \n \nLogin ID: $row->userid \n To reset your password, please visit this link( or copy and paste this link in your browser window )\n\n
\n\n
$site_url
\n\n
<a href='$site_url'>$site_url</a>

 \n\n Thank You \n \n siteadmin","$headers")){echo "<center><font face='Verdana' size='2' ><b>THANK YOU</b> <br>Your password is posted to your emil address . Please check your mail after some time. </center>";}
else{ echo " <center><font face='Verdana' size='2' color=red >There is some system problem in sending login details to your address. Please contact site-admin. <br><br><input type='button' value='Retry' onClick='history.go(-1)'></center></font>";}


	} 


//$site_url=$site_url."activepassword.php?ak=$key&name=$row->name";
//$subject = "Your Request for Login Details";
//$recipients = "$em"
//$txt = "hello, this is the text field";
//$headers = "From: pzalidesine@gmail.com" . "\r\n" .
//"CC: pzali@cmu.edu";

//if(mail("$em","$subject","$txt","$headers")){echo "<center><font face='Verdana' size='2' ><b>THANK YOU</b> <br>Your password is posted to your email address . Please check your mail after some time. </center>";}
//else{ echo " <center><font face='Verdana' size='2' color=red >There is some system problem in sending login details to your address. Please contact site-admin. <br><br><input type='button' value='Retry' onClick='history.go(-1)'></center></font>";}


//	}



//$headers4="pzalidesine@gmail.com"; ///// Change this address within quotes to your address ///
//$headers = "Reply-to: $headers4";

//$headers = "Content-Type: text/html; charset=iso-8859-1\n".$headers;// for html mail un-comment this line
//$site_url=$site_url."activepassword.php?ak=$key&name=$row->name";
 //echo $site_url;
//if(mail("$em","Your Request for login details","This is in response to your request for login detailst at site_name \n \nLogin ID: $row->name \n To reset your password, please visit this link( or copy and paste this link in your browser window )\n\n
//\n\n
//$site_url
//\n\n
//<a href='$site_url'>$site_url</a>

// \n\n Thank You \n \n siteadmin","$headers")){echo "<center><font face='Verdana' size='2' ><b>THANK YOU</b> <br>Your password is posted to your email address . Please check your mail after some time. </center>";}
//else{ echo " <center><font face='Verdana' size='2' color=red >There is some system problem in sending login details to your address. Please contact site-admin. <br><br><input type='button' value='Retry' onClick='history.go(-1)'></center></font>";}


//	} 

	else {echo "<center><font face='Verdana' size='2' color=red >$msg <br><br><input type='button' value='Retry' onClick='history.go(-1)'></center></font>";}
?>
<center>
<br><br><a href='https://resources.ideate.cmu.edu/reservations/' rel="nofollow">Back to Reservation System</a></center> 

</body>

</html>
