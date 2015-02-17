
$(document).ready(function() {

    $('#video-slideshow').slick({
        arrows: false,
        centerMode : true,
        centerPadding : '0px',
        cssEase: 'linear',
        fade : true,
        slidesToShow : 1
    });

    $('#video-slideshow').on('afterChange', function(slider, currentSlide) {
        $('.video-description').hide();
        $('#video-' + currentSlide.currentSlide + ' .video-description').show('blind');
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
        $('#video-slideshow').slick('slickGoTo', index);
    });
});
