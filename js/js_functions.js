$('#frm_logout').submit(function(e) {
    var pfad = path_to_project + 'db/ajax/json_logout.php';
    
    $.ajax({
            type:  'post',
            url: pfad,
            
            data: { 'logout': 'logout' },
                    
            dataType: 'json',
            
            error: function(e) {
                alert('Beim Abmelden ist ein Fehler aufgetreten: ' + e);
                e.preventDefault();
            }
    });
})