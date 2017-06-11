(function($){
popJSRuntimeManager = {
	
	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------

	'full-session-ids': {},
	'last-session-ids': {},
	blockURL: null,
	pageSectionURL: 'general',

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	setBlockURL : function(url) {
		var t = this;
		t.blockURL = url;
	},

	// initPageSectionVarPaths : function(vars, pssId, template, group) {
	
	// 	var t = this;

	// 	if (!vars[pssId]) {
	// 		vars[pssId] = {};
	// 	}
	// 	if (!vars[pssId][template]) {
	// 		vars[pssId][template] = {};
	// 	}
	// 	if (!vars[pssId][template][group]) {
	// 		vars[pssId][template][group] = [];
	// 	}
	// },
	initBlockVarPaths : function(vars, url, pssId, targetId, template, group) {
	
		var t = this;
		group = group || M.JSMETHOD_GROUP_MAIN;

		if (!vars[url]) {
			vars[url] = {};
		}
		if (!vars[url][pssId]) {
			vars[url][pssId] = {};
		}
		if (!vars[url][pssId][targetId]) {
			vars[url][pssId][targetId] = {};
		}
		if (!vars[url][pssId][targetId][template]) {
			vars[url][pssId][targetId][template] = {};
		}
		if (!vars[url][pssId][targetId][template][group]) {
			vars[url][pssId][targetId][template][group] = [];
		}
	},
	initVars : function(url, pssId, targetId, template, group) {
	
		var t = this;
		t.initBlockVarPaths(t['full-session-ids'], url, pssId, targetId, template, group);
		t.initBlockVarPaths(t['last-session-ids'], url, pssId, targetId, template, group);
	},

	addGroup : function(id, group) {
	
		var t = this;

		if (group) {
			id += M.ID_SEPARATOR+group;
		}
		
		return id;
	},
	// addPageSectionId : function(pssId, template, id, group) {
	
	// 	var t = this;

	// 	id = t.addGroup(id, group);

	// 	// Comment Leo 13/02/2017: adding the URL, so that the ID for an initialized component (eg: GetPoP's decentralized calendar in the homepage)
	// 	// are not returned when doing getTargetSessionIds when initializing the same component on another page (eg: GetPoP's decentralized calendar in the Documentation Overview)
	// 	var url = t.pageSectionURL;
		
	// 	// Add only under pageSection
	// 	t.addTargetId(url, pssId, pssId, template, group, id);
	// 	return id;
	// },
	addPageSectionId : function(pssId, template, id, group) {
	
		var t = this;
		return t.addTemplateId(pssId, pssId, template, id, group, true, true);
	},
	addTemplateId : function(pssId, targetId, template, id, group, fixed, isIdUnique, ignorePSRuntimeId) {
	
		var t = this;
		
		// If the ID is not unique, then we gotta make it unique, getting the POP_FRONTENDENGINE_CONSTANT_UNIQUE_ID from the topLevel feedback
		if (!isIdUnique) {
			id += popManager.getUniqueId();
		}

		// Add a counter id at the end so that no two ids will be the same (only needed for elements other than pageSection and blocks)
		if (!fixed) {
			id += M.ID_SEPARATOR+counterNext();
		}

		// Add the group at the end, so that we can recreate the ID in the back-end calling function GD_TemplateManager_Utils::get_frontend_id
		id = t.addGroup(id, group);

		// Add under both pageSection and block, unless the targetId is the pssId, then no need for the block (eg: pagesection-tabpane.tmpl for the group="interceptor" link)
		// ignorePSRuntimeId: to not add the runtime ID for the pageSection when re-drawing data inside a block (the IDs will be generated again, but no need to add them to the pageSection side, since pageSection js methods will not be executed again)
		if (!ignorePSRuntimeId) {
			t.addTargetId(t.pageSectionURL, pssId, pssId, template, group, id);
		}
		if (pssId != targetId) {
			t.addTargetId(t.blockURL, pssId, targetId, template, group, id);
		}
		return id;
	},
	addTargetId : function(url, pssId, targetId, template, group, id) {
	
		var t = this;
		group = group || M.JSMETHOD_GROUP_MAIN;

		t.initVars(url, pssId, targetId, template, group);
		t['full-session-ids'][url][pssId][targetId][template][group].push(id);
		t['last-session-ids'][url][pssId][targetId][template][group].push(id);

		return id;
	},
	getLastGeneratedId : function(pssId, targetId, template, group) {
	
		var t = this;
		group = group || M.JSMETHOD_GROUP_MAIN;

		// Is it a pageSection? or a block?
		var url = (pssId == targetId) ? t.pageSectionURL : t.blockURL;
		var ids = t['full-session-ids'][url][pssId][targetId][template][group];
		return ids[ids.length-1];
	},

	getPageSectionSessionIds : function(pageSection, session) {
	
		var t = this;

		// The URL is a static placeholder, since the pageSection is always initialized immediately
		var url = t.pageSectionURL;

		return t.getTargetSessionIds(url, pageSection, pageSection, session);
	},
	getBlockSessionIds : function(pageSection, block, session) {
	
		var t = this;

		// Get the URL from the toplevel-url, since the block may be initialized later on (eg: in a Carousel or TabPane)
		var url = t.getBlockURL(pageSection, block);

		return t.getTargetSessionIds(url, pageSection, block, session);
	},
	getTargetSessionIds : function(url, pageSection, target, session) {
	
		var t = this;
		var pssId = popManager.getSettingsId(pageSection);
		var targetId = popManager.getSettingsId(target);

		// session can be 'last' or 'full'. 'last' is the default since it's the more used one (for appending newDOMs, we need just the newly added ids)
		session = session || 'last';
		if (t[session+'-session-ids'][url] && t[session+'-session-ids'][url][pssId]) {

			return t[session+'-session-ids'][url][pssId][targetId];
		}
		
		return null;
	},
	deletePageSectionLastSessionIds : function(pageSection) {

		var t = this;
		var pssId = popManager.getSettingsId(pageSection);
		var url = t.pageSectionURL;
		
		delete t['last-session-ids'][url][pssId][pssId];
		if ($.isEmptyObject(t['last-session-ids'][url][pssId])) {

			delete t['last-session-ids'][url][pssId];
			if ($.isEmptyObject(t['last-session-ids'][url])) {

				delete t['last-session-ids'][url];
			}
		}
	},
	deleteBlockLastSessionIds : function(pageSection, block) {

		var t = this;
		var pssId = popManager.getSettingsId(pageSection);
		var targetId = popManager.getSettingsId(block);
		
		// Get the URL from the toplevel-url, since the block may be initialized later on (eg: in a Carousel or TabPane)
		var url = t.getBlockURL(pageSection, block);
		if (t['last-session-ids'][url] && t['last-session-ids'][url][pssId] && t['last-session-ids'][url][pssId][targetId]) {
	
			delete t['last-session-ids'][url][pssId][targetId];
			if ($.isEmptyObject(t['last-session-ids'][url][pssId])) {
	
				delete t['last-session-ids'][url][pssId];
				if ($.isEmptyObject(t['last-session-ids'][url])) {
	
					delete t['last-session-ids'][url];
				}
			}
		}
	},
	getBlockURL : function(pageSection, block) {

		var t = this;
		
		return block.data('toplevel-url');
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// popJSLibraryManager.register(popJSRuntimeManager, [], true); // Make all base JS classes high priority so that they execute first
