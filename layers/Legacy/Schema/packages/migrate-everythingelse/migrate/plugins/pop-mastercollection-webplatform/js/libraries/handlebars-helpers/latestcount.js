"use strict";
Handlebars.registerHelper('latestCountTargets', function(resolvedObject, options) {

    // Build the notification prompt targets, based on the data on the resolvedObject
    var targets = [];
    var selector = '.pop-block.'+pop.c.JS_INITIALIZED+' > .blocksection-latestcount > .pop-latestcount';

    // By Sections (post type + categories)
    var trigger_values = resolvedObject['latestcountsTriggerValues'] || [];

    // By Post Type + Categories
    jQuery.each(trigger_values, function(index, trigger_value) {

        // trigger_value will be translated to 'postType'+'mainCategory' attribute
        targets.push(selector+'.'+trigger_value);
    });

    // By Tags
    jQuery.each(resolvedObject['tags'], function(index, tag) {

        var target = selector+'.tag'+tag;
        targets.push(target);
    });

    // By author pages
    jQuery.each(resolvedObject['authors'], function(index, author) {

		var target = selector+'.author'+author;

        // ... combined with Categories
        if (trigger_values.length) {
            jQuery.each(trigger_values, function(index, trigger_value) {

                targets.push(target+'.author-'+trigger_value);
            });
        }
        else {
           targets.push(target);
        }
    });

    // By single relatedto posts
    jQuery.each(resolvedObject['references'], function(index, post_id) {

        var target = selector+'.single'+post_id;

        // ... combined with Categories
        if (trigger_values.length) {
            jQuery.each(trigger_values, function(index, trigger_value) {

                targets.push(target+'.single-'+trigger_value);
            });
        }
        else {
           targets.push(target);
        }
    });

    return new Handlebars.SafeString(targets.join(','));
});
