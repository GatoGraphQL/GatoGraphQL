(function($){
var config = popResourceLoader.config[$domain];
config['loaded-files'].push($fileurl);
if ($matchHierarchy) {

	var loadedPaths = config['loaded-paths'];
	loadedPaths[$matchHierarchy] = loadedPaths[$matchHierarchy] || [];
	loadedPaths[$matchHierarchy] = loadedPaths[$matchHierarchy].concat($matchPaths);
}
if (!config.loaded) {
	
	$.extend(config.keys, $keys);
	$.extend(config.sources, $sources);
	config['ordered-load-resources'] = config['ordered-load-resources'].concat($orderedLoadResources);
	$.extend(config.bundles, $bundles);
	$.extend(config['bundle-groups'], $bundleGroups);
	$.extend(true, config.resources, $resources);
}
$(document).triggerHandler('loaded-url:'+escape($fileurl));
})(jQuery);