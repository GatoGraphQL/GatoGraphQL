"use strict";
(function($){
window.pop.BootstrapCustomFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	pageSectionNewDOMsBeforeInitialize : function(args) {

		var that = this;
		var pageSection = args.pageSection, newDOMs = args.newDOMs, inactivePane = args.inactivePane;
		var psId = pageSection.attr('id');

		// If there are no other tabs open, then the inactive is invalidated, make it active anyway (or nothing will open)
		inactivePane = (inactivePane === true) && (newDOMs.siblings('.pop-pagesection-page').length > 0);

		// Comment Leo 10/04/2014: firstLoad var will prevent from executing .scrollTop initially, when the perfectScrollbar
		// is still not initialized (pageSectionNewDOMsBeforeInitialize executes before documentInitialized - check perfectscrollbar.js)
		var firstLoad = pop.Manager.isFirstLoad(pageSection);
		var tabPane = newDOMs.filter('.tab-pane');

		var pageTabs = [pop.c.PS_PAGETABS_ID, pop.c.PS_ADDONTABS_ID];
		var mainTabPanes = [pop.c.PS_MAIN_ID, pop.c.PS_ADDONS_ID];
		if (mainTabPanes.indexOf(psId) > -1) {

			// Make the new tabPane visible only if it hasn't been marked inactive
			if (!inactivePane) {
				tabPane.addClass('active');
			}
			else {
				// If inactive, then open the pageTabs, so the user can see the new tab
				if (psId == pop.c.PS_MAIN_ID) {
					var mainPageTabs = $('#'+pop.c.PS_PAGETABS_ID);
					pop.PageSectionManager.open(mainPageTabs);
					pop.PageSectionManager.open(mainPageTabs, 'xs');
				}
			}

			// For the Addons: each time a tab is open, if the window is minimized then maximize it
			if (psId == pop.c.PS_ADDONS_ID) {
				
				// Functions to trigger when the top level of the tabPanes is open (not for, say, the Content/Members in a Profile page)
				tabPane.on('shown.bs.tabpane', function() {
					
					pop.CustomFunctions.handleWindow(pageSection, 'minimized');
				});
				if (!inactivePane && !firstLoad) {
					pop.CustomFunctions.handleWindow(pageSection, 'minimized');
				}
			}
			// If not, check if the addon window is fullsize, if so maximize it
			else {

				var addonsPageSection = $('#'+pop.c.PS_ADDONS_ID);
				tabPane.on('shown.bs.tabpane', function() {
					
					pop.CustomFunctions.handleWindow(addonsPageSection, 'fullsize');
				});
				if (!inactivePane && !firstLoad) {
					pop.CustomFunctions.handleWindow(addonsPageSection, 'fullsize');
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
		var tabPanes = [pop.c.PS_MAIN_ID, pop.c.PS_HOVER_ID, pop.c.PS_ADDONS_ID, pop.c.PS_FRAME_NAVIGATOR_ID];
		var quickviews = [pop.c.PS_QUICKVIEW_ID, pop.c.PS_QUICKVIEWINFO_ID];
		var sides = [pop.c.PS_SIDEINFO_ID];
		var modals = [pop.c.PS_MODALS_ID];
		if (tabPanes.indexOf(psId) > -1) {

			// For the Main pageSection remove the theater mode, for the others no need
			var removeTheather = (psId == pop.c.PS_MAIN_ID);
			var sides = $('#'+pop.c.PS_SIDEINFO_ID+',#'+pop.c.PS_FRAME_SIDE_ID+',#'+pop.c.PS_FRAME_NAVIGATOR_ID);
			pop.CustomBootstrap.activateTabPanes(pageSection, newDOMs, inactivePane, removeTheather, sides);
		}
		else if (pageTabs.indexOf(psId) > -1) {

			// Only do it for the pageSections that have tab-content in its merge target container
			// activate the new tabpane
			pop.CustomBootstrap.activatePageTabs(pageSection, newDOMs, inactivePane);
		}
		else if (sides.indexOf(psId) > -1) {

			pop.CustomBootstrap.activateSideTabpanes(pageSection, newDOMs, inactivePane);
		}
		else if (quickviews.indexOf(psId) > -1) {

			// Only do it for the pageSections that have tab-content in its merge target container
			// activate the new tabpane
			pop.CustomBootstrap.activateQuickviewTabpanes(pageSection, newDOMs, inactivePane);
		}
		else if (modals.indexOf(psId) > -1) {

			pop.CustomBootstrap.activateModals(pageSection, newDOMs/*, inactivePane*/);
		}
	},

	closeFeedbackMessage : function(args) {
		
		var that = this;
		var elem = args.elem;

		// Message is an alert, so close it
		$(document).ready( function($) {

			elem.find('.pop-feedbackmessage').removeClass('fade').alert('close');
		});
	},

	closeFeedbackMessages : function(args) {
		
		var that = this;
		var pageSection = args.pageSection;

		// Message is an alert, so close it
		$(document).ready( function($) {
			pageSection.find('.pop-feedbackmessage').removeClass('fade').alert('close');
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BootstrapCustomFunctions, ['pageSectionNewDOMsBeforeInitialize'], true);
pop.JSLibraryManager.register(pop.BootstrapCustomFunctions, ['closeFeedbackMessage', 'closeFeedbackMessages']);
