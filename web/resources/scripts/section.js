
$(document).ready(function() {

    // Init action when user select sildeshow to see
    $('.slideshow-item').on('click', function(event) {

        // Cancel event
        global.cancelEvent(event);

        // Select new slideshow item
        $('.slideshow-item').removeClass('activ');
        $(this).addClass('activ');

        // Extract slideshow identifier and display slideshow 
        var slideshowId = String($(this).attr('data-slideshow-id'));
        displaySlideshow(slideshowId);
    });

    // Select first slideshow per default
    if ($('.slideshow-item').length > 0)
        $('.slideshow-item')[0].click();
});

/**
 * Allows to display slideshow using Ajax.
 * @param event Click event to prevent.
 * @param slideshowItem Slideshow item that have been selected.
 */
function displaySlideshow(slideshowId) {

    // Load slideshow content and display it
    var ajaxBaseUrl = global.basePath + '/ajax/slideshow/' + slideshowId;

    $.ajax({
        url: ajaxBaseUrl,
        timeout: 10000,
        success: function(msg) {

            // Add new content and initialize it (carousel + slideshow)
            $('#section-content').html(msg);
            initAjaxSlideshow();
            initThumbnailAction();
        },
        error: function(msg) {
            alert("Impossible de charger le diaporama");
        }
    });
}

/**
 * Allows to initialize action when user click on thumbnails.
 */
function initThumbnailAction() {

    $('.thumbnail-item').on('click', function() {

        var index = $(this).attr('data-index');
        $('#picture-list').slickGoTo(index);
    });
}
