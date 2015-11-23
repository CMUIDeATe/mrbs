<?Php
//***************************************
// This is downloaded from www.plus2net.com //
/// You can distribute this code with the link to www.plus2net.com ///
//  Please don't  remove the link to www.plus2net.com ///
// This is for your learning only not for commercial use. ///////
//The author is not responsible for any type of loss or problem or damage on using this script.//
/// You can use it at your own risk. /////
//*****************************************

include "config.php"; // database connection details stored here
// Collect the data from post method of form submission // 
$userid=$_POST['userid'];
$password=$_POST['password'];
$password2=$_POST['password2'];
$agree=$_POST['agree'];
$todo=$_POST['todo'];
$email=$_POST['email'];
$name=$_POST['name'];
$sex=$_POST['sex'];

?>
<!doctype html public "-//w3c//dtd html 3.2//en">

<html>

<head>
<title>Signup page for plus2net.com </title>
</head>

<body >
<?Php
if(isset($todo) and $todo=="post"){

$status = "OK";
$msg="";

// if userid is less than 3 char then status is not ok
if(!isset($userid) or strlen($userid) <3){
$msg=$msg."User id should be =3 or more than 3 char length<BR>";
$status= "NOTOK";}					

if(!ctype_alnum($userid)){
$msg=$msg."User id should contain alphanumeric  chars only<BR>";
$status= "NOTOK";}					


$count=$dbo->prepare("select userid from plus_signup where userid=:userid");
$count->bindParam(":userid",$userid);
$count->execute();
$no=$count->rowCount();

if($no >0 ){
$msg=$msg."User Name already exists. Choose a different User Name<br>";
$status= "NOTOK";
}

$count=$dbo->prepare("select email from plus_signup where email=:email");
$count->bindParam(":email",$email);
$count->execute();
$no=$count->rowCount();
if($no >0 ){
$msg=$msg."This email address is there with us. If you forgot your password you can activate it by using forgot password link. Or Please try another one<BR>";
$status= "NOTOK";
}



if ( strlen($password) < 3 ){
$msg=$msg."Password must be more than 3 char legth<BR>";
$status= "NOTOK";}					

if ( $password <> $password2 ){
$msg=$msg."Both passwords are not matching<BR>";
$status= "NOTOK";}					


if ($agree<>"yes") {
$msg=$msg."You must agree to terms and conditions<BR>";
$status= "NOTOK";}	
if($status<>"OK"){ 
echo "<font face='Verdana' size='2' color=red>$msg</font><br><input type='button' value='Retry' onClick='history.go(-1)'>";
}else{ // if all validations are passed.
$password_original = $password;
$password=md5($password); // Encrypt the password before storing
$sql=$dbo->prepare("insert into plus_signup(userid,password,email,name,sex) values(:userid,:password,:email,:name,:sex)");
$sql->bindParam(':userid',$userid,PDO::PARAM_STR, 15);
$sql->bindParam(':password',$password,PDO::PARAM_STR, 32);
$sql->bindParam(':email',$email,PDO::PARAM_STR, 75);
$sql->bindParam(':name',$name,PDO::PARAM_STR);
$sql->bindParam(':sex',$sex,PDO::PARAM_STR);
if($sql->execute()){
//echo " Inside ok loop ";
$mem_id=$dbo->lastInsertId(); 
/////////////////Posting confirmation mail ///////////////
$em="userid@domain.com";    // Change to your email address 
$headers4=$em;
$headers="";
$headers.="Reply-to: $headers4\n";
$headers .= "From: $headers4\n"; 
$headers .= "Errors-to: $headers4\n"; 
//$headers = "Content-Type: text/html; charset=iso-8859-1\n".$headers;

$content="Your login details from ******  \n\n";
$content .="User ID= $userid \n";
$content .="Password = $password_original \n";

//echo $content;
$sub="Your login details";
//mail($email,"$sub",$content,$headers);
echo "<font face='Verdana' size='2' color=green>Welcome, You have successfully signed up<br><br><a href=login.php>Click here to login</a><br></font>";
//////////////// End of posting mail ////////
}// if sql executed 
else{print_r($sql->errorInfo()); }

}
} // end of todo if condition
?>
<center>
<br><br><a href='http://www.plus2net.com' rel="nofollow">PHP SQL HTML free tutorials and scripts</a></center> 

</body>
</html>
