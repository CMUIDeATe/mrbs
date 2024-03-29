<?php

require_once "config.php";
$mysqli = new mysqli($dbhost_name, $username, $password, $database);

$now = time();
# Fake times here for testing purposes
//$now = strtotime("2016-11-21 13:20");

?>
<style>
  @import 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin-ext';

  body { background: #c6c8c7; margin: 0; font-family: 'Open Sans'; font-size: 9pt; }

  #reservations { padding: 1em; }
  #reservations #reservations-list { list-style: none; padding: 0; }
  #reservations #reservations-list li { padding: 0.5em 0 0.5em 1em; text-indent: -3em; }
  #reservations-list li .time { font-weight: bold; }
  #reservations-list li.A .name { color: #005; }
  #reservations-list li.B .name { color: #740; }
  #reservations-list li.C .name { color: #333; }
  #reservations-list li.D .name { color: #042; }
  #reservations-list li.E { font-size: 0.75em !important; font-style: italic; color: #999; padding-top: 0.1em; padding-bottom: 0.1em; }
  #reservations-list li.E-closed .name { color: #b00; font-weight: 600; }
  #reservations-list li.I .name { color: #700; }

  #by-appointment { background: #5f6369; color: #c6c8c7; text-align: center; width: 90%; margin: 0.3em 5% 0.3em 5%; padding: 0.1em 0; font-size: 9pt; }

  #hours { padding: 1em; }
  #hours .date { text-transform: uppercase; text-align: right; line-height: 1em; color: #5f6369; font-weight: normal; padding: 0.25em 0.5em 0 0; }
  #hours table { width: 100%; }
  #hours tr { padding: 0; border-top: 1px solid #5f6369; }
  #hours #reservations-list { list-style: none; margin: 0 0 0.5em 0; padding: 0; }
  #hours #reservations-list li { padding: 0 0 0 1em; text-indent: -1em; }
  #hours #reservations-list.today li { font-size: 130%; padding: 0.1em 1.3em; background: #fdb813; }

</style>
<?php



# Select room.
switch (strtoupper($_GET['room'])) {
  #case 'A5': # Experimental Fabrication
  #  $room_id = 42;
  #  break;
  #case 'A10': # Physical Computing
  #  $room_id = 60;
  #  break;
  #case 'A10A': # Media Lab
  #  $room_id = 56;
  #  break;
  case 'A29': # Lending Desk
    $room_id = 66;
    break;
  case 'A30': # Wood Shop
    $room_id = 43;
    break;
  #case '106B': # Studio A
  #  $room_id = 58;
  #  break;
  #case '106C': # Studio B
  #  $room_id = 59;
  #  break;
  default:
    $room_id = 0;
}

for ($i = 0; $i <= 8; $i++) {
  $dates[$i] = strtotime("today 00:00:00 +".$i." days", $now);
}

# Next seven days of hours
echo "<div id=\"hours\">";
echo getLendingHours($room_id, $dates);
echo "</div>";

$mysqli->close();



# -----------------------------------------------------

function getLendingHours($room_id, $dates) {
  global $mysqli, $now;

  $r = '';
  # Today
  $r .= "<table>";
  $r .= "<tr style=\"border-bottom: 1px solid #333;\">";
  $r .= "<th valign=\"top\" class=\"date\"><b>Today,</b> ".date("<b>D</b> j M", $dates[0])."</th>";
  $r .= "<td valign=\"top\">";

  $result = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where end_time > ".$dates[0]." and start_time <= ".$dates[1]." and room_id = ".$room_id." order by start_time");
  $r .= "<ul id=\"reservations-list\" class=\"today\">";
  if ($result->num_rows > 0) {
    # TODO: Also print "CLOSED" if lending is closed even if library is not.
    $r .= printReservationsList($result, $dates[0], $dates[1]);
  }
  else {
    $r .= "<li class=\"E-closed\"><span class=\"name\">CLOSED</span></li>";
  }
  $r .= "</ul>";
  $result->free();

  $r .= "</td>";
  $r .= "</tr>";


  # Alert about "by appointment" scheduling if necessary
  $alert_appointments = false;
  # Friday of spring exams (Friday between 6 and 12 May)
  $summer_starts = strtotime("5 May ".date("Y",$now)." next Friday");
  # Saturday before fall classes (Saturday between 23 and 29 August)
  $summer_ends = strtotime("22 August ".date("Y",$now)." next Saturday");

  if ($now >= $summer_starts && $now < $summer_ends) {
    $alert_appointments = true;
  }
  else {
    # This heuristic which checks the next 4 days, starting now, works well for
    # holidays during the academic year.
    $upcoming_types = '';
    $result = $mysqli->query("select type from mrbs_entry where end_time > ".$now." and start_time <= ".$dates[4]." and room_id = ".$room_id." order by start_time");
    while ($row = $result->fetch_array()) {
      $upcoming_types .= $row['type'];
    }
    # If only E (library closed) and I (other), or if no B (open), the alert is necessary.
    if (preg_match('/[EI]*E[EI*]$/', $upcoming_types) || !(preg_match('/B/', $upcoming_types))) {
      $alert_appointments = true;
    }
    $result->free();
  }

  if ($alert_appointments) {
    $r .= "<div id=\"by-appointment\">";
    $r .= "Advance appointments can be<br/>scheduled via <b>help@ideate.cmu.edu</b>";
    $r .= "</div>";
  }


  # Moving forward
  for ($i = 1; $i < 8; $i++) {
    $r .= "<tr>";
    $r .= "<th valign=\"top\" class=\"date\">".date("<b>D</b> j M", $dates[$i])."</th>";
    $r .= "<td valign=\"top\">";
    $result = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where end_time > ".$dates[$i]." and start_time <= ".$dates[$i+1]." and room_id = ".$room_id." order by start_time");
    $r .= "<ul id=\"reservations-list\">";
    if ($result->num_rows > 0) {
      # TODO: Also print "CLOSED" if lending is closed even if library is not.
      $r .= printReservationsList($result, $dates[$i], $dates[$i+1]);
    }
    else {
      $r .= "<li class=\"E-closed\"><span class=\"name\">CLOSED</span></li>";
    }
    $r .= "</ul>";
    $r .= "</td>";

    $result->free();
  }
  $r .= "</table>";

  return $r;
}

function printReservationsList($result, $periodStart, $periodEnd) {
  $r = '';
  while ($row = $result->fetch_array()) {
    if ($row['type'] != 'E') {
      # Display a normal event.
      $r .= printEvent($row, $periodStart);
    }
    else {
      # Say something about library closures (type 'E').
      # TODO: These could be coalesced even further when there are no other events.
      $r .= "<li class=\"E\">";
      if ($row['start_time'] > $periodStart) {
        $r .= "Hunt Library closes at <b>".printTime($row['start_time'], $periodStart)."</b>";
      }
      if ($row['end_time'] < $periodEnd) {
        $r .= "Hunt Library opens at <b>".printTime($row['end_time'], $periodStart)."</b>";
      }
      if ($row['start_time'] < $periodStart && $row['end_time'] > $periodEnd) {
        $r .= "Hunt Library is <b>closed</b>";
      }
      $r .= "</li>";
    }
  }

  return $r;
}

function printEvent($event, $rel) {
  $r = "<li class=\"".$event['type']."\">";
    $r .= "<span class=\"time\">";
    $r .= eventTimes($event['start_time'], $event['end_time'], $rel);
    $r .= "</span>";
    $r .= " ";
    $r .= "<span class=\"name\">";
    $r .= $event['name'];
    $r .= "</span>";
  $r .= "</li>";

  return $r;
}

function printTime($time, $rel=-1) {
  global $now;

  # Print times relative to now, unless otherwise specified.
  if ($rel == -1) { $rel = $now; }

  # If null, make it known that this is unknown.
  if ($time == 0) {
    return "???";
  }

  # For exactly midnight tomorrow, use "24:00" instead as though it were part of today.
  if ($time == strtotime("tomorrow 00:00:00", $rel)) {
    return "24:00";
  }
  # If it's yesterday or in the next two days, a day-of-week will suffice for disambiguation.
  if (
    ($time >= strtotime("yesterday 00:00:00", $rel) && $time < strtotime("today 00:00:00", $rel)) ||
    ($time >= strtotime("tomorrow 00:00:00", $rel) && $time < strtotime("+2 days 00:00:00", $rel))
  ) {
    return date("H:i l", $time);
  }
  # If it's older than yesterday or more than two days out, be more explicit.
  if ($time < strtotime("yesterday 00:00:00", $rel) || $time >= strtotime("+2 days 00:00:00", $rel)) {
    return date("H:i, D j M", $time);
  }
  # Otherwise, it's just a normal time today.
  return date("H:i", $time);
}

function eventTimes($start, $end, $date) {
  # These times should be printed relative to the date they're being printed under.
  $startStr = printTime($start, $date);
  $endStr = printTime($end, $date);
  $startFar = false;
  $endFar = false;

  # If the start time has spaces, it is continued from a previous day.
  if (strpos($startStr, " ")) {
    $startStr = "[&raquo;]";
    $startFar = true;
  }
  # If the end time has spaces, it is continued to a subsequent day.
  if (strpos($endStr, " ")) {
    $endStr = "[&raquo;]";
    $endFar = true;
  }

  if ($startFar && $endFar) {
    return "All day";
  }
  elseif ($startFar || $endFar) {
    return $startStr." &mdash; ".$endStr;
  }
  else {
    return $startStr."&ndash;".$endStr;
  }
}

?>
