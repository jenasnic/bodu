
$(document).ready(function() {

    $('#video-slideshow').slick({
        centerMode : true,
        centerPadding : '0px',
        fade : true,
        slidesToShow : 1,
        onAfterChange : function(slider, currentIndex) {
            $('.video-description').hide();
            $('#video-' + currentIndex + ' .video-description').show('blind');
        }
    });

    // Init action when user select video to see
    $('.video-menu-item').on('click', function(event) {

        // Cancel event
        global.cancelEvent(event);

        // Select new video menu item
        $('.video-menu-item').removeClass('activ');
        $(this).addClass('activ');

        // Display selected video
        var index = $(this).attr('data-index');
        $('#video-slideshow').slickGoTo(index);
    });

    // Select first video per default
    if ($('.video-menu-item').length > 0)
        $('.video-menu-item')[0].click();
});
