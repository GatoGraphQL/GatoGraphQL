"use strict";
//first, checks if it isn't implemented yet
if (!String.prototype.format) {
  String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) { 
      return typeof args[number] != 'undefined'
        ? args[number]
        : match
      ;
    });
  };
}

if (typeof String.prototype.startsWith != 'function') {
  // see below for better implementation!
  String.prototype.startsWith = function (str){
    return this.indexOf(str) == 0;
  };
}

// Implementation of javascript `some` function, for browsers not yet implementing it
// Taken from https://www.tutorialspoint.com/javascript/array_some.htm
if (!Array.prototype.some)
{
   Array.prototype.some = function(fun /*, thisp*/)
   {
      var len = this.length;
      if (typeof fun != "function")
      throw new TypeError();
      
      var thisp = arguments[1];
      for (var i = 0; i < len; i++)
      {
         if (i in this && fun.call(thisp, this[i], i, this))
         return true;
      }
      return false;
   };
}

// Implementation of javascript `filter` function, for browsers not yet implementing it
// Taken from https://www.tutorialspoint.com/javascript/array_some.htm
if (!Array.prototype.filter)
{
   Array.prototype.filter = function(fun /*, thisp*/)
   {
      var len = this.length;
      if (typeof fun != "function")
      throw new TypeError();
      
      var res = new Array();
      var thisp = arguments[1];
      for (var i = 0; i < len; i++)
      {
         if (i in this)
         {
            var val = this[i]; // in case fun mutates this
            if (fun.call(thisp, val, i, this))
            res.push(val);
         }
      }
      return res;
   };
}

// Taken from http://stackoverflow.com/questions/221294/how-do-you-get-a-timestamp-in-javascript
if (!Date.now) {
    Date.now = function() { return new Date().getTime(); }
}
