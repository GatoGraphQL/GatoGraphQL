"use strict";

Handlebars.registerHelper('formatFeedbackMessage', function(message, options) {

    var topLevelSettings = options.hash.tls, 
        pageSectionSettings = options.hash.pss, 
        blockSettings = options.hash.bs;

    // Allow pop.MultiDomain to modify the message, adding the domain name
    message = pop.FeedbackMessage.formatFeedbackMessage(message, topLevelSettings, pageSectionSettings, blockSettings);
    return new Handlebars.SafeString(message);
});
