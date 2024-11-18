<?php
    include_once 'header.php';
?>
<style>
    .homefav{display:none;}
</style>
            <section class="signup-form text-center">
            <br><br>
                <h2>Log <span class="text-warning">In
                        </span></h2>
                <div class="signup-form-form">
                <form action="includes/login.inc.php" method="post"><br><br>
                    <input type="text" name="uid" placeholder="Email Address..."><br><br>
                    <input type="password" name="pwd" placeholder="Password..."><br><br>
                    <button type="submit" name="submit">Log In</button><br><br><br><br><br><br>
                </form>
                </div>
                <?php
                if(isset($_GET["error"])){
                    if($_GET["error"] == "emptyinput") {
                        echo "<p>Fill in all fields!</p>";
                    }
                    else if($_GET["error"] == "wronglogin"){
                        echo "<p>Incorrect login information!</p>";
                    }
                }
            ?>
            </section>

<?php
    include_once 'footer.php';
?>
        
        