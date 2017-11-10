(function($){
popCustomFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	addPageSectionIds : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, template = args.template;
		var pssId = popManager.getSettingsId(pageSection);
		var psId = pageSection.attr('id');

		if (psId == M.PS_HOVER_ID) {

			popJSRuntimeManager.addPageSectionId(domain, pssId, template, psId, 'closehover');
		}	
		else if (psId == M.PS_FRAME_NAVIGATOR_ID) {

			popJSRuntimeManager.addPageSectionId(domain, pssId, template, psId, 'closenavigator');
		}	
		else if (psId == M.PS_ADDONS_ID) {

			popJSRuntimeManager.addPageSectionId(domain, pssId, template, psId, 'window-fullsize');
			popJSRuntimeManager.addPageSectionId(domain, pssId, template, psId, 'window-maximize');
			popJSRuntimeManager.addPageSectionId(domain, pssId, template, psId, 'window-minimize');
		}	
	},

	pageSectionInitialized : function(args) {
	
		var t = this;
		var pageSection = args.pageSection;
		var psId = pageSection.attr('id');

		// For the pageTabs: whenever the Hover pageSection is closed, activate the active pageTab again,
		// so then the browser will update the URL
		if (psId == M.PS_PAGETABS_ID) {

			popManager.getPageSectionGroup(pageSection).on('on.bs.pagesection-group:pagesection-'+M.PS_HOVER_ID+':closed', function() {

				pageSection.find('a.pop-pagetab-btn.active').trigger('click');
			});
		}
	},
	
	pageSectionNewDOMsBeforeInitialize : function(args) {

		var t = this;
		var pageSection = args.pageSection, newDOMs = args.newDOMs, inactivePane = args.inactivePane;
		var psId = pageSection.attr('id');

		// If there are no other tabs open, then the inactive is invalidated, make it active anyway (or nothing will open)
		inactivePane = (inactivePane === true) && (newDOMs.siblings('.pop-pagesection-page').length > 0);

		// Comment Leo 10/04/2014: firstLoad var will prevent from executing .scrollTop initially, when the perfectScrollbar
		// is still not initialized (pageSectionNewDOMsBeforeInitialize executes before documentInitialized - check perfectscrollbar.js)
		var firstLoad = popManager.isFirstLoad(pageSection);
		var tabPane = newDOMs.filter('.tab-pane');

		var pageTabs = [M.PS_PAGETABS_ID, M.PS_ADDONTABS_ID];
		var mainTabPanes = [M.PS_MAIN_ID, M.PS_ADDONS_ID];
		if (mainTabPanes.indexOf(psId) > -1) {

			// Make the new tabPane visible only if it hasn't been marked inactive
			if (!inactivePane) {
				tabPane.addClass('active');
			}
			else {
				// If inactive, then open the pageTabs, so the user can see the new tab
				if (psId == M.PS_MAIN_ID) {
					var mainPageTabs = $('#'+M.PS_PAGETABS_ID);
					popPageSectionManager.open(mainPageTabs);
					popPageSectionManager.open(mainPageTabs, 'xs');
				}
			}

			// For the Addons: each time a tab is open, if the window is minimized then maximize it
			if (psId == M.PS_ADDONS_ID) {
				
				// Functions to trigger when the top level of the tabPanes is open (not for, say, the Content/Members in a Profile page)
				tabPane.on('shown.bs.tabpane', function() {
					
					t.handleWindow(pageSection, 'minimized');
				});
				if (!inactivePane && !firstLoad) {
					t.handleWindow(pageSection, 'minimized');
				}
			}
			// If not, check if the addon window is fullsize, if so maximize it
			else {

				var addonsPageSection = $('#'+M.PS_ADDONS_ID);
				tabPane.on('shown.bs.tabpane', function() {
					
					t.handleWindow(addonsPageSection, 'fullsize');
				});
				if (!inactivePane && !firstLoad) {
					t.handleWindow(addonsPageSection, 'fullsize');
				}
			}
		}
		else if (pageTabs.indexOf(psId) > -1) {

			if (inactivePane) {
				
				// While opening the pageTabs pageSection, also highlight the tab
				newDOMs.filter('.pop-pagesection-page').find('.pop-pagetab-btn').addClass('pop-highlight');
			}
		}

		// Only do it for the pageSections that have tab-content in its merge target container
		var tabPanes = [M.PS_MAIN_ID, M.PS_HOVER_ID, M.PS_ADDONS_ID, M.PS_FRAME_NAVIGATOR_ID];
		var quickviews = [M.PS_QUICKVIEW_ID, M.PS_QUICKVIEWINFO_ID];
		var sides = [M.PS_SIDEINFO_ID];
		var modals = [M.PS_MODALS_ID];
		if (tabPanes.indexOf(psId) > -1) {

			// For the Main pageSection remove the theater mode, for the others no need
			var removeTheather = (psId == M.PS_MAIN_ID);
			var sides = $('#'+M.PS_SIDEINFO_ID+',#'+M.PS_FRAME_SIDE_ID+',#'+M.PS_FRAME_NAVIGATOR_ID);
			popCustomBootstrap.activateTabPanes(pageSection, newDOMs, inactivePane, removeTheather, sides);
		}
		else if (pageTabs.indexOf(psId) > -1) {

			// Only do it for the pageSections that have tab-content in its merge target container
			// activate the new tabpane
			popCustomBootstrap.activatePageTabs(pageSection, newDOMs, inactivePane);
		}
		else if (sides.indexOf(psId) > -1) {

			popCustomBootstrap.activateSideTabpanes(pageSection, newDOMs, inactivePane);
		}
		else if (quickviews.indexOf(psId) > -1) {

			// Only do it for the pageSections that have tab-content in its merge target container
			// activate the new tabpane
			popCustomBootstrap.activateQuickviewTabpanes(pageSection, newDOMs, inactivePane);
		}
		else if (modals.indexOf(psId) > -1) {

			popCustomBootstrap.activateModals(pageSection, newDOMs/*, inactivePane*/);
		}
	},

	getEmbedUrl : function(args) {
	
		var t = this;
		var url = args.url;
		
		return t.addURLParams(add_query_arg(M.URLPARAM_THEMEMODE, M.THEMEMODE_WASSUP_EMBED, url));
	},

	getUnembedUrl : function(args) {

		var t = this;
		var url = args.url;
		
		return removeQueryFields(url, [M.URLPARAM_THEMEMODE]);
	},

	getPrintUrl : function(args) {
	
		var t = this;
		var url = args.url;
		
		// Also add param print automatically when opening the page
		return t.addURLParams(add_query_arg(M.URLPARAM_ACTION, M.URLPARAM_ACTION_PRINT, add_query_arg(M.URLPARAM_THEMEMODE, M.THEMEMODE_WASSUP_PRINT, url)));
	},

	scrollToElem : function(args) {

		var t = this;
		var elem = args.elem, position = args.position, animate = args.animate;

		var pageSection = popManager.getPageSection(position);
		var top = position.offset().top - pageSection.offset().top;
		
		return t.execScrollTop(elem, top, animate);
	},

	scrollTop : function(args) {

		var t = this;
		var elem = args.elem, top = args.top, animate = args.animate;

		return t.execScrollTop(elem, top, animate);
	},

	getPosition : function(args) {

		var t = this;
		var elem = args.elem;

		// If the element is the mainPageSection, and not of the perfectScrollbar type (defined in function PoPTheme_Wassup_Utils::add_mainpagesection_scrollbar())...
		if (elem.attr('id') == M.PS_MAIN_ID && !elem.hasClass('perfect-scrollbar')) {

			// Return the height of the body
			// Taken from https://stackoverflow.com/questions/19618545/body-scrolltop-vs-documentelement-scrolltop-vs-window-pagyoffset-vs-window-scrol
			return window.pageYOffset || document.documentElement.scrollTop || body.scrollTop || 0;
		}

		return 0;
	},

	closeMessageFeedback : function(args) {
		
		var t = this;
		var elem = args.elem;

		// Message is an alert, so close it
		$(document).ready( function($) {

			elem.find('.pop-messagefeedback').removeClass('fade').alert('close');
		});
	},

	closeMessageFeedbacks : function(args) {
		
		var t = this;
		var pageSection = args.pageSection;

		// Message is an alert, so close it
		$(document).ready( function($) {
			pageSection.find('.pop-messagefeedback').removeClass('fade').alert('close');
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	execScrollTop : function(elem, top, animate) {

		var t = this;

		// If the element is the mainPageSection, and not of the perfectScrollbar type (defined in function PoPTheme_Wassup_Utils::add_mainpagesection_scrollbar())...
		if (elem.attr('id') == M.PS_MAIN_ID && !elem.hasClass('perfect-scrollbar')) {

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
	
		var t = this;

		if (M.THEMESTYLE) {

			url = add_query_arg(M.URLPARAM_THEMESTYLE, M.THEMESTYLE, url);
		}

		return url;
	},
	
	handleWindow : function(pageSection, exitSize) {

		var t = this;

		var windoww = pageSection.closest('.window');
		if (popWindow.getSize(windoww) == exitSize) {

			popWindow.maximize(windoww);
		}
	},	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popCustomFunctions, ['addPageSectionIds', 'pageSectionNewDOMsBeforeInitialize'], true);
popJSLibraryManager.register(popCustomFunctions, [/*'documentInitialized', */'pageSectionInitialized', 'getEmbedUrl', 'getUnembedUrl', 'getPrintUrl', 'getPosition', 'scrollToElem', 'scrollTop', 'closeMessageFeedback', 'closeMessageFeedbacks']);
