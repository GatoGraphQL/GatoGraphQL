<?php 

use Aws\Common\Aws;
// use Aws\S3\Exception\S3Exception;

class PoP_UserAvatar_AWS_Override {

	protected $s3;

	function configure($configuration) {

		$this->bucket = $configuration['bucket'];
		$this->region = $configuration['region'];
	}

	protected function lazy_init() {

		if (!$this->s3) {
			$this->s3 = Aws::factory(POP_AWS_DIR.'/config/aws-config.php')->get('s3')->registerStreamWrapper(); 	
		}
	}

	// function open_aws_wrapper() {

	// 	// Simply initialize
	// 	$this->lazy_init();
	// }

	function get_useravatar_photoinfo($user_id, $use_default = true) {

		// If the user has no avatar/photo, use the default one
		$avatar_data = user_avatar_avatar_exists($user_id, array('source' => 'photo'));
		if (!$avatar_data && $use_default) {

			$avatar_data = user_avatar_avatar_exists(POP_WPAPI_AVATAR_GENERICUSER, array('source' => 'photo'));
		}

		if ($avatar_data) {

			// Initialize the AWS StreamWrapper
			$this->lazy_init();

			// If the image does not exist, AWS will return an exception, so make the operation silent
			$size = @getimagesize($avatar_data['file']);
			return array(
				'src' => $avatar_data['url'],
				'width' => $size[0],
				'height' => $size[1],
			);
		}

		return array();
	}

	function get_bucket_url() {

		$prefix = 's3';
		if ($this->region) {
			$prefix .= '-'.$this->region;
		}

		// Use the same scheme as the current request
		$scheme = is_ssl() ? 'https' : 'http';

		// Bucket name as subdomain
		// $domain = $scheme.'://'.$this->bucket.'.'.$prefix.'.amazonaws.com';

		// Bucket name in path
		$domain = $scheme.'://'.$prefix.'.amazonaws.com/'.$this->bucket.'/';

		// Because we are using https, then we can't use the Bucket name as subdomain path, such as:
		// https://tppdebate‑dev.s3‑ap‑southeast‑1.amazonaws.com/
		// Instead we are using the Bucket name in path option:
		// https://s3‑ap‑southeast‑1.amazonaws.com/tppdebate‑dev/


		// Allow to override the bucket, to add a CDN host instead (eg: media.tppdebate.org)
		return trailingslashit(apply_filters('PoP_UserAvatar_AWS:bucket_url', $domain, $this->bucket, $this->region));
	}

	function avatar_exists($id, $params){

		$size = $params['size'];
		$source = $params['source'];

		// If using meta, then simply check if the meta exists
		if (POP_USERAVATAR_USEMETA) {

			if ($filename = gd_useravatar_get_filename($id)) {

				// If the size is among the list of avatars generated, then return that one
				if ($size && in_array($size, gd_useravatar_avatar_sizes())) {

					return array(
						'url' => $this->get_bucket_url().USERAVATAR_AWS_USERPHOTO_PATH."{$id}/avatars/{$size}/".$filename,
						'file' => "s3://" . $this->bucket . "/".USERAVATAR_AWS_USERPHOTO_PATH."{$id}/avatars/{$size}/".$filename,
					);
				}

				// Otherwise, return the generic one
				return array(
					'url' => $this->get_bucket_url().USERAVATAR_AWS_USERPHOTO_PATH."{$id}/{$source}/".$filename,
					'file' => "s3://" . $this->bucket . "/".USERAVATAR_AWS_USERPHOTO_PATH."/{$id}/{$source}/".$filename,
				);
			}

			return false;
		}

		// If not using meta, check if the file actually exists in the bucket

		// First check if the $size is given, if so try to find the avatar with that size
		if ($size) {

		    $prefix = USERAVATAR_AWS_USERPHOTO_PATH."{$id}/avatars/{$size}/";
		    if ($ret = $this->list_objects($prefix)) {
		    	return $ret;
		    }
		}

		$prefix = USERAVATAR_AWS_USERPHOTO_PATH."{$id}/{$source}/";
	    if ($ret = $this->list_objects($prefix)) {
	    	return $ret;
	    }

	    return false;
	}

	protected function list_objects($prefix) {

		$this->lazy_init();

	    //http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.S3.S3Client.html#_listObjects
	    $result = $this->s3->listObjects(array(
	        // Bucket is required
	        'Bucket' => $this->bucket,
	        'Prefix' => $prefix
	    ));

	    if(isset($result['Contents']) && count($result['Contents'])>0){

	    	foreach($result['Contents'] as $obj){

	            if($obj['Key']!=$prefix){ //if Key is a full file path and not just a "directory"
	            
		            return array(
						'url' => $this->get_bucket_url().$obj['Key'],
						'file' => 's3://'.$this->bucket.'/'.$obj['Key'],
					);
				}
	        }                        
	    }   
		
		return false;
	}

	function upload_to_s3($user_id, $folderpath, $filename) {

		$this->lazy_init();

		// List all files to copy
		// Taken from https://stackoverflow.com/questions/3826963/php-list-all-files-in-directory
		$files = array();
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderpath)) as $filename) {

		    // filter out "." and ".."
		    if ($filename->isDir()) continue;
		    $files[] = $filename->getPathname();
		}

		try{

            // First delete all existing objects in the destination bucket
            $result = $this->s3->deleteMatchingObjects(
                $this->bucket,
                USERAVATAR_AWS_USERPHOTO_PATH.$user_id.'/'
            );

            // Then copy all the objects from the source to the destination bucket
            foreach ($files as $file) {

	            // Remove the folderpath bit from the file
	            $relativepath = substr($file, strlen($folderpath));
				$result = $this->s3->putObject(array(
				    'ACL'   	 => 'public-read',
				    'Bucket'     => $this->bucket,
				    'Key'        => USERAVATAR_AWS_USERPHOTO_PATH.$user_id.'/'.$relativepath,
				    'SourceFile' => $file,
				));                        
			}   
        }
        catch (/*S3Exception*/Exception $e) {
            // $data['status']=0;
            // $data['message']=$e;
        }
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $useravatar_aws_pop_override;
$useravatar_aws_pop_override = new PoP_UserAvatar_AWS_Override();
