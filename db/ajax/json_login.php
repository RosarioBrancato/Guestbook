<?php
	session_start();

	include '../db_functions.php';
	
	if(isset($_POST['login'])) {
		$_SESSION['db_user'] = 'loggedin';
		
		$nickname = $_POST['nickname'];
		$password = md5($_POST['password']);
		$success = FALSE;
		$errText = '';
		
		$connection = get_connection();
		
		$stmt = $connection->prepare('SELECT u.id, u.nickname, ud.password, ud.last_name, ud.first_name, ud.email FROM tbl_user u LEFT JOIN tbl_user_data ud ON ud.user_id = u.id WHERE u.nickname = ? AND ud.password = ?');
		if($stmt !== FALSE)  {
			$stmt->bind_param('ss', $nickname, $password);
			$stmt->execute();
			
			$db_user_id;
			$db_nickname;
			$db_password;
			$db_name;
			$db_vorname;
			$db_email;
			
			$stmt->bind_result($db_user_id, $db_nickname, $db_password, $db_name, $db_vorname, $db_email);
			$stmt->fetch();
			
			if($nickname === $db_nickname && $password === $db_password) {
				$_SESSION['db_user'] = 'loggedin';
				$_SESSION['user_id'] =  $db_user_id;
				$_SESSION['nickname'] = $db_nickname;
				$_SESSION['password'] = $db_password;
				$_SESSION['last_name'] = $db_name;
				$_SESSION['first_name'] = $db_vorname;
				$_SESSION['email'] = $db_email;
				$success = TRUE;
			
			} else {
				$errText = 'Anmeldung fehlgeschlagen. Nickname und/oder Passwort sind falsch!';
			}
			
			$stmt->close();
		} else {
			$success = FALSE;
			$errText = 'Ein Fehler ist beim Anmelden ist aufgetreten. Versuche es erneut!';
		}
		$connection->close();
		
		if(!$success) {
			session_destroy();
		}
		
		$json = array('success'=>$success,
				'errorText'=>$errText);
		
		echo json_encode($json);
	
	} else {
		echo '{}';
	}
?>