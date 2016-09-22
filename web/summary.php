<style>
  .status-available { background: #085; color: #fff; }
  .status-reserved { background: #b00; color: #fff; }
</style>
<?php

require_once "config.php";
$mysqli = new mysqli($dbhost_name, $username, $password, $database);

for ($i = 0; $i <= 7; $i++) {
  $dates[$i] = strtotime("today 00:00:00 +".$i." days");
}

echo date("n/j H:i:s T");


foreach (array(42, 56, 60, 58, 59) as $room_id) {
  $result = $mysqli->query("select * from mrbs_room where id = ".$room_id);
  $row = $result->fetch_array();
  $room_name = $row['room_name'];
  $area_id = $row['area_id'];
  echo "<h1><a href=\"week.php?area=".$area_id."&room=".$room_id."\">".$room_name."</a></h1>";
  $result->free();

  $result = $mysqli->query("select * from mrbs_area where id = ".$area_id);
  $row = $result->fetch_array();
  $area_name = $row['area_name'];
  echo "in <b>".$area_name."</b>";
  $result->free();

  # Current room status
  #
  # Is there currently a booking?
  $result = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where start_time <= ".time()." and end_time > ".time()." and room_id = ".$room_id." order by end_time desc"); # get the latest end time if overlapping bookings
  $status = $result->num_rows;
  if ($status == 0) {
    # If not, it's available until the next one starts.
    $result2 = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where start_time > ".time()." and room_id = ".$room_id." order by start_time limit 1");
    $row2 = $result2->fetch_array();
    echo "<div class=\"status-available\">";
    echo "<b>Available</b>";
    echo " until ";
    echo date("H:i", $row2['start_time']);
    if (date("Y-m-d", $row2['start_time']) != date("Y-m-d")) {
      echo " ".date("l", $row2['start_time']);
    }
    echo "</div>";
    $result2->free();
  }
  else {
    # If there is, we want to know what it is
    $row = $result->fetch_array();
   
    # But if there are contiguous things beyond that, we need to know about them, too
    $busy_until = $row['end_time'];
    $num_results = 1;
    while ($num_results > 0) {
      $result2 = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where start_time <= ".$busy_until." and end_time > ".$busy_until." and room_id = ".$room_id." order by end_time desc limit 1"); # get latest end time possible
      $num_results = $result2->num_rows;
      if ($num_results > 0) {
        $row2 = $result2->fetch_array();
        $busy_until = $row2['end_time'];
      }
    }

    # Display useful information about these reservations
    echo "<div class=\"status-reserved\">";
    if ($row['type'] == 'D') {
      echo "<b>Class In Session:</b>";
      echo " ".$row['name'];
      echo " until ".date("H:i", $row['end_time']);
    }
    elseif ($row['type'] == 'E') {
      echo "<b>Closed</b>";
      echo " until ".date("H:i", $row['end_time']);
    }
    else {
      echo "<b>Reserved</b>";
      echo " by ".$row['name'];
      echo " until ".date("H:i", $row['end_time']);
    }

    if ($busy_until != $row['end_time']) {
      echo "; room in use until ".date("H:i", $busy_until);
    }
    echo "</div>";
    $result2->free();
  }
  $result->free();

  # Next seven days
  #
  for ($i = 0; $i < 7; $i++) {
  echo "<h2><a href=\"day.php?area=".$area_id."&room=".$room_id."&year=".date("Y", $dates[$i])."&month=".date("m", $dates[$i])."&day=".date("d", $dates[$i])."\">".date("l j F", $dates[$i])."</a></h2>";
    $result = $mysqli->query("select id,create_by,name,type,room_id,start_time,end_time from mrbs_entry where start_time >= ".$dates[$i]." and start_time < ".$dates[$i+1]." and room_id = ".$room_id." order by start_time");

    echo "<ul>";
    while ($row = $result->fetch_array()) {
      echo "<li>";
      echo "<small>";
      echo "<a href=\"view_entry.php?id=".$row['id']."\">".$row['id']."</a>";
      echo "</small>";
      echo " ";
      echo "<b>";
      echo date("n/j H:i", $row['start_time']);
      echo " &ndash; ";
      echo date("n/j H:i", $row['end_time']);
      echo "</b>";
      echo " ";
      echo $row['name'];
      echo " ";
      echo "<small>";
      echo "type ".$row['type'];
      echo ", ";
      echo $row['create_by'];
      echo "</small>";
      echo "</li>";
    }
    echo "</ul>";
/*
    echo "<pre>";
    while ($row = $result->fetch_array()) {
      print_r($row);
    }
    echo "</pre>";
*/

    $result->free();
  }
}

$mysqli->close();

?>
