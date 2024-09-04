/**
 * MG Recipe Manager Admin Scripts
 *
 * @package MG_Recipe_Manager
 * @since 1.0.0
 */

// Admin JavaScript for MG Recipe Manager
(function($) {
    'use strict';

    $(document).ready(function() {
        console.log('MG Recipe Manager admin script loaded');

        // Add any admin-specific functionality here
        $('#mg_recipe_manager_display_author').on('change', function() {
            console.log('Display author setting changed');
        });
    });

})(jQuery);