<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * File Upload Avatar
 *
 * ---------------------------------------------------------------------------------------------------------------*/

use Aws\Common\Aws;
// use Aws\S3\Exception\S3Exception;

class GD_FileUpload_UserPhoto_AWS extends GD_FileUpload_UserPhoto {

	protected $s3;

    // function init($user_id) {   
	function init() {	

		if (!$this->s3) {

			$this->s3 = Aws::factory(POP_AWS_DIR.'/config/aws-config.php')->get('s3')->registerStreamWrapper(); 
			$this->workingBucket = POP_FILEUPLOAD_AWS_WORKINGBUCKET;
	        $this->mediaBucket = POP_AWS_UPLOADSBUCKET;			
		}
	}

	function save_picture($user_id, $delete_source = false) {

        // $this->init($user_id);
		$this->init();
			
		// Copy the avatars from the working bucket to the media bucket
        $sourceBucket = $this->workingBucket;
        $targetBucket = $this->mediaBucket;
        $filename = '';

        $result = array();
        try{

            // First delete all existing objects in the destination bucket
            $result = $this->s3->deleteMatchingObjects(
                $targetBucket,
                USERAVATAR_AWS_USERPHOTO_PATH.$user_id.'/'
            );

            // Then copy all the objects from the source to the destination bucket
            //http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.S3.S3Client.html#_listObjects
            $result = $this->s3->listObjects(array(
                // Bucket is required
                'Bucket' => $sourceBucket,
                'Prefix' => $this->upload_path.'/'
            ));
            if(isset($result['Contents']) && count($result['Contents'])>0){
                foreach($result['Contents'] as $obj){

                    if($obj['Key']!=$this->upload_path){ //if Key is a full file path and not just a "directory"

	                    $sourceKeyname = $obj['Key'];
                        // Replace the upload_path (which could be "_43243432_942983" for new users) with the user_id
	                    $targetKeyname = USERAVATAR_AWS_USERPHOTO_PATH.$user_id.substr($obj['Key'], strlen($this->upload_path));
                        $filename = basename($sourceKeyname);
	                                        
	                    // Copy an object.
	                    $this->s3->copyObject(array(
	                        'ACL' => 'public-read',
	                        'Bucket'     => $targetBucket,
	                        'Key'        => $targetKeyname,
	                        'CopySource' => "{$sourceBucket}/{$sourceKeyname}",
	                    ));
                    }                        
                }                        
            }   

            // Delete source: needed to delete the images when first creating a user, since the created upload_path folder
            // is something like _43204930_432049320 and won't be used again
            // (In addition, there's a bug: since different users share the same upload_path, for it being saved in the settings cache,
            // then a 2nd user will see a 1st user's pic set by default when registering)
            if ($delete_source) {
                $this->s3->deleteMatchingObjects(
                    $sourceBucket,
                    $this->upload_path.'/'
                );
            }                               
        }
        catch (/*S3Exception*/Exception $e) {
            // $data['status']=0;
            // $data['message']=$e;
        }

        // Save the filename in the user meta
        if ($filename) {
            gd_useravatar_save_filename($user_id, $filename);
        }
        else {
            // No filename => No avatar uploaded, delete the existing one
            gd_useravatar_delete_filename($user_id);
        }
	}

	function copy_picture($user_id) {

        // $this->init($user_id);
		$this->init();

		// Copy the avatars from the media bucket to the working bucket
        $sourceBucket = $this->mediaBucket;
        $targetBucket = $this->workingBucket;

        try{

            // Only copy if the image in the working bucket is different than the one in the media bucket
            // This is because copying images takes time, and most likely the image will be the same 
            // (different only if the user went to Change pic page, uploaded a new pic but never saved)
            $originalPrefix = USERAVATAR_AWS_USERPHOTO_PATH.$user_id.'/original/';
            $sourceOriginal = '';
            $result = $this->s3->listObjects(array(
                // Bucket is required
                'Bucket' => $sourceBucket,
                'Prefix' => $originalPrefix
            ));
            if(isset($result['Contents']) && count($result['Contents']) > 0){
                foreach($result['Contents'] as $obj){
                    if($obj['Key']!=$originalPrefix){ //if Key is a full file path and not just a "directory"
                        $sourceOriginal = basename($obj['Key']);
                        break;
                    }                        
                }                        
            }  
            if ($sourceOriginal) {
                
                // If this same image exists in the target, then do nothing and exit
                if ($this->s3->doesObjectExist($targetBucket, $user_id.'/original/'.$sourceOriginal)) {

                    return;
                }
            }

            // Copy all files from the media bucket to the working bucket
            // First delete all existing objects in the destination bucket
            $result = $this->s3->deleteMatchingObjects(
                $targetBucket,
                $user_id.'/'
            );

            // Then copy all the objects from the source to the destination bucket
            //http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.S3.S3Client.html#_listObjects
            $result = $this->s3->listObjects(array(
                // Bucket is required
                'Bucket' => $sourceBucket,
                'Prefix' => USERAVATAR_AWS_USERPHOTO_PATH.$user_id.'/'
            ));
            if(isset($result['Contents']) && count($result['Contents'])>0){

            	// Remove the wp-content/... when copying it into the working bucket, no need for it
            	$start = strlen(USERAVATAR_AWS_USERPHOTO_PATH);
                foreach($result['Contents'] as $obj){

                    if($obj['Key']!=$user_id.'/'){ //if Key is a full file path and not just a "directory"

	                    $sourceKeyname = $obj['Key'];
	                    $targetKeyname = substr($obj['Key'], $start);
	                                        
	                    // Copy an object.
	                    $this->s3->copyObject(array(
	                        'ACL' => 'public-read',
	                        'Bucket'     => $targetBucket,
	                        'Key'        => $targetKeyname,
	                        'CopySource' => "{$sourceBucket}/{$sourceKeyname}",
	                    ));
                    }                        
                }                        
            }                                  
        }
        catch (/*S3Exception*/Exception $e) {
            // $data['status']=0;
            // $data['message']=$e;
        }
	}
	
	function get_action_url() {
		
		// Allow to replace this URL, mainly to change the avatar sizes as needed by the specific website
        return apply_filters('GD_FileUpload_UserPhoto_AWS:action_url', POP_USERAVATAR_AWS_URI_LIB.'/fileupload-userphoto/server/index.php?upload_path=' . $this->upload_path, $this->upload_path);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_FileUpload_UserPhoto_AWS();
