<?php
/*
 * jQuery File Upload Plugin PHP Example 5.7
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);

require_once dirname(__FILE__).'/../../../../pop-aws/config/constants.php';
require_once dirname(__FILE__).'/../../../config/constants.php';

// Declare the required constants
require_once dirname(__FILE__).'/constants.php';

// Validate that all required constants have been defined
require_once dirname(__FILE__).'/validation.php';

// AWS SDK Version 2.8.27, taken from https://github.com/aws/aws-sdk-php/releases
// require dirname(__FILE__).'/includes/aws/aws-autoloader.php';
require_once dirname(__FILE__).'/../../../../pop-aws/includes/aws/aws-autoloader.php';

require_once dirname(__FILE__).'/../../../../pop-useravatar/directaccess-library/fileupload-userphoto/server/constants.php';

// Override with AWS version
require_once dirname(__FILE__).'/uploadHandlerS3.php';

// Load the upload class from UserAvatar PoP
require_once dirname(__FILE__).'/../../../../pop-useravatar/directaccess-library/fileupload-userphoto/server/upload.php';

// Override with this class
require_once dirname(__FILE__).'/upload.php';

new UploadS3();
