<?php
    session_start();

    if($_POST['logout']) {
        if(isset($_SESSION)) {
            session_destroy();
        }
    }
    
    echo '{}';
?>