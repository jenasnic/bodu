$.fn.extend({

    /**
     * Allows to define an element as file explorer.
     * @param option List of options to initialize explorer :
     * <ul>
     * <li>filePath : default file path to load (as String).</li>
     * <li>afterReload(explorer, result) : callback function to call after explorer has reload (result : TRUE in case of successful reloading, FALSE either).</li>
     * <li>onFileClick(item, explorer) : callback function to call when user click on file item. Return FALSE to avoid further process, TRUE either.</li>
     * <li>onFolderClick(item, explorer) : callback function to call when user click on folder item. Return FALSE to avoid further process, TRUE either.</li>
     * <li>onDelete(item, explorer) : callback function to call when user remove file (file or folder). Return FALSE to avoid further process, TRUE either.</li>
     * </ul>
     */
    fileUserExplorer : function(options) {

        var options = options;

        // Apply function to each selected element
        this.each(function() {

            var filePath = (options && options.filePath) ? options.filePath : '';
            loadExplorer($(this), filePath);

            /**
             * Allows to load file content inside specified element.
             * @param explorer HTML element (as JQuery element) we want to set as file explorer.
             * @param filePath Base file path we want to get file content.
             */
            function loadExplorer(explorer, filePath) {

                var ajaxBaseUrl = global.basePath + '/admin/userfile/ajax/explore';

                $.ajax({
                    url: ajaxBaseUrl,
                    type: 'POST',
                    data: {path: filePath},
                    timeout: 10000,
                    success: function(msg) {

                        // Refresh file manager content (listing all files) and initialize actions
                        explorer.html(msg);
                        initializeExplorerAction(explorer);

                        if (options && options.afterReload)
                            options.afterReload(explorer, true);
                    },
                    error: function(msg) {
                        alert("Impossible de charger le contenu du dossier");
                        explorer.html('Erreur');
                        if (options && options.afterReload)
                            options.afterReload(explorer, false);
                    }
                });
            }

            /**
             * Allows to initialize explorer actions.
             * @param explorer Explorer (as JQuery element) we want to initialize actions.
             */
            function initializeExplorerAction(explorer) {

                // Initialize action when user click on parent => load parent file content
                explorer.children('ul').children('li.file-manager-parent').children('span.file-explore').on('click', function(event) {

                    var parentFileToLoad = $("input[type='hidden'].file-path").val();
                    parentFileToLoad = parentFileToLoad.substring(0, parentFileToLoad.lastIndexOf("/"));
                    loadExplorer(explorer, parentFileToLoad);
                });

                // Initialize action when user click on folder => load folder content
                explorer.children('ul').children('li.file-manager-folder').children('span.file-explore').on('click', function(event) {

                    var keepProcessing = true;

                    // If callback function => call it
                    if (options && options.onFolderClick)
                        keepProcessing = options.onFolderClick($(this).parent('li'), explorer);

                    if (keepProcessing) {

                        var fileToLoad = $("input[type='hidden'].file-path").val() + "/" + $(this).html();
                        loadExplorer(explorer, fileToLoad);
                    }
                });

                // Initialize action when user click on file => only if action defined (no default action)
                if (options && options.onFileClick) {

                    explorer.children('ul').children('li.file-manager-file').children('span.file-explore').on('click', function(event) {
                        options.onFileClick($(this).parent('li'), explorer);
                    });
                }

                // Initialize action when user hovers over file/folder => display picture in preview area
                explorer.children('ul').children('li.file-manager-folder, li.file-manager-file').on('mouseover', function(event) {
                    explorer.children(".file-manager-preview").children("img").attr("src", $(this).children('span.file-shortcut').html());
                });

                // Initialize action when user click on delete (for file and folder) => remove file
                explorer.children('ul').children('li').children('span.file-delete').on('click', function(event) {

                    var fileName = $(this).siblings('span.file-explore').html();

                    if (confirm("Etes-vous sur de vouloir supprimer le fichier '" + fileName + "' ?"))
                        removeFileItem($(this).parent('li'), explorer);
                });
            }

            /**
             * Allows to remove specified file (or folder).
             * NOTE : In case of folder, all its content will be removed (files and sub directories).
             * @param item Element matching file we want to remove (LI element).
             * @param explorer Explorer (as JQuery element) we want to remove item.
             */
            function removeFileItem(item, explorer) {

                var keepProcessing = true;

                // If callback function => call it
                if (options && options.onDelete)
                    keepProcessing = options.onDelete(item, explorer);

                if (keepProcessing) {

                    var path = $("input[type='hidden'].file-path").val() + "/" + $(item).children('span').html();

                    var ajaxBaseUrl = global.basePath + '/admin/userfile/ajax/delete';

                    $.ajax({
                        url: ajaxBaseUrl,
                        type: 'POST',
                        data: {path: path},
                        timeout: 10000,
                        success: function(result) {

                            // In case of success, display appropriate message and remove item...
                            if (result)
                                $(item).remove();
                            else
                                alert("Impossible de supprimer le fichier");
                        },
                        error: function(msg) {
                            alert("Impossible de supprimer le fichier");
                        }
                    });
                }
            }

        });
    }
});
