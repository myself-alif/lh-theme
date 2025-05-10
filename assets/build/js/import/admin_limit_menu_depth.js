/**
 * Limit max menu depth in admin panel
 * Expects wp_localize_script to have set an object of menu locations
 * in the shape of { location: max_depth, location2: max_depth2 }
 * e.g var lh_menu_depths = {"primary":"1","footer":"0"};
 */
(function ($) {
    // Get initial maximum menu depth, so that we may restore to it later.
    let initial_max_depth = null;

    if (typeof wpNavMenu !== 'undefined') {
        initial_max_depth = wpNavMenu.options.globalMaxDepth;
    }

    function set_max_depth () {
        if (typeof lh_menu_depths === 'undefined') {
            return false;
        }

        // Loop through each of the menu locations
        $.each(lh_menu_depths, function (location, max_depth) {
            if (typeof wpNavMenu === 'undefined') {
                return false;
            }

            if (
                $('#locations-' + location).prop('checked') &&
                max_depth < wpNavMenu.options.globalMaxDepth
            ) {
                // If this menu location is checked
                // and if the max depth for this location is less than the current globalMaxDepth
                // Then set globalMaxDepth to the max depth for this location
                wpNavMenu.options.globalMaxDepth = max_depth;
            }
        });
    }

    $(function () {
        // Run the function once on document ready
        set_max_depth();

        // Re-run the function if the menu location checkboxes are changed
        $('.menu-theme-locations input').on('change', function () {
            if (typeof wpNavMenu === 'undefined') {
                return false;
            }

            wpNavMenu.options.globalMaxDepth = initial_max_depth;

            set_max_depth();
        });
    });
})(jQuery);
