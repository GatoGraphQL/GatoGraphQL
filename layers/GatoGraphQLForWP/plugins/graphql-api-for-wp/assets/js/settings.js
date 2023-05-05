jQuery( document ).ready( function($){
    $('.gato-graphql-settings-section a.gato-graphql-show-settings-items').on('click', function(e){
        e.preventDefault();
        settingsSection = $(this).closest('.gato-graphql-settings-section');
        settingsSection.find('.gato-graphql-settings-item').show();
    });
});
