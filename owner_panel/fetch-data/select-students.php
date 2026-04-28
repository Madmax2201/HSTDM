<?php
 include("../../assets/config.php");
$class = $_POST['select'];
if($class!=""){
    $sql = "SELECT * FROM students WHERE class='" . $class . "'";
}
else{
    $sql = "SELECT * FROM students";
}

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr class='align-middle'>
              <th scope='row' style='color:#6b7280;'>" . $row['s_no'] . "</th>
              <td style='font-weight:600;color:#111827;'>" . $row['fname'] . " " . $row['lname'] . "</td>
              <td><span class='badge-status' style='background:#f3f4f6;color:#374151;'>" . $row['class'] . " " . $row['section'] . "</span></td>
              <td style='text-align:right;'>
                  <a href='modal-student.php?id=" . $row['id'] . "' class='action-btn btn-view' style='text-decoration:none;'>
                      <i class='fas fa-eye me-1'></i> Voir Détails
                  </a>
              </td>
              </tr>";
    }
}
?>
