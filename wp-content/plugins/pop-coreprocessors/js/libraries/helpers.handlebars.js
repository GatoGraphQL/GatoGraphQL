Handlebars.registerHelper('latestCountTargets', function(itemObject, options) {

    // Build the notification prompt targets, based on the data on the itemObject
    var targets = [];
    var selector = '.pop-block.'+M.JS_INITIALIZED+' > .blocksection-latestcount > .pop-latestcount';

    // By Sections (post type + categories)
    var post_type = itemObject['post-type'];
    var cats = itemObject['cats'];
    if (cats && cats.length) {
	    targets.push(selector+'.'+post_type+'-'+cats.join('.'+post_type+'-'));
	}

    // By Tags
    jQuery.each(itemObject['tags'], function(index, tag) {

        var target = selector+'.tag'+tag;
        targets.push(target);
    });

    // By author pages
    jQuery.each(itemObject['authors'], function(index, author) {

		var target = selector+'.author'+author;
		if (cats && cats.length) {
			target += '.author-'+post_type+'-'+cats.join('.author-'+post_type+'-');
		}
		targets.push(target);
    });

    // By single relatedto posts
    jQuery.each(itemObject['references'], function(index, post_id) {

        var target = selector+'.single'+post_id;
        if (cats && cats.length) {
            target += '.single-'+post_type+'-'+cats.join('.single-'+post_type+'-');
        }
        targets.push(target);
    });

    return new Handlebars.SafeString(targets.join(','));
});

Handlebars.registerHelper('formatFeedbackMessage', function(message, options) {

    var topLevelSettings = options.hash.tls, 
        pageSectionSettings = options.hash.pss, 
        blockSettings = options.hash.bs;

    // Allow popMultiDomain to modify the message, adding the domain name
    message = popSystem.formatFeedbackMessage(message, topLevelSettings, pageSectionSettings, blockSettings);
    return new Handlebars.SafeString(message);
});
