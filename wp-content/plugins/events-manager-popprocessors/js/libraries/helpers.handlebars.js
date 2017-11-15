"use strict";
Handlebars.registerHelper('locationsPageURL', function(domain, options) {

    // Allow PoP MultiDomain to add the page URLs for other domains
    return M.LOCATIONS_PAGE_URL[domain];
});
