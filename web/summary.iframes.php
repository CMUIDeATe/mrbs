<style>
  h2 { margin: 0; }
</style>

<table>
<tr>

<?php

foreach (array("A5", "A10", "A10A", "106B", "106C", "A29") as $room) {
  echo "<td style=\"width: 377px;\">";
    echo "<h2>".$room."</h2>";
    echo "<iframe src=\"summary.display.php?room=".$room."\" style=\"height: 90vh; border: 0;\"></iframe>";
  echo "</td>";
}


?>
</tr>
</table>
