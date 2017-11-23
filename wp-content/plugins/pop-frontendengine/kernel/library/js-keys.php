<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

$compact = PoP_ServerUtils::compact_js_keys();
define ('GD_JS_TEMPLATEIDS', $compact ? 'ti' : 'template-ids');
define ('GD_JS_SETTINGSIDS', $compact ? 'ss' : 'settings-ids');
define ('GD_JS_BLOCKSETTINGSIDS', $compact ? 'b' : 'block-settings-ids');
define ('GD_JS_TEMPLATESOURCES', $compact ? 'tx' : 'template-sources');
define ('GD_JS_METHODS', $compact ? 'mt' : 'methods');
define ('GD_JS_NEXT', $compact ? 'n' : 'next');
define ('GD_JS_FIXEDID', $compact ? 'fx' : 'fixed-id');
define ('GD_JS_ISIDUNIQUE', $compact ? 'iu' : 'is-id-unique');
define ('GD_JS_FRONTENDID', $compact ? 'f' : 'frontend-id');
define ('GD_JS_MODULEOPTIONS', $compact ? 'mo' : 'moduleoptions');
define ('GD_JS_RUNTIMEMODULEOPTIONS', $compact ? 'rmo' : 'runtime-moduleoptions');
define ('GD_JS_OVERRIDEFROMITEMOBJECT', $compact ? 'oio' : 'override-from-itemobject');
define ('GD_JS_REPLACESTRFROMITEMOBJECT', $compact ? 'rio' : 'replacestr-from-itemobject');
define ('GD_JS_BLOCKUNITSREPLICABLE', $compact ? 'bur' : 'blockunits-replicable');
define ('GD_JS_BLOCKUNITSFRAME', $compact ? 'buf' : 'blockunits-frame');
define ('GD_JS_BLOCKUNITS', $compact ? 'bu' : 'blockunits');
define ('GD_JS_INTERCEPTSKIPSTATEUPDATE', $compact ? 'is' : 'intercept-skipstateupdate');
define ('GD_JS_UNIQUEURLS', $compact ? 'uu' : 'unique-urls');
define ('GD_JS_REPLICATETYPES', $compact ? 'rt' : 'replicate-types');
define ('GD_JS_REPLICATEBLOCKSETTINGSIDS', $compact ? 'rbsi' : 'replicate-blocksettingsids');
define ('GD_JS_APPENDABLE', $compact ? 'ap' : 'appendable');
define ('GD_JS_CLASS', $compact ? 'c' : 'class');
define ('GD_JS_CLASSES', $compact ? 'cs' : 'classes');
define ('GD_JS_STYLE', $compact ? 'y' : 'style');
define ('GD_JS_STYLES', $compact ? 'ys' : 'styles');
define ('GD_JS_TITLES', $compact ? 'tt' : 'titles');
define ('GD_JS_PARAMS', $compact ? 'p' : 'params');
define ('GD_JS_ITEMOBJECTPARAMS', $compact ? 'iop' : 'itemobject-params');
define ('GD_JS_PREVIOUSTEMPLATESIDS', $compact ? 'pt' : 'previoustemplates-ids');
define ('GD_JS_BLOCKFEEDBACKPARAMS', $compact ? 'bfp' : 'blockfeedback-params');
define ('GD_JS_RESOURCES', $compact ? 'r' : 'resources');

// Intercept
define ('GD_JS_INTERCEPT', $compact ? 'i' : 'intercept');
define ('GD_JS_TYPE', $compact ? 'ty' : 'type');
define ('GD_JS_SETTINGS', $compact ? 'st' : 'settings');
define ('GD_JS_TARGET', $compact ? 'tg' : 'target');
define ('GD_JS_SKIPSTATEUPDATE', $compact ? 'sk' : 'skipstateupdate');

add_filter('gd_jquery_constants', 'gd_jquery_constants_jsparams');
function gd_jquery_constants_jsparams($jquery_constants) {

	// From PoP Engine
	$jquery_constants['JS_TEMPLATE'] = GD_JS_TEMPLATE;	
	$jquery_constants['JS_MODULES'] = GD_JS_MODULES;	
	$jquery_constants['JS_SUBCOMPONENTS'] = GD_JS_SUBCOMPONENTS;
	$jquery_constants['JS_SETTINGSID'] = GD_JS_SETTINGSID;

	// From Frontend PoP Engine
	$jquery_constants['JS_TEMPLATEIDS'] = GD_JS_TEMPLATEIDS;	
	$jquery_constants['JS_SETTINGSIDS'] = GD_JS_SETTINGSIDS;	
	$jquery_constants['JS_BLOCKSETTINGSIDS'] = GD_JS_BLOCKSETTINGSIDS;
	$jquery_constants['JS_TEMPLATESOURCES'] = GD_JS_TEMPLATESOURCES;
	$jquery_constants['JS_METHODS'] = GD_JS_METHODS;	
	$jquery_constants['JS_NEXT'] = GD_JS_NEXT;	
	$jquery_constants['JS_FIXEDID'] = GD_JS_FIXEDID;	
	$jquery_constants['JS_ISIDUNIQUE'] = GD_JS_ISIDUNIQUE;	
	$jquery_constants['JS_FRONTENDID'] = GD_JS_FRONTENDID;	
	$jquery_constants['JS_MODULEOPTIONS'] = GD_JS_MODULEOPTIONS;	
	$jquery_constants['JS_RUNTIMEMODULEOPTIONS'] = GD_JS_RUNTIMEMODULEOPTIONS;	
	$jquery_constants['JS_OVERRIDEFROMITEMOBJECT'] = GD_JS_OVERRIDEFROMITEMOBJECT;	
	$jquery_constants['JS_REPLACESTRFROMITEMOBJECT'] = GD_JS_REPLACESTRFROMITEMOBJECT;	
	$jquery_constants['JS_BLOCKUNITSREPLICABLE'] = GD_JS_BLOCKUNITSREPLICABLE;	
	$jquery_constants['JS_BLOCKUNITSFRAME'] = GD_JS_BLOCKUNITSFRAME;	
	$jquery_constants['JS_BLOCKUNITS'] = GD_JS_BLOCKUNITS;	
	$jquery_constants['JS_INTERCEPTSKIPSTATEUPDATE'] = GD_JS_INTERCEPTSKIPSTATEUPDATE;
	$jquery_constants['JS_UNIQUEURLS'] = GD_JS_UNIQUEURLS;
	$jquery_constants['JS_REPLICATETYPES'] = GD_JS_REPLICATETYPES;
	$jquery_constants['JS_REPLICATEBLOCKSETTINGSIDS'] = GD_JS_REPLICATEBLOCKSETTINGSIDS;
	$jquery_constants['JS_APPENDABLE'] = GD_JS_APPENDABLE;
	$jquery_constants['JS_CLASS'] = GD_JS_CLASS;
	$jquery_constants['JS_CLASSES'] = GD_JS_CLASSES;
	$jquery_constants['JS_STYLE'] = GD_JS_STYLE;
	$jquery_constants['JS_STYLES'] = GD_JS_STYLES;
	$jquery_constants['JS_TITLES'] = GD_JS_TITLES;
	$jquery_constants['JS_PARAMS'] = GD_JS_PARAMS;
	$jquery_constants['JS_ITEMOBJECTPARAMS'] = GD_JS_ITEMOBJECTPARAMS;
	$jquery_constants['JS_PREVIOUSTEMPLATESIDS'] = GD_JS_PREVIOUSTEMPLATESIDS;
	$jquery_constants['JS_BLOCKFEEDBACKPARAMS'] = GD_JS_BLOCKFEEDBACKPARAMS;
	$jquery_constants['JS_INTERCEPT'] = GD_JS_INTERCEPT;
	$jquery_constants['JS_TYPE'] = GD_JS_TYPE;
	$jquery_constants['JS_SETTINGS'] = GD_JS_SETTINGS;
	$jquery_constants['JS_TARGET'] = GD_JS_TARGET;
	$jquery_constants['JS_SKIPSTATEUPDATE'] = GD_JS_SKIPSTATEUPDATE;
	$jquery_constants['JS_RESOURCES'] = GD_JS_RESOURCES;

	return $jquery_constants;
}

