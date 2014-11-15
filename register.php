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
					<h1 class="panel-heading">Registrieren</h1>
					<form method="post" action="login.php" id="frm_register">
						<div class="panel-body">
							<p>Nickname: <input type="text" id="nickname" maxlength="50" required="required" /></p>
							<p>Passwort: <input type="password" id="password" maxlength="50" required="required" /></p>
							<p>Passwort wiederholen: <input type="password" id="password2" maxlength="50" required="required" /></p>
							<p>Vorname: <input type="text" id="first_name" maxlength="50" /></p>
							<p>Name: <input type="text" id="last_name" maxlength="50" /></p>
							<p>E-Mail: <input type="email" id="email" maxlength="50" /></p>
							<input type="submit" id="register" value="Registrieren" />
							
							<div id="error" class="alert alert-danger" role="alert" hidden></div>
						</div>
					</form>
				</div>
				
				
				<form id="submit" method="get" action="login.php"></form>
			</div>
			
			<?php get_footer(); ?>
		</div>
		
		<script src="js/js_functions.js" type="text/javascript"></script>
		<script>
			$(document).ready(function() {
				$('#frm_register').submit(function(e) {
					e.preventDefault();
					
					var pfad = path_to_project + 'db/ajax/json_register.php';
					var isOk = true;
					var errText = '';
					
					var nickname = $('#nickname').val();
					var password = $('#password').val();
					var password2 = $('#password2').val();
					var first_name = $('#first_name').val();
					var last_name = $('#last_name').val();
					var email = $('#email').val();
					
					if (nickname.length <= 0) {
						isOk = false;
						errText += '<p>Geben Sie bitte einen Nickname ein.</p>';
					}
					
					if (password != password2) {
						isOk = false;
						errText += '<p>Die Passwörter stimmen nicht überrein.</p>';
					}
						
					
					if (isOk) {
						$.ajax({
							type:  'post',
							url: pfad,
							
							data: { 'register': 'register',
								'nickname': nickname,
								'password': password,
								'first_name': first_name,
								'last_name': last_name,
								'email': email
							},
								
							dataType: 'json',
							success: function(data) {
								if(data.success) {
									$('#submit').submit();
								} else {
									$('#error').removeAttr('hidden').html(data.errorText);
								}
							},
							
							error: function(e) {
								$('#error').removeAttr('hidden').html('Anfrage konnte nicht abgeschickt werden! Registrierung war nicht erfolgreich!');
							}
						});
						
					} else {
						$('#error').removeAttr('hidden').html(errText);
					}
					
				});
			});
		</script>
	</body>
</html>