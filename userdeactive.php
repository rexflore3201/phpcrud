<?php
    include_once 'header.php';
?>
    <section class="index-intro">
    <?php
        if(isset($_SESSION["username"])){
            echo "<p>Hello there ". $_SESSION["username"]." you're now login. </p>";
        }
    ?><br><br>
    <?php 
    $serverName = "localhost";
    $dBUsername = "root";
    $dBPassword = "";
    $dBName = "phpproject01";

    $conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName); 
    
    if(!$conn){
        die("Connection failed: ".mysqli_connect());
    }
    ?>
    <!--Home-->
    <form method="post">
        <label>Name</label>
        <input type="text" name="name" placeholder="Enter your Name">
        <br><br>
        <label>Home Address</label>
        <input type="text" name="homead" placeholder="Enter your Home Address">
        <br><br>
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your Email Address">
        <br><br>
        <label>Username</label>
        <input type="text" name="uid" placeholder="Enter your Username">
        <br><br>
        <input type="submit" name="submit" value="Submit">


    </form>
    
    <hr>

    <h3>Member List</h3>
    <table style="width: 80%" borders="1">
        <tr>
            <th>ID No.</th>
            <th>Name</th>
            <th>Home Address</th>
            <th>Email Address</th>
            <th>Username</th>
            <th>Operations</th>
            <th>Status</th>
        </tr>
        <?php
            $i = 1;
            $sql = "select * from users where usersReference='member' ";
            $run = $conn -> query($sql);
            if($run -> num_rows > 0){
                while($row = $run -> fetch_assoc()){

        ?>
        
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row['usersName'] ?></td>
            <td><?php echo $row['userHome'] ?></td>
            <td><?php echo $row['usersEmail'] ?></td>
            <td><?php echo $row['usersUid'] ?></td>
            <td>
                <a href="./edit.php?userid=<?php echo $row['userid']; ?>">Edit</a>
                <a href="./delete.php?userid=<?php echo $row['userid']; ?>" onclick="return 
                    confirm('Are you sure?')">Delete</a>
            </td>
            <?php

            $userid = $_GET['userid'];
            $sql = "update users set status ='0'
            where userid = '$userid'";
            if(mysqli_query($conn,$sql))
                    {
                        echo"<br> User Deactivated ! ";
                    }

                    $status=$row['status'];
                    if($status==0) $strStatus="<a href=useractive.php?userid=".$row['userid'].">Activate User</a>";
                    if($status==1) $strStatus="<a href=userdeactive.php?userid=".$row['userid'].">Deactivate User</a>";
                    echo "<br><td>".$strStatus."</td>";
            ?>
        </tr>
        <?php 
                  }
               }
            ?>
    </table>

    <?php 


if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['homead'];
    $email = $_POST['email'];
    $address = $_POST['uid'];

    $sql = "insert into user values(null, '$name', '$homead', '$email', '$uid')";
    if(mysqli_query($conn, $sql)){
        echo '<script>alert("User registered successfully.")</script>';
        header('location: user.php');
    }else{
        echo mysqli_error($conn);
    }
}

?>
<?php
    include_once 'footer.php';
?>