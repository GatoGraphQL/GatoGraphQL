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

class UploadS3 extends Upload
{
    public function initUploadhandler()
    {
        $vars = $this->getVars();

        return new UploadHandlerS3($vars);
    }
}
