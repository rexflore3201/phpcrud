<?php
    include_once 'header.php';
?>
<style>
    .homefav{display:none;}
</style>
            <section class="signup-form text-center">
            <br><br>
                <h2>Sign <span class="text-warning">Up
                        </span></h2>
                <div class="signup-form-form">
                <form action="includes/signup.inc.php" method="post"><br><br>
                    <input type="text" name="name" placeholder="Name"><br><br>
                    <input type="text" name="email" placeholder="Email Address"><br><br>
                    <input type="password" name="pwd" placeholder="Password"><br><br>
                    <input type="password" name="pwdrepeat" placeholder="Confirm Password"><br><br>
                    <!-- <label for="choose">Choose:</label>
                    <select name="choose" id="choose">
                        <option>Select..</option>
                        <option value="admin">Admin</option>
                        <option value="member">Member</option>
                    </select><br><br> -->
                    <button type="submit" name="submit">Sign Up</button><br><br>
                </form>
                </div>
                <?php
                if(isset($_GET["error"])){
                    if($_GET["error"] == "emptyinput") {
                        echo "<p>Fill in all fields!</p>";
                    }
                    // else if($_GET["error"] == "invaliduid"){
                    //     echo "<p>Choose a proper username!</p>";
                    // }
                    else if($_GET["error"] == "invalidemail"){
                        echo "<p>Choose a proper email!</p>";
                    }
                    else if($_GET["error"] == "passwordsdontmatch"){
                        echo "<p>Password doesn't match!</p>";
                    }
                    else if($_GET["error"] == "stmtfailed"){
                        echo "<p>Something went wrong, try again!</p>";
                    }
                    else if($_GET["error"] == "usernametaken"){
                        echo "<p>Email Address already taken!</p>";
                    }
                    else if($_GET["error"] == "none"){
                        echo "<p>Thank you for registering</p>";
                    }
                }
            ?>
            </section>


  
<?php
    include_once 'footer.php';
?>