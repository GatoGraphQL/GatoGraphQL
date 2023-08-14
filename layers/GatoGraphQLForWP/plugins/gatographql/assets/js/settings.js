jQuery( document ).ready( function($){
    $('.gatographql-settings-section a.gatographql-show-settings-items').on('click', function(e){
        e.preventDefault();
        settingsSection = $(this).closest('.gatographql-settings-section');
        settingsSection.find('.gatographql-settings-item').show();
    });
});
