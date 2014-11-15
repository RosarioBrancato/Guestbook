<?php
	function get_connection() {
		$db_user = 'guest';
		$db_pw = 'guest';
		
		if(isset($_SESSION['db_user'])) {
			//User
			$db_user = $_SESSION['db_user'];
			//Password
			if($db_user === 'guest') {
				$db_pw = 'guest';
			} else if($db_user === 'loggedin') {
				$db_pw = 'loggedin';
			} else if($db_user === 'admin') {
				$db_pw = 'admin';
			}
		}
		
		$mysqli = new mysqli('localhost', $db_user, $db_pw, 'Guestbook');
		$mysqli->set_charset('utf8');
		
		return $mysqli;
	}
	
	function show_entries($count) {
		$connection = get_connection();
		$stmt = $connection->prepare('SELECT e.id, e.title, e.text, e.posted, e.last_edit, u.nickname FROM tbl_entry e LEFT JOIN tbl_user u ON u.id = e.user_id ORDER BY e.posted DESC LIMIT ?');
		$stmt->bind_param('i', $count);
		$stmt->execute();
		
		$id;
		$title;
		$text;
		$posted;
		$last_edit;
		$nickname;
		
		$stmt->bind_result($id, $title, $text, $posted, $last_edit, $nickname);
		
		while($stmt->fetch()) {
?>
			<section class="panel panel-default">
				<h1 class="entry_title panel-heading"><?php echo $title; ?></h1>
				<div class="entry_text panel-body"><?php echo nl2br($text); ?></div>
				<div class="panel-footer" >Von <?php get_nickname_tag($nickname); ?> gepostet am <?php echo date('d/m/Y', strtotime($posted)); ?> um <?php echo date('H:i', strtotime($posted)); ?>
<?php 
			if($last_edit !== NULL) { 
?>		
				<p>Zuletzt bearbeitet am <?php echo date('d/m/Y', strtotime($last_edit)); ?> um <?php echo date('H:i', strtotime($last_edit)); ?></p>			
<?php			
			}
			
			if(isset($_SESSION['user_id']) && $nickname == $_SESSION['nickname']) {
?>				
				<div class="edit_buttons">
					<input type="hidden" class="entry_id" value="<?php echo $id; ?>" />
					<input type="button" class="btn_to_link entry_edit" name="entry_edit" value="Bearbeiten" />
					<input type="button" class="btn_to_link entry_delete" name="entry_delete" value="Löschen" />
					<div class="error_entry_delete alert alert-danger" role="alert" hidden></div>
				</div>
<?php
			}
?>
				</div>
			</section>
<?php
		}
		
		$stmt->close();
	}
	
	function get_nickname_tag($nickname) {
		if(isset($_SESSION['user_id'])) {
			$action = 'visit.php';
			if($nickname == $_SESSION['nickname']) {
				$action = 'account.php';
			}
?>
			<form action="<?php echo $action; ?>" method="get" class="header_profile inline">
				<input type="submit" class="btn_to_link" name="nickname" value="<?php echo $nickname; ?>" />
			</form>
<?php
		} else {
			echo $nickname;
		}
	}
	
	function is_nickname_unique($connection, $nickname) {
		$is_free = FALSE;
		
		$check = $connection->prepare('SELECT nickname FROM tbl_user WHERE nickname = ?');
		$check->bind_param('s', $nickname);
		$check->execute();
		
		if($check->fetch() == NULL) {
		    $is_free = TRUE;
		}
		
		return $is_free;
	}
	
	function get_user_info($nickname) {
		$connection = get_connection();
		$stmt = $connection->prepare('SELECT u.nickname, ud.first_name, ud.last_name, ud.email FROM tbl_user u LEFT JOIN tbl_user_data ud ON ud.user_id = u.id WHERE u.nickname = ?');
		$stmt->bind_param('s', $nickname);
		$stmt->execute();
		
		$nickname;
		$first_name;
		$last_name;
		$email;
		
		$stmt->bind_result($nickname, $first_name, $last_name, $email);
		$stmt->fetch();
		
		return array('nickname'=>$nickname,
			     'first_name'=>$first_name,
			     'last_name'=>$last_name,
			     'email'=>$email);
	}
?>
