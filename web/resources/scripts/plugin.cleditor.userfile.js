
/**
 * This file allows to add user file plugin for cleditor.
 * WARNING : It requires "userFileExplorer.js" file to work.
 */

(function($) {

    // Define userfile button
    $.cleditor.buttons.userfile = {
        name: "userfile",
        css: {"background-position": "-552px center"},
        title: "Userfile",
        command: "inserthtml",
        popupName: "Userfile",
        popupClass: "cleditorPrompt",
        popupContent: "Position : <input type='radio' name='float' value='none' checked> Normale "
            + "<input type='radio' name='float' value='right'> Droite "
            + "<input type='radio' name='float' value='left'> Gauche "
            + "<div class='sep-10'></div>"
            + "Marges : <input type='text' id='margin' name='margin' style='width:30px;text-align:right;' /> px"
            + "<div class='sep-10'></div><hr/><div class='sep-10'></div>"
            + "</div><div class='file-manager'></div>",
        buttonClick: initUserfilePopup
    };

    /**
     * Allows to initialize popup as file explorer
     */
    function initUserfilePopup(e, data) {

        var explorer = $(data.popup).find('.file-manager');

        // If file explorer not yet initialized => process initialization
        if (!explorer.html()) {

            explorer.fileUserExplorer({

                // Define our own method on file explorer when user click on file => get file and add it as an image
                onFileClick: function(item, explorer) {

                    // Get optional configuration to display picture (position + margin)
                    var position = $(data.popup).children('input[name="float"]:checked').val();
                    var margin = $(data.popup).children('#margin').val();

                    var style = 'float:' + position + ';';

                    // Set margin depending on image's postion
                    if (margin > 0) {

                        if (position == 'left')
                            style += "margin: 0 " + margin + "px " + margin + "px 0;";
                        else if (position == 'right')
                            style += "margin: 0 0 " + margin + "px " + margin + "px;";
                        else
                            style += "margin: 0 " + margin + "px;";
                    }

                    var name = item.children('.file-explore').html();
                    var path = item.children('.file-shortcut').html();
                    var html = '<img src="' + path + '" alt="' + name + '" style="' + style + '" />';

                    // Add HTML content (i.e. image with CSS) to textarea
                    data.editor.execCommand(data.command, html, null, data.button);
                    data.editor.hidePopups();
                    data.editor.focus();
                }
            });
        }
    }
})(jQuery);
