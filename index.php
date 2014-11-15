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
		<!--jQuery UI-->
		<script src="jquery-ui/jquery-ui.js" type="text/javascript"></script>
		<!--jQuery UI CSS-->
		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
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
				<?php show_entries(10); ?>
				
<?php 
	if(isset($_SESSION['user_id'])) {
?>
				<section class="new_entry panel panel-default">
					<h2 class="panel-heading">Neuer Eintrag verfassen</h2>
					<div class="panel-body">
						<p>Titel:<input type="text" id="new_entry_title" /></p>
						<div>Eintrag:</div><textarea id="new_entry_text" rows="5"></textarea>
						<input type="button" id="new_entry_submit" value="Abschicken!"/>
						<div id="new_entry_error" class="alert alert-danger" role="alert" hidden></div>
					</div>
				</section>
<?php
	}
?>			
				<form id="submit" method="get" action="index.php"></form>
			</div>
			
			<div id="dialog-confirm" title="Löschen"  hidden="hidden">
				<p>Willst du den Eintrag wirklich löschen?</p>
			</div>
			
			<?php get_footer(); ?>
		</div>
		
		<script src="js/js_functions.js" type="text/javascript"></script>
		<script>
			$(document).ready(function() {
				//POST ENTRY
				$('#new_entry_submit').click(function() {
					var pfad = path_to_project + 'db/ajax/json_entry_post.php';
					var title = $('#new_entry_title').val();
					var text = $('#new_entry_text').val();
					var geklappt = true;
					
					var errText = '';
					
					if(title.length <= 0) {
						errText += 'Gib einen Titel ein!<br>';
						geklappt = false;
					}
					
					if(text.length <= 0) {
						errText += 'Gib einen Text ein!<br>';
						geklappt = false;
					}
					
					if (geklappt) {
						$.ajax({
							type:  'post',
							url: pfad,
							
							data: { 'post_entry': 'post_entry',
								'title': title,
								'text': text
							},
								
							dataType: 'json',
							success: function(data) {
								if(data.success) {
									$('#submit').submit();
								} else {
									$('#new_entry_error').removeAttr('hidden').html(data.errorText);
								}
							},
							
							error: function(e) {
								//$('#error').html('Anfrage konnte nicht abgeschickt werden! Eintrag konnte gesendet werden!');
								errText += 'Anfrage konnte nicht abgeschickt werden! Eintrag konnte gesendet werden!<br>';
							}
						});	
					} else {
						$('#new_entry_error').removeAttr('hidden').html(errText);
					}
				});
			
				//EDIT ENTRY
				$('.entry_edit').click(function() {
					//section tag
					var section = $(this).parent().parent().parent();
					
					//hidden input tag
					var id = $(section).find('.entry_id').val();
					$(section).prepend('<input type="hidden" class="entry_id" value="' + id + '" />');
					
					//p tag
					var title = $(section).find('.entry_title');
					var title_text = $(title).html();
					$(title).replaceWith('<p><h2 class="inline">Titel:</h2><input type="text" class="edit_title" value="' + title_text + '" /></p>');
					
					//p tag
					var text = $(section).find('.entry_text');
					var text_text = $(text).html().replace(/\<br\>/g, '');
					$(text).replaceWith('Text:<textarea class="edit_text" rows="5">' + text_text + '</textarea><br>');
					
					//remove edit buttons of section
					$(section).find('.edit_buttons').remove();
					
					//add save/cancel buttons
					$(section).append('<div class="edit_buttons">'
							  + '<input type="button" class="edit_save" value="Speichern" />'
							  + '<input type="button" class="edit_cancel" value="Abbrechen" />'
							  + '<p class="error_entry_edit alert alert-danger" role="alert" hidden></p>'
							  + '</div>');
					
					//register events on edit buttons
					//save
					$('.edit_save').click(function() {
						var pfad = path_to_project + 'db/ajax/json_entry_edit.php';
						
						var section = $(this).parent().parent();
						var id = $(section).find('.entry_id').val();
						var title =  $(section).find('.edit_title').val();
						var text = $(section).find('.edit_text').val();
						
						var success = true;
						var errorText = '';
						
						if (title.length <= 0) {
							errorText += 'Titel ist leer! Gib einen Titel ein!<br>';
							success = false;
						}
						
						if (text.length <= 0) {
							errorText += 'Text ist leer! Gib einen Text ein!<br>';
							success = false;
						}
						
						if (success) {
							$.ajax({
								type:  'post',
								url: pfad,
								
								data: { 'edit_entry': 'edit_entry',
									'entry_id': id,
									'title': title,
									'text': text
								},
									
								dataType: 'json',
								success: function(data) {
									if(data.success) {
										location.reload(true);	
									} else {
										$(section).find('.error_entry_edit').html(data.errorText);
									}
								},
								
								error: function(e) {
									$(section).find('.error_entry_edit').removeAttr('hidden').html('Anfrage konnte nicht abgeschickt werden! Versuche es erneut!<br>');
								}
							});
						} else {
							$(section).find('.error_entry_edit').removeAttr('hidden').html(errorText);
						}
					});
					
					//cancel
					$('.edit_cancel').click(function() {
						location.reload(true);
					});
					
				});
				
				//DELETE ENTRY
				$('.entry_delete').click(function() {
					var pfad = path_to_project + 'db/ajax/json_entry_delete.php';
					var id = $(this).parent().find('.entry_id').val();
					
					//modal dialog
					 $('#dialog-confirm').dialog({
						resizable: false,
						width:500,
						height:280,
						modal: true,
						buttons: {
							'Eintrag löschen': function() {
								//delete
								$.ajax({
									type:  'post',
									url: pfad,
									
									data: { 'delete_entry': 'delete_entry',
										'entry_id': id,
									},
										
									dataType: 'json',
									success: function(data) {
										if(data.success) {
											location.reload(true);	
										} else {
											$(section).find('.error_entry_delete').removeAttr('hidden').html(data.errorText);
										}
									},
									
									error: function(e) {
										$(section).find('.error_entry_delete').removeAttr('hidden').html('Anfrage konnte nicht abgeschickt werden! Versuche es erneut!<br>');
									}
								});
							},
							'Abbrechen': function() {
								$(this).dialog('close');
							}
						}
					});
					
				});
				
			});
			
		</script>
		
	</body>
</html>