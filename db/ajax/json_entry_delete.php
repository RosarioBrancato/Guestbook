<?php
    session_start();
    
    include '../db_functions.php';
    
    if(isset($_SESSION['user_id'])) {
        if(isset($_POST['delete_entry'])) {
            $user_id = $_SESSION['user_id'];
            
            $entry_id = $_POST['entry_id'];
            
            $success = TRUE;
            $errText = '';
            
            $connection = get_connection();
            
            if($entry_id <= 0) {
                $errText .= 'Eintrag konnte nicht identifiziert werden. Probiere es nochmal!<br>';
                $success = FALSE;
            }
            
            if($success) {
                $stmt = $connection->prepare('DELETE FROM tbl_entry WHERE user_id = ? AND id = ?');
                $stmt->bind_param('ii', $user_id, $entry_id);
                $success = $stmt->execute();
                if(!$success) {
                    $errText = 'Eintrag konnte nicht gelöscht werden! Versuche es erneut!<br>';
                }
                $stmt->close();
            }
    
            $json = array('success'=>$success,
                          'errorText'=>$errText);
            
            echo json_encode($json);
        }
        
    } else {
        $json = array('success'=>FALSE,
                          'errorText'=>'Du bist nicht angemeldet! Melde dich an und versuche es erneut!');
        
        echo json_encode($json);
    }
?>