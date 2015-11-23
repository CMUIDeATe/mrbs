<?Php
//***************************************
// This is downloaded from www.plus2net.com //
/// You can distribute this code with the link to www.plus2net.com ///
//  Please don't  remove the link to www.plus2net.com ///
// This is for your learning only not for commercial use. ///////
//The author is not responsible for any type of loss or problem or damage on using this script.//
/// You can use it at your own risk. /////
//*****************************************
include "include/session.php";

include "config.php";
//////////////////////////////

/*
while (list ($key,$val) = each ($_POST)) {
$$key = $val;
}
*/

?>
<!doctype html public "-//w3c//dtd html 3.2//en">

<html>

<head>
<title>Update profile in plus signup script plus2net.com</title>
</head>

<body>
<?Php
require "check.php";

$todo=$_POST['todo'];
$name=$_POST['name'];
$email=$_POST['email'];
$sex=$_POST['sex'];
// check the login details of the user and stop execution if not logged in

if(isset($todo) and $todo=="update-profile"){

// set the flags for validation and messages
$status = "OK";
$msg="";

// if name is less than 5 char then status is not ok
if (strlen($name) < 5) {
$msg=$msg."Your name  must be more than 5 char length<BR>";
$status= "NOTOK";}	

// you can add email validation here if required. 
// The code for email validation is available at www.plus2net.com

if($status<>"OK"){ // if validation failed
echo "<font face='Verdana' size='2' color=red>$msg</font><br><input type='button' value='Retry' onClick='history.go(-1)'>";
}else{ // if all validations are passed.
//////////////////////////////////////////////////////////
$sql=$dbo->prepare("update plus_signup set name=:name,email=:email,sex=:sex where userid='$_SESSION[userid]'");
$sql->bindParam(':name',$name,PDO::PARAM_STR, 25);
$sql->bindParam(':email',$email,PDO::PARAM_STR, 15);
$sql->bindParam(':sex',$sex,PDO::PARAM_STR, 7);
if($sql->execute()){
echo "<font face='Verdana' size='2' color=green>You have successfully updated your profile<br></font>";
}// End of if profile is ok 
else{
print_r($sql->errorInfo()); // if any error is there it will be posted
$msg=" <font face='Verdana' size='2' color=red>There is some problem in updating your profile. Please contact site admin<br></font>";
}// end of if else if database updation failed
}// end of if else for satus<> ok
echo $msg;
}// end of todo to check form inputs
require "bottom.php";
?>
<center>
<br><br><a href='http://www.plus2net.com' rel="nofollow">PHP SQL HTML free tutorials and scripts</a></center> 

</body>
</html>
