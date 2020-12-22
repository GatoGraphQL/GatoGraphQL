"use strict";
(function($){
window.pop.InputFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	fillURLParamInput : function(args) {

		var that = this;
		var intercepted = args.intercepted;

		// Only execute if the trigger is an intercepted URL. If not, this functionality is already implemented on the server-side
		if (intercepted) {

			var domain = args.domain, block = args.block, targets = args.targets;
			targets.each(function() {

				var input = $(this);
				var val = that.getURLParamInputValue(domain, block, input);			
				input.val(val);
			});
		}
	},
	// fill the input when a new Addon PageSection is created (eg: Add Comment)
	fillAddonInput : function(args) {

		var that = this;
		var intercepted = args.intercepted;

		// Only execute if the trigger is an intercepted URL. If not, this functionality is already implemented on the server-side
		if (intercepted) {

			var targets = args.targets, link = args.relatedTarget;

			// Whenever replicating an Addon, we might want to pick extra information from the opening link (relatedTarget), eg: Header from att data-header for the Contact Profile Addon
			if (link) {
				
				targets.each(function() {

					var input = $(this);
					that.fillInput(input, link);
				});
			}
		}
	},
	fillAddonURLInput : function(args) {

		var that = this;
		var intercepted = args.intercepted;

		// Only execute if the trigger is an intercepted URL. If not, this functionality is already implemented on the server-side
		if (intercepted) {

			var domain = args.domain, targets = args.targets, link = args.relatedTarget;

			// Whenever replicating an Addon, we might want to pick extra information from the opening link (relatedTarget), eg: Header from att data-header for the Contact Profile Addon
			if (link) {
				
				targets.each(function() {

					var input = $(this);
					that.fillURLInput(domain, input, link);
				});
			}
		}
	},

	browserUrl : function(args) {

		var that = this;
		var targets = args.targets;

		// All the targets are inputs inside a form. Before the form submits, fill itself with the browser url value
		targets.closest('form').on('beforeSubmit', function() {

			var url = window.location.href;
			targets.val(url);
		})
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	getURLParamInputValue : function(block, input) {

		var that = this;
		var val, urlparam = input.data('urlparam');
		if (urlparam) {

			var url = pop.Manager.getBlockTopLevelURL(domain, block);//pop.Manager.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/;
			val = getParam(urlparam, url);
		}
		return val || '';
	},

	fillInput : function(input, link) {

		var that = this;

		// Make sure the link we got is the original one, and not the intercepted one
		// All properties are stored under the original link, not the interceptor
		link = pop.Manager.getOriginalLink(link);

		var val, attr = input.data('attr');
		if (attr) {

			val = link.data(attr);
		}
		val = val || '';
		
		input.val(val);
	},

	fillURLInput : function(domain, input, link) {

		var that = this;

		// Make sure the link we got is the original one, and not the intercepted one
		// All properties are stored under the original link, not the interceptor
		link = pop.Manager.getOriginalLink(link);
		
		var url = pop.Functions.getUrl(domain, link);
		input.val(url);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.InputFunctions, ['fillURLParamInput', 'fillAddonInput', 'fillAddonURLInput', 'browserUrl']);
