<?php
use Aws\Common\Aws;
use PoP\ComponentModel\Misc\GeneralUtils;


// use Aws\S3\Exception\S3Exception;

class PoP_Avatar_AWSFunctions
{
    protected $s3;

    public function __construct()
    {

        // Make the AWS functions take over the original ones
        \PoP\Root\App::addFilter(
            'popcomponent:avatar:avatarexists',
            array($this, 'avatarExists'),
            10,
            3
        );
        \PoP\Root\App::addAction(
            'popcomponent:avatar:avataruploaded',
            array($this, 'uploadToS3'),
            10,
            3
        );
        \PoP\Root\App::addFilter(
            'gdGetAvatarPhotoinfo:override',
            array($this, 'getPhotoInfo'),
            10,
            3
        );

        // Register the AWS S3 domain in the Allowed Domains list
        \PoP\Root\App::addFilter(
            'pop_modulemanager:allowed_domains',
            array($this, 'getAllowedDomains')
        );
    }

    public function avatarExists($exists, $id, $params)
    {
        $size = $params['size'];
        $source = $params['source'];

        // If using meta, then simply check if the meta exists
        $pluginapi = PoP_Avatar_FunctionsAPIFactory::getInstance();
        if ($pluginapi->useMeta()) {
            if ($filename = $pluginapi->getFilename($id)) {
                // If the size is among the list of avatars generated, then return that one
                if ($size && in_array($size, $pluginapi->getAvatarSizes())) {
                    return array(
                        'url' => $this->getBucketUrl().POP_AVATAR_AWS_USERPHOTO_PATH."{$id}/avatars/{$size}/".$filename,
                        'file' => "s3://" . $this->getBucket() . "/".POP_AVATAR_AWS_USERPHOTO_PATH."{$id}/avatars/{$size}/".$filename,
                    );
                }

                // Otherwise, return the generic one
                return array(
                    'url' => $this->getBucketUrl().POP_AVATAR_AWS_USERPHOTO_PATH."{$id}/{$source}/".$filename,
                    'file' => "s3://" . $this->getBucket() . "/".POP_AVATAR_AWS_USERPHOTO_PATH."/{$id}/{$source}/".$filename,
                );
            }

            return false;
        }

        // If not using meta, check if the file actually exists in the bucket

        // First check if the $size is given, if so try to find the avatar with that size
        if ($size) {
            $prefix = POP_AVATAR_AWS_USERPHOTO_PATH."{$id}/avatars/{$size}/";
            if ($ret = $this->listObjects($prefix)) {
                return $ret;
            }
        }

        $prefix = POP_AVATAR_AWS_USERPHOTO_PATH."{$id}/{$source}/";
        if ($ret = $this->listObjects($prefix)) {
            return $ret;
        }

        return false;
    }

    public function uploadToS3($user_id, $folderpath, $filename)
    {
        $this->lazyInit();

        // List all files to copy
        // Taken from https://stackoverflow.com/questions/3826963/php-list-all-files-in-directory
        $files = array();
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderpath)) as $filename) {
            // filter out "." and ".."
            if ($filename->isDir()) {
                continue;
            }
            $files[] = $filename->getPathname();
        }

        try {
            // First delete all existing objects in the destination bucket
            $result = $this->s3->deleteMatchingObjects(
                $this->getBucket(),
                POP_AVATAR_AWS_USERPHOTO_PATH.$user_id.'/'
            );

            // Then copy all the objects from the source to the destination bucket
            foreach ($files as $file) {
                // Remove the folderpath bit from the file
                $relativepath = substr($file, strlen($folderpath));
                $result = $this->s3->putObject(
                    array(
                        'ACL'        => 'public-read',
                        'Bucket'     => $this->getBucket(),
                        'Key'        => POP_AVATAR_AWS_USERPHOTO_PATH.$user_id.'/'.$relativepath,
                        'SourceFile' => $file,
                    )
                );
            }
        } catch (/*S3Exception*/Exception $e) {
            // $data['status']=0;
            // $data['message']=$e;
        }
    }

    public function getPhotoInfo($photoinfo, $user_id, $use_default = true)
    {

        // If the user has no avatar/photo, use the default one
        $pluginapi = PoP_Avatar_FunctionsAPIFactory::getInstance();
        $avatar_data = $pluginapi->userAvatarExists($user_id, array('source' => 'photo'));
        if (!$avatar_data && $use_default) {
            $avatar_data = $pluginapi->userAvatarExists(POP_AVATAR_GENERICAVATARUSER, array('source' => 'photo'));
        }

        $photoinfo = array();
        if ($avatar_data) {
            $photoinfo['src'] = $avatar_data['url'];

            // Initialize the AWS StreamWrapper
            $this->lazyInit();

            // If the image does not exist, AWS will return an exception, so make the operation silent
            // Catch the exception, don't let it explode (eg: it happens if there is no connection to the internet while testing in localhost)
            try {
                $size = @getimagesize($avatar_data['file']);
                $photoinfo['width'] = $size[0];
                $photoinfo['height'] = $size[1];
            } catch (Exception $e) {
                // Do nothing
            }
        }

        return $photoinfo;
    }

    public function getAllowedDomains($allowed_domains)
    {
        $allowed_domains[] = GeneralUtils::getDomain($this->getBucketUrl());
        return $allowed_domains;
    }

    protected function getBucket()
    {
        return POP_AWS_UPLOADSBUCKET;
    }

    protected function getRegion()
    {
        return POP_AWS_REGION;
    }

    protected function lazyInit()
    {
        if (!$this->s3) {
            $this->s3 = Aws::factory(POP_AWS_DIR.'/config/aws-config.php')->get('s3')->registerStreamWrapper();
        }
    }

    public function getBucketUrl()
    {
        $prefix = 's3';
        $region = $this->getRegion();
        // Adding the region in the prefix doesn't work for North Virginia
        if ($region && $region != 'us-east-1') {
            $prefix .= '-'.$region;
        }

        // Use the same scheme as the current request
        $scheme = is_ssl() ? 'https' : 'http';

        // Because we are using https, then we can't use the Bucket name as subdomain path, such as:
        // https://tppdebate‑dev.s3‑ap‑southeast‑1.amazonaws.com/
        // Instead we are using the Bucket name in path option:
        // https://s3‑ap‑southeast‑1.amazonaws.com/tppdebate‑dev/

        // Bucket name as subdomain
        // $domain = $scheme.'://'.$this->getBucket().'.'.$prefix.'.amazonaws.com';

        // Bucket name in path
        $domain = $scheme.'://'.$prefix.'.amazonaws.com/'.$this->getBucket().'/';

        // Allow to override the bucket, to add a CDN host instead (eg: media.tppdebate.org)
        return GeneralUtils::maybeAddTrailingSlash(\PoP\Root\App::applyFilters('PoP_Avatar_AWS:bucket_url', $domain, $this->getBucket(), $region));
    }

    protected function listObjects($prefix)
    {
        $this->lazyInit();

        //http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.S3.S3Client.html#_listObjects
        $result = $this->s3->listObjects(
            array(
                // Bucket is required
                'Bucket' => $this->getBucket(),
                'Prefix' => $prefix
            )
        );

        if (isset($result['Contents']) && count($result['Contents'])>0) {
            foreach ($result['Contents'] as $obj) {
                if ($obj['Key']!=$prefix) { //if Key is a full file path and not just a "directory"
                    return array(
                        'url' => $this->getBucketUrl().$obj['Key'],
                        'file' => 's3://'.$this->getBucket().'/'.$obj['Key'],
                    );
                }
            }
        }
        
        return false;
    }
}

/**
 * Initialization
 */
new PoP_Avatar_AWSFunctions();
