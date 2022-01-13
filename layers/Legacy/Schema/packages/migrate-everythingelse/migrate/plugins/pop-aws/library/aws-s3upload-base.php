<?php
use Aws\Common\Aws;

use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_AWS_S3UploadBase
{
    protected $s3;

    protected function getRegion()
    {
        return POP_AWS_REGION;
    }

    protected function getBucket()
    {
        return POP_AWS_WORKINGBUCKET;
    }

    protected function getBucketPrefix()
    {
        return '';
    }

    protected function getDomain()
    {
        $prefix = 's3';
        $region = $this->getRegion();
        $bucket = $this->getBucket();

        // Adding the region in the prefix doesn't work for North Virginia
        if ($region && $region != 'us-east-1') {
            $prefix .= '-'.$region;
        }

        // Use the same scheme as the current request
        $scheme = is_ssl() ? 'https' : 'http';

        // Bucket name as subdomain
        // $domain = $scheme.'://'.$this->bucket.'.'.$prefix.'.amazonaws.com';

        // Bucket name in path
        $domain = $scheme.'://'.$prefix.'.amazonaws.com/'.$bucket;

        // Allow to inject the CDN instead
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_AWS_S3UploadBase:domain',
            $domain,
            $bucket
        );
    }

    protected function getConfig()
    {
        return POP_AWS_DIR.'/config/aws-config.php';
    }

    protected function lazyInit()
    {
        if (!$this->s3) {
            $this->s3 = Aws::factory($this->getConfig())->get('s3')->registerStreamWrapper();
        }
    }

    protected function getCachecontrolMaxage()
    {
        return '';
    }

    protected function getAcl()
    {
        return 'public-read';
    }

    protected function skipIfItExists()
    {
        return true;
    }

    protected function uploadToS3($file)
    {
        $this->lazyInit();
        $bucket = $this->getBucket();
        $bucketprefix = $this->getBucketPrefix();
        $file_path = $bucketprefix.substr($file, strlen(ABSPATH));
        
        try {
            // Check first that the file does not exist
            if (!$this->skipIfItExists() || !$this->s3->doesObjectExist($bucket, $file_path)) {
                $configuration = array(
                    'ACL'        => $this->getAcl(),
                    'Bucket'     => $bucket,
                    'Key'        => $file_path,
                    'SourceFile' => $file,
                );
                if ($cache = $this->getCachecontrolMaxage()) {
                    $configuration['CacheControl'] = $cache;
                }
                $result = $this->s3->putObject($configuration);
            }
        } catch (/*S3Exception*/Exception $e) {
            throw $e;
        }
    }

    protected function downloadFromS3($file)
    {
        $this->lazyInit();
        $bucket = $this->getBucket();
        $bucketprefix = $this->getBucketPrefix();
        $file_path = substr($file, strlen(ABSPATH));
        
        try {
            // Check first that the file does not exist
            if ($this->s3->doesObjectExist($bucket, $bucketprefix.$file_path)) {
                $configuration = array(
                    'Bucket'     => $bucket,
                    'Key'        => $bucketprefix.$file_path,
                    'SaveAs' => $file,
                );
                $this->s3->getObject($configuration);
            }
        } catch (/*S3Exception*/Exception $e) {
            throw $e;
        }
    }
}
