<?php
const POP_RESOURCELOADER_RESOURCETYPE_JS = 'js';
const POP_RESOURCELOADER_RESOURCETYPE_CSS = 'css';
const POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL = 'normal';
const POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR = 'vendor';
const POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC = 'dynamic';
const POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE = 'template';
const POP_RESOURCELOADER_LOADINGTYPE_IMMEDIATE = 'immediate';
const POP_RESOURCELOADER_LOADINGTYPE_ASYNC = 'async';
const POP_RESOURCELOADER_LOADINGTYPE_DEFER = 'defer';

\PoP\Root\App::addFilter('gd_jquery_constants', 'popWebPlatformResourceloaderJqueryConstants');
function popWebPlatformResourceloaderJqueryConstants($jqueryConstants) {

	if (PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {
		
		$jqueryConstants['RESOURCELOADER'] = array(
			'TYPES' => array(
				'JS' => POP_RESOURCELOADER_RESOURCETYPE_JS,
				'CSS' => POP_RESOURCELOADER_RESOURCETYPE_CSS,
			),
		);
	}
	
	return $jqueryConstants;
}
