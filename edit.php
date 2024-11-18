<?php
include_once 'header.php';

function formatPhoneNumber($phoneNumber) {
    $cleaned = preg_replace('/\D/', '', $phoneNumber);
    if (strlen($cleaned) == 10) {
        return sprintf('(%s) %s %s',
            substr($cleaned, 0, 3),
            substr($cleaned, 3, 3),
            substr($cleaned, 6, 4)
        );
    } else {
        return $phoneNumber;
    }
}

// Database connection
$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "phpproject01";

$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName); 

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$usersName = $usersCompany = $usersEmail = $usersPhone = '';
$userid = 0;

// Handle user details for editing
if (isset($_GET['userid'])) {
    $userid = intval($_GET['userid']);
    $sql = "SELECT userssName, userssEmail, userssCompany, userssPhone FROM userss WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $usersName = htmlspecialchars($row['userssName']);
        $usersCompany = htmlspecialchars($row['userssCompany']);
        $usersEmail = htmlspecialchars($row['userssEmail']);
        $usersPhone = htmlspecialchars($row['userssPhone']);
    }
    $stmt->close();
}

// Handle search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$rowsPerPage = 5;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($currentPage - 1) * $rowsPerPage;

// Fetch total number of rows for pagination
$sqlTotal = "SELECT COUNT(*) AS total FROM userss";
if (!empty($search)) {
    $sqlTotal .= " WHERE userssName LIKE ?";
}
$stmt = $conn->prepare($sqlTotal);
if (!empty($search)) {
    $searchTerm = "%$search%";
    $stmt->bind_param('s', $searchTerm);
}
$stmt->execute();
$resultTotal = $stmt->get_result();
$totalRows = $resultTotal->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);
$stmt->close();

// Fetch paginated rows with search query if provided
$sql = "SELECT * FROM userss";
if (!empty($search)) {
    $sql .= " WHERE userssName LIKE ?";
}
$sql .= " LIMIT ?, ?";
$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $stmt->bind_param('sii', $searchTerm, $start, $rowsPerPage);
} else {
    $stmt->bind_param('ii', $start, $rowsPerPage);
}
$stmt->execute();
$run = $stmt->get_result();
$stmt->close();
?>

<style>
    td, th {
        border: solid 1px;
    }
    th { background: lightgray; }
</style>

<section class="index-intro">
    <?php
    if (isset($_SESSION["username"])) {
        echo "<p>Hello there " . htmlspecialchars($_SESSION["username"]) . ", you're now logged in.</p>";
    }
    ?><br><br>

    <!-- Home -->

    <form method="post">
        <label>Name</label>
        <input type="text" name="name" value="<?php echo $usersName ?>" required>
        <br><br>
        <label>Company</label>
        <input type="text" name="company" value="<?php echo $usersCompany ?>">
        <br><br>
        <label>Phone</label>
        <input type="text" id="phone" name="phone" oninput="formatPhoneInput(this)" maxlength="14" required value="<?php echo $usersPhone ?>">
        <br><br>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $usersEmail ?>">
        <br><br>
        <input type="submit" name="update" value="Update">
    </form>

    <hr>

    <h3>Contacts</h3>

    <form method="get" action="index.php">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" placeholder="Search by Name" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>
    
    <table style="width: 80%" border="1">
        <thead>
            <tr>
                <th>ID No.</th>
                <th>Name</th>
                <th>Company</th>
                <th>Phone</th>
                <th>Email Address</th>
                <th>Operations</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($run->num_rows > 0) {
                $i = $start + 1;
                while ($row = $run->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['userssName']); ?></td>
                        <td><?php echo htmlspecialchars($row['userssCompany']); ?></td>
                        <td><?php echo htmlspecialchars(formatPhoneNumber($row['userssPhone'])); ?></td>
                        <td><?php echo htmlspecialchars($row['userssEmail']); ?></td>
                        <td>
                            <a href="./edit.php?userid=<?php echo $row['userid']; ?>">Edit</a>
                            <a href="./delete.php?userid=<?php echo $row['userid']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = $i === $currentPage ? 'class="active"' : '';
            echo "<a href=\"?page=$i\" $activeClass>$i</a> ";
        }
        ?>
    </div>

    <?php
    if(isset($_POST['update'])){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $company = mysqli_real_escape_string($conn, $_POST['company']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);

        $sql = "UPDATE userss SET userssName=?, userssCompany=?, userssEmail=?, userssPhone=? WHERE userid=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $name, $company, $email, $phone, $userid);

        if ($stmt->execute()) {
            echo '<script>alert("User updated successfully.")</script>';
            // header('Location: index.php');
        } else {
            echo 'Error: ' . $stmt->error;
        }
        $stmt->close();
    }
    ?>

</section>

<script>
function formatPhoneInput(input) {
    // Remove all non-digit characters
    let phoneNumber = input.value.replace(/\D/g, '');

    // Format the number
    if (phoneNumber.length <= 3) {
        input.value = phoneNumber;
    } else if (phoneNumber.length <= 6) {
        input.value = `(${phoneNumber.slice(0, 3)}) ${phoneNumber.slice(3)}`;
    } else {
        input.value = `(${phoneNumber.slice(0, 3)}) ${phoneNumber.slice(3, 6)} ${phoneNumber.slice(6, 10)}`;
    }
}
</script>

<?php
include_once 'footer.php';
?>
