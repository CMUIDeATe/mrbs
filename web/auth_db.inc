<?php
/*****************************************************************************
*
*   File name       auth_db.inc
*
*   Description     Authenticate users from a table in the MRBS database.
*
*   Notes           To use this authentication scheme, set in config.inc.php:
*         $auth["type"]  = "db";
*
*   History
*    Available in the source control system
*
******************************************************************************/

// $Id: auth_db.inc 1141 2009-06-29 12:03:30Z cimorrison $

/* session_php.inc and session_cookie.inc will add a link to the user list
    in the logon box, if the value $user_list_link is set. */
$user_list_link = "edit_users.php";


/* authValidateUser($user, $pass)
 * 
 * Checks if the specified username/password pair are valid
 * 
 * $user  - The user name
 * $pass  - The password
 * 
 * Returns:
 *   0        - The pair are invalid or do not exist
 *   non-zero - The pair are valid
 */

  

function authValidateUser( $user, $pass ) 
{
	global $tbl_users;
	$user = strtolower(addslashes($user));
    // Select the users's password hash from the database
    $stored = sql_query1( "SELECT user_pass FROM $tbl_users WHERE user_login = '$user';" );

    require_once( 'wp-helpers/class-phpass.php');
    $hasher = new PasswordHash(8, TRUE);

	return max(0, sql_query1("select count(*) from $tbl_users where user_login='$user' and user_pass='$hasher';"));
  // The max is used to cover the case when there's an SQL Query error (ie -1), probably because $tbl_users
  // hasn't been set up yet.   In that case we want to return 0 (ie validation failed)  
}



/* authGetUserLevel($user)
 * 
 * Determines the users access level
 * 
 * $user - The user name
 *
 * Returns:
 *   The users access level
 */
function authGetUserLevel($user)
{
  global $tbl_users;
  // User not logged in, user level '0'
  if(!isset($user))
  {
    return 0;
  }
  
  return max (0, sql_query1("SELECT level FROM $tbl_users WHERE user_login='$user' LIMIT 1"));
  // The max is used to cover the case when there's an SQL Query error (ie -1), probably because
  // the database hasn't yet been upgraded and there's no 'level' column.   In that case we want 
  // to return 0 (ie the user has no rights)
}

?>
