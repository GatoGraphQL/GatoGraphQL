"use strict";
(function ($) {
    window.pop.Replicate = {

        //-------------------------------------------------
        // PUBLIC FUNCTIONS
        //-------------------------------------------------

        replicateTopLevel : function (args) {

            var that = this;
            var pageSection = args.pageSection, targets = args.targets;

            targets.each(
                function () {
            
                    var link = $(this);
                    var type = link.data('replicate-type');
                    if (type == pop.c.REPLICATETYPES.MULTIPLE) {
                        that.replicateMultipleTopLevel(pageSection, link);
                    } else if (type == pop.c.REPLICATETYPES.SINGLE) {
                        that.replicateSingleTopLevel(pageSection, link);
                    }
                }
            );
        },

        replicatePageSection : function (args) {

            var that = this;
            var pageSection = args.pageSection, targets = args.targets;

            targets.each(
                function () {
            
                    var link = $(this);
                    var type = link.data('replicate-type');
                    if (type == pop.c.REPLICATETYPES.MULTIPLE) {
                         that.replicateMultiplePageSection(pageSection, link);
                    } else if (type == pop.c.REPLICATETYPES.SINGLE) {
                         that.replicateSinglePageSection(pageSection, link);
                    }
                }
            );
        },

        //-------------------------------------------------
        // 'PRIVATE' FUNCTIONS
        //-------------------------------------------------

        replicateMultipleTopLevel : function (pageSection, targets) {

            var that = this;
            targets.click(
                function (e) {
            
                    e.preventDefault();
                    var link = $(this);
                    var addUniqueId = link.data('unique-url') || false;
                    that.execReplicateTopLevel(pageSection, link, true, addUniqueId);
                }
            );
        },
        replicateSingleTopLevel : function (pageSection, targets) {

            var that = this;
            targets.one(
                'click',
                function (e) {
            
                    e.preventDefault();
                    var link = $(this);
                    that.execReplicateTopLevel(pageSection, link, false, false);
                }
            );
        },
        execReplicateTopLevel : function (pageSection, link, generateUniqueId, addUniqueId) {

            var that = this;

            // Comment Leo 26/10/2015: the URL is not the intercepted one but the original one. These 2 differ when intercepting without params
            // Eg: adding a new comment, https://www.mesym.com/add-comment/?pid=19604, url to intercept is https://www.mesym.com/add-comment/
            var url = link.data('original-url');
        
            var domain = getDomain(url);
            // var memory = pop.Manager.getMemory(domain);
        
            // Restore initial toplevel feedback from when the page was loaded
            var interceptUrl = link.data('intercept-url');
            var target = pop.Manager.getFrameTarget(pageSection);
            var replicableMemory = pop.Manager.getReplicableMemory(interceptUrl, target);

            // Comment Leo 05/04/2017: doing the line below deleted the topLevelFeedback params,
            // and that was a problem since it had all values added on loading_frame(): version, theme, thememode, etc
            // So now iterating all the fields, and setting the value by copy
            // memory.feedback.toplevel = $.extend({}, replicableMemory.feedback.toplevel);
            var tlFeedback = pop.Manager.getTopLevelFeedback(domain);
            // var tlFeedback = memory.feedback.toplevel;
            $.each(
                replicableMemory.feedback.toplevel[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT],
                function (key, value) {

                    // If it is an empty array then do nothing but set the object: this happens when the pageSection has no components (eg: sideInfo for Discussions page)
                    // and because we can't specify FORCE_OBJECT for encoding the json, then it assumes it's an array instead of an object, and it makes mess
                    if ($.type(value) == 'array' && value.length == 0) {
                         // do Nothing
                    } else if ($.type(value) == 'object') {
                         // If it is an object, extend it. If not, just assign the value
                        if (!tlFeedback[key]) {
                            tlFeedback[key] = {};
                        }
                        $.extend(tlFeedback[key], value);
                    } else {
                         tlFeedback[key] = value;
                    }
                }
            );
            // pop.Manager.maybeRestoreUniqueId(memory);

            // Generate a new uniqueId
            // Change the tab "current-page" URL to the intercepted URL + add an ID to make this URL
            // different for if again replicating the same element (eg: clicking twice on Add Event)
            if (generateUniqueId) {
                // function openTabs(): It might be the case that we're calling a an Add Post page with a unique-id and that page doesn't exist
                // That is because the saved URL contains the hashtag, so after refreshing the page, it will intercept again that URL
                // So if the URL already has an ID, use that one. Otherwise, it makes a mess, adding hashtags on top of each other
                // (something like /add-post/#asddk8980808234#fdkwwp4234355) and it creats mess opening way many tabs when refreshing the page, one for each new URL
                if (addUniqueId && url.indexOf('#') > -1) {
                    tlFeedback[pop.c.UNIQUEID] = url.substr(url.indexOf('#')+1);
                } else {
                    pop.Manager.generateUniqueId(domain);
                    if (addUniqueId) {
                        url = pop.Manager.addUniqueId(url);
                    }
                }
            }
            var title = link.data('title');

            // Set new values, coming from the intercepted link
            tlFeedback[pop.c.URLPARAM_TITLE] = title;
            // tlFeedback[pop.c.URLPARAM_PARENTPAGEID] = null;
            tlFeedback[pop.c.URLPARAM_URL] = url;

            // Update document
            pop.Manager.maybeUpdateDocument(domain, pageSection);
        },

        replicateMultiplePageSection : function (pageSection, targets) {

            var that = this;
            targets.click(
                function (e) {

                    e.preventDefault();
                    var link = $(this);
                    that.execReplicatePageSection(pageSection, link);
                }
            );
        },
        replicateSinglePageSection : function (pageSection, targets) {

            var that = this;
            targets.one(
                'click',
                function (e) {

                    e.preventDefault();
                    var link = $(this);
                    that.execReplicatePageSection(pageSection, link);
                }
            );
        },
        execReplicatePageSection : function (pageSection, link) {

            var that = this;

            var bsId = link.data('block-settingsid');
            var pssId = pop.Manager.getSettingsId(pageSection);
            var componentName = link.data('componentname');
        
            var interceptUrl = link.data('intercept-url');
            var domain = getDomain(interceptUrl);
            // var memory = pop.Manager.getMemory(domain);

            var target = pop.Manager.getFrameTarget(pageSection);
            var replicableMemory = pop.Manager.getReplicableMemory(interceptUrl, target);
        
            // Override the feedback, dbobjectids, params to the initial values
            // (otherwise: sequence: click Add Project, submit with errors, Add a Project again, it will also draw the validation error, we gotta clear the feedbackmessage)
            // $.extend(memory.statefuldata.feedback.pagesection, replicableMemory.feedback.pagesection);
            // $.each(replicableMemory.feedback.block, function(ipssId, ipsFeedback) {
            //     $.extend(memory.statefuldata.feedback.block[ipssId], ipsFeedback);
            //     $.extend(memory.statefuldata.dbobjectids[ipssId], replicableMemory.dbobjectids[ipssId]);
            //     $.extend(memory.statefuldata.querystate.sharedbydomains[ipssId], replicableMemory.querystate.sharedbydomains[ipssId]);
            //     $.extend(memory.statefuldata.querystate.uniquetodomain[ipssId], replicableMemory.querystate.uniquetodomain[ipssId]);
            // });

            // Intercept URL: the newly created URL, assigned already to the toplevel feedback on the fuction above
            var tlFeedback = pop.Manager.getTopLevelFeedback(domain);
            var psConfiguration = pop.Manager.getPageSectionConfiguration(domain, pageSection);
            psConfiguration[pop.c.JS_INTERCEPTURLS][componentName] = psConfiguration[pop.c.JS_INTERCEPTURLS][componentName] || {};
            psConfiguration[pop.c.JS_INTERCEPTURLS][componentName][componentName] = tlFeedback[pop.c.URLPARAM_URL];

            // Set what blocks must be replicated in 'blockunits', replicable must be empty since the "tell me what blocks are to be replicated" was already executed
            psConfiguration[pop.c.JS_COMPONENT] = componentName;

            // Comment Leo 05/11/2015: the configuration of the block-settings-ids to be drawn is passed as settings
            // next to the interceptor link, on <span class="pop-interceptor-blocksettingsids"/>
            // contained inside are configuration items, namely: what blockunitGroup ('blockunits', 'replicable', 'blockunits-frame', etc)
            // must be initialized with what block-settings-ids (as a list)
            var blockSettingsIds = {};
            link.next('.pop-interceptor-blocksettingsids').children().each(
                function () {
            
                    var settingElem = $(this);

                    // The values must be an array
                    var val = settingElem.data('value');
                    if (settingElem.data('value')) {
                         val = [settingElem.data('value')];
                    } else {
                         val = [];
                    }
                    blockSettingsIds[settingElem.data('key')] = val;
                }
            );
            psConfiguration[pop.c.JS_BLOCKSETTINGSIDS/*'block-settings-ids'*/] = blockSettingsIds;

            // This will set the 'pss' in the context with the new toplevel feedback
            pop.Manager.initPageSectionSettings(domain, pageSection, psConfiguration);

            // Set up the clicked link as a relatedTarget. This is needed for the Addons, eg: when clicking on Volunteer, it can pickup what Project it is from the data-header in the original link
            var options = {
                'js-args': {
                    relatedTarget: link
                },
                url: tlFeedback[pop.c.URLPARAM_URL]
            }
            pop.JSRuntimeManager.setPageSectionURL(tlFeedback[pop.c.URLPARAM_URL]);
            pop.Manager.renderPageSection(domain, pageSection, null, options);
        },
    };
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Replicate, ['replicateTopLevel', 'replicatePageSection']);
