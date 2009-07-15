<?php

// $Id: version.inc 1152 2009-07-15 22:36:28Z jberanek $

// This should probably be a class, but you can only have protected
// class members in PHP 5, so we won't bother

function get_mrbs_version()
{
  // MRBS developers, make sure to update this string before each release
  $mrbs_version = "MRBS 1.4.2";

  return $mrbs_version;
}

?>
