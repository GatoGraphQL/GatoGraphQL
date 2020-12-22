"use strict";

Handlebars.registerHelper('ondate', function(date, options) {

    return new Handlebars.SafeString(pop.c.ONDATE.format(date));     
});