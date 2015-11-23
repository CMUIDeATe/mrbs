<?php 
require_once "theme.inc";
header("Content-type: text/css"); 

// $Id: mrbs-js-overrides.css.php 1092 2009-04-16 13:23:57Z cimorrison $

// Only used if JavaScript is enabled

?>

<?php
// Over-rides for multiple bookings.  If JavaScript is enabled then we want to see the JavaScript controls.
// And we will need to extend the padding so that the controls don't overwrite the booking text
?>

div.multiple_control {
    display: block;   /* if JavaScript is enabled then we want to see the JavaScript controls */
  }
.multiple_booking .maxi a {padding-left: <?php echo $main_cell_height + $main_table_cell_border_width + 2 ?>px}
