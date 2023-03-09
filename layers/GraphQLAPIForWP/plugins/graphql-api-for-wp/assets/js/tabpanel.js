jQuery( document ).ready( function($){
    $('.graphql-api-tabpanel > .nav-tab-container > .nav-tab-wrapper > a.nav-tab').on('click', function(e){
        e.preventDefault();
        tab = $(this).attr('href');
        /**
         * Allow to specify which is the target to show/hide.
         * This allows to have a tabPanel wrapping another tabPanel,
         * and avoid toggling the inner ".tab-content" (in addition
         * to the outer ones) when clicking on the outer tabPanels's tab.
         */
        tabContentTarget = $(this).data('tab-content-target');
        if (tabContentTarget !== undefined) {
            $(tabContentTarget).hide();
        } else {
            $('.graphql-api-tabpanel > .nav-tab-container > .nav-tab-content .tab-content').hide();
        }        
        $(tab).show();
        $('.graphql-api-tabpanel > .nav-tab-container > .nav-tab-wrapper > a.nav-tab').removeClass('nav-tab-active');
        $('.graphql-api-tabpanel > .nav-tab-container > .nav-tab-wrapper > a[href="'+tab+'"].nav-tab').addClass('nav-tab-active');
    });
});
