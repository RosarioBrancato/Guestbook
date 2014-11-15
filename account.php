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
				<h1 class="panel-heading">Benutzerkonto</h1>
                                <form method="post" action="index.php" id="frm_user_edit">
                                    <div class="panel-body">
                                        <p>Nickname: <input type="text" id="nickname" maxlength="50" value="<?php if(isset($_SESSION['nickname'])){echo $_SESSION['nickname'];}else{echo '';} ?>" required="required" /></p>
                                        <p>Vorname: <input type="text" id="first_name" maxlength="50" value="<?php if(isset($_SESSION['first_name'])){echo $_SESSION['first_name'];}else{echo '';} ?>" /></p>
                                        <p>Name: <input type="text" id="last_name" maxlength="50" value="<?php if(isset($_SESSION['last_name'])){echo $_SESSION['last_name'];}else{echo '';} ?>" /></p>
                                        <p>E-Mail: <input type="email" id="email" maxlength="50" value="<?php if(isset($_SESSION['email'])){echo $_SESSION['email'];}else{echo '';} ?>" /></p>
                                        <input type="submit" id="save" value="Änderungen speichern" />
                                        <div id="save_message" class="alert alert-danger" role="alert" hidden></div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="panel panel-default">
                                <h2 class="panel-heading">Passwort ändern</h2>
                                <form method="post" action="index.php" id="frm_pw_edit">
                                    <div class="panel-body">
                                        <p>Altes Passwort: <input type="password" id="password" maxlength="50" required="required" /></p>
                                        <p>Passwort wiederholen: <input type="password" id="password2" maxlength="50" required="required" /></p>
                                        <p>Neues Passwort: <input type="password" id="password_new" maxlength="50" required="required" /></p>
                                        <input type="submit" id="change_password" value="Passwort speichern" />
                                        <div id="change_password_message" class="alert alert-danger" role="alert" hidden></div>
                                    </div>
                                </form>
                            </div>
                            
                            <form id="submit" method="get" action="index.php">
                                    <input type="submit" value="Zurück zum Gästebuch"  />
                            </form>
			</div>
			
			<?php get_footer(); ?>
		</div>
                
                <script src="js/js_functions.js" type="text/javascript"></script>
                <script>
                    $(document).ready(function() {
                        //Submit user data
                        $('#frm_user_edit').submit(function(e) {
                            e.preventDefault();
                            
                            var pfad = path_to_project + 'db/ajax/json_account.php';
                            var nickname = $('#nickname').val();
                            var first_name = $('#first_name').val();
                            var last_name = $('#last_name').val();
                            var email = $('#email').val();
                    
                            $.ajax({
                                type:  'post',
                                url: pfad,
                                
                                data: { 'edit_account': 'edit_account',
                                        'nickname': nickname,
                                        'first_name': first_name,
                                        'last_name': last_name,
                                        'email': email
                                },
                                        
                                dataType: 'json',
                                success: function(data) {
                                        if(data.success) {
                                                $('#save_message').removeAttr('hidden').attr('class', 'alert alert-success').html(data.successText);
                                        } else {
                                                $('#save_message').removeAttr('hidden').attr('class', 'alert alert-danger').html(data.errorText);
                                        }
                                },
                                
                                error: function(e) {
                                        $('#save_message').removeAttr('hidden').attr('class', 'alert alert-danger').html('Anfrage konnte nicht verschickt werden. Bitte versuche es erneut!');
                                }
                            });
                        });
                        
                        //Change password
                        $('#frm_pw_edit').submit(function(e) {
                            e.preventDefault();
                            
                            var pfad = path_to_project + 'db/ajax/json_account.php';
                            var password = $('#password').val();
                            var password2 = $('#password2').val();
                            var password_new = $('#password_new').val();
                            
                            $.ajax({
                                type:  'post',
                                url: pfad,
                                
                                data: { 'change_password': 'change_password',
                                        'password_old': password,
                                        'password_old2': password2,
                                        'password_new': password_new
                                },
                                        
                                dataType: 'json',
                                success: function(data) {
                                    if(data.success) {
                                        $('#change_password_message').removeAttr('hidden').attr('class', 'alert alert-success').html(data.successText);
                                    } else {
                                        $('#change_password_message').removeAttr('hidden').attr('class', 'alert alert-danger').html(data.errorText);
                                    }
                                },
                                
                                error: function(e) {
                                    $('#change_password_message').removeAttr('hidden').attr('class', 'alert alert-danger').html('Anfrage konnte nicht verschickt werden. Bitte versuche es erneut!');
                                }
                            });
                        });
                    });
            </script>
	</body>
</html>