/**
 * This file contains global functions. It is loaded on every pages.
 */

var global = {

    basePath : "/app_dev.php",

    cleditorConfig : {
        controls: "bold italic underline strikethrough | font size color | removeformat | bullets numbering | "
                + "outdent indent | alignleft center alignright justify | link unlink | source",
        height: '250',
        colors: 'FFF 999 606C6C 383E3E 000 2696D5 002554 F7FF11 FB9407 F00 24FF19 FF09C2',
    },

    /**
     * Cancel event default action
     * @param event
     */
    cancelEvent : function(event) {
        event = $.event.fix(event);
        event.preventDefault();
    }
};

/**
 * Specific function used to decorate select controls in form.
 */
$.fn.extend({

    /**
     * Allows to define an element as show/hide bloc with an item to expand/collapse.
     * @param moreText Text to display to expand element.
     * @param lessText Text to display to collapse element.
     * @param display TRUE to display element per default, FALSE either.
     * @param bottom TRUE to add expand link after element (bottom of element), FALSE to add expand link before element (top of element).
     */
    customizeExpand : function(moreText, lessText, display, bottom) {

        // Apply function to each selected element
        this.each(function() {

            if (!display)
                $(this).hide();

            var linkElementFlux = '<span class="custom-expand">' + (display ? lessText : moreText) + '</span>';

            // If expand link must be added after element, define action as consequence
            if (bottom) {

                $(this).after(linkElementFlux);

                // Define action when clicking on 'custom-expand' element (show/hide)
                $(this).next().on('click', function() {
                    switchExpandElement($(this), $(this).prev());
                });
            }
            else {

                $(this).before(linkElementFlux);

                // Define action when clicking on 'custom-expand' element (show/hide)
                $(this).prev().on('click', function() {
                    switchExpandElement($(this), $(this).next());
                });
            }

            /**
             * Allows to display hide expand element and to update expand link text.
             * @param expandLinkElement link element we want to update text (more/less).
             * @param expandableElement expandable element to display or hide.
             */
            function switchExpandElement(expandLinkElement, expandableElement) {

                var isVisible = expandableElement.is(":visible");

                if (isVisible) {

                    expandableElement.hide();
                    expandLinkElement.html(moreText);
                }
                else {

                    expandableElement.show();
                    expandLinkElement.html(lessText);
                }
            }
        });
    },

    /**
     * Allows to split an element with a show/hide bloc depending on specific size (i.e. height).
     * @param moreText Text to display to expand element.
     * @param lessText Text to display to collapse element.
     * @param size Max height of element we want to display (element will be splited over this size).
     * @param display TRUE to display more than max size per default (i.e. full element), FALSE either.
     */
    customizeSplitSize : function(moreText, lessText, size, display) {

        // Apply function to each selected element
        this.each(function() {

            if ($(this).height() <= size)
                return;

            // Add expand link after element
            var linkElementFlux = '<span class="custom-expand">' + (display ? lessText : moreText) + '</span>';
            $(this).after(linkElementFlux);

            if (!display)
                switchSplitElement($(this).next(), $(this));

            // Define action when clicking on 'custom-expand' element (display full/splited element)
            $(this).next().on('click', function() {
                switchSplitElement($(this), $(this).prev());
            });

            /**
             * Allows to display full/splited element and to update expand link text.
             * @param expandLinkElement Link element we want to update text (more/less).
             * @param splitedElement Splited element to display as fulle element or splited element.
             */
            function switchSplitElement(expandLinkElement, splitedElement) {

                var hasStyle = splitedElement.attr("style");

                // If element is expanded => hide splited area
                if (hasStyle) {

                    $(splitedElement).removeAttr('style');
                    expandLinkElement.html(lessText);
                }
                else {

                    $(splitedElement).css({
                        'max-height' : size + 'px',
                        'overflow' :'hidden'
                    });
                    expandLinkElement.html(moreText);
                }
            }
        });
    }
});

function dump(obj) {
    var out = '';
    for (var i in obj)
        out += i + ": " + obj[i] + "\n";
    console.log(out);
}
