
$(document).ready(function () {

    $("#menu-list").sortable({
        update: function(event, ui) {
            $("#ordered-menu-list").val($('#menu-list').sortable('serialize'));
        }
    });
    $("#menu-list").disableSelection();

    initializeMenuResize();
});

/**
 * Allows to initialize area used to customize menu width for front-office.
 */
function initializeMenuResize() {

    var resizing, resizingItem, startPosition, initialWidth;

    $("#bo-preview-menu li").mousedown(function (e) {

        resizingItem = $(this)
        initialWidth = resizingItem.width();
        resizingItem.addClass("resizing");
        startPosition = e.pageX;
        resizing = true;
    });

    $(document).mousemove(function (e) {
        if (resizing)
            resizingItem.width(initialWidth + (e.pageX - startPosition));
    });

    $(document).mouseup(function () {

        if (resizing) {

            resizingItem.removeClass("resizing");
            resizing = false;
            updateMenuWidthField();
        }
    });
}

/**
 * Allows to update specific field used to save menu's width.
 */
function updateMenuWidthField() {

    var menuWidthList = '';
    $('#bo-preview-menu li').each(function(){
        menuWidthList += ';' + $(this).attr('data-id') + ':' + $(this).width(); 
    });

    // Remove first ';'
    if (menuWidthList.length > 0)
        menuWidthList = menuWidthList.substring(1);

    $('#width-menu-list').val(menuWidthList);
}
