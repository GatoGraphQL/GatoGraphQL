<?php

$compact = \PoP\ComponentModel\Environment::compactResponseJsonKeys();
define('GD_JS_TEMPLATE', $compact ? 't' : 'template');
define('GD_JS_SUBCOMPONENTOUTPUTNAMES', $compact ? 'ss' : 'submoduleoutputnames');
define('POP_JS_TEMPLATES', $compact ? 'tx' : 'templates');
define('GD_JS_METHODS', $compact ? 'mt' : 'methods');
define('GD_JS_NEXT', $compact ? 'n' : 'next');
define('GD_JS_FIXEDID', $compact ? 'fx' : 'fixed-id');
define('GD_JS_ISIDUNIQUE', $compact ? 'iu' : 'is-id-unique');
define('GD_JS_FRONTENDID', $compact ? 'f' : 'frontend-id');
define('GD_JS_INTERCEPTSKIPSTATEUPDATE', $compact ? 'is' : 'intercept-skipstateupdate');
define('GD_JS_APPENDABLE', $compact ? 'ap' : 'appendable');
define('GD_JS_CLASS', $compact ? 'c' : 'class');
define('GD_JS_CLASSES', $compact ? 'cs' : 'classes');
define('GD_JS_STYLE', $compact ? 'y' : 'style');
define('GD_JS_STYLES', $compact ? 'ys' : 'styles');
define('GD_JS_TITLES', $compact ? 'tt' : 'titles');
define('GD_JS_PARAMS', $compact ? 'p' : 'params');
define('GD_JS_DBOBJECTPARAMS', $compact ? 'dop' : 'dbobject-params');
define('GD_JS_PREVIOUSCOMPONENTSIDS', $compact ? 'pt' : 'previousmodules-ids');
define('GD_JS_DESCRIPTION', $compact ? 'd' : 'description');
