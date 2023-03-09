jQuery( document ).ready( function($){
    $('.graphql-api-tabpanel > .nav-tab-container > .nav-tab-wrapper > a.nav-tab').on('click', function(e){
        e.preventDefault();
        tab = $(this).attr('href');
        tabPanel = $(tab).closest('.graphql-api-tabpanel');
        /**
         * Allow to specify which is the target to show/hide.
         * This allows to have a tabPanel wrapping another tabPanel,
         * and avoid toggling the inner ".tab-content" (in addition
         * to the outer ones) when clicking on the outer tabPanels's tab.
         */
        tabContentTarget = tabPanel.data('tab-content-target');
        if (tabContentTarget == undefined) {
            tabContentItems = tabPanel.children('.nav-tab-container').children('.nav-tab-content').find('.tab-content');
        } else {
            tabContentItems = $(tabContentTarget);
        }
        $(tabContentItems).hide();
        $(tab).show();
        tabPanel.children('.nav-tab-container').children('.nav-tab-wrapper').children('a.nav-tab').removeClass('nav-tab-active');
        tabPanel.children('.nav-tab-container').children('.nav-tab-wrapper').children('a[href="'+tab+'"].nav-tab').addClass('nav-tab-active');
    });
});
