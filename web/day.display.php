<!--
  day.display.php
  Prepared by Tim Parenti, tparenti@andrew.cmu.edu, 2016-08-16
-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<style>
<?php
# Squelch PHP notice about undefined variable
$before_after_links_html = "";

if ($_GET['area'] == 7) {
	echo "body { padding: 2em 3em; }";
	echo "body, table { font-size: 100%; }";
	echo "#banner { display: none; }";
	echo "thead th a { font-size: 30px; }";
	echo "thead th.first_last { font-size: 0; }";

	echo "td.A, td.B, td.C, td.D, td.E, td.I { font-size: 180%; }";
}
elseif ($_GET['area'] == 8) {
	echo "body { padding: 2em 3em; }";
	echo "body, table { font-size: 100%; }";
	echo "#banner { display: none; }";
	echo "thead { display: none; }";

	echo "td.A, td.B, td.C, td.D, td.E, td.I { font-size: 180%; }";
}
else {
	echo "body, table { font-size: 100%; }";
	echo "#banner { display: none; }";
	echo "thead { display: none; }";

	echo "td.A, td.B, td.C, td.D, td.E, td.I { font-size: 200%; }";
}
?>

td.even_row { background-color: #f9fafb !important; }
td.odd_row { background-color: #f2f4f6 !important; }

#ideate-welcome { width: 40%; margin: 0 2%; display: block; float: left; text-align: center; }
#ideate-schedule { width: 55%; display: block; float: right; }

#ideate-welcome #ideate-logo { width: 100%; padding: 0.5em 0; }

#ideate-explain { margin: 0.5em 0; padding: 8em 0; border-top: 2px solid #c41230; border-bottom: 2px solid #c41230; }
#ideate-welcome #today { font-size: 50px; margin-bottom: 1em; }
#ideate-welcome #dow { font-size: 55px; font-weight: bold; color: #c41230; }
#ideate-welcome #date { font-size: 85px; font-weight: bold; color: #c41230; }
#ideate-welcome #pretext { font-size: 40px; margin: 0.5em 0; }
#ideate-welcome #roomName { font-size: 50px; color: #000; }
#ideate-welcome #roomNum { font-size: 40px; }

#ideate-welcome #instructions { font-size: 28px; padding: 0.5em; }
</style>

<?php
  # UNCOMMENT TO FAKE DATE FOR TESTS
  # $_GET['year']  = 2016;
  # $_GET['month'] = 9;
  # $_GET['day']   = 28;
?>

<script type="text/javascript">
<?php
echo "$(document).ready(function() {";
//	if ($_GET['area'] == 4) {
//		echo "$('th:nth-child(3)').hide();";
//		echo "$('td:nth-child(3)').hide();";
//		echo "$('td:nth-child(4)').hide();";
//	}
	if ($_GET['area'] == 7) {
		echo "$('td').css('width', '50%');";
	}
	echo "$('td:nth-child(1)').css('width', '5em');";
echo "});";
?>
</script>

<div id="ideate-schedule">

<?php
// $Id: day.php 1092 2009-04-16 13:23:57Z cimorrison $

require_once "grab_globals.inc.php";
require_once "config.inc.php";
require_once "functions.inc";
require_once "dbsys.inc";
require_once "mrbs_auth.inc";
require_once "mincals.inc";
require_once "theme.inc";

// Get form variables
$day = get_form_var('day', 'int');
$month = get_form_var('month', 'int');
$year = get_form_var('year', 'int');
$area = get_form_var('area', 'int');
$room = get_form_var('room', 'int');  // not needed for the main display, but needed for trailer links
$timetohighlight = get_form_var('timetohighlight', 'int');
$debug_flag = get_form_var('debug_flag', 'int');

if (empty($debug_flag))
{
  $debug_flag = 0;
}

if (empty($area))
{
  $area = get_default_area();
}

// Get the timeslot settings (resolution, etc.) for this area
get_area_settings($area);


// If we don't know the right date then use today:
if (!isset($day) or !isset($month) or !isset($year))
{
  $day   = date("d");
  $month = date("m");
  $year  = date("Y");
}
else
{
  // Make the date valid if day is more than number of days in month:
  while (!checkdate($month, $day, $year))
  {
    $day--;
    if ($day == 0)
    {
      $day   = date("d");
      $month = date("m");
      $year  = date("Y");   
      break;
    }
  }
}

// form the room parameter for use in query strings.    We want to preserve room information
// if possible when switching between views
if (empty($room))
{
  $room_param = "";
}
else
{
  $room_param = "&amp;room=$room";
}

// print the page header
// THIS IS HIDDEN IN THE DISPLAY VERSION
print_header($day, $month, $year, $area, isset($room) ? $room : "");

$format = "Gi";
if ( $enable_periods )
{
  $format = "i";
  $resolution = 60;
  $morningstarts = 12;
  $morningstarts_minutes = 0;
  $eveningends = 12;
  $eveningends_minutes = count($periods)-1;
}

// ensure that $morningstarts_minutes defaults to zero if not set
if ( empty( $morningstarts_minutes ) )
{
  $morningstarts_minutes=0;
}

// Define the start and end of each day in a way which is not affected by
// daylight saving...
// dst_change:
// -1 => no change
//  0 => entering DST
//  1 => leaving DST
$dst_change = is_dst($month,$day,$year);
$am7=mktime($morningstarts,$morningstarts_minutes,0,
            $month,$day,$year,is_dst($month,$day,$year,$morningstarts));
$pm7=mktime($eveningends,$eveningends_minutes,0,
            $month,$day,$year,is_dst($month,$day,$year,$eveningends));

?>

<?php

//y? are year, month and day of yesterday
//t? are year, month and day of tomorrow

// find the last non-hidden day
$d = $day;
do
{  
  $d--;
  $i= mktime(12,0,0,$month,$d,$year);
}
while (is_hidden_day(date("w", $i)) && ($d > $day - 7));  // break the loop if all days are hidden
$yy = date("Y",$i);
$ym = date("m",$i);
$yd = date("d",$i);

// find the next non-hidden day
$d = $day;
do
{
  $d++;
  $i= mktime(12,0,0,$month,$d,$year);
}
while (is_hidden_day(date("w", $i)) && ($d < $day + 7));  // break the loop if all days are hidden
$ty = date("Y",$i);
$tm = date("m",$i);
$td = date("d",$i);


//We want to build an array containing all the data we want to show
//and then spit it out. 

//Get all appointments for today in the area that we care about
//Note: The predicate clause 'start_time <= ...' is an equivalent but simpler
//form of the original which had 3 BETWEEN parts. It selects all entries which
//occur on or cross the current day.
$sql = "SELECT $tbl_room.id AS room_id, start_time, end_time, name, $tbl_entry.id AS entry_id, type,
        $tbl_entry.description AS entry_description, 
        $tbl_entry.private AS entry_private, $tbl_entry.create_by AS entry_create_by
   FROM $tbl_entry, $tbl_room
   WHERE $tbl_entry.room_id = $tbl_room.id
   AND area_id = $area
   AND start_time <= $pm7 AND end_time > $am7
   ORDER BY start_time";   // necessary so that multiple bookings appear in the right order
   
$res = sql_query($sql);
if (! $res)
{
  fatal_error(0, sql_error());
}

$today = array();

for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
{
  // Each row we've got here is an appointment.
  //  row['room_id'] = Room ID
  //  row['start_time'] = start time
  //  row['end_time'] = end time
  //  row['name'] = short description
  //  row['entry_id'] = id of this booking
  //  row['type'] = type (internal/external)
  //  row['entry_description'] = description
  //  row['entry_private'] = if entry is private
  //  row['entry_create_by'] = Creator/owner of entry
  
  map_add_booking($row, $today[$row['room_id']][$day], $am7, $pm7, $format);

}

if ($debug_flag) 
{
  echo "<p>DEBUG:<pre>\n";
  echo "\$dst_change = $dst_change\n";
  echo "\$am7 = $am7 or " . date($format,$am7) . "\n";
  echo "\$pm7 = $pm7 or " . date($format,$pm7) . "\n";
  if (gettype($today) == "array")
  {
    while (list($w_k, $w_v) = each($today))
    {
      while (list($t_k, $t_v) = each($w_v))
      {
        while (list($k_k, $k_v) = each($t_v))
        {
          echo "d[$w_k][$t_k][$k_k] = '$k_v'\n";
        }
      }
    }
  }
  else
  {
    echo "today is not an array!\n";
  }
  echo "</pre><p>\n";
}

// We need to know what all the rooms area called, so we can show them all
// pull the data from the db and store it. Convienently we can print the room
// headings and capacities at the same time

$sql = "select room_name, capacity, id, description from $tbl_room where area_id=$area order by 1";

$res = sql_query($sql);

// It might be that there are no rooms defined for this area.
// If there are none then show an error and don't bother doing anything
// else
if (! $res)
{
  fatal_error(0, sql_error());
}
if (sql_count($res) == 0)
{
  echo "<h1>".get_vocab("no_rooms_for_area")."</h1>";
  sql_free($res);
}
else
{
  // Include the active cell content management routines.
  // Must be included before the beginnning of the main table.
  if ($javascript_cursor) // If authorized in config.inc.php, include the javascript cursor management.
  {
    echo "<script type=\"text/javascript\" src=\"xbLib.js\"></script>\n";
    echo "<script type=\"text/javascript\">\n";
    echo "//<![CDATA[\n";
    echo "InitActiveCell("
      . ($show_plus_link ? "true" : "false") . ", "
      . "true, "
      . ((FALSE != $row_labels_both_sides) ? "true" : "false") . ", "
      . "\"$highlight_method\", "
      . "\"" . get_vocab("click_to_reserve") . "\""
      . ");\n";
    echo "//]]>\n";
    echo "</script>\n";
  }

  // START DISPLAYING THE MAIN TABLE
  echo "<table class=\"dwm_main\" id=\"day_main\">\n";
  ( $dst_change != -1 ) ? $j = 1 : $j = 0;
  
  // TABLE HEADER
  echo "<thead>\n";
  echo "<tr>\n";
  
  
  // We can display the table in two ways
  if ($times_along_top)
  {
    // with times along the top and rooms down the side
    $start_first_slot = ($morningstarts*60) + $morningstarts_minutes;   // minutes
    $start_last_slot  = ($eveningends*60) + $eveningends_minutes;       // minutes
    $start_difference = ($start_last_slot - $start_first_slot) * 60;    // seconds
    $n_slots = ($start_difference/$resolution) + 1;
    $column_width = (int)(95 / $n_slots);
    echo "<th class=\"first_last\">" . get_vocab("room") . ":</th>";
    for (
         $t = mktime($morningstarts, $morningstarts_minutes, 0, $month, $day+$j, $year);
         $t <= mktime($eveningends, $eveningends_minutes, 0, $month, $day+$j, $year);
         $t += $resolution
        )
    {
      echo "<th style=\"width: $column_width%\">";
      if ( $enable_periods )
      {
        // convert timestamps to HHMM format without leading zeros
        $time_t = date($format, $t);
        // and get a stripped version of the time for use with periods
        $time_t_stripped = preg_replace( "/^0/", "", $time_t );
        echo $periods[$time_t_stripped];
      }
      else
      {
        echo utf8_strftime(hour_min_format(),$t);
      }
      echo "</th>\n";
    }
    // next: line to display times on right side
    if ( FALSE != $row_labels_both_sides )
    {
      echo "<th class=\"first_last\">" . get_vocab("room") . ":</th>";
    }
  } // end "times_along_top" view (for the header)
  
  else
  {
    // the standard view, with rooms along the top and times down the side
    echo "<th class=\"first_last\">" . ($enable_periods ? get_vocab("period") : get_vocab("time")) . ":</th>";
  
    $column_width = (int)(95 / sql_count($res));
    for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
    {
      echo "<th style=\"width: $column_width%\">
              <a href=\"week.php?year=$year&amp;month=$month&amp;day=$day&amp;area=$area&amp;room=".$row['id']."\"
              title=\"" . get_vocab("viewweek") . " &#10;&#10;".$row['description']."\">"
        . htmlspecialchars($row['room_name']) . ($row['capacity'] > 0 ? "(".$row['capacity'].")" : "") . "</a></th>";
      $rooms[] = $row['id'];
    }
  
    // next line to display times on right side
    if ( FALSE != $row_labels_both_sides )
    {
      echo "<th class=\"first_last\">" . ( $enable_periods  ? get_vocab("period") : get_vocab("time") ) . ":</th>";
    }
  }  // end standard view (for the header)
  
  echo "</tr>\n";
  echo "</thead>\n";
  
  
  // TABLE BODY LISTING BOOKINGS
  echo "<tbody>\n";
  
  // This is the main bit of the display
  // We loop through time and then the rooms we just got

  // if the today is a day which includes a DST change then use
  // the day after to generate timesteps through the day as this
  // will ensure a constant time step
  
  // URL for highlighting a time. Don't use REQUEST_URI or you will get
  // the timetohighlight parameter duplicated each time you click.
  $hilite_url="day.php?year=$year&amp;month=$month&amp;day=$day&amp;area=$area$room_param&amp;timetohighlight";
  
  $row_class = "even_row";
  
  // We can display the table in two ways
  if ($times_along_top)
  {
    // with times along the top and rooms down the side
    for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++, $row_class = ($row_class == "even_row")?"odd_row":"even_row")
    {
      echo "<tr>\n";
      $room_id = $row['id']; 
      $room_cell_link = "week.php?year=$year&amp;month=$month&amp;day=$day&amp;area=$area&amp;room=$room_id";
      draw_room_cell($row, $room_cell_link);
      for (
           $t = mktime($morningstarts, $morningstarts_minutes, 0, $month, $day+$j, $year);
           $t <= mktime($eveningends, $eveningends_minutes, 0, $month, $day+$j, $year);
           $t += $resolution
          )
      {
        // convert timestamps to HHMM format without leading zeros
        $time_t = date($format, $t);
        // and get a stripped version of the time for use with periods
        $time_t_stripped = preg_replace( "/^0/", "", $time_t );
        
        // calculate hour and minute (needed for links)
        $hour = date("H",$t);
        $minute = date("i",$t);
        
        // set up the query strings to be used for the link in the cell
        $query_strings = array();
        $query_strings['new_periods'] = "area=$area&amp;room=$room_id&amp;period=$time_t_stripped&amp;year=$year&amp;month=$month&amp;day=$day";
        $query_strings['new_times']   = "area=$area&amp;room=$room_id&amp;hour=$hour&amp;minute=$minute&amp;year=$year&amp;month=$month&amp;day=$day";
        $query_strings['booking']     = "area=$area&amp;day=$day&amp;month=$month&amp;year=$year";
        // and then draw the cell
        if (!isset($today[$room_id][$day][$time_t]))
        {
          $today[$room_id][$day][$time_t] = array();  // to avoid an undefined index NOTICE error
        }   
        $cell_class = $row_class;
        draw_cell($today[$room_id][$day][$time_t], $query_strings, $cell_class);
      }  // end for (looping through the times)
      if ( FALSE != $row_labels_both_sides )
      {
        draw_room_cell($row, $room_cell_link);
      }
      echo "</tr>\n";
    }  // end for (looping through the rooms)
  }  // end "times_along_top" view (for the body)
  
  else
  {
    // the standard view, with rooms along the top and times down the side
    for (
         $t = mktime($morningstarts, $morningstarts_minutes, 0, $month, $day+$j, $year);
         $t <= mktime($eveningends, $eveningends_minutes, 0, $month, $day+$j, $year);
         $t += $resolution, $row_class = ($row_class == "even_row")?"odd_row":"even_row"
        )
    {
      // convert timestamps to HHMM format without leading zeros
      $time_t = date($format, $t);
      // and get a stripped version of the time for use with periods
      $time_t_stripped = preg_replace( "/^0/", "", $time_t );
      
      // calculate hour and minute (needed for links)
      $hour = date("H",$t);
      $minute = date("i",$t);
  
      // Show the time linked to the URL for highlighting that time
      echo "<tr>";
      draw_time_cell($t, $time_t, $time_t_stripped, $hilite_url);
  
      // Loop through the list of rooms we have for this area
      while (list($key, $room_id) = each($rooms))
      {
        // set up the query strings to be used for the link in the cell
        $query_strings = array();
        $query_strings['new_periods'] = "area=$area&amp;room=$room_id&amp;period=$time_t_stripped&amp;year=$year&amp;month=$month&amp;day=$day";
        $query_strings['new_times']   = "area=$area&amp;room=$room_id&amp;hour=$hour&amp;minute=$minute&amp;year=$year&amp;month=$month&amp;day=$day";
        $query_strings['booking']     = "area=$area&amp;day=$day&amp;month=$month&amp;year=$year";
        // and then draw the cell
        if (!isset($today[$room_id][$day][$time_t]))
        {
          $today[$room_id][$day][$time_t] = array();  // to avoid an undefined index NOTICE error
        }
        if (isset($timetohighlight) && ($time_t == $timetohighlight))
        {
          $cell_class = "row_highlight";
        }
        else
        {
          $cell_class = $row_class;
        }
        draw_cell($today[$room_id][$day][$time_t], $query_strings, $cell_class);
      }
      
      // next lines to display times on right side
      if ( FALSE != $row_labels_both_sides )
      {
        draw_time_cell($t, $time_t, $time_t_stripped, $hilite_url);
      }
  
      echo "</tr>\n";
      reset($rooms);
    }
  }  // end standard view (for the body)
  
  echo "</tbody>\n";
  echo "</table>\n";

  print $before_after_links_html;

}

?>

</div> <?php // end #ideate-schedule ?>

<?php
  echo "<div id=\"ideate-welcome\">";

  echo "<img id=\"ideate-logo\" src=\"wp-helpers/ideatelogo.png\">";
  echo "<div id=\"ideate-explain\">";

    switch ($_GET['area']) {
      case 4:
        $todayHeading = "Today&rsquo;s Reservations";
        $pretext = 'in';
        $roomName = 'Experimental Fabrication';
        $roomNum = 'Hunt A5';
        $instructions = 'Reserve online at<br /><strong>resources.ideate.cmu.edu/reservations</strong>';
        break;
      case 5:
        $todayHeading = "Today&rsquo;s Reservations";
        $pretext = 'in the';
        $roomName = 'Media Lab';
        $roomNum = 'Hunt A10A';
        $instructions = 'Reserve online at<br /><strong>resources.ideate.cmu.edu/reservations</strong>';
        break;
      case 6:
        $todayHeading = "Today&rsquo;s Reservations";
        $pretext = 'in';
        $roomName = 'Physical Computing';
        $roomNum = 'Hunt A10';
        $instructions = 'Reserve online at<br /><strong>resources.ideate.cmu.edu/reservations</strong>';
        break;
      case 7:
        $todayHeading = "Today&rsquo;s Reservations";
        $pretext = 'in';
        $roomName = 'Studios A & B';
        $roomNum = 'Hunt 106B / 106C';
        $instructions = 'Reserve online at<br /><strong>resources.ideate.cmu.edu/reservations</strong>';
        break;
      case 8:
        $todayHeading = "Today&rsquo;s Hours of Operation";
        $pretext = 'at the';
        $roomName = 'IDeATe Lending Booth';
        $roomNum = 'Hunt A29';
        $instructions = 'For service requests, email<br /><strong>help@ideate.cmu.edu</strong>';
        break;
      default:
    }

    // Show current date
    echo "<div id=\"today\">".$todayHeading."</div>";
    echo "<div id=\"dow\">".utf8_strftime("%A", $am7)."</div>";
    echo "<div id=\"date\">".utf8_strftime("%e %B", $am7)."</div>";
    // echo "<div id=\"dow\">".utf8_strftime("%Y", $am7)."</div>";
  
    echo "<div id=\"pretext\">".$pretext."</div>";
    echo "<div id=\"roomName\">".$roomName."</div>";
    echo "<div id=\"roomNum\">".$roomNum."</div>";

  echo "</div>";

  echo "<div id=\"instructions\">".$instructions."</div>";
?>
</div>
