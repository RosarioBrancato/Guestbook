<?php
	session_start();
	
	include '/db/db_functions.php';
	include 'generated_html.php';
	
	$nickname = '';
	$first_name = '';
	$last_name = '';
	$email = '';
	
	$results;
	
	if(isset($_SESSION['user_id']) && isset($_GET['nickname'])) {
		$results = get_user_info($_GET['nickname']);
		if(count($results) > 0) {
			$nickname = $results['nickname'];
			$first_name = $results['first_name'];
			$last_name = $results['last_name'];
			$email = $results['email'];
		}
	}
?>

<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<!--jQuery-->
		<script src="jquery/jquery.js" type="text/javascript"></script>
		<!--Bootstrap CSS-->
		<link  rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css" />
		<!--Bootstrap optional theme -->
		<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
		<!--Bootstrap JavaScript -->
		<script  src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<!--My CSS-->
		<link rel="stylesheet" href="css/stile.css" type="text/css" />
		<!--My JavaScript-->
		<script src="constant/js_constants.js" type="text/javascript"></script>
		
		<title>Guestbook</title>
	</head>

	<body>
		<div class="wrapper">
			
			
			<?php get_header(); ?>
			
			<div class="content">
				<h1>Benutzerinfos</h1>
                                <p>Nickname: <input type="text" id="nickname" maxlength="50"  value="<?php echo $nickname; ?>" disabled /></p>
                                <p>Vorname: <input type="text" id="first_name" maxlength="50" value="<?php echo $first_name; ?>" disabled /></p>
                                <p>Name: <input type="text" id="last_name" maxlength="50" value="<?php echo $last_name; ?>" disabled /></p>
                                <p>E-Mail: <input type="email" id="email" maxlength="50" value="<?php echo $email; ?>" disabled /></p>
                                
				<form id="submit" method="get" action="index.php">
					<input type="submit" value="Zurück zum Gästebuch"  />
				</form>
			</div>
			
			<?php get_footer(); ?>
		</div>
                
		
		<script src="js/js_functions.js" type="text/javascript"></script>
	</body>
</html>