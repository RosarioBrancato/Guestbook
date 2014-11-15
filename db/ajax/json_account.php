<?php
    session_start();

    include '../db_functions.php';
    
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
            
        //EDIT USER DATA
        if(isset($_POST['edit_account'])) {
            $nickname = $_POST['nickname'];
            $last_name = $_POST['last_name'];
            $first_name = $_POST['first_name'];
            $email = $_POST['email'];
            
            $success = FALSE;
            $successText = '';
            $errText = '';
            
            //Create connection
            $connection = get_connection();
            $connection->autocommit(FALSE);
            
            //If nickname is edited, check if it already exists
            if($nickname !== $_SESSION['nickname']) {
                    $success = is_nickname_unique($connection, $nickname);
                    if(!$success) {
                            $errText = 'Nickname ist bereits belegt! Bitte gib einen anderen Nickname ein.';
                    }
            } else {
                    $success = TRUE;
            }
            
            //Insert nickname
            if($success) {
                $stmt = $connection->prepare('UPDATE tbl_user SET nickname = ? WHERE id = ?');
                $stmt->bind_param('si', $nickname, $user_id);
                $stmt->execute();
                if(!$success) {
                    $errText = 'Benutzerdaten konnten nicht aktualisiert werden! Versuche es nochmal!';
                }
                $stmt->close();
            }
            
            //Insert user data
            if($success) {
                $stmt2 = $connection->prepare('UPDATE tbl_user_data SET last_name = ?, first_name = ?, email = ? WHERE user_id = ?');
                $stmt2->bind_param('sssi', $last_name, $first_name, $email, $user_id);
                $stmt2->execute();
                if(!$success) {
                    $errText = 'Benutzerdaten konnten nicht aktualisiert werden! Versuche es nochmal!';
                }
                $stmt2->close();
            }
            
            //Commit or rollback
            if($success) {
                $connection->commit();
                
                $_SESSION['nickname'] = $nickname;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['email'] = $email;
                
                $successText = 'Deine  Benutzerdaten wurden erfolgreich aktualisiert!';
                
            } else {
                $connection->rollback();
            }
            
            //Close connection
            $connection->close();
            
            //Create json
            $json = array('success'=>$success,
                            'successText'=>$successText,
                            'errorText'=>$errText);
            
            //Write json
            echo json_encode($json);
        
            
        //CHANGE PASSWORD
        } else if(isset($_POST['change_password'])) {
            $user_pw = $_SESSION['password'];
            
            $pw_old = md5($_POST['password_old']);
            $pw_old2 = md5($_POST['password_old2']);
            $pw_new = md5($_POST['password_new']);
            
            $success = FALSE;
            $successText = '';
            $errText = '';
            
            //Get connection
            $connection = get_connection();
            
            //Are old password identical
            $success = ($pw_old ==  $pw_old2);
            if(!$success) {
                $errText = 'Altes Passwort und wiederholtes Passwort sind nicht identisch!';
            }
            
            //Check if old password  is the actuall password of the user
            if($success)  {
                $stmt = $connection->prepare('SELECT password FROM  tbl_user_data  WHERE user_id = ?');
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                
                $db_password;
                
                $stmt->bind_result($db_password);
                $stmt->fetch();
                
                if($db_password !== $pw_old) {
                    $success = false;
                    $errText = 'Altes Passwort ist falsch. Versuche es erneut!';
                }
                $stmt->close();
            }
            
            //Update password
            if($success) {
                $stmt = $connection->prepare('UPDATE tbl_user_data  SET password = ? WHERE user_id = ?');
                $stmt->bind_param('si', $pw_new, $user_id);
                $success = $stmt->execute();
                if(!$success) {
                    $errText = 'Passwort konnte nicht verändert werden. Versuche es erneut!';
                } else {
                    $successText = 'Passwort wurde erfolgreich verändert!';
                }
                $stmt->close();
            }
            
            //Close connection
            $connection->close();
            
            //Create json
            $json = array('success'=>$success,
                            'successText'=>$successText,
                            'errorText'=>$errText);
            
            //Write json
            echo json_encode($json);
        }
    } else {
        echo '{}';
    }
?>