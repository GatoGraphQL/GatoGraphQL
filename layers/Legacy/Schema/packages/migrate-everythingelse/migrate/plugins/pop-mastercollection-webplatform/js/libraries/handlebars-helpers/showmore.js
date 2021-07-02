"use strict";

Handlebars.registerHelper('showmore', function(str, options) {

    // len == 0 => No need for showmore
    var len = options.hash.len || 0;

    // Only if at least 100 chars more, so that it doesn't shorten just a tiny bit of text
    if (len > 0 && str.length > len + 100) {

		// If we find "</p>", then we must also hide the bit until that </p>
		var delim = "</p>";
		var total_len = len;
		var morelink = '<a href="#" class="pop-showmore-more">'+pop.c.STRING_MORE+'</a>';
		var lesslink = '<a href="#" class="pop-showmore-less hidden">'+pop.c.STRING_LESS+'</a>';
		var moreless = false, add_morelink = true;
		if ((str.length > total_len) && (str.substr(len).indexOf(delim) > -1)) {

			// Add the moreless links at the end, if only to show the hidden text inside the <p></p>
			moreless = true;
			add_morelink = false;
			
			// Wrap excess characters inside the <p></p> inside a hidden span
			// Add the morelink inside the <p></p> so it shows inline
			str = 
				str.substr(0, len)+
				'<span class="pop-showmore-more-full hidden">'+
				str.substr(len, str.substr(len).indexOf(delim))+
				'</span> '+
				morelink+
				str.substr(len+str.substr(len).indexOf(delim));

			total_len = len + str.substr(len).indexOf(delim) + delim.length;
		}

		if (moreless || (str.length > total_len)) {
			
			// Make sure there still some string left after the operation. If not, then nothing to hide
			var str_end = str.substr(total_len);
			var has_endstr = str_end.trim().length;
			if (moreless || has_endstr) {
				var str_beg = str.substr(0, total_len);
				var str_new = 	
					'<span class="pop-showmore-more-teaser">'+str_beg+'</span>'+ 
					(has_endstr ? '<span class="pop-showmore-more-full hidden">'+str_end+'</span> ' : ' ')+
					(add_morelink ? morelink : '')+
					lesslink;
		        return new Handlebars.SafeString(str_new);     
		    }
	    }
    }
    
    return str;
});