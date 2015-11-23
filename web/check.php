<?Php
if(!isset($_SESSION['userid'])){
echo "<center><font face='Verdana' size='2' color=red>Sorry, Please <a href=login.php>login</a> and use this page </font></center>";
exit;
}else{
echo "<center><font face='Verdana' size='2' color=green>Welcome $_SESSION[userid] . <a href=update-profile.php>Update Profile</a> . <a href=change-password.php>Change Password</a> . <a href=logout.php>Logout</a></font></center>";
}
?>