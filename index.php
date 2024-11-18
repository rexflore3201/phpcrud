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

// Handle user details for editing
if (isset($_GET['userid'])) {
    $userid = intval($_GET['userid']);
    $sql = "SELECT userssName, userssEmail, userssCompany, userssPhone FROM userss WHERE userid = $userid";
    $run = $conn->query($sql);

    if ($run->num_rows > 0) {
        $row = $run->fetch_assoc();
        $usersName = $row['userssName'];
        $usersCompany = $row['userssCompany'];
        $usersEmail = $row['userssEmail'];
        $usersPhone = $row['userssPhone'];
    }
}

// Handle search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$rowsPerPage = 5;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($currentPage - 1) * $rowsPerPage;

// Fetch total number of rows for pagination
$sqlTotal = "SELECT COUNT(*) AS total FROM userss";
if (!empty($search)) {
    $sqlTotal .= " WHERE userssName LIKE '%$search%'";
}
$resultTotal = $conn->query($sqlTotal);
$totalRows = $resultTotal->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);

// Fetch paginated rows with search query if provided
$sql = "SELECT * FROM userss";
if (!empty($search)) {
    $sql .= " WHERE userssName LIKE '%$search%'";
}
$sql .= " LIMIT $start, $rowsPerPage";
$run = $conn->query($sql);
?>

<style>
    td, th {
        border: solid 1px;
    }
    th {
        background: lightgray;
    }
</style>

<section class="index-intro">
    <?php
    if (isset($_SESSION["username"])) {
        echo "<p>Hello there " . htmlspecialchars($_SESSION["username"]) . ", you're now logged in.</p>";
    }
    ?><br><br>

    <!-- Showcase -->

    <!-- Home -->
    <form method="post" action="includes/add.inc.php">
        <label>Name</label>
        <input type="text" name="name" placeholder="Enter your Name" required>
        <br><br>
        <label>Company</label>
        <input type="text" name="company" placeholder="Enter your Company">
        <br><br>
        <label>Phone</label>
        <input type="text" id="phone" name="phone" placeholder="Enter your Phone" oninput="formatPhoneInput(this)" maxlength="14" required>
        <br><br>
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your Email Address">
        <br><br>
        <input type="submit" name="submit2" value="Submit">
    </form>

    <?php
    if (isset($_GET["error"])) {
        switch ($_GET["error"]) {
            case "emptyinput":
                echo "<p>Fill in all fields!</p>";
                break;
            case "invalidemail":
                echo "<p>Choose a proper email!</p>";
                break;
            case "stmtfailed":
                echo "<p>Something went wrong, try again!</p>";
                break;
            case "none":
                echo "<p>You have signed up!</p>";
                break;
        }
    }
    ?>

    <hr>

    <h3>Contacts</h3>

    <form method="get" action="index.php">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" placeholder="Search by Name">
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
            } else {
                echo '<tr><td colspan="6">No records found.</td></tr>';
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
    if (isset($_POST['submit2'])) {
        $name = $_POST['name'];
        $company = $_POST['company'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $sql = "INSERT INTO userss (userssName, userssCompany, userssEmail, userssPhone) VALUES ('$name', '$company', '$email', '$phone')";
        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("User registered successfully.")</script>';
            header('Location: index.php');
            exit();
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }
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
