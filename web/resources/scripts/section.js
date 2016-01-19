
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

            // Add new content and initialize it (slideshow)
            $('#section-content').html(msg);
            initSlideshow();
        },
        error: function(msg) {
            alert("Impossible de charger le diaporama");
        }
    });
}

/**
 * Allows to initialize slideshow after reloading it through AJAX.
 */
function initSlideshow() {

    $('#picture-list').slick({
        centerMode : true,
        centerPadding : '0px',
        nextArrow: '<span class="slick-next"></span>',
        prevArrow: '<span class="slick-prev"></span>',
        slide : 'li',
        slidesToShow : 1
    });

    initFullScreenAction();
}

/**
 * Allows to initialize action and CSS for pictures with fullscreen.
 */
function initFullScreenAction() {

    $('#picture-list img').each(function() {

        // Define action when user click on button
        $(this).on('click', function() {

            // Add css style (useful to keep border if exist)
            $('#section-popup').attr('style', $(this).attr('style'));

            // Display full screen picture as popup
            $('#section-popup').bPopup({
                content: 'image',
                loadUrl: $(this).attr('src')
            });

            // Close popup when clicking on picture
            // NOTE : Clear popup content to avoid multiple content when re-open...
            $('#section-popup').on('click', function() {
                $('#section-popup').bPopup().close();
                $('#section-popup').html('');
            })

            return false;
        });

        // Display button depending on picture size
        // NOTE 1 : picture container => height: 550px; width: 700px;
        // NOTE 2 : max picture border size => 5px;
        var pictureSize = getPictureSize($(this), 690, 540);
        var fullscreenButton = $(this).next('span.picture-button');
        var bottomPosition = 550 - pictureSize.height + 10;
        var rightPosition = ((700 - pictureSize.width) / 2) + 10;
        $(fullscreenButton).css('bottom', bottomPosition);
        $(fullscreenButton).css('right', rightPosition);

        // Define action if user click => same as picture click
        $(fullscreenButton).on('click', function() {
            $(this).prev('img').click();
        });
    });
}

/**
 * Allows to calculate new picture size depending on specified max width/height
 * @param imgElement Element picture we want to calculate true size
 * @param maxWidth Maximum authorized width
 * @param maxHeight Maximum authorized height
 * @returns Size element with both properties width and height.
 */
function getPictureSize(imgElement, maxWidth, maxHeight) {

    var width = parseInt(imgElement.attr('data-width'));
    var height = parseInt(imgElement.attr('data-height'));

    // Check max width
    if (width > maxWidth) {

        height = Math.round(maxWidth / (width / height));
        width = maxWidth;
    }

    // Check max heigth
    if (height > maxHeight) {

        width = Math.round((width * maxHeight) / height);
        height = maxHeight;
    }

    return {width: width, height: height};
}
