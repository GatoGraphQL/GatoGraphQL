<?php

class PoP_Avatar_AWS_S3Upload extends PoP_AWS_S3UploadBase
{
    private $user_id;

    public function __construct()
    {
        $this->files_to_upload = array();

        \PoP\Root\App::addAction(
            'user_avatar_add_photo:file-uploaded',
            array($this, 'maybeUploadToS3'),
            10,
            2
        );

        \PoP\Root\App::addAction(
            'user_avatar_add_photo:image-url',
            array($this, 'getImageUrl'),
            10,
            3
        );

        \PoP\Root\App::addAction(
            'user_avatar_add_photo:retrieve-file',
            array($this, 'maybeDownloadFromS3'),
            10,
            2
        );
    }

    // protected function getBucket() {

    //     return POP_AWS_WORKINGBUCKET;
    // }

    protected function getBucketPrefix()
    {
        return POP_AVATAR_AWS_WORKINGBUCKETPREFIX.'/'.$this->user_id.'/';
    }

    public function maybeUploadToS3($file, $user_id)
    {
        $this->user_id = $user_id;
        $this->uploadToS3($file);

        // If there is a "midsize-" image, also upload it
        $midsize_file = dirname($file).'/midsize-'.basename($file);
        if (file_exists($midsize_file)) {
            $this->uploadToS3($midsize_file);
        }
    }

    public function getImageUrl($url, $file, $user_id)
    {
        $bucketprefix = $this->getBucketPrefix();
        $parts = parse_url($url);
        return $this->getDomain().'/'.$bucketprefix.ltrim($parts['path'], '/');
    }

    public function maybeDownloadFromS3($file, $user_id)
    {
        if (!file_exists($file)) {
            $this->user_id = $user_id;
            $this->downloadFromS3($file);
        }
    }
}

/**
 * Initialization
 */
new PoP_Avatar_AWS_S3Upload();
