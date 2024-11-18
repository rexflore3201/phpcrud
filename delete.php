<?php
$conn = mysqli_connect("localhost", "root", "", "phpproject01");
if (!$conn) {
    die('Error in connection: ' . mysqli_error($conn));
}

if (isset($_GET['userid'])) {
    $userid = intval($_GET['userid']);

    $sql = "DELETE FROM userss WHERE userid = $userid";
    if (mysqli_query($conn, $sql)) {
        header('Location: index.php');
        exit();
    } else {
        echo mysqli_error($conn);
    }
} else {
    echo "No user ID provided.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Confirmation</title>
    <script>
        function confirmDelete(userid) {
            if (confirm("Are you sure you want to delete?")) {
                window.location.href = "delete.php?userid=" + userid;
            }
        }
    </script>
</head>
<body>
    <!-- Example delete link -->
    <a href="javascript:void(0);" onclick="confirmDelete(1);">Delete User 1</a>
</body>
</html>
