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

require_once dirname(__FILE__).'/constants.php';
// require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/uploadHandler.php';
require_once dirname(__FILE__).'/upload.php';

new Upload();
