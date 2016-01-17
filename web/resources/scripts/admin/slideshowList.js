
$(document).ready(function() {

    $('#create-slideshow-button').on('click', function() {

        // Clear field + display popup to create slideshow (enter name) and focus field
        $('#create-slideshow-popup input[type="text"]').val('');
        $('#create-slideshow-popup').bPopup();
        $('#create-slideshow-popup input[type="text"]').focus();
    });

    $('#create-slideshow-popup span').on('click', function() {

        var name = $('#create-slideshow-popup input[type="text"]').val();
        createSlideshow(name);
    });
});

function createSlideshow(name) {

    var ajaxBaseUrl = global.basePath + '/admin/slideshow/create';

    $.ajax({
        url: ajaxBaseUrl,
        type: 'POST',
        data: {name: name},
        timeout: 10000,
        success: function(response) {

            // In case of success => redirect to slideshow
            if (response.success && response.redirectUrl)
                window.location = response.redirectUrl;
            else {

                alert(response.message);
                $('#create-slideshow-popup').bPopup().close();
            }
        },
        error: function(msg) {
            alert("Impossible de créer le diaporama");
            $('#create-slideshow-popup').bPopup().close();
        }
    });
}
