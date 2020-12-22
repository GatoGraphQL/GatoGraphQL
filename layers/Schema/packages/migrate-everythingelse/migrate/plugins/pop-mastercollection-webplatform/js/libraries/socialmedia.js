"use strict";
(function($){
window.pop.SocialMedia = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	socialmediaCounter : function(args) {

		var that = this;

		// Execute it only on hover, not directly because it takes so much time to load!
		var targets = args.targets;
		targets.one('mouseenter', function() {

			var socialmedia = $(this);
			socialmedia.children('a.socialmedia-item').each(function() {
				var item = $(this);
				var provider = item.data('provider');
				
				// If there's no provider, then do nothing (eg: GPlus)
				if (provider) {
					
					var settings = pop.c.SOCIALMEDIA[provider];
					if (settings) {

						// Copied from http://www.codechewing.com/library/facebook-share-button-with-share-count/		
						$.ajax({
							type: 'GET',
							dataType: settings.dataType,
							url: settings['counter-url'].replace('%s', item.data('url')),
							success: function(json) {

								// Check if the response contains a 'shares'/'count' property.
								if( !json.hasOwnProperty(settings.property) )
								return;

								var count = json[settings.property];
								if (count == 0) return;

								// A shares property and value must exist, update the span element with the share count
								item.children('.pop-counter').html(count);
							}
						});
					}
				}
			});
		});	
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.SocialMedia, ['socialmediaCounter']);
