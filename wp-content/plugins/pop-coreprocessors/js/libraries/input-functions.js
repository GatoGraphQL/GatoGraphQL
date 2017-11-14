(function($){
window.popInputFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	fillURLParamInput : function(args) {

		var that = this;
		var block = args.block, targets = args.targets;

		targets.each(function() {

			var input = $(this);
			var val = that.getURLParamInputValue(block, input);			
			input.val(val);
		});
	},
	// fill the input when a new Addon PageSection is created (eg: Add Comment)
	fillAddonInput : function(args) {

		var that = this;
		var targets = args.targets, link = args.relatedTarget;

		// Whenever replicating an Addon, we might want to pick extra information from the opening link (relatedTarget), eg: Header from att data-header for the Contact Profile Addon
		if (link) {
			
			targets.each(function() {

				var input = $(this);
				that.fillInput(input, link);
			});
		}
	},
	// fill the input when a the modal is shown (eg: Share by email)
	fillModalInput : function(args) {

		var that = this;

		var targets = args.targets;
		targets.each(function() {

			var input = $(this);
			var modal = input.closest('.modal');
			modal.on('show.bs.modal', function(e) {

				var link = $(e.relatedTarget);
				that.fillInput(input, link);
			});
		});
	},
	fillAddonURLInput : function(args) {

		var that = this;
		var domain = args.domain, targets = args.targets, link = args.relatedTarget;

		// Whenever replicating an Addon, we might want to pick extra information from the opening link (relatedTarget), eg: Header from att data-header for the Contact Profile Addon
		if (link) {
			
			targets.each(function() {

				var input = $(this);
				that.fillURLInput(domain, input, link);
			});
		}
	},
	// fill the input when a the modal is shown (eg: Share by email)
	fillModalURLInput : function(args) {

		var that = this;

		var domain = args.domain, targets = args.targets;
		targets.each(function() {

			var input = $(this);
			var modal = input.closest('.modal');
			modal.on('show.bs.modal', function(e) {

				var link = $(e.relatedTarget);
				that.fillURLInput(domain, input, link);
			});
		});
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

			var url = popManager.getBlockTopLevelURL(block);//popManager.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/;
			val = getParam(urlparam, url);
		}
		return val || '';
	},

	fillInput : function(input, link) {

		var that = this;

		// Make sure the link we got is the original one, and not the intercepted one
		// All properties are stored under the original link, not the interceptor
		link = popManager.getOriginalLink(link);

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
		link = popManager.getOriginalLink(link);
		
		var url = popFunctions.getUrl(domain, link);
		input.val(url);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popInputFunctions, ['fillURLParamInput', 'fillAddonInput', 'fillModalInput', 'fillAddonURLInput', 'fillModalURLInput', 'browserUrl']);
