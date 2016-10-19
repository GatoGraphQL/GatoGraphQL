<?php

abstract class AS3CF_Filter {

	/**
	 * @var Amazon_S3_And_CloudFront
	 */
	protected $as3cf;

	/**
	 * @var array
	 */
	protected $query_cache = array();

	/**
	 * Constructor
	 *
	 * @param Amazon_S3_And_CloudFront $as3cf
	 */
	public function __construct( $as3cf ) {
		$this->as3cf = $as3cf;

		// Purge on attachment delete
		add_action( 'delete_attachment', array( $this, 'purge_cache_on_attachment_delete' ) );

		$this->init();
	}

	/**
	 * Filter EDD download files.
	 *
	 * @param array $value
	 *
	 * @return array
	 */
	public function filter_edd_download_files( $value ) {
		if ( ! $this->should_filter_content() ) {
			// Not filtering content, return
			return $value;
		}

		if ( empty( $value ) ) {
			// Nothing to filter, return
			return $value;
		}

		foreach ( $value as $key => $attachment ) {
			$url = $this->get_url( $attachment['attachment_id'] );

			if ( $url ) {
				$value[ $key ]['file'] = $this->get_url( $attachment['attachment_id'] );
			}
		}

		return $value;
	}

	/**
	 * Filter customizer image.
	 *
	 * @param string      $value
	 * @param bool|string $old_value
	 *
	 * @return string
	 */
	public function filter_customizer_image( $value, $old_value = false ) {
		if ( empty( $value ) || is_a( $value, 'stdClass' ) ) {
			return $value;
		}

		$cache    = $this->get_option_cache();
		$to_cache = array();
		$value    = $this->process_content( $value, $cache, $to_cache );

		$this->maybe_update_option_cache( $to_cache );
		
		return $value;
	}

	/**
	 * Filter header image data.
	 *
	 * @param stdClass      $value
	 * @param bool|stdClass $old_value
	 *
	 * @return stdClass
	 */
	public function filter_header_image_data( $value, $old_value = false ) {
		$url = $this->get_url( $value->attachment_id );

		if ( $url ) {
			$value->url           = $url;
			$value->thumbnail_url = $url;
		}

		return $value;
	}

	/**
	 * Filter post.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function filter_post( $content ) {
		if ( empty( $content ) ) {
			// Nothing to filter, continue
			return $content;
		}

		$cache    = $this->get_post_cache();
		$to_cache = array();
		$content  = $this->process_content( $content, $cache, $to_cache );

		$this->maybe_update_post_cache( $to_cache );

		return $content;
	}

	/**
	 * Process content.
	 *
	 * @param string $content
	 * @param array  $cache
	 * @param array  $to_cache
	 *
	 * @return mixed
	 */
	protected function process_content( $content, $cache, &$to_cache ) {
		if ( ! $this->should_filter_content() ) {
			// Not filtering content, return
			return $content;
 		}

 		$content = $this->pre_replace_content( $content );

		// Find URLs from img src
		$url_pairs = $this->get_urls_from_img_src( $content, $to_cache );
		$content   = $this->replace_urls( $content, $url_pairs );

		// Find leftover URLs
		$content = $this->find_urls_and_replace( $content, $cache, $to_cache );

		// Perform post processing if required
		$content = $this->post_process_content( $content );

		return $content;
	}

	/**
	 * Find URLs and replace.
	 *
	 * @param string $value
	 * @param array  $cache
	 * @param array  $to_cache
	 *
	 * @return string
	 */
	protected function find_urls_and_replace( $value, $cache, &$to_cache ) {
		if ( ! $this->should_filter_content() ) {
			// Not filtering content, return
			return $value;
		}

		$url_pairs = $this->get_urls_from_content( $value, $cache, $to_cache );
		$value     = $this->replace_urls( $value, $url_pairs );

		return $value;
	}

	/**
	 * Get URLs from img src.
	 *
	 * @param string $content
	 * @param array  $to_cache
	 *
	 * @return array
	 */
	protected function get_urls_from_img_src( $content, &$to_cache ) {
		$url_pairs = array();

		if ( ! preg_match_all( '/<img [^>]+>/', $content, $matches ) || ! isset( $matches[0] ) ) {
			// No img tags found, return
			return $url_pairs;
		}

		$matches        = array_unique( $matches[0] );
		$attachment_ids = array();

		foreach ($matches as $image) {
			if ( ! preg_match( '/src=\\\?["\']+([^"\'\\\]+)/', $image, $src ) || ! isset( $src[1] ) ) {
				// Can't determine URL, skip
				continue;
			}

			$url = $src[1];

			if ( ! $this->url_needs_replacing( $url ) ) {
				// URL already correct, skip
				continue;
			}

			if ( ! preg_match( '/wp-image-([0-9]+)/i', $image, $class_id ) || ! isset( $class_id[1] ) ) {
				// Can't determine ID from class, skip
				continue;
			}

			$attachment_ids[ absint( $class_id[1] ) ] = $url;
		}

		if ( count( $attachment_ids ) > 1 ) {
			/*
			 * Warm object cache for use with 'get_post_meta()'.
			 *
			 * To avoid making a database call for each image, a single query
			 * warms the object cache with the meta information for all images.
			 */
			update_meta_cache( 'post', array_keys( $attachment_ids ) );
		}

		foreach ( $attachment_ids as $attachment_id => $url ) {
			if ( ! $this->attachment_id_matches_src( $attachment_id, $url ) ) {
				// Path doesn't match attachment, skip
				continue;
			}

			$this->push_to_url_pairs( $url_pairs, $attachment_id, $url, $to_cache );
		}

		return $url_pairs;
	}

	/**
	 * Get URLs from content.
	 *
	 * @param string $content
	 * @param array  $cache
	 * @param array  $to_cache
	 *
	 * @return array
	 */
	protected function get_urls_from_content( $content, $cache, &$to_cache ) {
		$url_pairs = array();

		if ( ! preg_match_all( '/(http|https)?:?\/\/[^"\'\s<>\\\]*/', $content, $matches ) || ! isset( $matches[0] ) ) {
			// No URLs found, return
			return $url_pairs;
		}

		$matches = array_unique( $matches[0] );

		foreach ($matches as $url) {
			if ( ! $this->url_needs_replacing( $url ) ) {
				// URL already correct, skip
				continue;
			}

			$parts = parse_url( $url );

			if ( ! isset( $parts['path'] ) ) {
				// URL doesn't have a path, continue
				continue;
			}

			$info = pathinfo( $parts['path'] );

			if ( ! isset( $info['extension'] ) ) {
				// URL doesn't have a file extension, continue
				continue;
			}

			$attachment_id = null;

			if ( isset( $cache[ $this->as3cf->maybe_remove_query_string( $url ) ] ) ) {
				$cached_id = $cache[ $this->as3cf->maybe_remove_query_string( $url ) ];

				if ( $this->is_failure( $cached_id ) ) {
					// Attachment ID failure, continue
					continue;
				}

				// Attachment ID cached
				$attachment_id = $cached_id;
			}

			if ( is_null( $attachment_id ) || is_array( $attachment_id ) ) {
				// Attachment ID not cached
				$attachment_id = $this->get_attachment_id_from_url( $url );
			}

			if ( ! $attachment_id ) {
				// Can't determine attachment ID, continue
				$this->url_cache_failure( $url, $to_cache );

				continue;
			}

			$this->push_to_url_pairs( $url_pairs, $attachment_id, $url, $to_cache );
		}

		return $url_pairs;
	}

	/**
	 * Is failure?
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	protected function is_failure( $value ) {
		if ( ! is_array( $value ) || ! isset( $value['timestamp'] ) ) {
			return false;
		}

		$expires = time() - ( 15 * MINUTE_IN_SECONDS );

		if ( $expires >= $value['timestamp'] ) {
			return false;
		}

		return true;
	}

	/**
	 * Does attachment ID match src?
	 *
	 * @param int    $attachment_id
	 * @param string $url
	 *
	 * @return bool
	 */
	protected function attachment_id_matches_src( $attachment_id, $url ) {
		$base_urls = array();
		$meta      = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );

		if ( ! isset( $meta['sizes'] ) ) {
			// No sizes found, return
			return false;
		}

		$base_url = $this->as3cf->remove_scheme( $this->as3cf->maybe_remove_query_string( $this->get_base_url( $attachment_id ) ) );
		$basename = wp_basename( $base_url );
		$url      = $this->as3cf->remove_scheme( $this->as3cf->maybe_remove_query_string( $url ) );

		// Add full size URL
		$base_urls[] = $base_url;

		// Add additional image size URLs
		foreach ( $meta['sizes'] as $size ) {
			$base_urls[] = str_replace( $basename, $size['file'], $base_url );
		}

		if ( in_array( $this->as3cf->remove_scheme( $url ), $base_urls ) ) {
			// Match found, return true
			return true;
		}

		return false;
	}

	/**
	 * Push to URL pairs.
	 *
	 * @param array  $url_pairs
	 * @param int    $attachment_id
	 * @param string $find
	 * @param array  $to_cache
	 */
	protected function push_to_url_pairs( &$url_pairs, $attachment_id, $find, &$to_cache ) {
		$replace_full = $this->get_url( $attachment_id );
		
		if ( ! $replace_full ) {
			// Replacement URL can't be found, return
			return;
		}

		$size         = $this->get_size_string_from_url( $attachment_id, $find );
		$replace_size = $this->get_url( $attachment_id, $size );
		$parts        = parse_url( $find );

		if ( ! isset( $parts['scheme'] ) ) {
			$replace_full = $this->as3cf->remove_scheme( $replace_full );
			$replace_size = $this->as3cf->remove_scheme( $replace_size );
		}

		$find_full = $this->as3cf->remove_size_from_filename( $find );
		$find_full = $this->normalize_find_value( $this->as3cf->maybe_remove_query_string( $find_full ) );
		$find_size = $this->normalize_find_value( $this->as3cf->maybe_remove_query_string( $find ) );

		// Find and replace full version
		$url_pairs[ $find_full ] = $replace_full;
		$to_cache[ $find_full ]  = $attachment_id;

		// Find and replace sized version
		if ( wp_basename( $find_full ) !== wp_basename( $find_size ) ) {
			$url_pairs[ $find_size ] = $replace_size;
			$to_cache[ $find_size ]  = $attachment_id;
		}

		// Prime cache, when filtering the opposite way
		$replace_full = $this->as3cf->maybe_remove_query_string( $replace_full );
		$replace_size = $this->as3cf->maybe_remove_query_string( $replace_size );

		$to_cache[ $this->normalize_find_value( $replace_full ) ] = $attachment_id;
		$to_cache[ $this->normalize_find_value( $replace_size ) ] = $attachment_id;
	}

	/**
	 * Get size string from URL.
	 *
	 * @param int    $attachment_id
	 * @param string $url
	 *
	 * @return null|string
	 */
	protected function get_size_string_from_url( $attachment_id, $url ) {
		$meta     = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );
		$basename = wp_basename( $this->as3cf->maybe_remove_query_string( $url ) );

		if ( empty( $meta['sizes'] ) ) {
			// No alternative sizes available, return
			return null;
		}

		foreach ( $meta['sizes'] as $size => $file ) {
			if ( $basename === $file['file'] ) {
				return $size;
			}
		}

		return null;
	}

	/**
	 * URL cache failure.
	 *
	 * @param string $url
	 * @param array  $to_cache
	 */
	protected function url_cache_failure( $url, &$to_cache ) {
		$full    = $this->as3cf->remove_size_from_filename( $url );
		$failure = array(
			'timestamp' => time(),
		);

		$to_cache[ $full ] = $failure;

		if ( $full !== $url ) {
			$to_cache[ $url ] = $failure;
		}
	}

	/**
	 * Replace URLs.
	 *
	 * @param string $content
	 * @param array  $url_pairs
	 *
	 * @return string
	 */
	protected function replace_urls( $content, $url_pairs ) {
		if ( empty( $url_pairs ) ) {
			// No URLs to replace return
			return $content;
		}

		foreach ( $url_pairs as $find => $replace ) {
			$replace = $this->normalize_replace_value( $replace );
			$content = str_replace( $find, $replace, $content );
		}

		return $content;
	}

	/**
	 * Get post cache
	 *
	 * @return array
	 */
	protected function get_post_cache() {
		global $post;

		if ( ! isset( $post->ID ) ) {
			// Post ID not found, return empty cache
			return array();
		}

		$cache = get_post_meta( $post->ID, 'amazonS3_cache', true );

		if ( empty( $cache ) ) {
			$cache = array();
		}

		return $cache;
	}

	/**
	 * Maybe update post cache
	 *
	 * @param array $to_cache
	 */
	protected function maybe_update_post_cache( $to_cache ) {
		global $post;

		if ( ! isset( $post->ID ) || empty( $to_cache ) ) {
			return;
		}

		$urls = array_merge( $this->get_post_cache(), $to_cache );

		update_post_meta( $post->ID, 'amazonS3_cache', $urls );
	}

	/**
	 * Get option cache.
	 *
	 * @return array
	 */
	protected function get_option_cache() {
		return get_option( 'amazonS3_cache', array() );
	}

	/**
	 * Maybe update option cache.
	 *
	 * @param array $to_cache
	 */
	protected function maybe_update_option_cache( $to_cache ) {
		if ( empty( $to_cache ) ) {
			return;
		}

		$urls = array_merge( $this->get_option_cache(), $to_cache );

		update_option( 'amazonS3_cache', $urls );
	}

	/**
	 * Purge attachment from cache on delete.
	 *
	 * @param int $post_id
	 */
	public function purge_cache_on_attachment_delete( $post_id ) {
		$this->purge_from_cache( $this->get_url( $post_id ) );
	}

	/**
	 * Purge URL from cache
	 *
	 * @param string   $url
	 * @param bool|int $blog_id
	 */
	public function purge_from_cache( $url, $blog_id = false ) {
		global $wpdb;

		if ( false !== $blog_id ) {
			$this->as3cf->switch_to_blog( $blog_id );
		}

		// Purge postmeta cache
		$sql = $wpdb->prepare( "
 			DELETE FROM {$wpdb->postmeta}
 			WHERE meta_key = %s
 			AND meta_value LIKE %s;
 		", 'amazonS3_cache', '%"' . $url . '"%' );

		$wpdb->query( $sql );

		// Purge option cache
		$sql = $wpdb->prepare( "
 			DELETE FROM {$wpdb->options}
 			WHERE option_name = %s
 			AND option_value LIKE %s;
 		", 'amazonS3_cache', '%"' . $url . '"%' );

		$wpdb->query( $sql );

		if ( false !== $blog_id ) {
			$this->as3cf->restore_current_blog();
		}
	}

	/**
	 * Should filter content.
	 *
	 * @return bool
	 */
	protected function should_filter_content() {
		if ( ! $this->as3cf->is_plugin_setup() || ! $this->as3cf->get_setting( 'serve-from-s3' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Remove AWS query strings.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	protected function remove_aws_query_strings( $content ) {
		if ( ! preg_match_all( '/\?[^\s"<\?]*X-Amz-Algorithm[^\s"<\?]+/', $content, $matches ) || ! isset( $matches[0] ) ) {
			// No query strings found, return
			return $content;
		}

		$matches = array_unique( $matches[0] );

		foreach ( $matches as $match ) {
			$content = str_replace( $match, '', $content );
		}

		return $content;
	}

	/**
	 * Does URL need replacing?
	 *
	 * @param string $url
	 *
	 * @return bool
	 */
	abstract protected function url_needs_replacing( $url );

	/**
	 * Get URL.
	 *
	 * @param int         $attachment_id
	 * @param null|string $size
	 *
	 * @return bool|string
	 */
	abstract protected function get_url( $attachment_id, $size = null );

	/**
	 * Get base URL.
	 *
	 * @param int $attachment_id
	 *
	 * @return string|false
	 */
	abstract protected function get_base_url( $attachment_id );

	/**
	 * Get attachment ID from URL.
	 *
	 * @param string $url
	 *
	 * @return bool|int
	 */
	abstract protected function get_attachment_id_from_url( $url );

	/**
	 * Normalize find value.
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	abstract protected function normalize_find_value( $url );

	/**
	 * Normalize replace value.
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	abstract protected function normalize_replace_value( $url );

	/**
	 * Post process content.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	abstract protected function post_process_content( $content );

	/**
	 * Pre replace content.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	abstract protected function pre_replace_content( $content );
}