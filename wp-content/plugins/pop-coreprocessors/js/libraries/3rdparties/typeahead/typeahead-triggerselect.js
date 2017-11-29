"use strict";
(function($){
window.popTypeaheadTriggerSelect = {
	
	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	triggerSelect : function(domain, pageSection, block, typeahead, datum) {
	
		var that = this;

		// Comment Leo 30/10/2017: when doing serverside-rendering, and first loading the website (eg: https://www.mesym.com/en/add-post/?related[0]=22199),
		// we will already have the item rendered. And since the database is not sent anymore, datum.id will be empty
		// so we must check for this condition too.

		// Check this value has not been added before
		if (datum && datum.id && typeahead.find('input[type="hidden"][value="'+datum.id+'"]').length == 0) {
		
			var jsSettings = popManager.getJsSettings(domain, pageSection, block, typeahead);
			var template = jsSettings['trigger-layout'];
			var pssId = popManager.getSettingsId(pageSection);
			var bsId = popManager.getSettingsId(block);
			var context = $.extend({itemObject: datum}, jsSettings['datum-context']);

			// Generate the code and append
			var options = {extendContext: context}
			popJSRuntimeManager.setBlockURL(block/*block.data('toplevel-url')*/);
			// var domain = block.data('domain') || getDomain(block.data('toplevel-url'));
			var html = popManager.getTemplateHtml(domain, pageSection, block, template, options);
			popManager.mergeHtml(html, typeahead.find('.pop-box'));
			popManager.runJSMethods(domain, pageSection, block, template, 'last');

			// Delete the session ids before starting a new session
			popJSRuntimeManager.deleteBlockLastSessionIds(domain, pageSection, block, template);

			popTypeaheadValidate.validateMaxSelected(pageSection, block, typeahead);
			
			typeahead.triggerHandler('selected', [datum]);
		}
	},

	removeSelected : function(typeahead, id) {
	
		var that = this;

		var selected = typeahead.find('input[type="hidden"][value="'+id+'"]');
		var alert = selected.closest('.alert');
		alert.alert('close');
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// popJSLibraryManager.register(popTypeaheadTriggerSelect, []);
