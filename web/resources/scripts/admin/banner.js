
$(document).ready(function() {

    // Set picture list sortable
    $('#banner-list').sortable({
        update: function(event, ui) {updateBannerRank();}
    });

    // Use dropzone to upload banner
    $('#banner-dropzone').dropzone({
        success: function(file, response) {addFileToList(file, response);}
    });

    // Define action when user remove picture from list
    $('#banner-list .delete-picture').on('click', function() {deleteBanner($(this));});
});

/**
 * This methode is called after picture upload succeed. It allows to update banner list adding new uploaded picture.
 * @param file Uploaded file.
 * @param response Response from upload methode called.
 */
function addFileToList(file, response) {

    if (response.success) {

        // Create new row for specified file
        var row = $('#row-template tbody tr').clone();
        row.attr("id", "banner-list-" + response.id);

        var inputRank = row.find('input.banner-rank');
        var inputTitle = row.find('input.banner-title');
        var pictureItem = row.find('td.bo-table-picture img');

        // Update fields values
        inputRank.val(response.rank);
        inputTitle.val(response.title);
        pictureItem.attr("src", response.url);

        // Set rank/title for POST (when submitting form)
        inputRank.attr("id", "banner-rank-" + response.id);
        inputRank.attr("name", "banner-rank-" + response.id);
        inputTitle.attr("id", "banner-title-" + response.id);
        inputTitle.attr("name", "banner-title-" + response.id);

        // Add event for remove button
        row.find('.delete-picture').on('click', function() {deleteBanner($(this));})
        $('#banner-list').append(row);
    }
    else
        alert(response.message);
}

/**
 * Allows to remove banner from home page.
 * @param button Cliqued JQuery element (used to identify picture and row to remove).
 */
function deleteBanner(button) {

    if (confirm('Confirmer la suppression ?')) {

        var rowToDelete = $(button).parent().parent();
        var bannerId = rowToDelete.find('input.banner-id').val();

        var ajaxBaseUrl = global.basePath + '/admin/home/banner/delete';

        $.ajax({
            url: ajaxBaseUrl,
            type: 'POST',
            data: {bannerId: bannerId},
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

/**
 * Allows to update rank field for banners (after user change sort...).
 */
function updateBannerRank() {

    // Get all pictures to set rank
    var bannerList = $("#banner-list").children("tr");

    for (var i=0; i<bannerList.length; i++)
        $(bannerList[i]).find(".banner-rank").val(i+1);
}
