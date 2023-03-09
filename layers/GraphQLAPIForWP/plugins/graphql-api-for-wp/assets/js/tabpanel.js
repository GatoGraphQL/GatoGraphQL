jQuery( document ).ready( function($){
    $('.graphql-api-tabpanel > .nav-tab-container > .nav-tab-wrapper > a.nav-tab').on('click', function(e){
        e.preventDefault();
        tab = $(this).attr('href');
        tabPanel = $(tab).closest('.graphql-api-tabpanel');
        tabPanelID = tabPanel.attr('id');
        tabPanelSelector = tabPanelID == undefined ? '.graphql-api-tabpanel' : '#' + tabPanelID;
        /**
         * Allow to specify which is the target to show/hide.
         * This allows to have a tabPanel wrapping another tabPanel,
         * and avoid toggling the inner ".tab-content" (in addition
         * to the outer ones) when clicking on the outer tabPanels's tab.
         */
        tabContentTarget = tabPanel.data('tab-content-target');
        if (tabContentTarget == undefined) {
            tabContentTarget = tabPanelSelector +' > .nav-tab-container > .nav-tab-content .tab-content';
        }
        $(tabContentTarget).hide();
        $(tab).show();
        $(tabPanelSelector +' > .nav-tab-container > .nav-tab-wrapper > a.nav-tab').removeClass('nav-tab-active');
        $(tabPanelSelector +' > .nav-tab-container > .nav-tab-wrapper > a[href="'+tab+'"].nav-tab').addClass('nav-tab-active');
    });
});
