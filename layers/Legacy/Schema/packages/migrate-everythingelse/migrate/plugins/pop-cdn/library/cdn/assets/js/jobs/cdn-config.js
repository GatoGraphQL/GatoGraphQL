"use strict";
(function($){
pop.CDN.config[{{$domain}}] = {
  thumbprints: {{$thumbprints}},
  criteria: {
	  thumbprints: {{$criteria_thumbprints}},
	  rejected: {{$criteria_rejected}}
  },
  cdnDomain: {{$cdnDomain}}
};
})(jQuery);
