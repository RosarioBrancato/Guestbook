<?php
    function get_header() {
?>
        <header>
            <div class="header_content">
                <div class="header_left" >
                        <h1><a href="index.php">Guestbook</a></h1> 
                </div>
                <div class="header_right">
<?php
        if(isset($_SESSION['nickname'])) {
?>
                    <div class="text_middle">Hallo <a href="account.php"><?php echo $_SESSION['nickname']; ?></a> <form method="post" action="index.php" id="frm_logout" class="inline" ><input type="submit" id="logout" name="logout" value="Abmelden" /></form></div>
<?php
        } else {
?>
                    <a href="login.php">Anmelden</a> oder <a href="register.php">Registrieren</a>
<?php

        }
?>
                </div>
            </div>
        </header>
<?php  
    }
    
    
    function get_footer() {
?>
        <footer>
            <div class="footer_content">
                <div class="footer_left">
                    <p></p>
                </div>
                <div class="footer_right">
                        <p>Made by Rosario Brancato, IAP12a</p>
                </div>
            </div>
        </footer>
<?php     
    }
    
?>