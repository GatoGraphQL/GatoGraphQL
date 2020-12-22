<?php

use Aws\Common\Aws;

class GD_FileUpload_UserPhoto_AWS extends GD_FileUpload_UserPhoto
{
    protected $s3;

    public function init()
    {
        if (!$this->s3) {
            $this->s3 = Aws::factory(POP_AWS_DIR.'/config/aws-config.php')->get('s3')->registerStreamWrapper();
            $this->workingBucketPrefix = POP_FILEUPLOAD_AWS_WORKINGBUCKETPREFIX;
            $this->workingBucket = POP_AWS_WORKINGBUCKET;
            $this->mediaBucket = POP_AWS_UPLOADSBUCKET;
        }
    }

    public function savePicture($user_id, $delete_source = false)
    {
        $this->init();
            
        // Calculate user_user_upload_path from the user_id
        $user_upload_path = GD_FileUpload_Picture_Utils::getFileuploadUserPath($user_id);

        // Copy the avatars from the working bucket to the media bucket
        $sourceBucket = $this->workingBucket;
        $targetBucket = $this->mediaBucket;
        $filename = '';

        $result = array();
        // try{

        // First delete all existing objects in the destination bucket
        $result = $this->s3->deleteMatchingObjects(
            $targetBucket,
            POP_AVATAR_AWS_USERPHOTO_PATH.$user_id.'/'
        );

        // Then copy all the objects from the source to the destination bucket
        //http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.S3.S3Client.html#_listObjects
        $result = $this->s3->listObjects(
            array(
                // Bucket is required
                'Bucket' => $sourceBucket,
                'Prefix' => $this->workingBucketPrefix.'/'.$user_upload_path.'/'
            )
        );

        if (isset($result['Contents']) && count($result['Contents'])>0) {
            foreach ($result['Contents'] as $obj) {
                if ($obj['Key']!=$this->workingBucketPrefix.'/'.$user_upload_path) { //if Key is a full file path and not just a "directory"
                    $sourceKeyname = $obj['Key'];
                    // Remove the "user-avatar/" prefix
                    $targetKeyname = substr($sourceKeyname, strlen($this->workingBucketPrefix.'/'));
                    // Replace the user_upload_path (which could be "_43243432_942983" for new users) with the user_id
                    $targetKeyname = POP_AVATAR_AWS_USERPHOTO_PATH.$user_id.substr($targetKeyname, strlen($user_upload_path));
                    $filename = basename($sourceKeyname);
                                        
                    // Copy an object.
                    $this->s3->copyObject(
                        array(
                            'ACL' => 'public-read',
                            'Bucket'     => $targetBucket,
                            'Key'        => $targetKeyname,
                            'CopySource' => "{$sourceBucket}/{$sourceKeyname}",
                        )
                    );
                }
            }
        }

        // Delete source: needed to delete the images when first creating a user, since the created user_upload_path folder
        // is something like _43204930_432049320 and won't be used again
        // (In addition, there's a bug: since different users share the same user_upload_path, for it being saved in the settings cache,
        // then a 2nd user will see a 1st user's pic set by default when registering)
        if ($delete_source) {
            $this->s3->deleteMatchingObjects(
                $sourceBucket,
                $this->workingBucketPrefix.'/'.$user_upload_path.'/'
            );
        }
        // }
        // catch (Exception $e) {
        //     throw $e;
        // }

        // Save the filename in the user meta
        if ($filename) {
            gd_useravatar_save_filename($user_id, $filename);
        } else {
            // No filename => No avatar uploaded, delete the existing one
            gd_useravatar_delete_filename($user_id);
        }
    }

    public function copyPicture($user_id)
    {
        $this->init();

        // Calculate user_upload_path from the user_id
        $user_upload_path = GD_FileUpload_Picture_Utils::getFileuploadUserPath($user_id);

        // Copy the avatars from the media bucket to the working bucket
        $sourceBucket = $this->mediaBucket;
        $targetBucket = $this->workingBucket;

        // try{

        // Only copy if the image in the working bucket is different than the one in the media bucket
        // This is because copying images takes time, and most likely the image will be the same
        // (different only if the user went to Change pic page, uploaded a new pic but never saved)
        $originalPrefix = POP_AVATAR_AWS_USERPHOTO_PATH.$user_id.'/original/';
        $sourceOriginal = '';
        $result = $this->s3->listObjects(
            array(
                // Bucket is required
                'Bucket' => $sourceBucket,
                'Prefix' => $originalPrefix
            )
        );
        if (isset($result['Contents']) && count($result['Contents']) > 0) {
            foreach ($result['Contents'] as $obj) {
                if ($obj['Key']!=$originalPrefix) { //if Key is a full file path and not just a "directory"
                    $sourceOriginal = basename($obj['Key']);
                    break;
                }
            }
        }
        if ($sourceOriginal) {
            // If this same image exists in the target, then do nothing and exit
            if ($this->s3->doesObjectExist($targetBucket, $this->workingBucketPrefix.'/'.$user_upload_path.'/original/'.$sourceOriginal)) {
                return;
            }
        }

        // Copy all files from the media bucket to the working bucket
        // First delete all existing objects in the destination bucket
        $result = $this->s3->deleteMatchingObjects(
            $targetBucket,
            $this->workingBucketPrefix.'/'.$user_upload_path.'/'
        );

        // Then copy all the objects from the source to the destination bucket
        //http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.S3.S3Client.html#_listObjects
        $result = $this->s3->listObjects(
            array(
                // Bucket is required
                'Bucket' => $sourceBucket,
                'Prefix' => POP_AVATAR_AWS_USERPHOTO_PATH.$user_id.'/'
            )
        );
        if (isset($result['Contents']) && count($result['Contents'])>0) {
            // Remove the wp-content/... when copying it into the working bucket, no need for it
            $start = strlen(POP_AVATAR_AWS_USERPHOTO_PATH.$user_id.'/');
            foreach ($result['Contents'] as $obj) {
                if ($obj['Key']!=$user_id.'/') { //if Key is a full file path and not just a "directory"
                    $sourceKeyname = $obj['Key'];
                    $targetKeyname = $this->workingBucketPrefix.'/'.$user_upload_path.'/'.substr($obj['Key'], $start);
                                        
                    // Copy an object.
                    $this->s3->copyObject(
                        array(
                            'ACL' => 'public-read',
                            'Bucket'     => $targetBucket,
                            'Key'        => $targetKeyname,
                            'CopySource' => "{$sourceBucket}/{$sourceKeyname}",
                        )
                    );
                }
            }
        }
        // }
        // catch (Exception $e) {
        //     throw $e;
        // }
    }
}

/**
 * Initialization
 */
new GD_FileUpload_UserPhoto_AWS();
