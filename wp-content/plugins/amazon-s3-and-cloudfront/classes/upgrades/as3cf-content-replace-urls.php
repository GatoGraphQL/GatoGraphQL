<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AS3CF_Upgrade_Content_Replace_URLs Class
 *
 * This class handles replacing all S3 URLs in post
 * content with the local URL.
 *
 * @since 1.2
 */
class AS3CF_Upgrade_Content_Replace_URLs extends AS3CF_Upgrade {

	/**
	 * @var int Current blog ID
	 */
	protected $blog_id;

	/**
	 * @var int Finish time
	 */
	protected $finish;

	/**
	 * @var array Session data
	 */
	protected $session;

	/**
	 * Initiate the upgrade
	 *
	 * @param object $as3cf Instance of calling class
	 */
	public function __construct( $as3cf ) {
		$this->upgrade_id   = 4;
		$this->upgrade_name = 'replace_s3_urls';
		$this->upgrade_type = 'posts';

		$this->running_update_text = __( 'and ensuring that only the local URL exists in post content.', 'amazon-s3-and-cloudfront' );

		parent::__construct( $as3cf );
	}

	/**
	 * Fire up the upgrade
	 */
	protected function init() {
		$session = array(
			'status'                => self::STATUS_RUNNING,
			'total_attachments'     => 0,
			'processed_attachments' => 0,
			'blogs_processed'       => false,
			'blogs'                 => array(),
		);

		foreach ( $this->as3cf->get_all_blog_table_prefixes() as $blog_id => $prefix ) {
			$session['blogs'][ $blog_id ] = array(
				'prefix'                => $prefix,
				'processed'             => false,
				'total_attachments'     => null,
				'last_attachment_id'    => null,
				'highest_post_id'       => null,
				'last_post_id'          => null,
			);
		}

		$this->save_session( $session );
		$this->schedule();
	}

	/**
	 * Count attachments to process. We don't care about the total at this stage
	 * so just loop over blogs until attachments exist on S3.
	 *
	 * @return int
	 */
	protected function count_items_to_process() {
		$table_prefixes = $this->as3cf->get_all_blog_table_prefixes();

		foreach ( $table_prefixes as $blog_id => $table_prefix ) {
			if ( $this->as3cf->count_attachments( $table_prefix, true ) ) {
				return 1;
			}
		}

		return 0;
	}

	/**
	 * Cron job to update post content, ensuring no S3 URLs exist.
	 */
	public function do_upgrade() {
		// Check if the cron should even be running
		if ( $this->get_saved_upgrade_id() >= $this->upgrade_id || $this->get_upgrade_status() !== self::STATUS_RUNNING ) {
			$this->unschedule();

			return;
		}

		$limit         = apply_filters( 'as3cf_update_' . $this->upgrade_name . '_batch_size', 50 );
		$this->finish  = time() + apply_filters( 'as3cf_update_' . $this->upgrade_name . '_time_limit', 10 );
		$this->session = $this->get_session();

		if ( ! $this->maybe_process_blogs() ) {
			// Blogs still to process but limits reached, return
			$this->save_session( $this->session );

			return;
		}

		foreach ( $this->session['blogs'] as $blog_id => $blog ) {
			$this->blog_id = $blog_id;
			$this->as3cf->switch_to_blog( $blog_id );

			if ( $this->batch_limit_reached() ) {
				// Limits reached, end batch
				break;
			}

			if ( $blog['processed'] ) {
				// Blog processed, move onto the next
				continue;
			}

			$offset      = $this->session['blogs'][ $blog_id ]['last_attachment_id'];
			$attachments = $this->get_items_to_process( $blog['prefix'], $limit, $offset );

			if ( empty( $attachments ) ) {
				// All attachments processed, maybe move onto next blog
				$this->session['blogs'][ $blog_id ]['processed'] = true;

				if ( $this->all_blogs_processed() ) {
					// All blogs processed, complete upgrade
					$this->upgrade_finished();

					return;
				}

				continue;
			}

			foreach ( $attachments as $attachment ) {
				if ( $this->batch_limit_reached() ) {
					// Limits reached, end batch
					break 2;
				}

				if ( $this->upgrade_item( $attachment ) ) {
					$this->session['processed_attachments'] += 1;
					$this->session['blogs'][ $blog_id ]['last_attachment_id'] = $attachment->ID;
					$this->session['blogs'][ $blog_id ]['last_post_id']       = null;
				} else {
					// Limits reached while processing posts, end batch
					break 2;
				}
			}

			$this->as3cf->restore_current_blog();
		}

		$this->save_session( $this->session );
	}

	/**
	 * Maybe process blogs.
	 *
	 * @return bool
	 */
	protected function maybe_process_blogs() {
		if ( $this->session['blogs_processed'] ) {
			// Blogs already processed, return
			return true;
		}

		foreach ( $this->session['blogs'] as $blog_id => $blog ) {
			if ( $this->batch_limit_reached() ) {
				// Limits reached, return
				return false;
			}

			if ( is_null( $blog['total_attachments'] ) ) {
				// Handle theme mods
				$this->upgrade_theme_mods( $blog['prefix'] );

				// Count total attachments
				$count = $this->as3cf->count_attachments( $blog['prefix'], true );

				// Update blog session data
				$this->session['blogs'][ $blog_id ]['total_attachments'] = $count;
				$this->session['total_attachments'] += $count;
			}

			if ( is_null( $blog['highest_post_id'] ) ) {
				// Retrieve highest post ID
				$this->session['blogs'][ $blog_id ]['highest_post_id'] = $this->get_highest_post_id( $blog['prefix'] );
			}
		}

		$this->session['blogs_processed'] = true;

		return true;
	}

	/**
	 * Get highest post ID.
	 *
	 * @param string $prefix
	 *
	 * @return int
	 */
	protected function get_highest_post_id( $prefix ) {
		global $wpdb;

		$sql = "SELECT ID FROM `{$prefix}posts` ORDER BY ID DESC LIMIT 1";

		return (int) $wpdb->get_var( $sql );
	}

	/**
	 * All blogs processed.
	 *
	 * @return bool
	 */
	protected function all_blogs_processed() {
		foreach ( $this->session['blogs'] as $blog ) {
			if ( ! $blog['processed'] ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Upgrade theme mods. Ensures background and header images have local URLs saved to the database.
	 *
	 * @param string $prefix
	 */
	protected function upgrade_theme_mods( $prefix ) {
		global $wpdb;

		$mods = $wpdb->get_results( "SELECT * FROM `{$prefix}options` WHERE option_name LIKE 'theme_mods_%'" );

		foreach ( $mods as $mod ) {
			$value = maybe_unserialize( $mod->option_value );

			if ( isset( $value['background_image'] ) ) {
				$value['background_image'] = $this->as3cf->filter_s3->filter_customizer_image( $value['background_image'] );
			}

			if ( isset( $value['header_image'] ) ) {
				$value['header_image'] = $this->as3cf->filter_s3->filter_customizer_image( $value['header_image'] );
			}

			if ( isset( $value['header_image_data'] ) ) {
				$value['header_image_data'] = $this->as3cf->filter_s3->filter_header_image_data( $value['header_image_data'] );
			}

			$value = maybe_serialize( $value );

			if ( $value !== $mod->option_value ) {
				$wpdb->query( "UPDATE `{$prefix}options` SET option_value = '{$value}' WHERE option_id = '{$mod->option_id}'" );
			}
		}
	}

	/**
	 * Get items to process.
	 *
	 * @param string     $prefix
	 * @param int        $limit
	 * @param bool|mixed $offset
	 *
	 * @return array
	 */
	protected function get_items_to_process( $prefix, $limit, $offset = false ) {
		global $wpdb;

		$sql = "SELECT posts.ID FROM `{$prefix}posts` AS posts
		        INNER JOIN `{$prefix}postmeta` AS postmeta
		        ON posts.ID = postmeta.post_id
		        WHERE posts.post_type = 'attachment'
		        AND postmeta.meta_key = 'amazonS3_info'";

		if ( ! empty( $offset ) ) {
			$sql .= " AND posts.ID < '{$offset}'";
		}

		$sql .= " ORDER BY posts.ID DESC LIMIT {$limit}";

		return $wpdb->get_results( $sql );
	}

	/**
	 * Upgrade attachment.
	 *
	 * @param mixed $attachment
	 *
	 * @return bool
	 */
	protected function upgrade_item( $attachment ) {
		$limit            = apply_filters( 'as3cf_update_' . $this->upgrade_name . '_sql_limit', 100000 );
		$highest_post_id  = $this->session['blogs'][ $this->blog_id ]['highest_post_id'];
		$last_post_id     = $this->session['blogs'][ $this->blog_id ]['last_post_id'];
		$where_highest_id = is_null( $last_post_id ) ? $highest_post_id : $last_post_id;
		$where_lowest_id  = max( $where_highest_id - $limit, 0 );

		while ( true ) {
			$this->find_and_replace_attachment_urls( $attachment->ID, $where_lowest_id, $where_highest_id );

			if ( $this->batch_limit_reached() ) {
				// Batch limit reached
				break;
			}

			if ( $where_lowest_id <= 0 ) {
				// Batch completed
				return true;
			}

			$where_highest_id = $where_lowest_id;
			$where_lowest_id  = max( $where_lowest_id - $limit, 0 );
		}

		$this->session['blogs'][ $this->blog_id ]['last_post_id'] = $where_lowest_id;

		return false;
	}

	/**
	 * Find and replace embedded URLs for an attachment.
	 *
	 * @param int $attachment_id
	 * @param int $where_lowest_id
	 * @param int $where_highest_id
	 */
	protected function find_and_replace_attachment_urls( $attachment_id, $where_lowest_id, $where_highest_id ) {
		$meta      = wp_get_attachment_metadata( $attachment_id, true );
		$backups   = get_post_meta( $attachment_id, '_wp_attachment_backup_sizes', true );
		$file_path = get_attached_file( $attachment_id, true );

		$new_url = $this->as3cf->get_attachment_local_url( $attachment_id );
		$old_url = $this->as3cf->maybe_remove_query_string( $this->as3cf->get_attachment_url( $attachment_id, null, null, $meta, array(), true ) );

		if ( empty( $old_url ) || empty( $new_url ) ) {
			return;
		}

		$urls = $this->get_find_and_replace_urls( $file_path, $old_url, $new_url, $meta, $backups );

		$this->process_pair_replacement( $urls, $where_lowest_id, $where_highest_id );
	}

	/**
	 * Get find and replace URLs.
	 *
	 * @param string       $file_path
	 * @param string       $old_url
	 * @param string       $new_url
	 * @param array        $meta
	 * @param array|string $backups
	 *
	 * @return array
	 */
	protected function get_find_and_replace_urls( $file_path, $old_url, $new_url, $meta, $backups = '' ) {
		$url_pairs     = array();
		$file_name     = basename( $file_path );
		$old_file_name = basename( $old_url );
		$new_file_name = basename( $new_url );

		// Full size image
		$url_pairs[] = $this->add_url_pair( $file_path, $file_name, $old_url, $old_file_name, $new_url, $new_file_name );

		if ( isset( $meta['thumb'] ) && $meta['thumb'] ) {
			// Replace URLs for legacy thumbnail of image
			$url_pairs[] = $this->add_url_pair( $file_path, $file_name, $old_url, $old_file_name, $new_url, $new_file_name, $meta['thumb'] );
		}

		if ( ! empty( $meta['sizes'] ) ) {
			// Replace URLs for intermediate sizes of image
			foreach ( $meta['sizes'] as $key => $size ) {
				if ( ! isset( $size['file'] ) ) {
					continue;
				}

				$url_pairs[] = $this->add_url_pair( $file_path, $file_name, $old_url, $old_file_name, $new_url, $new_file_name, $size['file'] );
			}
		}

		if ( ! empty( $backups ) ) {
			// Replace URLs for backup images
			foreach ( $backups as $backup ) {
				if ( ! isset( $backup['file'] ) ) {
					continue;
				}

				$url_pairs[] = $this->add_url_pair( $file_path, $file_name, $old_url, $old_file_name, $new_url, $new_file_name, $backup['file'] );
			}
		}

		// Also find encoded file names
		$url_pairs = $this->maybe_add_encoded_url_pairs( $url_pairs );

		// Remove URL protocols
		foreach ( $url_pairs as $key => $url_pair ) {
			$url_pairs[ $key ]['old_url'] = $this->as3cf->remove_scheme( $url_pair['old_url'] );
			$url_pairs[ $key ]['new_url'] = $this->as3cf->remove_scheme( $url_pair['new_url'] );
		}

		return apply_filters( 'as3cf_find_replace_url_pairs', $url_pairs, $file_path, $old_url, $new_url, $meta );
	}

	/**
	 * Add URL pair.
	 *
	 * @param string      $file_path
	 * @param string      $file_name
	 * @param string      $old_url
	 * @param string      $old_file_name
	 * @param string      $new_url
	 * @param string      $new_file_name
	 * @param string|bool $size_file_name
	 *
	 * @return array
	 */
	protected function add_url_pair( $file_path, $file_name, $old_url, $old_file_name, $new_url, $new_file_name, $size_file_name = false ) {
		if ( ! $size_file_name ) {
			return array(
				'old_path' => $file_path,
				'old_url'  => str_replace( $old_file_name, $file_name, $old_url ),
				'new_url'  => $new_url,
			);
		}

		return array(
			'old_path' => str_replace( $file_name, $size_file_name, $file_path ),
			'old_url'  => str_replace( $old_file_name, $size_file_name, $old_url ),
			'new_url'  => str_replace( $new_file_name, $size_file_name, $new_url ),
		);
	}

	/**
	 * Maybe add encoded URL pairs.
	 *
	 * @param array $url_pairs
	 *
	 * @return array
	 */
	protected function maybe_add_encoded_url_pairs( $url_pairs ) {
		foreach ( $url_pairs as $url_pair ) {
			$file_name         = basename( $url_pair['old_url'] );
			$encoded_file_name = $this->as3cf->encode_filename_in_path( $file_name );

			if ( $file_name !== $encoded_file_name ) {
				$url_pair['old_url'] = str_replace( $file_name, $encoded_file_name, $url_pair['old_url'] );
				$url_pairs[]         = $url_pair;
			}
		}

		return $url_pairs;
	}

	/**
	 * Perform the find and replace in the database of old and new URLs.
	 *
	 * @param array $url_pairs
	 * @param int   $where_lowest_id
	 * @param int   $where_highest_id
	 */
	protected function process_pair_replacement( $url_pairs, $where_lowest_id, $where_highest_id ) {
		global $wpdb;

		$posts = $wpdb->get_results( $this->generate_select_sql( $url_pairs, $where_lowest_id, $where_highest_id ) );

		if ( empty( $posts ) ) {
			// Nothing to process, move on
			return;
		}

		// Limit REPLACE statements to 10 per query and INTO to 100 per query
		$url_pairs = array_chunk( $url_pairs, 10 );
		$ids       = array_chunk( wp_list_pluck( $posts, 'ID' ), 100 );

		foreach ( $url_pairs as $url_pairs_chunk ) {
			foreach ( $ids as $ids_chunk ) {
				$wpdb->query( $this->generate_update_sql( $url_pairs_chunk, $ids_chunk ) );
			}
		}
	}

	/**
	 * Generate select SQL.
	 *
	 * @param array $url_pairs
	 * @param int   $where_lowest_id
	 * @param int   $where_highest_id
	 *
	 * @return string
	 */
	protected function generate_select_sql( $url_pairs, $where_lowest_id, $where_highest_id ) {
		global $wpdb;

		$paths = array();

		// Get unique URLs without size string and extension
		foreach ( $url_pairs as $url_pair ) {
			$paths[] = $this->as3cf->remove_size_from_filename( $url_pair['old_url'], true );
		}

		$paths = array_unique( $paths );
		$sql   = '';

		foreach ( $paths as $path ) {
			if ( ! empty( $sql ) ) {
				$sql .= " OR ";
			}

			$sql .= "post_content LIKE '%{$path}%'";
		}

		return "SELECT ID FROM {$wpdb->posts} WHERE ID > {$where_lowest_id} AND ID <= {$where_highest_id} AND ({$sql})";
	}

	/**
	 * Generate update SQL.
	 *
	 * @param array $url_pairs
	 * @param array $ids
	 *
	 * @return string
	 */
	protected function generate_update_sql( $url_pairs, $ids ) {
		global $wpdb;

		$ids = implode( ',', $ids );
		$sql = '';

		foreach ( $url_pairs as $pair ) {
			if ( ! isset( $pair['old_url'] ) || ! isset( $pair['new_url'] ) ) {
				// We need both URLs for the find and replace
				continue;
			}

			if ( empty( $sql ) ) {
				// First replace statement
				$sql = "REPLACE(post_content, '{$pair['old_url']}', '{$pair['new_url']}')";
			} else {
				// Nested replace statement
				$sql = "REPLACE({$sql}, '{$pair['old_url']}', '{$pair['new_url']}')";
			}
		}

		return "UPDATE {$wpdb->posts} SET `post_content` = {$sql} WHERE `ID` IN({$ids})";
	}

	/**
	 * Get running message.
	 *
	 * @return string
	 */
	protected function get_running_message() {
		return sprintf( __( '<strong>Running 1.2 Upgrade%1$s</strong><br>A find &amp; replace is running in the background to update URLs in your content. %2$s', 'amazon-s3-and-cloudfront' ), $this->get_progress_text(), $this->get_generic_message() );
	}

	/**
	 * Get paused message.
	 *
	 * @return string
	 */
	protected function get_paused_message() {
		return sprintf( __( '<strong>Paused 1.2 Upgrade</strong><br>The find &amp; replace to update URLs in your content has been paused. %s', 'amazon-s3-and-cloudfront' ), $this->get_generic_message() );
	}

	/**
	 * Get notice message.
	 *
	 * @return string
	 */
	protected function get_generic_message() {
		$link_text = __( 'See our documentation', 'amazon-s3-and-cloudfront' );
		$link      = $this->as3cf->dbrains_link( 'https://deliciousbrains.com/wp-offload-s3/doc/version-1-2-upgrade', $link_text );

		return sprintf( __( '%s for details on why we&#8217;re doing this, why it runs slowly, and how to make it run faster.', 'amazon-s3-and-cloudfront' ), $link );
	}

	/**
	 * Calculate progress.
	 *
	 * @return bool|int|float
	 */
	protected function calculate_progress() {
		$session = $this->get_session();

		if ( ! isset( $session['total_attachments'] ) || ! isset( $session['processed_attachments'] ) ) {
			// Session data not created, return
			return false;
		}

		if ( ! $session['blogs_processed'] || is_null( $session['total_attachments'] ) || is_null( $session['processed_attachments'] ) ) {
			// Still processing blogs, return 0
			return 0;
		}

		return round( $session['processed_attachments'] / $session['total_attachments'] * 100, 2 );
	}

	/**
	 * Batch limit reached.
	 *
	 * @return bool
	 */
	protected function batch_limit_reached() {
		if ( time() >= $this->finish || $this->as3cf->memory_exceeded( 'as3cf_update_' . $this->upgrade_name . '_memory_exceeded' ) ) {
			return true;
		}

		return false;
	}
}