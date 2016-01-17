
$(document).ready(function() {

    $("#bodu_slideshowbundle_slideshow_description").cleditor({height: 170});

    // Set picture list sortable
    $('#picture-list').sortable({
        update: function(event, ui) {
            $("#ordered-picture-list").val($('#picture-list').sortable('serialize'));
        }
    });

    // Use dropzone to upload slideshow's pictures
    $('#slideshow-dropzone').dropzone({
        previewTemplate: global.dropzoneTemplate,
        sending: function(file, xhr, formData) {formData.append('slideshow-id', $('#slideshow-id').val());},
        success: function(file, response) {addFileToList(file, response);}
    });

    // Define action when user remove picture from list
    $('#picture-list .delete-picture').on('click', function() {deletePicture($(this));});

    // Define actions when user submit form
    // NOTE : this button is outside form element...
    $('#slideshow-valid-button').on('click', function() {$('#slideshow-form').submit();});

    // Display/hide border properties configuration when activate/deactivate border
    $('#jc_slideshowbundle_slideshow_activBorder').on('change', function(event) {
        global.cancelEvent(event);
        selectBorder();
    });

    // Define action when setting color
    $('#jc_slideshowbundle_slideshow_borderColor').ColorPicker({
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

/**
 * This methode is called after picture upload succeed. It allows to update picture list adding new uploaded picture.
 * @param file Uploaded file.
 * @param response Response from upload methode called.
 */
function addFileToList(file, response) {

    if (response.success) {

        // Create new row for specified file
        var row = $('#row-template tbody tr').clone();
        row.find('input.picture-id').val(response.id);
        row.find('input.picture-name').val(response.name);

        // Add event for remove button
        row.find('.delete-picture').on('click', function() {deletePicture($(this));})
        $('#picture-list').append(row);

        // Remove picture from dropzone
        //file.previewElement.remove();
    }
    else
        alert(response.message);
}

/**
 * Allows to remove picture from slideshow.
 * @param button Cliqued JQuery element (used to identify picture and row to remove).
 */
function deletePicture(button) {

    if (confirm('Confirmer la suppression ?')) {

        var rowToDelete = $(button).parent().parent();
        var pictureId = rowToDelete.find('input.picture-id').val();

        var ajaxBaseUrl = global.basePath + '/admin/slideshow/picture/delete';

        $.ajax({
            url: ajaxBaseUrl,
            type: 'POST',
            data: {pictureId: pictureId},
            timeout: 10000,
            success: function(response) {

                // In case of success => remove row
                if (response.success)
                    rowToDelete.remove();
                else
                    alert(response.message);
            },
            error: function(msg) {
                alert("Impossible de supprimer l'image");
            }
        });
    }
}

function selectBorder() {

    var isActiv = $('#jc_slideshowbundle_slideshow_activBorder').is(':checked');

    if (isActiv)
        $('#slideshow-border').show();
    else
        $('#slideshow-border').hide();
}
