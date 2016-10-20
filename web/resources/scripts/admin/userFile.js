
$(document).ready(function() {

    refreshExplorer();

    // Configure drop zone for upload
    $('#userfile-dropzone').dropzone({
        autoProcessQueue: true,
        queuecomplete: function() {refreshExplorer();}
    });

    $('#userfile-reset').on('click', function() {
        Dropzone.forElement("#userfile-dropzone").removeAllFiles(true);
    });
});

function refreshExplorer() {

    var currentPath = $('#filePath').val();
    $('#user-file-explorer').fileUserExplorer({
        filePath: currentPath,
        afterReload: reloadFilePathInfo
    });
}

/**
 * Allows to refresh path info when browsing file explorer.
 * NOTE : This file path is used when uploading file or creating folder.
 * @param explorer Explorer element we are reloading.
 * @param result TRUE if reload is successful, FALSE either.
 */
function reloadFilePathInfo(explorer, result) {

    if (result) {

        // Refresh filePath field in forms (value send to controller) => upload + folder
        var newFilePath = explorer.children("input[type='hidden'].file-path").val();
        $('#filePath').val(newFilePath);
        $('#folderPath').val(newFilePath);

        // NOTE : For root path, display a default '/' to indicate current path...
        if (newFilePath.length == 0)
            newFilePath = '/';

        // Update full-path-info for both forms (upload + folder)
        $('.full-path-info').html(newFilePath);
    }
}
