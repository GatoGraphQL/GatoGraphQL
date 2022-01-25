<?php

// Library taken from https://gist.github.com/tim-peterson/8172999
use Aws\Common\Aws;

// use Aws\S3\Exception\S3Exception;
// make sure the SDK is installed
// I've used Composer to autoload it: http://docs.aws.amazon.com/aws-sdk-php/guide/latest/installation.html

/*
 * jQuery File Upload Plugin PHP Class 7.1.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

class UploadHandlerS3
{
    protected $vars;
    
    protected $options;

    // PHP File Upload error message codes:
    // http://php.net/manual/en/features.file-upload.errors.php
    protected $error_messages = array(
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
        'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
        'max_file_size' => 'File is too big',
        'min_file_size' => 'File is too small',
        'accept_file_types' => 'Filetype not allowed',
        'max_number_of_files' => 'Maximum number of files exceeded',
        'max_width' => 'Image exceeds maximum width',
        'min_width' => 'Image requires a minimum width',
        'max_height' => 'Image exceeds maximum height',
        'min_height' => 'Image requires a minimum height'
    );

    protected $image_objects = array();

    public function __construct($vars, $options = null, $initialize = true, $error_messages = null)
    {
        $this->vars = $vars;

        $this->s3 = Aws::factory(dirname(__FILE__).'/../../../../pop-aws/config/aws-config.php')->get('s3')->registerStreamWrapper();
        //Initialize the s3 client and "registerStreamWrapper()" allows using PHP's native file methods such as file_put_contents()
        //http://docs.aws.amazon.com/aws-sdk-php/guide/latest/service-s3.html#amazon-s3-stream-wrapper

        $this->workingBucket = POP_AWS_WORKINGBUCKET;

        // Change PoP
        $this->workingBucketPrefix = POP_FILEUPLOAD_AWS_WORKINGBUCKETPREFIX;
        $this->prefix = $this->vars['upload_dir'];
        $this->originalprefix = $this->vars['upload_originaldir'];
        $this->thumbprefix = $this->vars['upload_thumbdir'];
        $this->photoprefix = $this->vars['upload_photodir'];
        $this->avatarsprefix = $this->vars['upload_avatarsdir'];

        //compatible with PHP frameworks such as Codeigniter by calling:
        // $CI = & getInstance(); //get Codeigniter instance
        //$this->form_validation= $CI->form_validation; //access some Codeigniter class and make a property of UploadHandler class

        $this->options = array(

            'script_url' => $this->getFullUrl().'/',
            'server_dir' => dirname($_SERVER['SCRIPT_FILENAME']).'/'.$this->originalprefix,
            'upload_dir' =>'s3://'.$this->workingBucket.'/'.$this->workingBucketPrefix.'/'.$this->originalprefix,
            'server_url'=> $this->getFullUrl().'/'.$this->originalprefix,
            'upload_url'=> "https://" . $this->workingBucket . ".s3.amazonaws.com/".$this->workingBucketPrefix.'/'.$this->originalprefix,
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'param_name' => 'files',
            // Set the following option to 'POST', if your server does not support
            // DELETE requests. This is a parameter sent to the client:
            //'delete_type' => 'DELETE',
            'delete_type' => 'POST', //note this is different from the default setting. "DELETE" was untested and "POST" works.
          
            'access_control_allow_origin' => '*',
            'access_control_allow_credentials' => false,
            'access_control_allow_methods' => array(
                'OPTIONS',
                'HEAD',
                'GET',
                'POST',
                'PUT',
                'PATCH',
                'DELETE'
            ),
            'access_control_allow_headers' => array(
                'Content-Type',
                'Content-Range',
                'Content-Disposition'
            ),
            // Enable to provide file downloads via GET requests to the PHP script:
            //     1. Set to 1 to download files via readfile method through PHP
            //     2. Set to 2 to send a X-Sendfile header for lighttpd/Apache
            //     3. Set to 3 to send a X-Accel-Redirect header for nginx
            // If set to 2 or 3, adjust the upload_url option to the base path of
            // the redirect parameter, e.g. '/files/'.
            'download_via_php' => false,
            // Read files in chunks to avoid memory limits when download_via_php
            // is enabled, set to 0 to disable chunked reading of files:
            'readfile_chunk_size' => 10 * 1024 * 1024, // 10 MiB
            // Defines which files can be displayed inline when downloaded:
            'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Defines which files (based on their names) are accepted for upload:
            'accept_file_types' => '/.+$/i',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => null,
            'min_file_size' => 1,
            // The maximum number of files for the upload directory:
            // Change PoP: set number of files to 1
            'max_number_of_files' => 1,
            // Defines which files are handled as image files:
            'image_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Image resolution restrictions:
            'max_width' => null,
            'max_height' => null,
            'min_width' => 1,
            'min_height' => 1,
            // Set the following option to false to enable resumable uploads:
            'discard_aborted_uploads' => true,
            // Set to 0 to use the GD library to scale and orient images,
            // set to 1 to use imagick (if installed, falls back to GD),
            // set to 2 to use the ImageMagick convert binary directly:
            // Change PoP: originally this was set to "1", however it fails in AWS, it gives a 500 Internal Server Error, so changed to "0" instead
            'image_library' => 0,
            // Uncomment the following to define an array of resource limits
            // for imagick:
            /*
            'imagick_resource_limits' => array(
                imagick::RESOURCETYPE_MAP => 32,
                imagick::RESOURCETYPE_MEMORY => 32
            ),
            */
            // Command or path for to the ImageMagick convert binary:
            'convert_bin' => 'convert',
            // Uncomment the following to add parameters in front of each
            // ImageMagick convert call (the limit constraints seem only
            // to have an effect if put in front):
            /*
            'convert_params' => '-limit memory 32MiB -limit map 32MiB',
            */
            // Command or path for to the ImageMagick identify binary:
            'identify_bin' => 'identify',
            'image_versions' => array(
                // The empty image version key defines options for the original image:
                '' => array(
                    // Automatically rotate images based on EXIF meta data:
                    'auto_orient' => true
                ),
                'photo' => array(
                    'upload_dir' =>'s3://'.$this->workingBucket.'/'.$this->workingBucketPrefix.'/'.$this->photoprefix,
                    'upload_url'=> "https://" . $this->workingBucket . ".s3.amazonaws.com/".$this->workingBucketPrefix.'/'.$this->photoprefix,
                    'max_width' => $this->vars['photo_maxwidth'],
                    'max_height' => $this->vars['photo_maxheight'],
                    'crop' => false
                ),
                'thumb' => array(
                    'upload_dir' =>'s3://'.$this->workingBucket.'/'.$this->workingBucketPrefix.'/'.$this->thumbprefix,
                    'upload_url'=> "https://" . $this->workingBucket . ".s3.amazonaws.com/".$this->workingBucketPrefix.'/'.$this->thumbprefix,
                    'max_width' => $this->vars['thumb_size'],//GD_THUMBSIZE,
                    'max_height' => $this->vars['thumb_size'],//GD_THUMBSIZE,
                    'crop' => true
                )
            )
        );
        
        // Change PoP
        $thumb_sizes = $this->vars['avatar_sizes'];
        // Important: sort them from the largest to the smallest size, otherwise it fails on code "if ($scale >= 1) {"
        // (Somehow it takes the img_width/height from the previous step, then the scale is < 1 or something like that)
        array_multisort($thumb_sizes, SORT_DESC, SORT_NUMERIC);
        foreach ($thumb_sizes as $thumb_size) {
            $this->options['image_versions']['thumb-'.$thumb_size] = array(
                'upload_dir' =>'s3://'.$this->workingBucket.'/'.$this->workingBucketPrefix.'/'.$this->avatarsprefix.$thumb_size.'/',
                'upload_url'=> "https://" . $this->workingBucket . ".s3.amazonaws.com/".$this->workingBucketPrefix.'/'.$this->avatarsprefix.$thumb_size.'/',
                'max_width' => $thumb_size,
                'max_height' => $thumb_size,
                'crop' => true
            );
        }


        if ($options) {
            $this->options = $options + $this->options;
        }
        if ($error_messages) {
            $this->error_messages = $error_messages + $this->error_messages;
        }
        if ($initialize) {
            $this->initialize();
        }
    }

    protected function initialize()
    {

        // Change PoP: all headers below
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Content-Disposition: inline; filename="files.json"');
        header('X-Content-Type-Options: nosniff');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');
        
        switch ($this->getServerVar('REQUEST_METHOD')) {
            case 'OPTIONS':
            case 'HEAD':
                $this->head();
                break;
            case 'GET':
                $this->get();
                break;
            case 'PATCH':
            case 'PUT':
            case 'POST':
                $this->post();
                break;
            case 'DELETE':
                $this->delete();
                break;
            default:
                $this->header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    protected function getFullUrl()
    {

        // Change PoP: added the HTTP_X_FORWARDED_PROTO bit:
        // in some setups HTTP_X_FORWARDED_PROTO might contain
        // a comma-separated list e.g. http,https
        // so check for https existence

        // $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0;
        $https = (!empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false);
        return
            ($https ? 'https://' : 'http://').
            (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
            ($https && $_SERVER['SERVER_PORT'] === 443 ||
            $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
            substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }

    protected function getUserId()
    {
        @session_start();
        return session_id();
    }

    protected function getUserPath()
    {
        if ($this->options['user_dirs']) {
            return $this->getUserId().'/';
        }
        return '';
    }

    protected function getUploadPath($file_name = null, $version = null)
    {
        $file_name = $file_name ? $file_name : '';
        if (empty($version)) {
            $version_path = '';
        } else {
            $version_dir = @$this->options['image_versions'][$version]['upload_dir'];
            if ($version_dir) {
                return $version_dir.$this->getUserPath().$file_name;
            }
            $version_path = $version.'/'; //TRP 122713
            //$version_path = $version; //TRP 122713
        }
        return $this->options['upload_dir'].$this->getUserPath()
            .$version_path.$file_name;
    }

    protected function getQuerySeparator($url)
    {
        return strpos($url, '?') === false ? '?' : '&';
    }

    protected function getDownloadUrl($file_name, $version = null, $direct = false)
    {
        if (!$direct && $this->options['download_via_php']) {
            $url = $this->options['script_url']
                .$this->getQuerySeparator($this->options['script_url'])
                .'file='.rawurlencode($file_name);
            if ($version) {
                $url .= '&version='.rawurlencode($version);
            }
            return $url.'&download=1';
        }
        if (empty($version)) {
            $version_path = '';
        } else {
            $version_url = @$this->options['image_versions'][$version]['upload_url'];
            if ($version_url) {
                return $version_url.$this->getUserPath().rawurlencode($file_name);
            }
            $version_path = rawurlencode($version).'/';
        }
        return $this->options['upload_url'].$this->getUserPath()
            .$version_path.rawurlencode($file_name);
    }

    protected function setAdditionalFileProperties($file)
    {
        $file->deleteUrl = $this->options['script_url']
            .$this->getQuerySeparator($this->options['script_url'])
            .$this->getSingularParamName()
            .'='.rawurlencode($file->name);
        $file->deleteType = $this->options['delete_type'];
        if ($file->deleteType !== 'DELETE') {
            $file->deleteUrl .= '&_method=DELETE';
        }
        if ($this->options['access_control_allow_credentials']) {
            $file->deleteWithCredentials = true;
        }
        
        // Change PoP
        $file->deleteUrl .= '&upload_path='.$this->vars['upload_path'];

        // Change PoP: add also the width/height, needed for PhotoSwipe
        // Comment Leo 14/02/2016: we don't reference to "original" pic anymore, so no need to send the dimensions
        // $file_path = $this->getUploadPath($file->name);
        // if ($originalSize = getimagesize($file_path)) {
        //     $file->width = $originalSize[0];
        //     $file->height = $originalSize[1];
        // }
        // if ($photoSize = getimagesize(str_replace('/original/', '/photo/', $file_path))) {
        list($file_path, $photo_path) =
            $this->getScaledImageFilePaths($file->name, 'photo');
        if ($photoSize = getimagesize($photo_path)) {
            $file->photoWidth = $photoSize[0];
            $file->photoHeight = $photoSize[1];
        }
        $file->thumbSize = $this->vars['thumb_size'];//GD_THUMBSIZE;
    }

    // Fix for overflowing signed 32 bit integers,
    // works for sizes up to 2^32-1 bytes (4 GiB - 1):
    protected function fixIntegerOverflow($size)
    {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        return $size;
    }

    protected function getFileSize($file_path, $clear_stat_cache = false)
    {
        if ($clear_stat_cache) {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                clearstatcache(true, $file_path);
            } else {
                clearstatcache();
            }
        }
        return $this->fixIntegerOverflow(filesize($file_path));
    }

    protected function isValidFileObject($file_name)
    {
        $file_path = $this->getUploadPath($file_name);
        if (is_file($file_path) && $file_name[0] !== '.') {
            return true;
        }
        return false;
    }

    protected function getFileObject($file_name)
    {
        if ($this->isValidFileObject($file_name)) {
            $file = new stdClass();
            $file->name = $file_name;
            $file->size = $this->getFileSize(
                $this->getUploadPath($file_name)
            );
            $file->url = $this->getDownloadUrl($file->name);
            foreach ($this->options['image_versions'] as $version => $options) {
                if (!empty($version)) {
                    if (is_file($this->getUploadPath($file_name, $version))) {
                        $file->{$version.'Url'} = $this->getDownloadUrl(
                            $file->name,
                            $version
                        );
                    }
                }
            }
            $this->setAdditionalFileProperties($file);
            return $file;
        }
        return null;
    }

    protected function getFileObjects($iteration_method = 'getFileObject')
    {
        $upload_dir = $this->getUploadPath();
        if (!is_dir($upload_dir)) {
            //begin TRP 12/27/13
            try {
                //http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.S3.S3Client.html#_listObjects
                $result = $this->s3->listObjects(
                    array(
                        // Bucket is required
                        'Bucket' => $this->workingBucket,
                        'Prefix' => $this->workingBucketPrefix.'/'.$this->originalprefix
                    )
                );

                if (isset($result['Contents']) && count($result['Contents'])>0) {
                    foreach ($result['Contents'] as $obj) {
                        if ($obj['Key']!=$this->originalprefix/* && strpos($obj['Key'], '/thumbnail/')===false*/) { //if Key is a full file path and not just a "directory"
                            $a=pathinfo($obj['Key']);
                            $file_names_arr[]=$a['basename'];
                        }
                    }
                }
            } catch (/*S3Exception*/Exception $e) {
                $data['status']=0;
                $data['message']=$e;
                //echo "There was an error uploading the file.\n";
            }

            if (!isset($file_names_arr)) {
                $file_names_arr=array();
            }

            return array_values(
                array_filter(
                    array_map(
                        array($this, $iteration_method),
                        $file_names_arr
                    )
                )
            );
            //end TRP 12/27/13
            //return array(); //TRP 12/27/13
        }
        return array_values(
            array_filter(
                array_map(
                    array($this, $iteration_method),
                    scandir($upload_dir)
                )
            )
        );
    }

    protected function countFileObjects()
    {
        return count($this->getFileObjects('isValidFileObject'));
    }

    protected function getErrorMessage($error)
    {
        return array_key_exists($error, $this->error_messages) ?
            $this->error_messages[$error] : $error;
    }

    public function getConfigBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        // Change PoP: remove the last character from $val: $val is '8M' and not '8', so doing $val *= 1024; below throws a PHP Notice starting with PHP 7.1:
        // A non well formed numeric value encountered in /var/app/current/wp-content/plugins/pop-useravatar-aws/directaccess-library/fileupload-userphoto/server/uploadHandlerS3.php on line 452, referer: https://my.tppdebate.org/en/change-pic/
        $val = substr($val, 0, strlen($val)-1);
        switch ($last) {
            case 'g':
                $val *= 1024;
                // no break
            case 'm':
                $val *= 1024;
                // no break
            case 'k':
                $val *= 1024;
        }
        return $this->fixIntegerOverflow($val);
    }

    protected function validate($uploaded_file, $file, $error, $index)
    {
        if ($error) {
            $file->error = $this->getErrorMessage($error);
            return false;
        }
        $content_length = $this->fixIntegerOverflow(
            intval(
                $this->getServerVar('CONTENT_LENGTH')
            )
        );
        $post_max_size = $this->getConfigBytes(ini_get('post_max_size'));
        if ($post_max_size && ($content_length > $post_max_size)) {
            $file->error = $this->getErrorMessage('post_max_size');
            return false;
        }
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            $file->error = $this->getErrorMessage('accept_file_types');
            return false;
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = $this->getFileSize($uploaded_file);
        } else {
            $file_size = $content_length;
        }
        if ($this->options['max_file_size'] && ($file_size > $this->options['max_file_size']
            || $file->size > $this->options['max_file_size'])
        ) {
            $file->error = $this->getErrorMessage('max_file_size');
            return false;
        }
        if ($this->options['min_file_size']
            && $file_size < $this->options['min_file_size']
        ) {
            $file->error = $this->getErrorMessage('min_file_size');
            return false;
        }
        if (is_int($this->options['max_number_of_files']) && ($this->countFileObjects() >= $this->options['max_number_of_files'])
        ) {
            $file->error = $this->getErrorMessage('max_number_of_files');
            return false;
        }
        $max_width = @$this->options['max_width'];
        $max_height = @$this->options['max_height'];
        $min_width = @$this->options['min_width'];
        $min_height = @$this->options['min_height'];
        if (($max_width || $max_height || $min_width || $min_height)) {
            list($img_width, $img_height) = $this->getImageSize($uploaded_file);
        }
        if (!empty($img_width)) {
            if ($max_width && $img_width > $max_width) {
                $file->error = $this->getErrorMessage('max_width');
                return false;
            }
            if ($max_height && $img_height > $max_height) {
                $file->error = $this->getErrorMessage('max_height');
                return false;
            }
            if ($min_width && $img_width < $min_width) {
                $file->error = $this->getErrorMessage('min_width');
                return false;
            }
            if ($min_height && $img_height < $min_height) {
                $file->error = $this->getErrorMessage('min_height');
                return false;
            }
        }
        return true;
    }

    protected function upcountNameCallback($matches)
    {
        $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';
        return ' ('.$index.')'.$ext;
    }

    protected function upcountName($name)
    {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
            array($this, 'upcountNameCallback'),
            $name,
            1
        );
    }

    protected function getUniqueFilename(
        $file_path,
        $name,
        $size,
        $type,
        $error,
        $index,
        $content_range
    ) {
        while (is_dir($this->getUploadPath($name))) {
            $name = $this->upcountName($name);
        }
        // Keep an existing filename if this is part of a chunked upload:
        $uploaded_bytes = $this->fixIntegerOverflow(intval($content_range[1]));
        while (is_file($this->getUploadPath($name))) {
            if ($uploaded_bytes === $this->getFileSize(
                $this->getUploadPath($name)
            )
            ) {
                break;
            }
            $name = $this->upcountName($name);
        }
        return $name;
    }

    protected function trimFileName(
        $file_path,
        $name,
        $size,
        $type,
        $error,
        $index,
        $content_range
    ) {
        // Remove path information and dots around the filename, to prevent uploading
        // into different directories or replacing hidden system files.
        // Also remove control characters and spaces (\x00..\x20) around the filename:
        $name = trim(basename(stripslashes($name)), ".\x00..\x20");

        // Change PoP: it is not really replacing spaces in the name, so do it now
        $name = str_replace(' ', '-', $name);

        // Use a timestamp for empty filenames:
        if (!$name) {
            $name = str_replace('.', '-', microtime(true));
        }
        // Add missing file extension for known image types:
        if (strpos($name, '.') === false
            && preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches)
        ) {
            $name .= '.'.$matches[1];
        }
        if (function_exists('exif_imagetype')) {
            switch (exif_imagetype($file_path)) {
                case IMAGETYPE_JPEG:
                    $extensions = array('jpg', 'jpeg');
                    break;
                case IMAGETYPE_PNG:
                    $extensions = array('png');
                    break;
                case IMAGETYPE_GIF:
                    $extensions = array('gif');
                    break;
            }
            // Adjust incorrect image file extensions:
            if (!empty($extensions)) {
                $parts = explode('.', $name);
                $extIndex = count($parts) - 1;
                // Change PoP: if the filename ext is in uppercase (eg: JPG) transform to lowercase, because somehow uploading to AWS S3 will transform it to lowercase, so standardize
                // $ext = strtolower(@$parts[$extIndex]);
                $ext = @$parts[$extIndex];
                if (!in_array($ext, $extensions)) {
                    $parts[$extIndex] = $extensions[0];
                    $name = implode('.', $parts);
                }
            }
        }
        return $name;
    }

    protected function getFileName(
        $file_path,
        $name,
        $size,
        $type,
        $error,
        $index,
        $content_range
    ) {
        return $this->getUniqueFilename(
            $file_path,
            $this->trimFileName(
                $file_path,
                $name,
                $size,
                $type,
                $error,
                $index,
                $content_range
            ),
            $size,
            $type,
            $error,
            $index,
            $content_range
        );
    }

    protected function handleFormData($file, $index)
    {
        // Handle form data, e.g. $_GET['description'][$index]
    }

    protected function getScaledImageFilePaths($file_name, $version)
    {
        $file_path = $this->getUploadPath($file_name);
        if (!empty($version)) {
            $version_dir = $this->getUploadPath(null, $version);
            if (!is_dir($version_dir)) {
                mkdir($version_dir, $this->options['mkdir_mode'], true);
            }
            //$new_file_path = $version_dir.'/'.$file_name; TRP 122713
            $new_file_path = $version_dir.$file_name; //TRP 122713 b/c double slash
        } else {
            $new_file_path = $file_path;
        }

        return array($file_path, $new_file_path);
    }

    protected function gdGetImageObject($file_path, $func, $no_cache = false)
    {
        if (empty($this->image_objects[$file_path]) || $no_cache) {
            $this->gdDestroyImageObject($file_path);
            $this->image_objects[$file_path] = $func($file_path);
        }
        return $this->image_objects[$file_path];
    }

    protected function gdSetImageObject($file_path, $image)
    {
        $this->gdDestroyImageObject($file_path);
        $this->image_objects[$file_path] = $image;
    }

    protected function gdDestroyImageObject($file_path)
    {
        $image = @$this->image_objects[$file_path];
        return $image && imagedestroy($image);
    }

    protected function gdImageflip($image, $mode)
    {
        if (function_exists('imageflip')) {
            return imageflip($image, $mode);
        }
        $new_width = $src_width = imagesx($image);
        $new_height = $src_height = imagesy($image);
        $new_img = imagecreatetruecolor($new_width, $new_height);
        $src_x = 0;
        $src_y = 0;
        switch ($mode) {
            case '1': // flip on the horizontal axis
                $src_y = $new_height - 1;
                $src_height = -$new_height;
                break;
            case '2': // flip on the vertical axis
                $src_x  = $new_width - 1;
                $src_width = -$new_width;
                break;
            case '3': // flip on both axes
                $src_y = $new_height - 1;
                $src_height = -$new_height;
                $src_x  = $new_width - 1;
                $src_width = -$new_width;
                break;
            default:
                return $image;
        }
        imagecopyresampled(
            $new_img,
            $image,
            0,
            0,
            $src_x,
            $src_y,
            $new_width,
            $new_height,
            $src_width,
            $src_height
        );
        return $new_img;
    }

    protected function gdOrientImage($file_path, $src_img)
    {
        if (!function_exists('exif_read_data')) {
            return false;
        }
        $exif = @exif_read_data($file_path);
        if ($exif === false) {
            return false;
        }
        $orientation = intval(@$exif['Orientation']);
        if ($orientation < 2 || $orientation > 8) {
            return false;
        }
        switch ($orientation) {
            case 2:
                $new_img = $this->gdImageflip(
                    $src_img,
                    defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
                );
                break;
            case 3:
                $new_img = imagerotate($src_img, 180, 0);
                break;
            case 4:
                $new_img = $this->gdImageflip(
                    $src_img,
                    defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
                );
                break;
            case 5:
                $tmp_img = $this->gdImageflip(
                    $src_img,
                    defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
                );
                $new_img = imagerotate($tmp_img, 270, 0);
                imagedestroy($tmp_img);
                break;
            case 6:
                $new_img = imagerotate($src_img, 270, 0);
                break;
            case 7:
                $tmp_img = $this->gdImageflip(
                    $src_img,
                    defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
                );
                $new_img = imagerotate($tmp_img, 270, 0);
                imagedestroy($tmp_img);
                break;
            case 8:
                $new_img = imagerotate($src_img, 90, 0);
                break;
            default:
                return false;
        }
        $this->gdSetImageObject($file_path, $new_img);
        return true;
    }

    protected function gdCreateScaledImage($file_name, $version, $options)
    {
        if (!function_exists('imagecreatetruecolor')) {
            error_log('Function not found: imagecreatetruecolor');
            return false;
        }

        list($file_path, $new_file_path) =
            $this->getScaledImageFilePaths($file_name, $version);
        $type = strtolower(substr(strrchr($file_name, '.'), 1));
        switch ($type) {
            case 'jpg':
            case 'jpeg':
                $src_func = 'imagecreatefromjpeg';
                $write_func = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                $options['jpeg_quality'] : 75;
                break;
            case 'gif':
                $src_func = 'imagecreatefromgif';
                $write_func = 'imagegif';
                $image_quality = null;
                break;
            case 'png':
                $src_func = 'imagecreatefrompng';
                $write_func = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                $options['png_quality'] : 9;
                break;
            default:
                return false;
        }
        $src_img = $this->gdGetImageObject(
            $file_path,
            $src_func,
            !empty($options['no_cache'])
        );
        $image_oriented = false;
        if (!empty($options['auto_orient']) && $this->gdOrientImage(
            $file_path,
            $src_img
        )
        ) {
            $image_oriented = true;
            $src_img = $this->gdGetImageObject(
                $file_path,
                $src_func
            );
        }
        $max_width = $img_width = imagesx($src_img);
        $max_height = $img_height = imagesy($src_img);
        if (!empty($options['max_width'])) {
            $max_width = $options['max_width'];
        }
        if (!empty($options['max_height'])) {
            $max_height = $options['max_height'];
        }
        $scale = min(
            $max_width / $img_width,
            $max_height / $img_height
        );
        // Change PoP: Originally the condition below is:
        // if ($scale >= 1) {
        // But then, it doesn't work generating the first avatar with image size 150,
        // which is the same as as the "thumb"
        if ($scale > 1) {
            // Change PoP: the copied image does not have the right ACL, so fix it here
            // if ($image_oriented) {
            //     return $write_func($src_img, $new_file_path, $image_quality);
            // }
            // if ($file_path !== $new_file_path) {
            //     return copy($file_path, $new_file_path);
            // }

            //$ret = false;
            if ($image_oriented) {
                // $ret = $write_func($src_img, $new_file_path, $image_quality);
                return $write_func($src_img, $new_file_path, $image_quality);
            }
            if ($file_path !== $new_file_path) {
                // Comment Leo 09/01/2018: because AWS SDK doesn't allow 'CacheControl' in function `putObjectAcl` anymore, then pass the cacheControl through a StreamWrapper to function copy
                $context = stream_context_create(
                    array(
                        's3' => array(
                            'ACL' => 'public-read', // this code doesn't work: //\AmazonS3::ACL_PUBLIC,
                            // 'ContentType' => $this->getFileType($file_path),

                            // Change PoP: add also cache-control max age: 1 month
                            'CacheControl' => 'max-age=2592000, must-revalidate',
                        )
                    )
                );

                // $ret = copy($file_path, $new_file_path, $context);
                return copy($file_path, $new_file_path, $context);
            }
            // if ($ret) {

            //     // Re-create the key by substracting the s3 domain from the new_file_path
            //     $key = substr($new_file_path, strlen('s3://'.$this->workingBucket.'/'));
            //     $this->s3->putObjectAcl(array(
            //       'ACL' => 'public-read',
            //       'Bucket' => $this->workingBucket,
            //       'Key' => $this->workingBucketPrefix.'/'.$key,

            //         // // Change PoP: add also cache-control max age: 1 month
            //         // 'CacheControl' => 'max-age=2592000, must-revalidate',
            //     ));
            // }

            return true;
        }
        if (empty($options['crop'])) {
            $new_width = $img_width * $scale;
            $new_height = $img_height * $scale;
            $dst_x = 0;
            $dst_y = 0;
            $new_img = imagecreatetruecolor($new_width, $new_height);
        } else {
            if (($img_width / $img_height) >= ($max_width / $max_height)) {
                $new_width = $img_width / ($img_height / $max_height);
                $new_height = $max_height;
            } else {
                $new_width = $max_width;
                $new_height = $img_height / ($img_width / $max_width);
            }
            $dst_x = 0 - ($new_width - $max_width) / 2;
            $dst_y = 0 - ($new_height - $max_height) / 2;
            $new_img = imagecreatetruecolor($max_width, $max_height);
        }
        // Handle transparency in GIF and PNG images:
        switch ($type) {
            case 'gif':
            case 'png':
                imagecolortransparent($new_img, imagecolorallocate($new_img, 0, 0, 0));
                // no break
            case 'png':
                imagealphablending($new_img, false);
                imagesavealpha($new_img, true);
                break;
        }

        // Change PoP: the code below fails to upload to S3
        // Hack provided in https://gist.github.com/tim-peterson/8172999 (cassianotartari commented on Sep 25, 2014)
        // $success = imagecopyresampled(
        //     $new_img,
        //     $src_img,
        //     $dst_x,
        //     $dst_y,
        //     0,
        //     0,
        //     $new_width,
        //     $new_height,
        //     $img_width,
        //     $img_height
        // ) && $write_func($new_img, $new_file_path, $image_quality);
        // $this->gdSetImageObject($file_path, $new_img);
        // return $success;

        $success = imagecopyresampled(
            $new_img,
            $src_img,
            $dst_x,
            $dst_y,
            0,
            0,
            $new_width,
            $new_height,
            $img_width,
            $img_height
        );

        ob_start();
        $result = $write_func($new_img, null, $image_quality);
        if ($result) {
            $contents = ob_get_contents();
            ob_end_clean();
        }
        $this->gdSetImageObject($file_path, $new_img);

        $context = stream_context_create(
            array(
                's3' => array(
                    'ACL' => 'public-read', // this code doesn't work: //\AmazonS3::ACL_PUBLIC,
                    'ContentType' => $this->getFileType($file_path),

                    // Change PoP: add also cache-control max age: 1 month
                    'CacheControl' => 'max-age=2592000, must-revalidate',
                )
            )
        );

        return file_put_contents($new_file_path, $contents, null, $context);
    }

    protected function imagickGetImageObject($file_path, $no_cache = false)
    {
        if (empty($this->image_objects[$file_path]) || $no_cache) {
            $this->imagickDestroyImageObject($file_path);
            $image = new Imagick();
            if (!empty($this->options['imagick_resource_limits'])) {
                foreach ($this->options['imagick_resource_limits'] as $type => $limit) {
                    $image->setResourceLimit($type, $limit);
                }
            }
            $image->readImage($file_path);
            $this->image_objects[$file_path] = $image;
        }
        return $this->image_objects[$file_path];
    }

    protected function imagickSetImageObject($file_path, $image)
    {
        $this->imagickDestroyImageObject($file_path);
        $this->image_objects[$file_path] = $image;
    }

    protected function imagickDestroyImageObject($file_path)
    {
        $image = @$this->image_objects[$file_path];
        return $image && $image->destroy();
    }

    protected function imagickOrientImage($image)
    {
        $orientation = $image->getImageOrientation();
        $background = new ImagickPixel('none');
        switch ($orientation) {
            case imagick::ORIENTATION_TOPRIGHT: // 2
                $image->flopImage(); // horizontal flop around y-axis
                break;
            case imagick::ORIENTATION_BOTTOMRIGHT: // 3
                $image->rotateImage($background, 180);
                break;
            case imagick::ORIENTATION_BOTTOMLEFT: // 4
                $image->flipImage(); // vertical flip around x-axis
                break;
            case imagick::ORIENTATION_LEFTTOP: // 5
                $image->flopImage(); // horizontal flop around y-axis
                $image->rotateImage($background, 270);
                break;
            case imagick::ORIENTATION_RIGHTTOP: // 6
                $image->rotateImage($background, 90);
                break;
            case imagick::ORIENTATION_RIGHTBOTTOM: // 7
                $image->flipImage(); // vertical flip around x-axis
                $image->rotateImage($background, 270);
                break;
            case imagick::ORIENTATION_LEFTBOTTOM: // 8
                $image->rotateImage($background, 270);
                break;
            default:
                return false;
        }
        $image->setImageOrientation(imagick::ORIENTATION_TOPLEFT); // 1
        return true;
    }

    protected function imagickCreateScaledImage($file_name, $version, $options)
    {
        list($file_path, $new_file_path) =
            $this->getScaledImageFilePaths($file_name, $version);
        $image = $this->imagickGetImageObject(
            $file_path,
            !empty($options['no_cache'])
        );
        if ($image->getImageFormat() === 'GIF') {
            // Handle animated GIFs:
            $images = $image->coalesceImages();
            foreach ($images as $frame) {
                $image = $frame;
                $this->imagickSetImageObject($file_name, $image);
                break;
            }
        }
        $image_oriented = false;
        if (!empty($options['auto_orient'])) {
            $image_oriented = $this->imagickOrientImage($image);
        }
        $new_width = $max_width = $img_width = $image->getImageWidth();
        $new_height = $max_height = $img_height = $image->getImageHeight();
        if (!empty($options['max_width'])) {
            $new_width = $max_width = $options['max_width'];
        }
        if (!empty($options['max_height'])) {
            $new_height = $max_height = $options['max_height'];
        }
        if (!($image_oriented || $max_width < $img_width || $max_height < $img_height)) {
            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path);
            }
            return true;
        }
        $crop = !empty($options['crop']);
        if ($crop) {
            $x = 0;
            $y = 0;
            if (($img_width / $img_height) >= ($max_width / $max_height)) {
                $new_width = 0; // Enables proportional scaling based on max_height
                $x = ($img_width / ($img_height / $max_height) - $max_width) / 2;
            } else {
                $new_height = 0; // Enables proportional scaling based on max_width
                $y = ($img_height / ($img_width / $max_width) - $max_height) / 2;
            }
        }
        $success = $image->resizeImage(
            $new_width,
            $new_height,
            isset($options['filter']) ? $options['filter'] : imagick::FILTER_LANCZOS,
            isset($options['blur']) ? $options['blur'] : 1,
            $new_width && $new_height // fit image into constraints if not to be cropped
        );
        if ($success && $crop) {
            $success = $image->cropImage(
                $max_width,
                $max_height,
                $x,
                $y
            );
            if ($success) {
                $success = $image->setImagePage($max_width, $max_height, 0, 0);
            }
        }
        $type = strtolower(substr(strrchr($file_name, '.'), 1));
        switch ($type) {
            case 'jpg':
            case 'jpeg':
                if (!empty($options['jpeg_quality'])) {
                    $image->setImageCompression(Imagick::COMPRESSION_JPEG);
                    $image->setImageCompressionQuality($options['jpeg_quality']);
                }
                break;
        }
        if (!empty($options['strip'])) {
            $image->stripImage();
        }
        return $success && $image->writeImage($new_file_path);
    }

    protected function imagemagickCreateScaledImage($file_name, $version, $options)
    {
        list($file_path, $new_file_path) =
            $this->getScaledImageFilePaths($file_name, $version);
        $resize = @$options['max_width']
            .(empty($options['max_height']) ? '' : 'x'.$options['max_height']);
        if (!$resize && empty($options['auto_orient'])) {
            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path);
            }
            return true;
        }
        $cmd = $this->options['convert_bin'];
        if (!empty($this->options['convert_params'])) {
            $cmd .= ' '.$this->options['convert_params'];
        }
        $cmd .= ' '.escapeshellarg($file_path);
        if (!empty($options['auto_orient'])) {
            $cmd .= ' -auto-orient';
        }
        if ($resize) {
            // Handle animated GIFs:
            $cmd .= ' -coalesce';
            if (empty($options['crop'])) {
                $cmd .= ' -resize '.escapeshellarg($resize.'>');
            } else {
                $cmd .= ' -resize '.escapeshellarg($resize.'^');
                $cmd .= ' -gravity center';
                $cmd .= ' -crop '.escapeshellarg($resize.'+0+0');
            }
            // Make sure the page dimensions are correct (fixes offsets of animated GIFs):
            $cmd .= ' +repage';
        }
        if (!empty($options['convert_params'])) {
            $cmd .= ' '.$options['convert_params'];
        }
        $cmd .= ' '.escapeshellarg($new_file_path);
        exec($cmd, $output, $error);
        if ($error) {
            error_log(implode('\n', $output));
            return false;
        }
        return true;
    }

    protected function getImageSize($file_path)
    {
        if ($this->options['image_library']) {
            if (extension_loaded('imagick')) {
                $image = new Imagick();
                try {
                    if (@$image->pingImage($file_path)) {
                        $dimensions = array($image->getImageWidth(), $image->getImageHeight());
                        $image->destroy();
                        return $dimensions;
                    }
                    return false;
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }
            if ($this->options['image_library'] === 2) {
                $cmd = $this->options['identify_bin'];
                $cmd .= ' -ping '.escapeshellarg($file_path);
                exec($cmd, $output, $error);
                if (!$error && !empty($output)) {
                    // image.jpg JPEG 1920x1080 1920x1080+0+0 8-bit sRGB 465KB 0.000u 0:00.000
                    $infos = preg_split('/\s+/', $output[0]);
                    $dimensions = preg_split('/x/', $infos[2]);
                    return $dimensions;
                }
                return false;
            }
        }
        if (!function_exists('getimagesize')) {
            error_log('Function not found: getimagesize');
            return false;
        }
        return @getimagesize($file_path);
    }

    protected function createScaledImage($file_name, $version, $options)
    {
        if ($this->options['image_library'] === 2) {
            return $this->imagemagickCreateScaledImage($file_name, $version, $options);
        }
        if ($this->options['image_library'] && extension_loaded('imagick')) {
            return $this->imagickCreateScaledImage($file_name, $version, $options);
        }
        return $this->gdCreateScaledImage($file_name, $version, $options);
    }

    protected function destroyImageObject($file_path)
    {
        if ($this->options['image_library'] && extension_loaded('imagick')) {
            return $this->imagickDestroyImageObject($file_path);
        }
    }

    protected function isValidImageFile($file_path)
    {
        if (!preg_match($this->options['image_file_types'], $file_path)) {
            return false;
        }
        if (function_exists('exif_imagetype')) {
            return @exif_imagetype($file_path);
        }
        $image_info = $this->getImageSize($file_path);
        return $image_info && $image_info[0] && $image_info[1];
    }

    protected function handleImageFile($file_path, $file)
    {
        $failed_versions = array();
        foreach ($this->options['image_versions'] as $version => $options) {
            if ($this->createScaledImage($file->name, $version, $options)) {
                if (!empty($version)) {
                    $file->{$version.'Url'} = $this->getDownloadUrl(
                        $file->name,
                        $version
                    );
                } else {
                    $file->size = $this->getFileSize($file_path, true);
                }
            } else {
                $failed_versions[] = $version;
            }
        }
        switch (count($failed_versions)) {
            case 0:
                break;
            case 1:
                $file->error = 'Failed to create scaled version: '
                .$failed_versions[0];
                break;
            default:
                $file->error = 'Failed to create scaled versions: '
                .implode($failed_versions, ', ');
        }
        // Free memory:
        $this->destroyImageObject($file_path);
    }

    protected function handleFileUpload(
        $uploaded_file,
        $name,
        $size,
        $type,
        $error,
        $index = null,
        $content_range = null
    ) {
        $file = new stdClass();
        $file->name = $this->getFileName(
            $uploaded_file,
            $name,
            $size,
            $type,
            $error,
            $index,
            $content_range
        );

        $file->size = $this->fixIntegerOverflow(intval($size));
        $file->type = $type;
        if ($this->validate($uploaded_file, $file, $error, $index)) {
            $this->handleFormData($file, $index);
            $upload_dir = $this->getUploadPath();
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, $this->options['mkdir_mode'], true);
            }
            $file_path = $this->getUploadPath($file->name);

            $append_file = $content_range && is_file($file_path) &&
                $file->size > $this->getFileSize($file_path);
            if ($uploaded_file && is_uploaded_file($uploaded_file) || (isset($_POST['fileSourceChooser']) && $_POST['fileSourceChooser']=='dropbox')) {
                // multipart/formdata uploads (POST method uploads)

                //print_r($uploaded_file);

                if ($append_file || (isset($_POST['fileSourceChooser']) && $_POST['fileSourceChooser']=='dropbox')) {
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploaded_file, $file_path);

                    // Change PoP: the file uploaded has ACL set as private. Make it 'public-read'
                    // Taken from https://gist.github.com/tim-peterson/8172999 (adc-tcarvalho commented on Sep 23, 2014)
                    $key = $this->workingBucketPrefix.'/'.$this->originalprefix . basename($file_path);
                    $this->s3->putObjectAcl(
                        array(
                            'ACL' => 'public-read',
                            'Bucket' => $this->workingBucket,
                            'Key' => $key,

                            // // Change PoP: add also cache-control max age: 1 month
                            // 'CacheControl' => 'max-age=2592000, must-revalidate',
                        )
                    );
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $file_path,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
            }
            $file_size = $this->getFileSize($file_path, $append_file);
            if ($file_size === $file->size) {
                $file->url = $this->getDownloadUrl($file->name);
                if ($this->isValidImageFile($file_path)) {
                    //$file_path='/private/var/tmp';
                    //$file_path=$uploaded_file; //TRP 122713
                    $this->handleImageFile($file_path, $file);
                }
            } else {
                $file->size = $file_size;
                if (!$content_range && $this->options['discard_aborted_uploads']) {
                    unlink($file_path);
                    $file->error = 'abort';
                }
            }
            $this->setAdditionalFileProperties($file);
        }

        return $file;
    }

    protected function readfile($file_path)
    {
        $file_size = $this->getFileSize($file_path);
        $chunk_size = $this->options['readfile_chunk_size'];
        if ($chunk_size && $file_size > $chunk_size) {
            $handle = fopen($file_path, 'rb');
            while (!feof($handle)) {
                echo fread($handle, $chunk_size);
                ob_flush();
                flush();
            }
            fclose($handle);
            return $file_size;
        }
        return readfile($file_path);
    }

    protected function body($str)
    {
        echo $str;
    }
    
    protected function header($str)
    {
        header($str);
    }

    protected function getServerVar($id)
    {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }

    protected function generateResponse($content, $print_response = true)
    {
        if ($print_response) {
            $json = json_encode($content);
            $redirect = isset($_GET['redirect']) ?
                stripslashes($_GET['redirect']) : null;
            if ($redirect) {
                $this->header('Location: '.sprintf($redirect, rawurlencode($json)));
                return;
            }
            $this->head();
            if ($this->getServerVar('HTTP_CONTENT_RANGE')) {
                $files = isset($content[$this->options['param_name']]) ?
                    $content[$this->options['param_name']] : null;
                if ($files && is_array($files) && is_object($files[0]) && $files[0]->size) {
                    $this->header(
                        'Range: 0-'.(
                        $this->fixIntegerOverflow(intval($files[0]->size)) - 1
                        )
                    );
                }
            }
            $this->body($json);
        }
        return $content;
    }

    protected function getVersionParam()
    {
        return isset($_GET['version']) ? basename(stripslashes($_GET['version'])) : null;
    }

    protected function getSingularParamName()
    {
        return substr($this->options['param_name'], 0, -1);
    }

    protected function getFileNameParam()
    {
        $name = $this->getSingularParamName();
        return isset($_GET[$name]) ? basename(stripslashes($_GET[$name])) : null;
    }

    protected function getFileNamesParams()
    {
        $params = isset($_GET[$this->options['param_name']]) ?
            $_GET[$this->options['param_name']] : array();
        foreach ($params as $key => $value) {
            $params[$key] = basename(stripslashes($value));
        }
        return $params;
    }

    protected function getFileType($file_path)
    {
        switch (strtolower(pathinfo($file_path, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            default:
                return '';
        }
    }

    protected function download()
    {
        switch ($this->options['download_via_php']) {
            case 1:
                $redirect_header = null;
                break;
            case 2:
                $redirect_header = 'X-Sendfile';
                break;
            case 3:
                $redirect_header = 'X-Accel-Redirect';
                break;
            default:
                return $this->header('HTTP/1.1 403 Forbidden');
        }
        $file_name = $this->getFileNameParam();
        if (!$this->isValidFileObject($file_name)) {
            return $this->header('HTTP/1.1 404 Not Found');
        }
        if ($redirect_header) {
            return $this->header(
                $redirect_header.': '.$this->getDownloadUrl(
                    $file_name,
                    $this->getVersionParam(),
                    true
                )
            );
        }
        $file_path = $this->getUploadPath($file_name, $this->getVersionParam());
        // Prevent browsers from MIME-sniffing the content-type:
        $this->header('X-Content-Type-Options: nosniff');
        if (!preg_match($this->options['inline_file_types'], $file_name)) {
            $this->header('Content-Type: application/octet-stream');
            $this->header('Content-Disposition: attachment; filename="'.$file_name.'"');
        } else {
            $this->header('Content-Type: '.$this->getFileType($file_path));
            $this->header('Content-Disposition: inline; filename="'.$file_name.'"');
        }
        $this->header('Content-Length: '.$this->getFileSize($file_path));
        $this->header('Last-Modified: '.gmdate('D, d M Y H:i:s T', filemtime($file_path)));
        $this->readfile($file_path);
    }

    protected function sendContentTypeHeader()
    {
        $this->header('Vary: Accept');
        if (strpos($this->getServerVar('HTTP_ACCEPT'), 'application/json') !== false) {
            $this->header('Content-type: application/json');
        } else {
            $this->header('Content-type: text/plain');
        }
    }

    protected function sendAccessControlHeaders()
    {
        $this->header('Access-Control-Allow-Origin: '.$this->options['access_control_allow_origin']);
        $this->header(
            'Access-Control-Allow-Credentials: '
            .($this->options['access_control_allow_credentials'] ? 'true' : 'false')
        );
        $this->header(
            'Access-Control-Allow-Methods: '
            .implode(', ', $this->options['access_control_allow_methods'])
        );
        $this->header(
            'Access-Control-Allow-Headers: '
            .implode(', ', $this->options['access_control_allow_headers'])
        );
    }

    public function head()
    {
        $this->header('Pragma: no-cache');
        $this->header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->header('Content-Disposition: inline; filename="files.json"');
        // Prevent Internet Explorer from MIME-sniffing the content-type:
        $this->header('X-Content-Type-Options: nosniff');
        if ($this->options['access_control_allow_origin']) {
            $this->sendAccessControlHeaders();
        }
        $this->sendContentTypeHeader();
    }

    public function get($print_response = true)
    {
        if ($print_response && isset($_GET['download'])) {
            return $this->download();
        }
        $file_name = $this->getFileNameParam();
        if ($file_name) {
            $response = array(
                $this->getSingularParamName() => $this->getFileObject($file_name)
            );
        } else {
            $response = array(
                $this->options['param_name'] => $this->getFileObjects()
            );
        }
        return $this->generateResponse($response, $print_response);
    }

    public function post($print_response = true)
    {
        if (isset($_GET['_method']) && $_GET['_method'] === 'DELETE') {
            return $this->delete($print_response);
        }
        $upload = isset($_FILES[$this->options['param_name']]) ?
            $_FILES[$this->options['param_name']] : null;
        // Parse the Content-Disposition header, if available:
        $file_name = $this->getServerVar('HTTP_CONTENT_DISPOSITION') ?
            rawurldecode(
                preg_replace(
                    '/(^[^"]+")|("$)/',
                    '',
                    $this->getServerVar('HTTP_CONTENT_DISPOSITION')
                )
            ) : null;
        // Parse the Content-Range header, which has the following form:
        // Content-Range: bytes 0-524287/2000000
        $content_range = $this->getServerVar('HTTP_CONTENT_RANGE') ?
            preg_split('/[^0-9]+/', $this->getServerVar('HTTP_CONTENT_RANGE')) : null;
        $size =  $content_range ? $content_range[3] : null;
        $files = array();
        if ($upload && is_array($upload['tmp_name'])) {
            // param_name is an array identifier like "files[]",
            // $_FILES is a multi-dimensional array:
            foreach ($upload['tmp_name'] as $index => $value) {
                $files[] = $this->handleFileUpload(
                    $upload['tmp_name'][$index],
                    $file_name ? $file_name : $upload['name'][$index],
                    $size ? $size : $upload['size'][$index],
                    $upload['type'][$index],
                    $upload['error'][$index],
                    $index,
                    $content_range
                );
            }
        } else {
            if (isset($_POST['fileSourceChooser']) && $_POST['fileSourceChooser']=='dropbox') {
                //http://justinvincent.com/page/1087/how-to-get-the-mime-type-of-a-remote-file-in-php-with-redirects
                function getUrlMimeType($url)
                {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_HEADER, 1);
                    curl_setopt($ch, CURLOPT_NOBODY, 1);
                    curl_exec($ch);
                    return curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                }


                $upload=$_POST['files']; ////if just a regular post

                $upload['parse_url']=parse_url($upload['link']);
                $upload['url']='https://dl.dropboxusercontent.com'.$upload['parse_url']['path'];

                $files[] = $this->handleFileUpload(
                    $upload['url'],
                    $file_name ? $file_name : $upload['name'],
                    $size ? $size : $upload['bytes'],
                    getUrlMimeType($upload['url']),
                    "",
                    null,
                    null,
                    $content_range
                );
              

                file_put_contents(
                    $this->options['upload_dir'].'thumbnail/'.$upload['name'],
                    fopen($upload['thumbnail'], 'r'),
                    FILE_APPEND //$append_file ? FILE_APPEND : 0
                );
            } else {
                // param_name is a single object identifier like "file",
                // $_FILES is a one-dimensional array:
                $files[] = $this->handleFileUpload(
                    isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
                    $file_name ? $file_name : (isset($upload['name']) ?
                            $upload['name'] : null),
                    $size ? $size : (isset($upload['size']) ?
                            $upload['size'] : $this->getServerVar('CONTENT_LENGTH')),
                    isset($upload['type']) ?
                            $upload['type'] : $this->getServerVar('CONTENT_TYPE'),
                    isset($upload['error']) ? $upload['error'] : null,
                    null,
                    $content_range
                );
            }
        }

        return $this->generateResponse(
            array($this->options['param_name'] => $files),
            $print_response
        );
    }

    public function delete($print_response = true)
    {
        $file_names = $this->getFileNamesParams();
        if (empty($file_names)) {
            $file_names = array($this->getFileNameParam());
        }
        $response = array();

        foreach ($file_names as $file_name) {
            $file_path = $this->getUploadPath($file_name);

            $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
            if ($success) {
                foreach ($this->options['image_versions'] as $version => $options) {
                    if (!empty($version)) {
                        $file = $this->getUploadPath($file_name, $version);
                        if (is_file($file)) {
                            unlink($file);
                        }
                    }
                }
            }
            $response[$file_name] = $success;
        }

        // Delete the file structure from S3
        // This is needed so that the resource "851/thumb" is not retrieved as a S3 object
        try {
            // First delete all existing objects in the destination bucket
            $this->s3->deleteMatchingObjects(
                $this->workingBucket,
                $this->workingBucketPrefix.'/'.$this->prefix
            );
        } catch (/*S3Exception*/Exception $e) {
            // $data['status']=0;
            // $data['message']=$e;
        }
        return $this->generateResponse($response, $print_response);
    }
}
