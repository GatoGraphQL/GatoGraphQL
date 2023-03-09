jQuery( document ).ready( function($){
    $('.graphql-api-tabpanel > .nav-tab-container > .nav-tab-wrapper > a.nav-tab').on('click', function(e){
        e.preventDefault();
        tab = $(this).attr('href');
        $('.graphql-api-tabpanel > .nav-tab-container > .nav-tab-content .tab-content').hide();
        $(tab).show();
        $('.graphql-api-tabpanel > .nav-tab-container > .nav-tab-wrapper > a.nav-tab').removeClass('nav-tab-active');
        $('.graphql-api-tabpanel > .nav-tab-container > .nav-tab-wrapper > a[href="'+tab+'"].nav-tab').addClass('nav-tab-active');
    });
});
