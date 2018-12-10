<?php

add_action('init', 'pop_engine_define_pagemodulegroup_content_module');
function pop_engine_define_pagemodulegroup_content_module() {

	// the "Main Content Module" group initially represents the entry module, but this is overridable (eg: by the theme, setting it to be the Main PageSection module)
	if (!defined('POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE')) {

		define ('POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE', POP_PAGEMODULEGROUP_ENTRYMODULE);
	}
}
