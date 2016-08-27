(function($){
popPerfectScrollbar = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	// documentInitialized : function() {
	
	// 	var t = this;

	// 	t.scrollbars();
	// },

	scrollbarHorizontal : function(args) {
	
		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		jQuery(document).ready( function($) {	

			// Comment Leo 10/04/2015: adding the filter '.horizontal' so that it can be disabled for the print theme
			// (it will just not add the corresponding 'horizontal' class to the pageSection)
			// targets.filter('.horizontal').perfectScrollbar({suppressScrollY: true});	
			targets.perfectScrollbar({suppressScrollY: true});	
		});
	},
	scrollbarVertical : function(args) {
	
		var t = this;
		var pageSection = args.pageSection, targets = args.targets;
		jQuery(document).ready( function($) {	

			// Comment Leo 10/04/2015: adding the filter '.vertical' so that it can be disabled for the print theme
			// (it will just not add the corresponding 'vertical' class to the pageSection)
			// targets.filter('.vertical').perfectScrollbar({suppressScrollX: true});
			targets.perfectScrollbar({suppressScrollX: true});
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	// scrollbars : function() {

	// 	var t = this;
			
	// 	$('.perfect-scrollbar.vertical').perfectScrollbar({suppressScrollX: true});
	// 	$('.perfect-scrollbar.horizontal').perfectScrollbar({suppressScrollY: true});

	// 	// If containing an offcanvas-collapse, update the scrollbar whenever this opens
	// 	// $('.perfect-scrollbar .offcanvas-collapse.collapse').on('shown.bs.collapse', function () {

	// 	// 	var collapse = $(this);

	// 	// 	// Make sure it is not a bubble (ie: it is a contained collapse the one opening, and not this one collapse)
	// 	// 	// (eg: info-layout inside the offcanvas panels)
	// 	// 	if (popCustomBootstrap.bubbling(collapse, '.collapse')) {

	// 	// 		return;
	// 	// 	}

	// 	// 	t.scrollTop(collapse);
	// 	// });
	// },

	doScroll : function(scrollbar, x, animate) {

		var t = this;
		
		if (animate) {
			scrollbar.animate({scrollTop: x});
		}
		else {
			scrollbar.scrollTop(x);
		}
		scrollbar.perfectScrollbar('update');
	},

	scrollToElem : function(elem, position, animate) {

		var t = this;
		var scrollbar = elem.closest('.perfect-scrollbar').addBack('.perfect-scrollbar');
		if (scrollbar.length) {
			
			var x = 0;
			if (position) {
				
				var offsetReference = scrollbar.children('.perfect-scrollbar-offsetreference');
				if (!offsetReference.length) {
					offsetReference = elem;
				}
				x = position.offset().top - offsetReference.offset().top;
			}

			// Scroll up
			t.doScroll(scrollbar, x, animate);
			return true;
		}

		return false;
	},

	getPosition : function(elem) {

		var t = this;
		var scrollbar = elem.closest('.perfect-scrollbar').addBack('.perfect-scrollbar');
		if (scrollbar.length) {
			
			var offsetReference = scrollbar.children('.perfect-scrollbar-offsetreference');
			if (offsetReference.length) {
			
				// Multiply by -1 to make it positive
				return offsetReference.position().top * -1;
			}
		}

		return 0;
	},

	scrollTop : function(elem, top, animate) {

		var t = this;
		top = top || 0;

		var scrollbar = elem.closest('.perfect-scrollbar').addBack('.perfect-scrollbar');
		if (scrollbar.length) {
			
			t.doScroll(scrollbar, top, animate);
			return true;
		}

		return false;
	},

	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popPerfectScrollbar, [/*'documentInitialized'*/ 'scrollbarHorizontal', 'scrollbarVertical']);