
(function($) {
 
    // Define the hello button
    $.cleditor.buttons.video = {
        name: "video",
        css: {"background": "url('/resources/css/lib/images-cleditor/video.png') no-repeat 5px 4px"},
        title: "Vidéo",
        command: "inserthtml",
        popupName: "Video",
        popupClass: "cleditorPrompt",
        popupContent: "<label>Vidéo intégrée</label>"
            + "<textarea style='height:70px;max-height:70px;max-width:350px;min-height:70px;min-width:350px;width:350px;' name='integrated-video-flux'></textarea>"
            + "<div class='sep-10'></div><input type='button' value='Valider'/>",
        buttonClick: addIntegratedVideo
    };

    function addIntegratedVideo(e, data) {

        $(data.popup).children(':button').unbind('click').bind('click', function() {

            var flux = $(data.popup).children('textarea[name="integrated-video-flux"]').val();
            var html = '<div class="center">' + flux + '</div>';

            // Add HTML content (i.e. integrated video)
            data.editor.execCommand(data.command, html, null, data.button);
            data.editor.hidePopups();
            data.editor.focus();
        });
    }
})(jQuery);
