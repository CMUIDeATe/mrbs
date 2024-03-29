<?php

// $Id: index.php 1069 2009-03-25 08:47:35Z cimorrison $

// Index is just a stub to redirect to the appropriate view
// as defined in config.inc.php using the variable $default_view
// If $default_room is defined in config.inc.php then this will
// be used to redirect to a particular room.

require_once "grab_globals.inc.php";
require_once "config.inc.php";
require_once "dbsys.inc";
require_once "mrbs_sql.inc";

$day   = date("d");
$month = date("m");
$year  = date("Y");

switch ($default_view)
{
  case "month":
    $redirect_str = "month.php?year=$year&month=$month";
    break;
  case "week":
    $redirect_str = "week.php?year=$year&month=$month&day=$day";
    break;
  default:
    $redirect_str = "day.php?year=$year&month=$month&day=$day";
}

if ( ! empty($default_room) )
{
  $area = mrbsGetRoomArea($default_room);
  $room = $default_room;
  $redirect_str .= "&area=$area&room=$room";
}

header("Location: $redirect_str");

?>
