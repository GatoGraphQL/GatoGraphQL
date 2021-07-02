"use strict";

// Taken from https://stackoverflow.com/questions/8853396/logical-operator-in-a-handlebars-js-if-conditional
Handlebars.registerHelper({
    eq: function (v1, v2) {
        return v1 === v2;
    },
    // ne: function (v1, v2) {
    //     return v1 !== v2;
    // },
    // lt: function (v1, v2) {
    //     return v1 < v2;
    // },
    // gt: function (v1, v2) {
    //     return v1 > v2;
    // },
    // lte: function (v1, v2) {
    //     return v1 <= v2;
    // },
    // gte: function (v1, v2) {
    //     return v1 >= v2;
    // },
    and: function (v1, v2) {
        return v1 && v2;
    },
    or: function (v1, v2) {
        return v1 || v2;
    },
    in: function (v1, v2) {
        return (v2 || []).indexOf(v1) >= 0;
    }
});