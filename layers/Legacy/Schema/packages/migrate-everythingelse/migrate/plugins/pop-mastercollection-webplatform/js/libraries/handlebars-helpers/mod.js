"use strict";

Handlebars.registerHelper('mod', function(lvalue, rvalue, options) {
    if (arguments.length < 3)
        throw new Error("Handlebars Helper equal needs 2 parameters");
        
    var offset = options.hash.offset || 0;
            
    if( (lvalue + offset) % rvalue === 0 ) {
        return options.fn(this);
    } else {
        return options.inverse(this);
    }
});

