;(function ($) {

    'use strict';

    jQuery(".bafg-twentytwenty-container").each(function () {

            if (jQuery(this).attr('bafg-move-slider-on-hover') == 'no') {
                var moveSliderHover = false;
            } else {
                var moveSliderHover = true;
            }

            if (jQuery(this).attr('bafg-overlay') == 'yes') {
                var overlay = false;
            } else {
                var overlay = true;
            }

            if (jQuery(this).attr('bafg-click-to-move') == 'no') {
                var clickToMove = false;
            } else {
                var clickToMove = true;
            }

            jQuery(this).twentytwenty({
                orientation: jQuery(this).attr('bafg-orientation'),
                default_offset_pct: jQuery(this).attr('bafg-default-offset'),
                before_label: jQuery(this).attr('bafg-before-label'),
                after_label: jQuery(this).attr('bafg-after-label'),
                no_overlay: overlay,
                move_slider_on_hover: moveSliderHover,
                click_to_move: clickToMove
            });
            
            
        });

        jQuery(".twentytwenty-wrapper .design-1 .twentytwenty-handle").wrapInner("<div class='handle-trnasf' />");
        
    jQuery(window).on('load', function () {
        
        jQuery(".bafg-twentytwenty-container").each(function () {
            var beforeImageW = jQuery(this).find('img.twentytwenty-before').width();
            jQuery(this).css( 'max-width', beforeImageW + 'px' );
        });
        
        jQuery(window).trigger("resize.twentytwenty");
        
    });
        
})(jQuery);
