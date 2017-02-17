<?php

/**************************************************************************
 *   MRBS Configuration Secrets
 *   Configure this file for your site.
 **************************************************************************/

/*******************
 * Database settings
 ******************/
// Which database system: "pgsql"=PostgreSQL, "mysql"=MySQL,
// "mysqli"=MySQL via the mysqli PHP extension
$dbsys = "mysql";
// Hostname of database server. For pgsql, can use "" instead of localhost
// to use Unix Domain Sockets instead of TCP/IP.
$db_host = "localhost";
// Database name:
$db_database = "mrbs";
// Database login user name:
$db_login = "mrbs";
// Database login password:
$db_password = 'mrbs-password';
// Prefix for table names.  This will allow multiple installations where only
// one database is available
$db_tbl_prefix = "mrbs_";
// Uncomment this to NOT use PHP persistent (pooled) database connections:
// $db_nopersist = 1;

/***********************************************
 * Authentication settings - read AUTHENTICATION
 ***********************************************/

// Configuration parameters for 'cookie' session scheme

// The encryption secret key for the session tokens. You are strongly
// advised to change this if you use this session scheme
$auth["session_cookie"]["secret"] = "This isn't a very good secret!";

// The list of administrators (can modify other peoples settings).
//
// This list is not needed when using the 'db' authentication scheme EXCEPT
// when upgrading from a pre-MRBS 1.4.2 system that used db authentication.
// Pre-1.4.2 the 'db' authentication scheme did need this list.   When running
// edit_users.php for the first time in a 1.4.2 system or later, with an existing
// users list in the database, the system will automatically add a field to
// the table for access rights and give admin rights to those users in the database
// for whom admin rights are defined here.   After that this list is ignored.
$auth["admin"][] = "127.0.0.1";     // localhost IP address. Useful with IP sessions.
$auth["admin"][] = "administrator"; // A user name from the user list. Useful 
                                    // with most other session schemes.
//$auth["admin"][] = "10.0.0.1";
//$auth["admin"][] = "10.0.0.2";
//$auth["admin"][] = "10.0.0.3";

// 'auth_config' user database
// Format: $auth["user"]["name"] = "password";
$auth["user"]["administrator"] = "secret";
$auth["user"]["alice"] = "a";
$auth["user"]["bob"] = "b";

// 'auth_db' configuration settings
// The highest level of access (0=none; 1=user; 2+=admin).    Used in edit_users.php
// In the future we might want a higher level of granularity, eg to distinguish between
// different levels of admin
$max_level = 2;
// The lowest level of admin allowed to edit other users
$min_user_editing_level = 2;

// 'auth_db_ext' configuration settings
// The 'db_system' variable is equivalent to the core MRBS $dbsys variable,
// and allows you to use any of MRBS's database abstraction layers for
// db_ext authentication.
$auth['db_ext']['db_system'] = 'mysql';
$auth['db_ext']['db_host'] = 'localhost';
$auth['db_ext']['db_username'] = 'authuser';
$auth['db_ext']['db_password'] = 'authpass';
$auth['db_ext']['db_name'] = 'authdb';
$auth['db_ext']['db_table'] = 'users';
$auth['db_ext']['column_name_username'] = 'name';
$auth['db_ext']['column_name_password'] = 'password';
// Either 'md5', 'sha1', 'crypt' or 'plaintext'
$auth['db_ext']['password_format'] = 'md5';


/*******************
 * SMTP settings
 */

// Set the username to use for SMTP authentication. Default is ""
define ("SMTP_USERNAME", '');

// Set the password to use for SMTP authentication. Default is ""
define ("SMTP_PASSWORD", '');


?>
