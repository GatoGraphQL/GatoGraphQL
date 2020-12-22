"use strict";
Handlebars.registerHelper('latestCountTargets', function(dbObject, options) {

    // Build the notification prompt targets, based on the data on the dbObject
    var targets = [];
    var selector = '.pop-block.'+pop.c.JS_INITIALIZED+' > .blocksection-latestcount > .pop-latestcount';

    // By Sections (post type + categories)
    var trigger_values = dbObject['latestcountsTriggerValues'] || [];

    // By Post Type + Categories
    jQuery.each(trigger_values, function(index, trigger_value) {

        // trigger_value will be translated to 'postType'+'mainCategory' attribute
        targets.push(selector+'.'+trigger_value);
    });

    // By Tags
    jQuery.each(dbObject['tags'], function(index, tag) {

        var target = selector+'.tag'+tag;
        targets.push(target);
    });

    // By author pages
    jQuery.each(dbObject['authors'], function(index, author) {

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
    jQuery.each(dbObject['references'], function(index, post_id) {

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
