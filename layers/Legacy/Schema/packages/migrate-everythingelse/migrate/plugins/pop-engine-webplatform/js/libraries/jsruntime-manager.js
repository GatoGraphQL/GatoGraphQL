"use strict";
(function($){
window.pop.JSRuntimeManager = {
	
	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------

	'full-session-ids': {},
	'last-session-ids': {},
	blockURL: null,
	pageSectionURL: null,

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	setPageSectionURL : function(url) {
		
		var that = this;
		that.pageSectionURL = url;
	},
	setBlockURL : function(domain, blockOrURL) {
		
		var that = this;
		var url = '';

		if ($.type(blockOrURL) == 'object') {
			
			var block = blockOrURL;
			url = pop.Manager.getBlockTopLevelURL(domain, block);
		}
		else {
			url = blockOrURL;
		}
		that.blockURL = url;

		// Also set the pageSection's URL
		that.setPageSectionURL(url);
	},

	initBlockVarPaths : function(vars, url, domain, pssId, targetId, componentName, group) {
	
		var that = this;
		group = group || pop.c.JSMETHOD_GROUP_MAIN;

		if (!vars[url]) {
			vars[url] = {};
		}
		if (!vars[url][domain]) {
			vars[url][domain] = {};
		}
		if (!vars[url][domain][pssId]) {
			vars[url][domain][pssId] = {};
		}
		if (!vars[url][domain][pssId][targetId]) {
			vars[url][domain][pssId][targetId] = {};
		}
		if (!vars[url][domain][pssId][targetId][componentName]) {
			vars[url][domain][pssId][targetId][componentName] = {};
		}
		if (!vars[url][domain][pssId][targetId][componentName][group]) {
			vars[url][domain][pssId][targetId][componentName][group] = [];
		}
	},
	initVars : function(url, domain, pssId, targetId, componentName, group) {
	
		var that = this;
		that.initBlockVarPaths(that['full-session-ids'], url, domain, pssId, targetId, componentName, group);
		that.initBlockVarPaths(that['last-session-ids'], url, domain, pssId, targetId, componentName, group);
	},

	addGroup : function(id, group) {
	
		var that = this;

		if (group) {
			id += pop.c.ID_SEPARATOR+group;
		}
		
		return id;
	},
	addPageSectionId : function(domain, pssId, componentName, id, group) {
	
		var that = this;
		return that.addModule(domain, pssId, pssId, componentName, id, group, true, true);
	},
	addModule : function(domain, pssId, targetId, componentName, id, group, fixed, isIdUnique, ignorePSRuntimeId) {
	
		var that = this;
		
		// If the ID is not unique, then we gotta make it unique, getting the POP_ENGINEWEBPLATFORM_CONSTANT_UNIQUE_ID from the topLevel feedback
		if (!isIdUnique) {
			id += pop.Manager.getUniqueId(domain);
		}

		// Add a counter id at the end so that no two ids will be the same (only needed for elements other than pageSection and blocks)
		if (!fixed) {
			id += pop.c.ID_SEPARATOR+counterNext();
		}

		// Add the group at the end, so that we can recreate the ID in the back-end calling function GD_TemplateManager_Utils::get_frontend_id
		id = that.addGroup(id, group);

		// Add under both pageSection and block, unless the targetId is the pssId, then no need for the block (eg: pagesection-tabpane.tmpl for the group="interceptor" link)
		// ignorePSRuntimeId: to not add the runtime ID for the pageSection when re-drawing data inside a block (the IDs will be generated again, but no need to add them to the pageSection side, since pageSection js methods will not be executed again)
		if (!ignorePSRuntimeId) {
			that.addTargetId(that.pageSectionURL, domain, pssId, pssId, componentName, group, id);
		}
		if (pssId != targetId) {
			that.addTargetId(that.blockURL, domain, pssId, targetId, componentName, group, id);
		}
		return id;
	},
	addTargetId : function(url, domain, pssId, targetId, componentName, group, id) {
	
		var that = this;
		group = group || pop.c.JSMETHOD_GROUP_MAIN;

		that.initVars(url, domain, pssId, targetId, componentName, group);
		that['full-session-ids'][url][domain][pssId][targetId][componentName][group].push(id);
		that['last-session-ids'][url][domain][pssId][targetId][componentName][group].push(id);

		return id;
	},
	getLastGeneratedId : function(domain, pssId, targetId, componentName, group) {
	
		var that = this;
		group = group || pop.c.JSMETHOD_GROUP_MAIN;

		// Is it a pageSection? or a block?
		var url = (pssId == targetId) ? that.pageSectionURL : that.blockURL;
		var ids = that['full-session-ids'][url][domain][pssId][targetId][componentName][group];
		return ids[ids.length-1];
	},

	getPageSectionSessionIds : function(domain, pageSection, session) {
	
		var that = this;
		var url = that.pageSectionURL;

		return that.getTargetSessionIds(url, domain, pageSection, pageSection, session);
	},
	getBlockSessionIds : function(domain, pageSection, block, session) {
	
		var that = this;

		// Get the URL from the toplevel-url, since the block may be initialized later on (eg: in a Carousel or TabPane)
		var url = pop.Manager.getBlockTopLevelURL(domain, block);

		return that.getTargetSessionIds(url, domain, pageSection, block, session);
	},
	getTargetSessionIds : function(url, domain, pageSection, target, session) {
	
		var that = this;
		var pssId = pop.Manager.getSettingsId(pageSection);
		var targetId = pop.Manager.getSettingsId(target);

		// session can be 'last' or 'full'. 'last' is the default since it's the more used one (for appending newDOMs, we need just the newly added ids)
		session = session || 'last';
		if (that[session+'-session-ids'][url] && that[session+'-session-ids'][url][domain] && that[session+'-session-ids'][url][domain][pssId]) {

			return that[session+'-session-ids'][url][domain][pssId][targetId];
		}
		
		return null;
	},
	deletePageSectionLastSessionIds : function(domain, pageSection) {

		var that = this;
		var pssId = pop.Manager.getSettingsId(pageSection);
		var url = that.pageSectionURL;
		
		delete that['last-session-ids'][url][domain][pssId][pssId];
		if ($.isEmptyObject(that['last-session-ids'][url][domain][pssId])) {

			delete that['last-session-ids'][url][domain][pssId];
			if ($.isEmptyObject(that['last-session-ids'][url][domain])) {

				delete that['last-session-ids'][url][domain];
				if ($.isEmptyObject(that['last-session-ids'][url])) {

					delete that['last-session-ids'][url];
				}
			}
		}
	},
	deleteBlockLastSessionIds : function(domain, pageSection, block) {

		var that = this;
		var pssId = pop.Manager.getSettingsId(pageSection);
		var targetId = pop.Manager.getSettingsId(block);
		
		// Get the URL from the toplevel-url, since the block may be initialized later on (eg: in a Carousel or TabPane)
		var url = pop.Manager.getBlockTopLevelURL(domain, block);
		if (that['last-session-ids'][url] && that['last-session-ids'][url][domain] && that['last-session-ids'][url][domain][pssId] && that['last-session-ids'][url][domain][pssId][targetId]) {
	
			delete that['last-session-ids'][url][domain][pssId][targetId];
			if ($.isEmptyObject(that['last-session-ids'][url][domain][pssId])) {
	
				delete that['last-session-ids'][url][domain][pssId];
				if ($.isEmptyObject(that['last-session-ids'][url][domain])) {
	
					delete that['last-session-ids'][url][domain];
					if ($.isEmptyObject(that['last-session-ids'][url])) {
	
						delete that['last-session-ids'][url];
					}
				}
			}
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// pop.JSLibraryManager.register(pop.JSRuntimeManager, [], true); // Make all base JS classes high priority so that they execute first
