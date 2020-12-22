"use strict";
(function($){
window.pop.BootstrapMap = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------
	mapModal : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		targets.on('shown.bs.modal', function() {

			var modal = $(this);
			$.each(modal.find('.pop-map'), function() {

				var map = $(this);
				var block = pop.Manager.getBlock(map);
				if (pop.Manager.jsInitialized(block)) {
					pop.Map.triggerShowMap(domain, pageSection, block, map);
				}
			});
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BootstrapMap, ['mapModal']);
