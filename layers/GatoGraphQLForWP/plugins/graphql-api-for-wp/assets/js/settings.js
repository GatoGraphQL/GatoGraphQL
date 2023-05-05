jQuery( document ).ready( function($){
    $('.graphql-api-settings-section a.graphql-api-show-settings-items').on('click', function(e){
        e.preventDefault();
        settingsSection = $(this).closest('.graphql-api-settings-section');
        settingsSection.find('.graphql-api-settings-item').show();
    });
});
