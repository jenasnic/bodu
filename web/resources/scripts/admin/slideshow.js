
$(document).ready(function() {

    $("#picture-list").sortable({
        update: function(event, ui) {
            $("#ordered-picture-list").val($('#picture-list').sortable('serialize'));
        }
    });

    $("#picture-list").disableSelection();

    $('#bodu_slideshowbundle_slideshow_activBorder').on('change', function(event) {
        global.cancelEvent(event);
        selectBorder();
    });

    $('#bodu_slideshowbundle_slideshow_borderColor').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).val(hex);
            $(el).ColorPickerHide();
        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }
    });

    selectBorder();
});

function selectBorder() {

    var isActiv = $('#bodu_slideshowbundle_slideshow_activBorder').is(':checked');

    if (isActiv)
        $('#slideshow-border').show();
    else
        $('#slideshow-border').hide();
}