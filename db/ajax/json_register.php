<?php
    session_start();

    include '../db_functions.php';
    
    if(isset($_POST['register'])) {
        $_SESSION['db_user'] = 'loggedin';
        
        $nickname = $_POST['nickname'];
        $password = md5($_POST['password']);
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        
        $user_id = 0;
        $success = FALSE;
        $errText = '';
        
        $connection = get_connection();
        $connection->autocommit(FALSE);
        
        //Check if nickname exists
        $success = is_nickname_unique($connection, $nickname);
        if(!$success) {
            $errText = 'Der Nickname ist bereits belegt. Bitte gib einen neuen Nickname ein!';
        }
        
        //Insert nickname
        if($success) {
            $stmt = $connection->prepare('INSERT INTO tbl_user (nickname) VALUES (?)');
            if($stmt !== FALSE) {
                $stmt->bind_param('s', $nickname);
                $success = $stmt->execute();
                $stmt->close();
            } else {
                $success = FALSE;
            }
            if(!$success) {
                $errText = 'Benutzer konnte nicht ergestellt werden. Versuche es erneut! (Nickname insert)';
            }
        }
        
        //Insert user data
        if($success) {
            $user_id = $connection->insert_id;
            
            $stmt2 = $connection->prepare('INSERT INTO tbl_user_data (user_id, password, first_name, last_name, email) values (?, ?, ?, ?, ?)');
            if($stmt2 !== FALSE) {
                $stmt2->bind_param('issss', $user_id, $password, $first_name, $last_name, $email);
                $success = $stmt2->execute();
                $stmt2->close();
            } else  {
                $success  = FALSE;
            }
            if(!$success) {
                $errText = 'Benutzer konnte nicht ergestellt werden. Versuche es erneut! (Userdata insert)';
            }
        }
        
        //Commit or rollback
        if ($success) {
            $connection->commit();
        } else {
            $connection->rollback();
            session_destroy();
        }
        
        //Close connection
        $connection->close();
        
        //Create  json
        $json = array('success'=>$success,
                        'errorText'=>$errText);
        
        //Return  json
        echo json_encode($json);
    
    } else {
        echo '{}';
    }
?>