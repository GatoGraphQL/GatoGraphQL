"use strict";
(function($){
window.pop.CustomBootstrap = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	initDocument : function() {
	
		var that = this;
		
		// Solution to problem: links can't be edited inside wpEditor inside Bootstrap modal
		// Solution taken from: 
		// - http://jsfiddle.net/e99xf/13/
		// - http://stackoverflow.com/questions/18111582/tinymce-4-links-plugin-modal-in-not-editable
		$(document).on('focusin', function(e) {
			if ($(e.target).closest(".mce-window").length) {
				e.stopImmediatePropagation();
			}
		});
	},
	// runScriptsBefore : function(args) {
	
	// 	var that = this;
	// 	var pageSection = args.pageSection, newDOMs = args.newDOMs;

	// 	/** Collapse */
	// 	newDOMs.find('.collapse').addBack('.collapse')
	// 		.on('show.bs.collapse', function() {

	// 			that.addBubblingFlag($(this), '.collapse', 'show');
	// 		})
	// 		.on('shown.bs.collapse', function() {

	// 			that.addBubblingFlag($(this), '.collapse', 'shown');
	// 		})
	// 		.on('hide.bs.collapse', function() {

	// 			that.addBubblingFlag($(this), '.collapse', 'hide');
	// 		})
	// 		.on('hidden.bs.collapse', function() {

	// 			that.addBubblingFlag($(this), '.collapse', 'hidden');
	// 		});
	// },
	pageSectionNewDOMsInitialized : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, newDOMs = args.newDOMs;
		
		// Function 'activateNestedComponents' must be executed before anything else: show the proper bootstrap components, open one inside of each other,
		// before executing the javascript
		// This is done so that we can capture the event as 'shown' (instead of 'show') and make sure that the js executes alright
		// (eg of problem with 'show': Clicking on My Announcements will first load My Content since that tabPanel is active at the beginning,
		// so we gotta make My Announcements and its parent tab My Content properly active before triggering the js initialization)
		that.activateNestedComponents(pageSection, newDOMs);

		that.showComponentInitializeJS(domain, pageSection, newDOMs);
		that.triggerTabPaneEvents(newDOMs);
	},

	onBootstrapEventWindowResize : function(args) {

		var that = this;
		var targets = args.targets;

		// Whenever any of this events are trigger, resize the window to refresh Waypoints
		// For the collapse also calculate when closing, because it may be that no other collapse panel is opening
		// For tabPane and carousel, since another one is opening, the resize shall be triggered then
		targets.find('.collapse').addBack('.collapse').on('shown.bs.collapse hidden.bs.collapse', windowResize);
		targets.find('.tab-pane').addBack('.tab-pane').on('shown.bs.tabpane', windowResize);
		targets.find('.carousel').addBack('.carousel').on('slid.bs.carousel', windowResize);
	},
		
	isHidden : function(args) {
	
		var that = this;
		var targets = args.targets;

		// Collapse, Modal, Tab Pane
		if (targets.parents('.collapse,.modal').not('.in').length) {

			return true;
		}
		if (targets.parents('.tab-pane').not('.active').length) {

			return true;
		}

		return false;		
	},

	isActive : function(args) {
	
		var that = this;
		var targets = args.targets;

		if (targets.hasClass('tab-pane') && !targets.hasClass('active')) {

			return false;
		}

		return true;		
	},

	activeTabLink : function(args) {
	
		var that = this;
		var targets = args.targets;

		targets.click(function(e) {

			var link = $(this);
			link.parent('li').addClass('active').siblings('li').removeClass('active');
		})	
	},

	activatePageTab : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.click(function() {

			var interceptor = $(this);
			var pageTabBtn = $(interceptor.data('target'));

			that.clickPageTab(pageSection, pageTabBtn);
		});	

		targets.each(function() {

			// Already trigger it so as to set the current pageTab as active
			var target = $(this);

			// But only if the tab has not been marked as inactive
			if (target.closest('.pop-pagesection-page').find('.pop-pagetab-btn').not('.pop-inactive').length) {
				
				target.trigger('click');
			}
		});
	},

	customQuickView : function(args) {

		var that = this;
		var pageSection = args.pageSection;
		var modal = pageSection.closest('.modal');

		// Open the Modal when fetching quickView
		pageSection.on('fetched', function() {

			if (!pop.Bootstrap.showingModal(modal)) {
				pop.Bootstrap.closeModals();
				modal.modal('show');
			}
		});

		// Close the Modal if destroying the pageSection and no other visible one around
		pop.Manager.getPageSectionGroup(pageSection).on('on.bs.pagesection-group:pagesection-'+pageSection.attr('id')+':closed', function() {

			modal.modal('hide');
		});
	},

	destroyPageOnModalClose : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		// When closing the modal, trigger a destroy of the interceptor. This is needed for the quickView => preview,
		// so that it doesn't keep the posts cached
		targets.each(function() {

			var link = $(this);
			link.closest('.modal').on('hidden.bs.modal', function() {

				link.trigger('click');
			});			
		});
	},

	customCloseModals : function(args) {

		var that = this;
		var pageSection = args.pageSection;

		pageSection.on('destroy', function() {

			pop.Bootstrap.closeModals();
		});
	},
	
	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	showComponentInitializeJS : function(domain, pageSection, newDOMs) {

		var that = this;

		var collapses = newDOMs.find('.collapse.pop-bscomponent').addBack('.collapse.pop-bscomponent');
		if (collapses.length) {
			that.initComponentHandlers(domain, pageSection, /*pageSectionPage, */collapses, 'shown.bs.collapse');
		}

		var modals = newDOMs.find('.modal.pop-bscomponent').addBack('.modal.pop-bscomponent');
		if (modals.length) {
			that.initComponentHandlers(domain, pageSection, /*pageSectionPage, */modals, 'shown.bs.modal');
		}

		var tabPanes = newDOMs.find('.tab-pane.pop-bscomponent').addBack('.tab-pane.pop-bscomponent');
		if (tabPanes.length) {
			that.initComponentHandlers(domain, pageSection, /*pageSectionPage, */tabPanes, 'shown.bs.tabpane');
		}

		var carousels = newDOMs.find('.carousel.pop-bscomponent').addBack('.carousel.pop-bscomponent');
		if (carousels.length) {
			that.initComponentHandlers(domain, pageSection, /*pageSectionPage, */carousels, 'slid.bs.carousel', true);
		}
	},
	
	initComponentHandlers : function(domain, pageSection, targets, handler, useRelatedTarget) {
	
		var that = this;

		// Comment Leo: IMPORTANT: do NOT alter the order of these pieces of code below:
		// 1. When opening a component, set the data (eg: post-data) in all the contained blocks
		// 2. Initialize JS
		// 3. Trigger 'visible' <= By then, it can use the proper data (eg: when editing project, it will first set the post_id on the blockData)

		// Make the contained blocks trigger the 'visible' handler, which will be caught by other functions (eg: reload)
		targets.on(handler, function (e) {

			// useRelatedTarget is needed to when the event executes on the javascript component and not on the active item.
			// Eg: Carousel's events (slid.bs.carousel) executes on the .carousel item, not on the .item.active div
			// So by specifing useRelatedTarget, we use .item.active, where we have stored the data-initjs-targets attribute
			var component = useRelatedTarget ? $(e.relatedTarget) : $(this);
			var blocks = $(component.data('initjs-targets'));

			var block_data = {
				'post-data': component.data('post-data'),
			}

			that.recursiveSetBlockData(domain, pageSection, blocks, block_data);
		});

		// Initialize JS: whenever the bootstrap javascrit component opens (tab, modal, collapse)
		// Comment Leo 04/06/2016: initially this was targets.one, expecting the event to be executed only the first time it's invoked
		// This works ok with the tabpane, since the event is executed on the tabpane, but it doesn't work with the carousel,
		// where event slid.bs.carousel is executed on the carousel. So because of that, it was changed to targets.on,
		// and this logic executes each time a carousel is slid, so initializing all different .item divs
		targets.on(handler, function (e) {

			var component = useRelatedTarget ? $(e.relatedTarget) : $(this);
			var blocks = $(component.data('initjs-targets')).not('.'+pop.c.JS_INITIALIZED);

			if (!blocks.length) {
				return;
			}

			// Force the initialization: remove lazy js
			var options = {
				'force-init': true
			};
			
			// 1. If the relatedTarget has intercept-url, then pass it along initBlock to know what URL this tabPane must handle
			// 2. Otherwise, if we are initializing a lazy block, using initjs_blockbranches, then the paramsscope-url must point to that original URL, and not to the URL in the topLevelFeedback at the moment the initialization takes place.
			// Otherwise, when initializing GetPoP decentralized calendar, it takes the url to be /loggedinuser-data/, which was the last url accessed before initializing the module
			// Simply get the paramsscope-url from data attribute 'original-url'
			var url = component.data('intercept-url') || component.data('original-url');
			if (url) {
				options.url = url;
			}

			pop.Manager.initBlockBranches(domain, pageSection, blocks, null, options);
		});

		// Make the contained blocks trigger the 'visible' handler, which will be caught by other functions (eg: reload)
		targets.on(handler, function (e) {

			var component = useRelatedTarget ? $(e.relatedTarget) : $(this);
			var blocks = $(component.data('initjs-targets'));

			if (component.data('onetime-refetch')) {

				that.recursiveOneTimeRefetch(domain, pageSection, blocks);
			}

			that.triggerBlocksVisible(domain, pageSection, blocks);
		});
	},

	recursiveSetBlockData : function(domain, pageSection, blocks, block_data) {
	
		var that = this;

		blocks.each(function() {

			var block = $(this);
			
			// Set the keys onto the block as extra data:
			// post_data: needed for Edit Project with the post_id to edit
			// onetime-refetch: needed for navigator
			$.each(block_data, function(key, value) {

				block.data(key, value);
			});
			
			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block);
			var blockBranches = jsSettings['initjs-blockbranches'];
			if (blockBranches) {

				that.recursiveSetBlockData(domain, pageSection, $(blockBranches.join(', ')), block_data);
			}
		});
	},

	recursiveOneTimeRefetch : function(domain, pageSection, blocks) {
	
		var that = this;

		blocks.each(function() {

			var block = $(this);
			pop.BlockDataQuery.makeOneTimeRefetch(pageSection, block);
			
			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block);
			var blockBranches = jsSettings['initjs-blockbranches'];
			if (blockBranches) {

				that.recursiveOneTimeRefetch(domain, pageSection, $(blockBranches.join(', ')));
			}
		});
	},

	triggerBlocksVisible : function(domain, pageSection, blocks) {
	
		var that = this;

		blocks.each(function() {

			var block = $(this);
			block.triggerHandler('visible');

			// Do the same for all contained 'visible' sub-blocks
			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block);
			var blockBranches = jsSettings['initjs-blockbranches'];
			if (blockBranches) {

				that.triggerBlocksVisible(domain, pageSection, $(blockBranches.join(', ')));
			}
		});
	},

	activateNestedComponents : function(pageSection, newDOMs) {
	
		var that = this;

		// When opening a tabPane, if it is inside another tab pane, open it
		// This way, when clicking on Load in Navigator, it will show (initially Navigator tab is hidden)
		newDOMs.find('div.tab-pane div.tab-pane').addBack('div.tab-pane div.tab-pane').on('shown.bs.tabpane', function () {
			that.activateOuterTabPanes($(this));
		});
		// collapse inside tabPane
		newDOMs.find('div.tab-pane div.collapse').addBack('div.tab-pane div.collapse').on('shown.bs.collapse', function () {
			that.activateOuterTabPanes($(this));
		});
		// collapse inside carousel
		newDOMs.find('div.carousel div.collapse').addBack('div.carousel div.collapse').on('shown.bs.collapse', function () {
			that.activateOuterCarousel($(this));
		});
		// tabPane inside of Carousel
		newDOMs.find('div.carousel div.tab-pane').addBack('div.carousel div.tab-pane').on('shown.bs.tabpane', function () {
			that.activateOuterCarousel($(this));
		});
	},
	activateOuterTabPanes : function(component) {

		var that = this;

		var outerTabPanes = component.parents('.tab-pane').not('.active');
		if (outerTabPanes.length) {

			// Gotta find the outer tabPane's tab
			$('a[href="#'+outerTabPanes.attr('id')+'"]').tab('show');
		}
	},
	activateOuterCarousel : function(component) {

		var that = this;

		var outerCarousel = component.closest('.carousel');
		pop.BootstrapCarousel.showElement(outerCarousel, component);
	},
	

	triggerTabPaneEvents : function(elem) {
	
		// This function is fired in initPageSection and not in initElem because if the component in the pageSection is lazy, then this is never executed
		// and so the event in the tab-pane will never be triggered for which the component will never be initialized
		var that = this;

		// Whenever firing 'shown.bs.tab' on the tab, also fire 'shown.bs.tabpane' on the tabpane
		// e.currentTarget: newly activated tabpane
		elem.find('a[data-toggle="tab"]').addBack('a[data-toggle="tab"]')
			.on('show.bs.tab', function (e) {				
				that.triggerTabPaneEvent($(this), e, 'show.bs.tabpane');
			})
			.on('shown.bs.tab', function (e) {		
				that.triggerTabPaneEvent($(this), e, 'shown.bs.tabpane');
			})
			.on('hide.bs.tab', function (e) {		
				that.triggerTabPaneEvent($(this), e, 'hide.bs.tabpane');
			})
			.on('hidden.bs.tab', function (e) {		
				that.triggerTabPaneEvent($(this), e, 'hidden.bs.tabpane');
			});
	},

	triggerTabPaneEvent : function(link, e, event) {
	
		// This function is fired in initPageSection and not in initElem because if the component in the pageSection is lazy, then this is never executed
		// and so the event in the tab-pane will never be triggered for which the component will never be initialized
		var that = this;
		var tabPane = $($(e.target).attr('href'));

		// Passed from the interceptor, needed to know if to refetch or skip it
		tabPane.data('post-data', link.data('post-data'));
		tabPane.data('onetime-refetch', link.data('onetime-refetch'));
		tabPane.data('intercept-url', link.data('intercept-url'));
		tabPane.data('original-url', link.data('original-url'));

		tabPane.triggerHandler(event, [e]);
	},

	// resetBubbling : function() {

	// 	// Delete all previous bubbling records (otherwise, once found a bubbling, it will always be considered one, and clicking on a link once again is the best way to reset everything)
	// 	$('[data-bubbling]').attr('data-bubbling', null);
	// 	$('[data-bubbling-origin]').attr('data-bubbling-origin', null);
	// },

	// // bubbling : function(elem, selector) {

	// // 	var that = this;

	// // 	if (elem.is(selector)) {

	// // 		var bubblingId = elem.attr('data-bubbling');
	// // 		if (bubblingId && elem.find('[data-bubbling-origin="'+bubblingId+'"]').length) {

	// // 			return true;
	// // 		}
	// // 	}

	// // 	return false;
	// // },

	// addBubblingFlag : function(elem, parentSelector, type) {

	// 	var that = this;
		
	// 	if (elem.parents(parentSelector).length) {
			
	// 		// Generate a randomId and assign it to all its parents under attr 'data-bubbling'
	// 		// Then the parents can then know that the events trigger on them are bubbles
	// 		var ran = getRandomInt(1, 10000000000);
	// 		var bubblingId = elem.attr('id')+'-'+type+'-'+ran;
	// 		elem.attr('data-bubbling-origin', bubblingId);
	// 		elem.parents(parentSelector).attr('data-bubbling', bubblingId);			
	// 	}
	// },

	activatePageTabs : function(pageSection, newDOMs, inactivePane) {

		var that = this;

		// Mark tabs as inactive
		if (inactivePane) {

			newDOMs.filter('.pop-pagesection-page').find('.pop-pagetab-btn').addClass('pop-inactive');
		}
	},

	// The functions below are invoked in pop.CustomFunctions
	activateSideTabpanes : function(pageSection, newDOMs, inactivePane) {

		var that = this;

		var tabPane = newDOMs.filter('.tab-pane');
		if (tabPane.length) {
			
			var firstLoad = pop.Manager.isFirstLoad(pageSection);
			
			// activate the new tabpane if not marked as inactive
			if (!inactivePane) {

				if (!firstLoad) {
					newDOMs.siblings('.tab-pane').removeClass('active');
				}

				tabPane.addClass('active');
			}

			// Functions to trigger when the top level of the tabPanes is open (not for, say, the Content/Members in a Profile page)
			tabPane.on('shown.bs.tabpane', function() {
				
				// Allow the pageSection to remain closed. eg: for the pageTabs in embed 
				var openmode = pop.PageSectionManager.getOpenMode(pageSection);
				if (openmode == 'automatic') {
					pop.PageSectionManager.open(pageSection);
					pop.Manager.scrollTop(pageSection);
				}
			});		

			if (!inactivePane && !firstLoad) {
				pop.Manager.scrollTop(pageSection);
			}
		}
	},
	activateQuickviewTabpanes : function(pageSection, newDOMs, inactivePane) {

		var that = this;

		var tabPane = newDOMs.filter('.tab-pane');
		if (tabPane.length) {
		
			// activate the new tabpane
			tabPane.addClass('active').siblings('.tab-pane').removeClass('active');
		}
	},
	activateModals : function(pageSection, newDOMs) {

		var that = this;

		var modal = newDOMs.filter('.modal');
		if (modal.length) {
		
			pop.Bootstrap.closeModals();
			modal.modal('show');

			modal.on('show.bs.modal', function(e) {

				// Close feedbackmessages from previous interactions in the opening modal
				pop.Manager.closeFeedbackMessage(modal);
			});

			// Refresh. This is needed for whenever the modal contains a google map to refresh it (Locations Map, Create Location)
			modal.on('shown.bs.modal', function(e) {

				windowResize();
			});
		}
	},
	activateTabPanes : function(pageSection, newDOMs, inactivePane, removeTheather, sides) {

		var that = this;

		// Only if tabPanes were actually drawn (eg: calling Background Load pages will bring only replicable tabPanes, no actual tabPane yet)
		var tabPane = newDOMs.filter('.tab-pane');
		if (tabPane.length) {
		
			var firstLoad = pop.Manager.isFirstLoad(pageSection);

			// save position and hide the previous tabPane
			if (!inactivePane) {

				if (!firstLoad) {
					var previousTabPane = newDOMs.siblings('.tab-pane.active');
					that.savePosition(pageSection, previousTabPane);
					previousTabPane.removeClass('active');
				}

				tabPane.addClass('active');
			}

			// activate the new tabpane
			// Functions to trigger when the top level of the tabPanes is open (not for, say, the Content/Members in a Profile page)
			tabPane
				.on('show.bs.tabpane', function() {

					// Using 'show.bs.tabpane' instead of 'hide.bs.tabpane' on the tabPane being hidden, because somehow that doesn't work! (related to how we intercept the links, Bootstrap gets confused and doesn't send the relatedTarget in the event to hide.bs.tab)
					
					// Save the position of the tabPane being hidden for when clicking back into this tab scroll directly to that position
					var tabPane = $(this);
					var previousTabPane = tabPane.siblings('.active');

					// Comment Leo 15/02/2017: if it has no active tabPanes, then the clicked on tabPane is the one currently active
					// So then save its current position, so that below, when doing newPageSectionJS, it scrolls to where it already is (otherwise, it may go to the top)
					if (previousTabPane.length) {
						that.savePosition(pageSection, previousTabPane);
					}
				})
				.on('shown.bs.tabpane', function() {
				
					var tabPane = $(this);
					pop.PageSectionManager.open(pageSection);					
					that.newPageSectionJS(pageSection, removeTheather, sides, tabPane);
				});

			if (!inactivePane && !firstLoad) {

				that.newPageSectionJS(pageSection, removeTheather, sides);
			}
		}
	},

	clickPageTab : function(pageSection, pageTabBtn) {

		var that = this;
		
		// activate (ie add class 'active') to the new pageTab, remove to all others
		pageSection.find('.pop-pagetab-btn').removeClass('active');
		pageTabBtn.addClass('active');
	},

	getPosition : function(elem) {

		var that = this;

		// Allow to have custom-functions.js provide the implementation of this function for the main pageSection, and perfectScrollbar also
		var executed = pop.JSLibraryManager.execute('getPosition', {elem: elem});
		var ret = 0;
		$.each(executed, function(index, value) {
			if (value) {
				ret = value;
				return -1;
			}
		});
		
		return ret;
	},

	savePosition : function(pageSection, tabPane) {

		var that = this;
		tabPane.data('lastposition', that.getPosition(pageSection));
		tabPane.data('theater', pop.PageSectionManager.isTheater());
	},

	newPageSectionJS : function(pageSection, removeTheather, sides, tabPane) {

		var that = this;

		// Close the the side pageSections for mobile phone
		sides.each(function() {

			var sidePageSection = $(this);
			pop.PageSectionManager.close(sidePageSection, 'xs');
		})

		// Needed for when clicking somewhere from inside the quickview modal: open page in main, close modal
		pop.Bootstrap.closeModals();

		// Scroll Top / Last position
		var theater;
		if (tabPane) {
			
			// If the tabPane had saved a position...
			// If lastposition = 0 it must work also, so comparing to 'undefined'
			if (typeof tabPane.data('lastposition') != 'undefined') {

				// Scroll to it
				pop.Manager.scrollTop(pageSection, tabPane.data('lastposition'));

				// Delete the position, since now the tabPane is active. It will be regenerated when switching out to another tabPane
				// Otherwise, if opening the hover pageSection and close, and this tabPane was always active, it will once again scroll it to that previous position
				tabPane.data('lastposition', null);
			}
			theater = tabPane.data('theater') || false;
			tabPane.data('theater', null);
		}
		else {
			// New pageSection, so scroll to the top
			pop.Manager.scrollTop(pageSection, 0);
		}

		// Remove theater mode?
		if (removeTheather && !theater) {
			pop.PageSectionManager.theater(false);
		}

		// Add the theater mode? (If when switching out from the pagetab it was theater, then restore it)
		if (theater) {
			pop.PageSectionManager.theater(true);
		}
		
		// Trigger resize: it will recalculate Waypoints
		windowResize();
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.CustomBootstrap, ['initDocument', /*'runScriptsBefore', */'pageSectionNewDOMsInitialized', 'activeTabLink', 'isHidden', 'isActive'], true);
pop.JSLibraryManager.register(pop.CustomBootstrap, ['customQuickView', 'destroyPageOnModalClose', 'customCloseModals', 'activatePageTab', 'onBootstrapEventWindowResize']);
