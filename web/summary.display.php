<?php

require_once "config.php";
$mysqli = new mysqli($dbhost_name, $username, $password, $database);

$now = time();
# Fake times here for testing purposes
// $now = strtotime("2017-01-17 16:40");

?>
<meta http-equiv="refresh" content="60" />
<style>
  @import 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin-ext';

  body { background: #c6c8c7; margin: 0; font-family: 'Open Sans'; overflow: hidden; }

  #status { text-align: center; width: 100%; padding: 1vh; min-height: 14.5vh; }
  #status.available { background: #085; color: #fff; }
  #status.caution { background: #a60; color: #fff; }
  #status.reserved { background: #b00; color: #fff; }
  #status #room-arrow { width: 10%; }
  #status #tag { font-size: 5vh; font-weight: 600; }
  #status #event { font-size: 2.5vh; color: #c6c8c7; }
  #status #until { font-size: 3.5vh; }
  #status #extra { font-size: 2.5vh; font-weight: 600; }

  #reservations { padding: 1vh; }
  #reservations .date, #hours .heading { text-transform: uppercase; text-align: center; font-size: 2vh; letter-spacing: 0.5vw; color: #5f6369; padding: 1.5vh 0 0.5vh 0; }
  #reservations #reservations-list { list-style: none; padding: 0; }
  #reservations #reservations-list li { padding: 0.5vh 0 0.5vh 3vh; text-indent: -3vh; font-size: 2.5vh; }
  #reservations-list li .time { font-weight: bold; }
  #reservations-list li.A .name { color: #005; }
  #reservations-list li.B .name { color: #740; }
  #reservations-list li.C .name { color: #333; }
  #reservations-list li.D .name { color: #042; }
  #reservations-list li.E { font-size: 1.5vh !important; font-style: italic; color: #999; padding-top: 0.25vh !important; padding-bottom: 0.25vh !important; }
  #reservations-list li.E-closed .name { color: #b00; font-weight: 600; }
  #reservations-list li.I .name { color: #700; }

  #by-appointment { background: #5f6369; color: #c6c8c7; text-align: center; width: 90%; margin: 3vh 5% 3vh 5%; padding: 1vh 0; font-size: 2.5vh; }

  #hours { padding: 1vh; }
  #hours .date { text-transform: uppercase; text-align: right; font-size: 1.6vh; line-height: 1em; color: #5f6369; font-weight: normal; padding: 0.8vh 0.5vh 0 0; }
  #hours table { width: 100%; }
  #hours tr { padding: 0; border-top: 1px solid #5f6369; }
  #hours #reservations-list { list-style: none; margin: 0 0 1vh 0; padding: 0; }
  #hours #reservations-list.today { margin: 1vh 0 3vh 0; padding: 0; }
  #hours #reservations-list.today li { font-size: 3vh; }
  #hours #reservations-list li { padding: 0 0 0 3vh; text-indent: -3vh; font-size: 2.5vh; }

  #instructions-clear { height: 6vh; }
  #instructions { background: #5f6369; color: #c6c8c7; text-align: center; width: 100%; position: fixed; bottom: 0; padding: 1vh 0; margin: 0; font-size: 2vh; }

</style>
<?php

# A default set of instructions that can be overridden.
$room_instructions = "For service requests,<br/>email <b>help@ideate.cmu.edu</b>";
switch (strtoupper($_GET['room'])) {
  case 'A5': # Experimental Fabrication
    $room_id = 42;
    $room_dir = 'left';
    break;
  case 'A10': # Physical Computing
    $room_id = 60;
    $room_dir = 'right';
    break;
  case 'A10A': # Media Lab
    $room_id = 56;
    $room_dir = 'left';
    $room_instructions = "Reserve online at<br/><b>resources.ideate.cmu.edu/reservations</b>";
    break;
  case 'A29': # Lending Desk
    $room_id = 66;
    $room_dir = 'left';
    break;
  case 'A30': # Wood Shop
    $room_id = 43;
    $room_dir = 'right';
    $room_instructions = "Shop hours are for IDeATe students only, except during <strong>Open Fabrication Hours.</strong>";
    break;
  case '106B': # Studio A
    $room_id = 58;
    $room_dir = 'right';
    break;
  case '106C': # Studio B
    $room_id = 59;
    $room_dir = 'left';
    break;
  default:
    $room_id = 0;
    $room_dir = 'right';
}

for ($i = 0; $i <= 8; $i++) {
  $dates[$i] = strtotime("today 00:00:00 +".$i." days", $now);
}


# Most of the rooms behave the same, but A29/A30 (rooms 66/43) are completely different.
if ($room_id != 66 && $room_id != 43) {
  # THE COMMON CASE: CLASSROOM SPACES
  
  # Current room status
  $status = getClassroomStatus($room_id);
  echo printStatus($status, $room_dir);
  
  # Next seven days
  # Override start of current date with now; we don't care about things in the past.
  $dates[0] = $now;
  
  echo "<div id=\"reservations\">";
  echo getReservations($room_id, $dates);
  echo "</div>";
}
else {
  # THE EXCEPTIONAL CASE: LENDING DESK / WOOD SHOP

  # Current room status
  $status = getLendingStatus($room_id);
  echo printStatus($status, $room_dir);

  # Next seven days of hours
  echo "<div id=\"hours\">";
  echo getLendingHours($room_id, $dates);
  echo "</div>";
}

$mysqli->close();


# Instructions
#
echo "<div id=\"instructions-clear\">";
echo "</div>";
echo "<div id=\"instructions\">".$room_instructions."</div>";



# -----------------------------------------------------

function getReservations($room_id, $dates) {
  global $mysqli;

  $r = '';
  $total_listed = 0;
  for ($i = 0; $i < 8; $i++) {
    $result = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where end_time > ".$dates[$i]." and start_time <= ".$dates[$i+1]." and room_id = ".$room_id." order by start_time");
    $total_listed += $result->num_rows;
    if ($result->num_rows > 0) {
      if ($i > 0) {
        $r .= "<div class=\"date\">&mdash;&nbsp;".date("l <b>j</b> F", $dates[$i])."&nbsp;&mdash;</div>";
      }
      $r .= "<ul id=\"reservations-list\">";
      $r .= printReservationsList($result, $dates[$i], $dates[$i+1]);
      $r .= "</ul>";
    }
    $result->free();
  }
  if ($total_listed == 0) {
    $r .= "<div class=\"date\">&mdash;&nbsp;No events scheduled&nbsp;&mdash;</div>";
  }
  return $r;
}

function getLendingHours($room_id, $dates) {
  global $mysqli, $now;

  $r = '';
  # Today
  $r .= "<div class=\"heading\">&mdash;&nbsp;Today&rsquo;s Hours&nbsp;&mdash;</div>";

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

  # Alert about "by appointment" scheduling if necessary
  $alert_appointments = false;
  # Friday of spring exams (Friday between 6 and 12 May)
  $summer_starts = strtotime("5 May ".date("Y",$now)." next Friday");
  # Saturday before fall classes (Saturday between 23 and 29 August)
  $summer_ends = strtotime("22 August ".date("Y",$now)." next Saturday");

  if ($now >= $summer_starts && $now < $summer_ends && getLendingStatus($room_id)['class'] == 'reserved') {
    $alert_appointments = true;
  }
  else {
    # This heuristic which checks the next 4 days, starting now, works well for
    # holidays during the academic year.
    $upcoming_types = '';
    $result = $mysqli->query("select type from mrbs_entry where end_time > ".$now." and start_time < ".$dates[4]." and room_id = ".$room_id." order by start_time");
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
  //$r .= "<div class=\"heading\">&mdash;&nbsp;The Week Ahead&nbsp;&mdash;</div>";
  $r .= "<table>";

  for ($i = 1; $i < 8; $i++) {
    $r .= "<tr>";
    $r .= "<th valign=\"top\" class=\"date\">".date("<b>D</b><\\b\\r>j M", $dates[$i])."</th>";
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

function getClassroomStatus($room) {
  global $now, $mysqli;

  # Is there a current reservation?  Type F (freely available) doesn't count.
  $result = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where start_time <= ".$now." and end_time > ".$now." and type != 'F' and room_id = ".$room." order by end_time desc"); # get the latest end time if overlapping bookings
  $status = $result->num_rows;

  $r = array();
  if ($status == 0) {

    # If there's no reservation, it's available until the next one starts.
    # Type F (freely available) doesn't count as busy.  
    $result2 = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where start_time > ".$now." and type != 'F' and room_id = ".$room." order by start_time limit 1");
    $row2 = $result2->fetch_array();

    # If that's in the next 15 minutes, warn folks and tell them when contiguous reservations end.
    if ($row2['start_time'] <= $now + (15*60)) {
      $r['class'] = 'caution';
      if ($row2['type'] == "E") {
        $r['tag'] = 'Closing Soon';
        $r['until'] = "at ".printTime($row2['start_time']);
        $busy_until = busy_until($room, $row2['start_time']);
        $r['extra'] = "Room reopens ".printTime($busy_until);
      }
      else {
        $r['event'] = $row2['name'];
        $r['tag'] = 'Starting Soon';
        $r['until'] = "beginning at ".printTime($row2['start_time']);
        $busy_until = busy_until($room, $row2['start_time']);
        $r['extra'] = "Room in use through ".printTime($busy_until);
      }
    }
    else {
      # Otherwise, the room is going to be available for a while.
      $r['class'] = 'available';
      $r['tag'] = 'Available';
      if (!is_null($row2['start_time'])) {
        $r['until'] = "until ".printTime($row2['start_time']);
      }
    }
    $result2->free();

  }
  else {

    # If there is a reservation, we want to know what it is.
    $row = $result->fetch_array();

    # Text to display varies slightly depending on what's going on.
    switch (strtoupper($row['type'])) {
      case 'B':
        $r['class'] = 'caution';
        $r['tag'] = 'Office Hours';
        $r['event'] = $row['name'];
        break;
      case 'D':
        $r['class'] = 'reserved';
        $r['tag'] = 'Class In Session';
        $r['event'] = $row['name'];
        break;
      case 'E':
        $r['class'] = 'reserved';
        $r['tag'] = 'Closed';
        break;
      default:
        $r['class'] = 'reserved';
        $r['tag'] = 'Reserved';
        $r['event'] = $row['name'];
    }

    # But in any case, we want to let folks know when any contiguous reservations will end,
    # especially if that's beyond just the current reservation.
    $r['until'] = "until ".printTime($row['end_time']);
    $busy_until = busy_until($room, $row['end_time']);
    if ($busy_until != $row['end_time']) {
      $r['extra'] = "Room in use through ".printTime($busy_until);
    }

  }

  $result->free();
  return $r;
}

function getLendingStatus($room) {
  global $now, $mysqli;

  # Is there a current reservation that isn't a library closure (type E)?
  $result = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where start_time <= ".$now." and end_time > ".$now." and type != 'E' and room_id = ".$room." order by end_time desc"); # get the latest end time if overlapping bookings
  $status = $result->num_rows;

  $r = array();
  if ($status == 1) {

    # There is some reservation; something is going on.
    $row = $result->fetch_array();
    switch (strtoupper($row['type'])) {   
      case 'B':
        # Actually open.
        $r['class'] = 'available'; 
        $r['tag'] = 'Open';
        break;
      case 'I':
        # "May be open", "May be closed", or "Returns only".
        $r['class'] = 'caution';
        switch (strtolower($row['name'])) {
          case 'may be closed':
          case 'may be open':
            $r['tag'] = "May be Open";
            $r['extra'] = "Check additional signage for details";
            break;
          case 'returns only':
            $r['tag'] = "Open for Returns Only";
            break;
          default:
            $r['tag'] = $row['name'];
            $r['extra'] = "Check additional signage for details";
        }
        break;
    }

    # Get the next thing.
    $result2 = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where start_time > ".$now." and room_id = ".$room." order by start_time limit 1");
    $row2 = $result2->fetch_array();
    if ($row2['start_time'] == $row['end_time']) {
      switch (strtoupper($row2['type'])) {   
        case 'B':
          # Next thing is a return to full staffing.
          $r['until'] = "Full staffing will resume by ".printTime($row2['start_time']);
          break;
        case 'I':
          # Next thing is a reduction in staffing.
          $r['until'] = "Staffing may be reduced from ".printTime($row2['start_time']);
          break;
        case 'E':
          # Next thing is a library closure.
          $r['until'] = "Library closes at ".printTime($row2['start_time']);
          break;
      }
    }
    else {
      # No event listing; next thing is a closure.
      $r['until'] = "Closing at ".printTime($row['end_time']);
    }
    $result2->free();

  }
  else {

    # There is no reservation; that is, we are closed.
    $r['class'] = 'reserved'; 
    $r['tag'] = 'Closed';

    # Get the next thing that isn't a library closure.
    $result2 = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where start_time > ".$now." and type != 'E' and room_id = ".$room." order by start_time limit 1");
    $row2 = $result2->fetch_array();

    if (!is_null($row2['start_time'])) {
      $r['until'] = "Opening at ".printTime($row2['start_time']);
    }
    $result2->free();

  }
  $result->free();

  return $r;
}

function printStatus($status, $room_dir) {
  $r = "<table id=\"status\" class=\"".$status['class']."\">";
    $r .= "<tr>";
  
      if ($room_dir == 'left') {
        $r .= "<td id=\"room-arrow\" width=\"10%\" valign=\"middle\">";
          $r .= "<svg version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" viewBox=\"0 0 10 20\" width=\"20\" height=\"40\">";
            $r .= "<polygon points=\"10,0 0,10 10,20\" style=\"fill: #fff;\" />";
          $r .= "</svg>";
        $r .= "</td>";
      }
  
      $r .= "<td width=\"90%\" valign=\"middle\">";
      $r .= "<div id=\"tag\">".$status['tag']."</div>";
      if (isset($status['event'])) {
        $r .= "<div id=\"event\">".$status['event']."</div>";
      }
      $r .= "<div id=\"until\">".$status['until']."</div>";
      if (isset($status['extra'])) {
        $r .= "<div id=\"extra\">".$status['extra']."</div>";
      }
      $r .= "</td>";
  
      if ($room_dir == 'right') {
        $r .= "<td id=\"room-arrow\" width=\"10%\" valign=\"middle\">";
          $r .= "<svg version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" viewBox=\"0 0 10 20\" width=\"20\" height=\"40\">";
            $r .= "<polygon points=\"0,0 10,10 0,20\" style=\"fill: #fff;\" />";
          $r .= "</svg>";
        $r .= "</td>";
      }
  
    $r .= "</tr>";
  $r .= "</table>";

  return $r;
}

function busy_until($room, $start_time) {
  global $mysqli;

  $busy_until = $start_time;
  $num_results = 1;
  while ($num_results > 0) {
    # If we come to type F (freely available), it doesn't count as busy.
    $result = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where start_time <= ".$busy_until." and end_time > ".$busy_until." and type != 'F' and room_id = ".$room." order by end_time desc limit 1"); # get latest end time possible
    $num_results = $result->num_rows;
    if ($num_results > 0) {
      $row = $result->fetch_array();
      $busy_until = $row['end_time'];
    }
    $result->free();
  }
  return $busy_until;
}

function printReservationsList($result, $periodStart, $periodEnd) {
  $r = '';
  while ($row = $result->fetch_array()) {
    if ($row['type'] != 'E') {
      # Display a normal event, so long as it doesn't start at exactly 24:00
      # (in which case it is displayed the next day).
      if ($row['start_time'] < $periodEnd) {
        $r .= printEvent($row, $periodStart);
      }
    }
    else {
      # Say something about library closures (type 'E').
      # TODO: These could be coalesced even further when there are no other events.
      if ($row['start_time'] > $periodStart) {
        $r .= "<li class=\"E\">Hunt Library closes at <b>".printTime($row['start_time'], $periodStart)."</b></li>";
      }
      if ($row['end_time'] <= $periodEnd) {
        $r .= "<li class=\"E\">Hunt Library opens at <b>".printTime($row['end_time'], $periodStart)."</b></li>";
      }
      if ($row['start_time'] < $periodStart && $row['end_time'] > $periodEnd) {
        $r .= "<li class=\"E\">Hunt Library is <b>closed</b></li>";
      }
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
    return NULL;
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

  if (($startFar || $startStr == "00:00") && ($endFar ||  $endStr == "24:00")) {
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
