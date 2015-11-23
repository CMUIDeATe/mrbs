<?php
/*****************************************************************************
*
*   File name       auth_db_ext.inc
*
*   Description     Authenticate users from a table in another database.
*
*   Notes           To use this authentication scheme, set in config.inc.php:
*         $auth["type"]  = "db_ext";
*                   Assumes passwords are stored in the other table in
*                   plaintext, authValidateUser() will need to be changed if
*                   the password is stored differently.
*
*   History
*    Available in the source control system
*
******************************************************************************/

// $Id: auth_db_ext.inc 1115 2009-05-28 22:23:30Z jberanek $

include_once('wp-helpers/class-phpass.php' );
//include_once('C:/wamp/www/sandbox/wordpress/wp-includes/class-phpass.php' );

function authValidateUser($user, $pass)
{
  global $auth;

  $retval = 0;

  $user = strtolower($user);

  if (empty($auth['db_ext']['db_system']))
  {
    $auth['db_ext']['db_system'] = 'mysqli';
  }

  $conn = sql_connect($auth['db_ext']['db_system'],
                      $auth['db_ext']['db_host'],
                      $auth['db_ext']['db_username'],
                      $auth['db_ext']['db_password'],
                      $auth['db_ext']['db_name']);

  // wordpress' username that his password going to compare
	$user_name = htmlspecialchars($user,ENT_QUOTES);

  // plain password to compare
	$password = $pass;

	$hasher = new PasswordHash(8, TRUE);

  $query = "SELECT " . $auth['db_ext']['column_name_password'] .
           " FROM " . $auth['db_ext']['db_table'] .
           " WHERE ". $auth['db_ext']['column_name_username'] . "='$user_name'";

	$r = sql_query($query, $conn);

if ($r && (sql_count($r, $conn) == 1)) // force a unique match
  {
    $row = sql_row($r, 0, $conn);

    switch ($auth['db_ext']['password_format'])
    {
      case 'md5':
        if (md5($pass) == $row[0])
        {
          $retval = 1;
        }
        break;

      case 'sha1':
        if (sha1($pass) == $row[0])
        {
          $retval = 1;
        }
        break;

      case 'crypt':
        $recrypt = crypt($pass,$row[0]);
        if ($row[0] == $recrypt)
        {
          $retval = 1;
        }
        break;

	  case 'wp':
		if ($hasher->CheckPassword( $password, $row[0] ))
		{
    	$retval = 1;
		} 
        break;

      default:
        // Otherwise assume plaintext

        // Backwards-compatibility config option
        if ($auth['db_ext']['use_md5_passwords'] == 1)
        {
          $pass = md5($pass);
        }

        if ($pass == $row[0])
        {
          $retval = 1;
        }
        break;
    }
  }

  return $retval;
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
  global $auth;

  $retval = 0;

  if (empty($auth['db_ext']['db_system']))
  {
    $auth['db_ext']['db_system'] = 'mysqli';
  }

  $conn = sql_connect($auth['db_ext']['db_system'],
                      $auth['db_ext']['db_host'],
                      $auth['db_ext']['db_username'],
                      $auth['db_ext']['db_password'],
                      $auth['db_ext']['db_name']);

  // wordpress' username that level going to compare
	$user_name = htmlspecialchars($user,ENT_QUOTES);


  $query = "SELECT " . $auth['db_ext']['column_name_level'] .
           " FROM " . $auth['db_ext']['db_table'] .
           " WHERE ". $auth['db_ext']['column_name_username'] . "='$user_name'";

	$r = sql_query($query, $conn);

if ($r && (sql_count($r, $conn) == 1)) // force a unique match
  {
    $row = sql_row($r, 0, $conn);
	$retval = $row[0];
  }

return $retval;

}

?>
