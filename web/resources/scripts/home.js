
$(document).ready(function() {

    $('#banner-list').slick({
        autoplay: true,
        centerMode : true,
        centerPadding : '0px',
        dots : true,
        fade : true,
        nextArrow: '<span class="slick-next"></span>',
        prevArrow: '<span class="slick-prev"></span>',
        slide : 'li',
        slidesToShow : 1,
        speed: 500
    });

    $('#banner-list li').on('click', function() {

        // Display full screen picture as popup
        $('#home-popup').bPopup({
            content: 'image',
            loadUrl: $(this).children('img.picture-fullscreen').attr('src')
        });

        // Close popup when clicking on picture
        // NOTE : Clear popup content to avoid multiple content when re-open...
        $('#home-popup').on('click', function() {
            $('#home-popup').bPopup().close();
            $('#home-popup').html('');
        })

        return false;
    });
});
