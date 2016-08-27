(function($){
popJSRuntimeManager = {
	
	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------

	'full-session-ids': {},
	'last-session-ids': {},

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	// preserveState : function(args) {
	
	// 	var t = this;
	// 	var pageSection = args.pageSection, state = args.state;
	// 	var pssId = popManager.getSettingsId(pageSection);

	// 	// Last session ids: no need to return, the 'last' session is lost
	// 	state.runtimeManager = {
	// 		'full-session-ids': $.extend({}, t['full-session-ids'][pssId])
	// 	};

	// 	return state;
	// },

	// retrieveState : function(args) {
	
	// 	var t = this;
	// 	var pageSection = args.pageSection, state = args.state.runtimeManager;
	// 	var pssId = popManager.getSettingsId(pageSection);

	// 	// t['full-pagesectionsession-ids'][pssId] = state['full-pagesectionsession-ids'];
	// 	t['full-session-ids'][pssId] = state['full-session-ids'];

	// 	// Set the block session ids with the whole long pageSection session, since it will be needed
	// 	// to execute JS on these elements (which already exist on the saved html)
	// 	t['last-session-ids'][pssId] = t['full-session-ids'][pssId];
	// },

	// clearState : function(args) {
	
	// 	var t = this;
	// 	var pageSection = args.pageSection;
	// 	var pssId = popManager.getSettingsId(pageSection);

	// 	t['full-session-ids'][pssId] = {};
	// 	t['last-session-ids'][pssId] = {};
	// },

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	initPageSectionVarPaths : function(vars, pssId, template, group) {
	
		var t = this;

		if (!vars[pssId]) {
			vars[pssId] = {};
		}
		if (!vars[pssId][template]) {
			vars[pssId][template] = {};
		}
		if (!vars[pssId][template][group]) {
			vars[pssId][template][group] = [];
		}
	},
	initBlockVarPaths : function(vars, pssId, targetId, template, group) {
	
		var t = this;
		group = group || M.JSMETHOD_GROUP_MAIN;

		if (!vars[pssId]) {
			vars[pssId] = {};
		}
		if (!vars[pssId][targetId]) {
			vars[pssId][targetId] = {};
		}
		if (!vars[pssId][targetId][template]) {
			vars[pssId][targetId][template] = {};
		}
		if (!vars[pssId][targetId][template][group]) {
			vars[pssId][targetId][template][group] = [];
		}
	},
	initVars : function(pssId, targetId, template, group) {
	
		var t = this;
		t.initBlockVarPaths(t['full-session-ids'], pssId, targetId, template, group);
		t.initBlockVarPaths(t['last-session-ids'], pssId, targetId, template, group);
	},

	addGroup : function(id, group) {
	
		var t = this;

		if (group) {
			id += M.ID_SEPARATOR+group;
		}
		
		return id;
	},
	addPageSectionId : function(pssId, template, id, group) {
	
		var t = this;

		id = t.addGroup(id, group);
		
		// Add only under pageSection
		t.addTargetId(pssId, pssId, template, group, id);
		return id;
	},
	addTemplateId : function(pssId, targetId, template, id, group, fixed, isIdUnique) {
	
		var t = this;

		id = t.addGroup(id, group);
		
		// If the ID is not unique, then we gotta make it unique, getting the POP_FRONTENDENGINE_CONSTANT_UNIQUE_ID from the topLevel feedback
		if (!isIdUnique) {
			id += popManager.getUniqueId();
		}

		// Add a counter id at the end so that no two ids will be the same (only needed for elements other than pageSection and blocks)
		if (!fixed) {
			id += M.ID_SEPARATOR+counterNext();
		}

		// Add under both pageSection and block 
		t.addTargetId(pssId, pssId, template, group, id);
		t.addTargetId(pssId, targetId, template, group, id);
		return id;
	},
	addTargetId : function(pssId, targetId, template, group, id) {
	
		var t = this;
		group = group || M.JSMETHOD_GROUP_MAIN;

		t.initVars(pssId, targetId, template, group);
		t['full-session-ids'][pssId][targetId][template][group].push(id);
		t['last-session-ids'][pssId][targetId][template][group].push(id);

		return id;
	},
	getLastGeneratedId : function(pssId, targetId, template, group) {
	
		var t = this;
		group = group || M.JSMETHOD_GROUP_MAIN;
		var ids = t['full-session-ids'][pssId][targetId][template][group];
		return ids[ids.length-1];
	},

	getPageSectionSessionIds : function(pageSection, session) {
	
		var t = this;
		return t.getTargetSessionIds(pageSection, pageSection, session);
	},
	getBlockSessionIds : function(pageSection, block, session) {
	
		var t = this;
		return t.getTargetSessionIds(pageSection, block, session);
	},
	getTargetSessionIds : function(pageSection, target, session) {
	
		var t = this;
		var pssId = popManager.getSettingsId(pageSection);
		var targetId = popManager.getSettingsId(target);

		// session can be 'last' or 'full'. 'last' is the default since it's the more used one (for appending newDOMs, we need just the newly added ids)
		session = session || 'last';
		if (t[session+'-session-ids'][pssId]) {

			return t[session+'-session-ids'][pssId][targetId];
		}
		
		return null;
	},
	deletePageSectionLastSessionIds : function(pageSection) {

		var t = this;
		var pssId = popManager.getSettingsId(pageSection);
		delete t['last-session-ids'][pssId][pssId];
	},
	deleteBlockLastSessionIds : function(pageSection, block) {

		var t = this;
		var pssId = popManager.getSettingsId(pageSection);
		var targetId = popManager.getSettingsId(block);

		if (t['last-session-ids'][pssId] && t['last-session-ids'][pssId][targetId]) {
	
			delete t['last-session-ids'][pssId][targetId];
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// popJSLibraryManager.register(popJSRuntimeManager, [], true); // Make all base JS classes high priority so that they execute first
