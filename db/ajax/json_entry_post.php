<?php
    session_start();
    
    include '../db_functions.php';
    
    if(isset($_SESSION['user_id'])) {
        if(isset($_POST['post_entry'])) {
            $user_id = $_SESSION['user_id'];
            
            $title = $_POST['title'];
            $text = $_POST['text'];
            
            $success = TRUE;
            $errText = '';
            
            $connection = get_connection();
            
            if(strlen($title) <= 0) {
                $errText .= 'Titel ist leer! Gib einen Titel ein!<br>';
                $success = FALSE;
            }
            
            if(strlen($text) <= 0) {
                $errText .= 'Text ist leer! Gib einen Text ein!<br>';
                $success = FALSE;
            }
            
            if($success) {
                $stmt = $connection->prepare('INSERT INTO tbl_entry (title, text, posted, user_id) values (?, ?, NOW(), ?)');
                $stmt->bind_param('ssi', $title, $text, $user_id);
                $success = $stmt->execute();
                if(!$success) {
                    $errText = 'Eintrag konnte nicht gespeichert werden! Versuche es nochmal!<br>';
                }
                $stmt->close();
            }
    
            $json = array('success'=>$success,
                          'errorText'=>$errText);
            
            echo json_encode($json);
        }
        
    } else {
        $json = array('success'=>FALSE,
                          'errorText'=>'Du bist nicht angemeldet! Melde dich an und probiere es erneut!');
        
        echo json_encode($json);
    }
?>