"use strict";
(function($){
window.pop.CustomFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	// addPageSectionIds : function(args) {

	// 	var that = this;
	// 	var domain = args.domain, pageSection = args.pageSection, moduleName = args.module;
	// 	var pssId = pop.Manager.getSettingsId(pageSection);
	// 	var psId = pageSection.attr('id');

	// 	if (psId == pop.c.PS_HOVER_ID) {

	// 		pop.JSRuntimeManager.addPageSectionId(domain, pssId, moduleName, psId, 'closehover');
	// 	}	
	// 	else if (psId == pop.c.PS_FRAME_NAVIGATOR_ID) {

	// 		pop.JSRuntimeManager.addPageSectionId(domain, pssId, moduleName, psId, 'closenavigator');
	// 	}	
	// 	else if (psId == pop.c.PS_ADDONS_ID) {

	// 		pop.JSRuntimeManager.addPageSectionId(domain, pssId, moduleName, psId, 'window-fullsize');
	// 		pop.JSRuntimeManager.addPageSectionId(domain, pssId, moduleName, psId, 'window-maximize');
	// 		pop.JSRuntimeManager.addPageSectionId(domain, pssId, moduleName, psId, 'window-minimize');
	// 	}	
	// },

	pageSectionInitialized : function(args) {
	
		var that = this;
		var pageSection = args.pageSection;
		var psId = pageSection.attr('id');

		// For the pageTabs: whenever the Hover pageSection is closed, activate the active pageTab again,
		// so then the browser will update the URL
		if (psId == pop.c.PS_PAGETABS_ID) {

			pop.Manager.getPageSectionGroup(pageSection).on('on.bs.pagesection-group:pagesection-'+pop.c.PS_HOVER_ID+':closed', function() {

				pageSection.find('a.pop-pagetab-btn.active').trigger('click');
			});
		}
	},

	getEmbedUrl : function(args) {
	
		var that = this;
		var url = args.url;
		
		return that.addURLParams(add_query_arg(pop.c.URLPARAM_THEMEMODE, pop.c.THEMEMODE_WASSUP_EMBED, url));
	},

	getUnembedUrl : function(args) {

		var that = this;
		var url = args.url;
		
		return removeQueryFields(url, [pop.c.URLPARAM_THEMEMODE]);
	},

	getPrintUrl : function(args) {
	
		var that = this;
		var url = args.url;
		
		// Also add param print automatically when opening the page
		return that.addURLParams(add_query_arg(pop.c.URLPARAM_ACTIONS+'[]', pop.c.URLPARAM_ACTION_PRINT, add_query_arg(pop.c.URLPARAM_THEMEMODE, pop.c.THEMEMODE_WASSUP_PRINT, url)));
	},

	scrollToElem : function(args) {

		var that = this;
		var elem = args.elem, position = args.position, animate = args.animate;

		var pageSection = pop.Manager.getPageSection(position);
		var top = position.offset().top - pageSection.offset().top;
		
		return that.execScrollTop(elem, top, animate);
	},

	scrollTop : function(args) {

		var that = this;
		var elem = args.elem, top = args.top, animate = args.animate;

		return that.execScrollTop(elem, top, animate);
	},

	getPosition : function(args) {

		var that = this;
		var elem = args.elem;

		// If the element is the mainPageSection, and not of the perfectScrollbar type (defined in function PoPTheme_Wassup_Utils::add_mainpagesection_scrollbar())...
		if (elem.attr('id') == pop.c.PS_MAIN_ID && !elem.hasClass('perfect-scrollbar')) {

			// Return the height of the body
			// Taken from https://stackoverflow.com/questions/19618545/body-scrolltop-vs-documentelement-scrolltop-vs-window-pagyoffset-vs-window-scrol
			return window.pageYOffset || document.documentElement.scrollTop || body.scrollTop || 0;
		}

		return 0;
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	execScrollTop : function(elem, top, animate) {

		var that = this;

		// If the element is the mainPageSection, and not of the perfectScrollbar type (defined in function PoPTheme_Wassup_Utils::add_mainpagesection_scrollbar())...
		if (elem.attr('id') == pop.c.PS_MAIN_ID && !elem.hasClass('perfect-scrollbar')) {

			if (animate) {
				// We need both 'html' and 'body' because body is used by webkit, html is used by firefox
				$('html, body').animate({ scrollTop: top }, 'fast');
			}
			else {
				window.scrollTo(0, top);
			}
			return true;
		}

		return false;
	},

	addURLParams : function(url) {
	
		var that = this;

		if (pop.c.THEMESTYLE) {

			url = add_query_arg(pop.c.URLPARAM_THEMESTYLE, pop.c.THEMESTYLE, url);
		}

		return url;
	},
	
	handleWindow : function(pageSection, exitSize) {

		var that = this;

		var windoww = pageSection.closest('.window');
		if (pop.Window.getSize(windoww) == exitSize) {

			pop.Window.maximize(windoww);
		}
	},	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// pop.JSLibraryManager.register(pop.CustomFunctions, ['addPageSectionIds'], true);
pop.JSLibraryManager.register(pop.CustomFunctions, ['pageSectionInitialized', 'getEmbedUrl', 'getUnembedUrl', 'getPrintUrl', 'getPosition', 'scrollToElem', 'scrollTop']);
