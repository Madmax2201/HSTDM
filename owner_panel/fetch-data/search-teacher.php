<?php
 include("../../assets/config.php");

// Assuming you've already sanitized the search term
$search = $_POST['search'];

// Using prepared statement to prevent SQL injection
$sql = "SELECT * FROM teachers WHERE fname LIKE ? OR lname LIKE ? OR gender LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
$searchPattern = "%{$search}%";
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr class='align-middle'>
            <th scope='row' style='color:#6b7280;'>" . $row['s_no'] . "</th>
            <td style='font-weight:600;color:#111827;'>" . $row['fname'] . " " . $row['lname'] . "</td>
            <td><span class='badge-status' style='background:#f3f4f6;color:#374151;'>" . $row['gender'] . "</span></td>
            <td style='text-align:right;'>
              <a href='modal-teacher.php?id=". $row['id'] ."' class='action-btn btn-view' style='text-decoration:none;'>
                <i class='fas fa-eye me-1'></i> Voir Détails
              </a>
            </td>
        </tr>";
    }
}
?>
