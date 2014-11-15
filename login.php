<?php
	session_start();
	
	include '/db/db_functions.php';
	include 'generated_html.php';
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
				<div class="panel panel-default">
					<h1 class="panel-heading">Anmelden</h1>
					<form method="post" action="index.php" id="login_form">
						<div class="panel-body">
							<p>Nickname: <input type="text" id="nickname" name="nickname" maxlength="50" required /></p>
							<p>Passwort: <input type="password" id="password" name="password" maxlength="50" /></p>
							<input type="submit" id="login_submit" name="login_submit" value="Anmelden" />
							
							<p id="error" class="alert alert-danger" role="alert" hidden></p>
						</div>
					</form>
				</div>
				
				<form id="submit_to_index" method="get" action="index.php"></form>
			</div>
			
			<?php get_footer(); ?>
		</div>
		
		<script src="js/js_functions.js" type="text/javascript"></script>
		<script>
			$(document).ready(function() {
				$('#login_form').submit(function(e) {
					e.preventDefault();
					
					var pfad = path_to_project + 'db/ajax/json_login.php';
					var nickname = $('#nickname').val();
					var password = $('#password').val();
                                        
					$.ajax({
						type:  'post',
						url: pfad,
						
						data: { 'login': 'login',
							'nickname': nickname,
							'password': password
						},
							
						dataType: 'json',
						success: function(data) {
							if(data.success) {
								$('#submit_to_index').submit();
							} else {
								$('#error').removeAttr('hidden').html(data.errorText);
							}
						},
						
						error: function(error) {
							$('#error').removeAttr('hidden').html('Anfrage konnte nicht abgeschickt werden! Anmeldung war nicht erfolgreich!');
						}
					});
					
				});
			});
		</script>
	</body>
</html>