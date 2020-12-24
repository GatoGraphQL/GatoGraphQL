jQuery( document ).ready( function($){
    $('.graphql-api-tabpanel .nav-tab').on('click', function(e){
        e.preventDefault();
        tab = $(this).attr('href');
        $('.graphql-api-tabpanel .tab-content').hide();
        $(tab).show();
        $('.graphql-api-tabpanel .nav-tab').removeClass('nav-tab-active');
        $('.graphql-api-tabpanel a[href="'+tab+'"].nav-tab').addClass('nav-tab-active');
    });
});
