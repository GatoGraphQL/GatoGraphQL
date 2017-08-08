<?php

//-------------------------------------------------------------------------------------
// Install | Updates
//-------------------------------------------------------------------------------------

// require_once POP_COREPROCESSORS_LIB.'/install.php';

//-------------------------------------------------------------------------------------
// Core functions
//-------------------------------------------------------------------------------------

require_once POP_COREPROCESSORS_LIB.'/core.php';

//-------------------------------------------------------------------------------------
// Load Library
//-------------------------------------------------------------------------------------
								
// Load the right timezone
require_once POP_COREPROCESSORS_LIB.'/timezone.php';

// Admin: only when in Development Environment
require_once POP_COREPROCESSORS_LIB.'/admin/load.php';

require_once POP_COREPROCESSORS_LIB.'/utils.php';
require_once POP_COREPROCESSORS_LIB.'/users/load.php';

require_once POP_COREPROCESSORS_LIB.'/screens.php';
require_once POP_COREPROCESSORS_LIB.'/media.php';
require_once POP_COREPROCESSORS_LIB.'/mentions/load.php';
// require_once POP_COREPROCESSORS_LIB.'/scripts_and_styles.php';
require_once POP_COREPROCESSORS_LIB.'/url-params.php';
// require_once POP_COREPROCESSORS_LIB.'/avatars/load.php';
require_once POP_COREPROCESSORS_LIB.'/captcha/load.php';
require_once POP_COREPROCESSORS_LIB.'/classes.php';
require_once POP_COREPROCESSORS_LIB.'/comments.php';
require_once POP_COREPROCESSORS_LIB.'/content.php';
require_once POP_COREPROCESSORS_LIB.'/crossdomain.php';
require_once POP_COREPROCESSORS_LIB.'/form-utils.php';
// require_once POP_COREPROCESSORS_LIB.'/formatting.php';
require_once POP_COREPROCESSORS_LIB.'/dataload.php';
require_once POP_COREPROCESSORS_LIB.'/date.php';
require_once POP_COREPROCESSORS_LIB.'/multiselect.php';
require_once POP_COREPROCESSORS_LIB.'/default-filters.php';
require_once POP_COREPROCESSORS_LIB.'/editor/load.php';
// require_once POP_COREPROCESSORS_LIB.'/email/load.php';
// require_once POP_COREPROCESSORS_LIB.'/email-utils.php';
require_once POP_COREPROCESSORS_LIB.'/embed.php';
// require_once POP_COREPROCESSORS_LIB.'/file.php';
// require_once POP_COREPROCESSORS_LIB.'/fileupload-userphoto/fileupload-picture.php';
require_once POP_COREPROCESSORS_LIB.'/gallery.php';
require_once POP_COREPROCESSORS_LIB.'/googleanalytics.php';
require_once POP_COREPROCESSORS_LIB.'/hide_admin_bar.php';
require_once POP_COREPROCESSORS_LIB.'/login/login.php';
require_once POP_COREPROCESSORS_LIB.'/preferences/load.php';
require_once POP_COREPROCESSORS_LIB.'/media/media.php';
require_once POP_COREPROCESSORS_LIB.'/meta/load.php';
require_once POP_COREPROCESSORS_LIB.'/navigation.php';
require_once POP_COREPROCESSORS_LIB.'/nonces.php';
require_once POP_COREPROCESSORS_LIB.'/redirects.php';
require_once POP_COREPROCESSORS_LIB.'/rss.php';
require_once POP_COREPROCESSORS_LIB.'/tabs.php';
require_once POP_COREPROCESSORS_LIB.'/socialmedia.php';
require_once POP_COREPROCESSORS_LIB.'/template.php';
require_once POP_COREPROCESSORS_LIB.'/translation.php';
require_once POP_COREPROCESSORS_LIB.'/viewers.php';
require_once POP_COREPROCESSORS_LIB.'/scripts-and-styles.php';
require_once POP_COREPROCESSORS_LIB.'/user-account.php';
// require_once POP_COREPROCESSORS_LIB.'/walker/load.php';

