<?php
/**
 * Co-Authors Plus commands for the WP-CLI framework
 *
 * @package wp-cli
 * @since 3.0
 * @see https://github.com/wp-cli/wp-cli
 */
WP_CLI::add_command( 'co-authors-plus', 'CoAuthorsPlus_Command' );

class CoAuthorsPlus_Command extends WP_CLI_Command {

	/**
	 * Subcommand to create guest authors based on users
	 *
	 * @since 3.0
	 *
	 * @subcommand create-guest-authors
	 */
	public function create_guest_authors( $args, $assoc_args ) {
		global $coauthors_plus;

		$defaults = array(
				// There are no arguments at this time
			);
		$this->args = wp_parse_args( $assoc_args, $defaults );

		$users = get_users();
		$created = 0;
		$skipped = 0;
		foreach( $users as $user ) {

			$result = $coauthors_plus->guest_authors->create_guest_author_from_user_id( $user->ID );
			if ( is_wp_error( $result ) ) {
				$skipped++;
			} else {
				$created++;
			}
		}

		WP_CLI::line( "All done! Here are your results:" );
		WP_CLI::line( "- {$created} guest author profiles were created" );
		WP_CLI::line( "- {$skipped} users already had guest author profiles" );

	}

	/**
	 * Create author terms for all posts that don't have them
	 *
	 * @subcommand create-terms-for-posts
	 */
	public function create_terms_for_posts() {
		global $coauthors_plus, $wp_post_types;

		// Cache these to prevent repeated lookups
		$authors = array();
		$author_terms = array();

		$args = array(
				'order'            => 'ASC',
				'orderby'          => 'ID',
				'post_type'         => $coauthors_plus->supported_post_types,
				'posts_per_page'    => 100,
				'paged'             => 1,
				'update_meta_cache' => false,
			);

		$posts = new WP_Query( $args );
		$affected = 0;
		$count = 0;
		WP_CLI::line( "Now inspecting or updating {$posts->found_posts} total posts." );
		while( $posts->post_count ) {

			foreach( $posts->posts as $single_post ) {

				$count++;
				
				$terms = wp_get_post_terms( $single_post->ID, $coauthors_plus->coauthor_taxonomy );
				if ( is_wp_error( $terms ) )
					WP_CLI::error( $terms->get_error_message() );

				if ( ! empty( $terms ) ) {
					WP_CLI::line( "{$count}/{$posts->found_posts}) Skipping - Post #{$single_post->ID} '{$single_post->post_title}' already has these terms: " . implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
					continue;
				}

				$author = ( ! empty( $authors[$single_post->post_author] ) ) ? $authors[$single_post->post_author] : get_user_by( 'id', $single_post->post_author );
				$authors[$single_post->post_author] = $author;

				$author_term = ( ! empty( $author_terms[$single_post->post_author] ) ) ? $author_terms[$single_post->post_author] : $coauthors_plus->update_author_term( $author );
				$author_terms[$single_post->post_author] = $author_term;

				wp_set_post_terms( $single_post->ID, array( $author_term->slug ), $coauthors_plus->coauthor_taxonomy );
				WP_CLI::line( "{$count}/{$posts->found_posts}) Added - Post #{$single_post->ID} '{$single_post->post_title}' now has an author term for: " . $author->user_nicename );
				$affected++;
				if ( $affected && $affected % 10 == 0 )
					sleep( 3 );
			}

			$this->stop_the_insanity();
			
			$this->args['paged']++;
			$posts = new WP_Query( $this->args );
		}
		WP_CLI::line( "Updating author terms with new counts" );
		foreach( $authors as $author ) {
			$coauthors_plus->update_author_term( $author );
		}

		WP_CLI::success( "Done! Of {$posts->found_posts} posts, {$affected} now have author terms." );

	}

	/**
	 * Subcommand to assign coauthors to a post based on a given meta key
	 *
	 * @since 3.0
	 *
	 * @subcommand assign-coauthors
	 * @synopsis [--meta_key=<key>] [--post_type=<ptype>]
	 */
	public function assign_coauthors( $args, $assoc_args ) {
		global $coauthors_plus;

		$defaults = array(
				'meta_key'         => '_original_import_author',
				'post_type'        => 'post',
				'order'            => 'ASC',
				'orderby'          => 'ID',
				'posts_per_page'   => 100,
				'paged'            => 1,
				'append_coauthors' => false,
			);
		$this->args = wp_parse_args( $assoc_args, $defaults );

		// For global use and not a part of WP_Query
		$append_coauthors = $this->args['append_coauthors'];
		unset( $this->args['append_coauthors'] );

		$posts_total = 0;
		$posts_already_associated = 0;
		$posts_missing_coauthor = 0;
		$posts_associated = 0;
		$missing_coauthors = array();

		$posts = new WP_Query( $this->args );
		while( $posts->post_count ) {

			foreach( $posts->posts as $single_post ) {
				$posts_total++;

				// See if the value in the post meta field is the same as any of the existing coauthors
				$original_author = get_post_meta( $single_post->ID, $this->args['meta_key'], true );
				$existing_coauthors = get_coauthors( $single_post->ID );
				$already_associated = false;
				foreach( $existing_coauthors as $existing_coauthor ) {
					if ( $original_author == $existing_coauthor->user_login )
						$already_associated = true;
				}
				if ( $already_associated ) {
					$posts_already_associated++;
					WP_CLI::line( $posts_total . ': Post #' . $single_post->ID . ' already has "' . $original_author . '" associated as a coauthor' );
					continue;
				}

				// Make sure this original author exists as a co-author
				if ( !$coauthors_plus->get_coauthor_by( 'user_login', $original_author ) ) {
					$posts_missing_coauthor++;
					$missing_coauthors[] = $original_author;
					WP_CLI::line( $posts_total . ': Post #' . $single_post->ID . ' does not have "' . $original_author . '" associated as a coauthor but there is not a coauthor profile' );
					continue;
				}

				// Assign the coauthor to the post
				$coauthors_plus->add_coauthors( $single_post->ID, array( $original_author ), $append_coauthors );
				WP_CLI::line( $posts_total . ': Post #' . $single_post->ID . ' has been assigned "' . $original_author . '" as the author' );
				$posts_associated++;
				clean_post_cache( $single_post->ID );
			}
			
			$this->args['paged']++;
			$this->stop_the_insanity();
			$posts = new WP_Query( $this->args );
		}

		WP_CLI::line( "All done! Here are your results:" );
		if ( $posts_already_associated )
			WP_CLI::line( "- {$posts_already_associated} posts already had the coauthor assigned" );
		if ( $posts_missing_coauthor ) {
			WP_CLI::line( "- {$posts_missing_coauthor} posts reference coauthors that don't exist. These are:" );
			WP_CLI::line( "  " . implode( ', ', array_unique( $missing_coauthors ) ) );
		}
		if ( $posts_associated )
			WP_CLI::line( "- {$posts_associated} posts now have the proper coauthor" );

	}

	/**
	 * Assign posts associated with a WordPress user to a co-author
	 * Only apply the changes if there aren't yet co-authors associated with the post
	 *
	 * @since 3.0
	 *
	 * @subcommand assign-user-to-coauthor
	 * @synopsis --user_login=<user-login> --coauthor=<coauthor>
	 */
	public function assign_user_to_coauthor( $args, $assoc_args ) {
		global $coauthors_plus, $wpdb;

		$defaults = array(
				'user_login'        => '',
				'coauthor'          => '',
			);
		$assoc_args = wp_parse_args( $assoc_args, $defaults );

		$user = get_user_by( 'login', $assoc_args['user_login'] );
		$coauthor = $coauthors_plus->get_coauthor_by( 'login', $assoc_args['coauthor'] );

		if ( ! $user )
			WP_CLI::error( __( 'Please specify a valid user_login', 'co-authors-plus' ) );

		if ( ! $coauthor )
			WP_CLI::error( __( 'Please specify a valid co-author login', 'co-authors-plus' ) );

		$post_types = implode( "','", $coauthors_plus->supported_post_types );
		$posts = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_author=%d AND post_type IN ('$post_types')", $user->ID ) );
		$affected = 0;
		foreach( $posts as $post_id ) {
			if ( $coauthors = wp_get_post_terms( $post_id, $coauthors_plus->coauthor_taxonomy ) ) {
				WP_CLI::line( sprintf( __( "Skipping - Post #%d already has co-authors assigned: %s", 'co-authors-plus' ), $post_id, implode( ', ', wp_list_pluck( $coauthors, 'slug' ) ) ) );
				continue;
			}

			$coauthors_plus->add_coauthors( $post_id, array( $coauthor->user_login ) );
			WP_CLI::line( sprintf( __( "Updating - Adding %s's byline to post #%d", 'co-authors-plus' ), $coauthor->user_login, $post_id ) );
			$affected++;
			if ( $affected && $affected % 20 == 0 )
				sleep( 5 );
		}
		WP_CLI::success( sprintf( __( "All done! %d posts were affected.", 'co-authors-plus' ), $affected ) );

	}

	/**
	 * Subcommand to reassign co-authors based on some given format
	 * This will look for terms with slug 'x' and rename to term with slug and name 'y'
	 * This subcommand can be helpful for cleaning up after an import if the usernames
	 * for authors have changed. During the import process, 'author' terms will be
	 * created with the old user_login value. We can use this to migrate to the new user_login
	 *
	 * @todo support reassigning by CSV
	 *
	 * @since 3.0
	 *
	 * @subcommand reassign-terms
	 * @synopsis [--author-mapping=<file>] [--old_term=<slug>] [--new_term=<slug>]
	 */
	public function reassign_terms( $args, $assoc_args ) {
		global $coauthors_plus;

		$defaults = array(
				'author_mapping'    => null,
				'old_term'          => null,
				'new_term'          => null,
			);
		$this->args = wp_parse_args( $assoc_args, $defaults );

		$author_mapping = $this->args['author_mapping'];
		$old_term = $this->args['old_term'];
		$new_term = $this->args['new_term'];

		// Get the reassignment data
		if ( $author_mapping && file_exists( $author_mapping ) ) {
			require_once( $author_mapping );
			$authors_to_migrate = $cli_user_map;
		} else if ( $author_mapping ) {
			WP_CLI::error( "author_mapping doesn't exist: " . $author_mapping );
			exit;
		}

		// Alternate reassigment approach
		if ( $old_term && $new_term ) {
			$authors_to_migrate = array(
					$old_term => $new_term,
				);
		}

		// For each author to migrate, check whether the term exists,
		// whether the target term exists, and only do the migration if both are met
		$results = (object)array(
				'old_term_missing' => 0,
				'new_term_exists' => 0,
				'success' => 0,
			);
		foreach( $authors_to_migrate as $old_user => $new_user ) {

			if ( is_numeric( $new_user ) )
				$new_user = get_user_by( 'id', $new_user )->user_login;

			// The old user should exist as a term
			$old_term = $coauthors_plus->get_author_term( $coauthors_plus->get_coauthor_by( 'login', $old_user ) );
			if ( !$old_term ) {
				WP_CLI::line( "Error: Term '{$old_user}' doesn't exist, skipping" );
				$results->old_term_missing++;
				continue;
			}

			// If the new user exists as a term already, we want to reassign all posts to that
			// new term and delete the original
			// Otherwise, simply rename the old term
			$new_term = $coauthors_plus->get_author_term( $coauthors_plus->get_coauthor_by( 'login', $new_user ) );
			if ( is_object( $new_term ) ) {
				WP_CLI::line( "Success: There's already a '{$new_user}' term for '{$old_user}'. Reassigning {$old_term->count} posts and then deleting the term" );
				$args = array(
						'default' => $new_term->term_id,
						'force_default' => true,
					);
				wp_delete_term( $old_term->term_id, $coauthors_plus->coauthor_taxonomy, $args );
				$results->new_term_exists++;
			} else {
				$args = array(
						'slug' => $new_user,
						'name' => $new_user,
					);
				wp_update_term( $old_term->term_id, $coauthors_plus->coauthor_taxonomy, $args );
				WP_CLI::line( "Success: Converted '{$old_user}' term to '{$new_user}'" );
				$results->success++;
			}
			clean_term_cache( $old_term->term_id, $coauthors_plus->coauthor_taxonomy );
		}

		WP_CLI::line( "Reassignment complete. Here are your results:" );
		WP_CLI::line( "- $results->success authors were successfully reassigned terms" );
		WP_CLI::line( "- $results->new_term_exists authors had their old term merged to their new term" );
		WP_CLI::line( "- $results->old_term_missing authors were missing old terms" );

	}

	/**
	 * Change a term from representing one user_login value to another
	 * If the term represents a guest author, the post_name will be changed
	 * in addition to the term slug/name
	 *
	 * @since 3.0.1
	 *
	 * @subcommand rename-coauthor
	 * @synopsis --from=<user-login> --to=<user-login>
	 */
	public function rename_coauthor( $args, $assoc_args ) {
		global $coauthors_plus, $wpdb;

		$defaults = array(
				'from'      => null,
				'to'        => null,
			);
		$assoc_args = array_merge( $defaults, $assoc_args );

		$to_userlogin = $assoc_args['to'];
		$to_userlogin_prefixed = 'cap-' . $to_userlogin;

		$orig_coauthor = $coauthors_plus->get_coauthor_by( 'user_login', $assoc_args['from'] );
		if ( ! $orig_coauthor )
			WP_CLI::error( "No co-author found for {$assoc_args['from']}" );

		if ( ! $to_userlogin )
			WP_CLI::error( '--to param must not be empty' );

		if ( $coauthors_plus->get_coauthor_by( 'user_login', $to_userlogin ) )
			WP_CLI::error( "New user_login value conflicts with existing co-author" );

		$orig_term = $coauthors_plus->get_author_term( $orig_coauthor );

		WP_CLI::line( "Renaming {$orig_term->name} to {$to_userlogin}" );
		$rename_args = array(
				'name'         => $to_userlogin,
				'slug'         => $to_userlogin_prefixed,
			);
		wp_update_term( $orig_term->term_id, $coauthors_plus->coauthor_taxonomy, $rename_args );

		if ( 'guest-author' == $orig_coauthor->type ) {
			$wpdb->update( $wpdb->posts, array( 'post_name' => $to_userlogin_prefixed ), array( 'ID' => $orig_coauthor->ID ) );
			clean_post_cache( $orig_coauthor->ID );
			update_post_meta( $orig_coauthor->ID, 'cap-user_login', $to_userlogin );
			$coauthors_plus->guest_authors->delete_guest_author_cache( $orig_coauthor->ID );
			WP_CLI::line( "Updated guest author profile value too" );
		}

		WP_CLI::success( "All done!" );
	}

	/**
	 * Swap one Co Author with another on all posts for which they are an author. Unlike rename-coauthor,
	 * this leaves the original Co Author term intact and works when the 'to' user already has a co-author term.
	 *
	 * @subcommand swap-coauthors
	 * @synopsis --from=<user-login> --to=<user-login> [--post_type=<ptype>] [--dry=<dry>]
	 */
	public function swap_coauthors( $args, $assoc_args ) {
		global $coauthors_plus, $wpdb;

		$defaults = array(
			'from'      => null,
			'to'        => null,
			'post_type'	=> 'post',
			'dry'		=> false
		);

		$assoc_args = array_merge( $defaults, $assoc_args );

		$dry 						= $assoc_args['dry'];

		$from_userlogin 			= $assoc_args['from'];
		$to_userlogin 				= $assoc_args['to'];

		$from_userlogin_prefixed	= 'cap-' . $from_userlogin;
		$to_userlogin_prefixed 		= 'cap-' . $to_userlogin;

		$orig_coauthor = $coauthors_plus->get_coauthor_by( 'user_login', $from_userlogin );

		if ( ! $orig_coauthor )
			WP_CLI::error( "No co-author found for $from_userlogin" );

		if ( ! $to_userlogin )
			WP_CLI::error( '--to param must not be empty' );

		$to_coauthor = $coauthors_plus->get_coauthor_by( 'user_login', $to_userlogin );

		if ( ! $to_coauthor )
			WP_CLI::error( "No co-author found for $to_userlogin" );

		WP_CLI::line( "Swapping authorship from {$from_userlogin} to {$to_userlogin}" );

		$query_args = array( 
			'post_type'        	=> $assoc_args['post_type'],
			'order'            	=> 'ASC',
			'orderby'          	=> 'ID',
			'posts_per_page'   	=> 100,
			'paged'            	=> 1,
			'tax_query'			=> array(
				array(
					'taxonomy' 	=> $coauthors_plus->coauthor_taxonomy,
					'field'		=> 'slug',
					'terms'		=> array( $from_userlogin_prefixed )
				)
			)
		);

		$posts = new WP_Query( $query_args );

		$posts_total = 0;

		WP_CLI::line( "Found $posts->found_posts posts to update." );

		while( $posts->post_count ) {
			foreach( $posts->posts as $post ) {
				$coauthors = get_coauthors( $post->ID );

				if ( ! is_array( $coauthors ) || ! count( $coauthors ) )
					continue;

				$coauthors = wp_list_pluck( $coauthors, 'user_login' );

				$posts_total++;
				
				if ( ! $dry ) {
					// Remove the $from_userlogin from $coauthors
					foreach( $coauthors as $index => $user_login ) {
						if ( $from_userlogin === $user_login ) {
							unset( $coauthors[ $index ] );

							break;
						}
					}

					// Add the 'to' author on
					$coauthors[] = $to_userlogin;

					// By not passing $append = false as the 3rd param, we replace all existing coauthors
					$coauthors_plus->add_coauthors( $post->ID, $coauthors, false );
				
					WP_CLI::line( $posts_total . ': Post #' . $post->ID . ' has been assigned "' . $to_userlogin . '" as a co-author' );
					
					clean_post_cache( $post->ID );
				} else {
					WP_CLI::line( $posts_total . ': Post #' . $post->ID . ' will be assigned "' . $to_userlogin . '" as a co-author' );
				}
			}

			$this->stop_the_insanity();

			$posts = new WP_Query( $query_args );
		}

		WP_CLI::success( "All done!" );
	}

	/**
	 * List all of the posts without assigned co-authors terms
	 *
	 * @since 3.0
	 *
	 * @subcommand list-posts-without-terms
	 * @synopsis [--post_type=<ptype>]
	 */
	public function list_posts_without_terms( $args, $assoc_args ) {
		global $coauthors_plus;

		$defaults = array(
				'post_type'         => 'post',
				'order'             => 'ASC',
				'orderby'           => 'ID',
				'year'              => '',
				'posts_per_page'    => 300,
				'paged'             => 1,
				'no_found_rows'     => true,
				'update_meta_cache' => false,
			);
		$this->args = wp_parse_args( $assoc_args, $defaults );

		$posts = new WP_Query( $this->args );
		while( $posts->post_count ) {

			foreach( $posts->posts as $single_post ) {
				
				$terms = wp_get_post_terms( $single_post->ID, $coauthors_plus->coauthor_taxonomy );
				if ( empty( $terms ) ) {
					$saved = array(
							$single_post->ID,
							addslashes( $single_post->post_title ),
							get_permalink( $single_post->ID ),
							$single_post->post_date,
						);
					WP_CLI::line( '"' . implode( '","', $saved ) . '"' );
				}
			}

			$this->stop_the_insanity();
			
			$this->args['paged']++;
			$posts = new WP_Query( $this->args );
		}

	}

	/**
	 * Migrate author terms without prefixes to ones with prefixes
	 * Pre-3.0, all author terms didn't have a 'cap-' prefix, which means
	 * they can easily collide with terms in other taxonomies
	 *
	 * @since 3.0
	 * 
	 * @subcommand migrate-author-terms
	 */
	public function migrate_author_terms( $args, $assoc_args ) {
		global $coauthors_plus;

		$author_terms = get_terms( $coauthors_plus->coauthor_taxonomy, array( 'hide_empty' => false ) );
		WP_CLI::line( "Now migrating up to " . count( $author_terms ) . " terms" );
		foreach( $author_terms as $author_term ) {
			// Term is already prefixed. We're good.
			if ( preg_match( '#^cap\-#', $author_term->slug, $matches ) ) {
				WP_CLI::line( "Term {$author_term->slug} ({$author_term->term_id}) is already prefixed, skipping" );
				continue;
			}
			// A prefixed term was accidentally created, and the old term needs to be merged into the new (WordPress.com VIP)
			if ( $prefixed_term = get_term_by( 'slug', 'cap-' . $author_term->slug, $coauthors_plus->coauthor_taxonomy ) ) {
				WP_CLI::line( "Term {$author_term->slug} ({$author_term->term_id}) has a new term too: $prefixed_term->slug ($prefixed_term->term_id). Merging" );
				$args = array(
					'default' => $author_term->term_id,
					'force_default' => true,
				);
				wp_delete_term( $prefixed_term->term_id, $coauthors_plus->coauthor_taxonomy, $args );
			}

			// Term isn't prefixed, doesn't have a sibling, and should be updated
			WP_CLI::line( "Term {$author_term->slug} ({$author_term->term_id}) isn't prefixed, adding one" );
			$args = array(
					'slug' => 'cap-' . $author_term->slug,
				);
			wp_update_term( $author_term->term_id, $coauthors_plus->coauthor_taxonomy, $args );
		}
		WP_CLI::success( "All done! Grab a cold one (Affogatto)" );
	}

	/**
	 * Update the post count and description for each author
	 *
	 * @since 3.0
	 *
	 * @subcommand update-author-terms
	 */
	public function update_author_terms() {
		global $coauthors_plus;
		$author_terms = get_terms( $coauthors_plus->coauthor_taxonomy, array( 'hide_empty' => false ) );
		WP_CLI::line( "Now updating " . count( $author_terms ) . " terms" );
		foreach( $author_terms as $author_term ) {
			$old_count = $author_term->count;
			$coauthor = $coauthors_plus->get_coauthor_by( 'user_nicename', $author_term->slug );
			$coauthors_plus->update_author_term( $coauthor );
			$coauthors_plus->update_author_term_post_count( $author_term );
			wp_cache_delete( $author_term->term_id, $coauthors_plus->coauthor_taxonomy );
			$new_count = get_term_by( 'id', $author_term->term_id, $coauthors_plus->coauthor_taxonomy )->count;
			WP_CLI::line( "Term {$author_term->slug} ({$author_term->term_id}) changed from {$old_count} to {$new_count} and the description was refreshed" );
		}
		// Create author terms for any users that don't have them
		$users = get_users();
		foreach( $users as $user ) {
			$term = $coauthors_plus->get_author_term( $user );
			if ( empty( $term ) || empty( $term->description ) ) {
				$coauthors_plus->update_author_term( $user );
				WP_CLI::line( "Created author term for {$user->user_login}" );
			}
		}

		// And create author terms for any Guest Authors that don't have them
		if ( $coauthors_plus->is_guest_authors_enabled() && $coauthors_plus->guest_authors instanceof CoAuthors_Guest_Authors ) {
			$args = array(
				'order'             => 'ASC',
				'orderby'           => 'ID',
				'post_type'         => $coauthors_plus->guest_authors->post_type,
				'posts_per_page'    => 100,
				'paged'             => 1,
				'update_meta_cache' => false,
				'fields'            => 'ids'
			);

			$posts = new WP_Query( $args );
			$count = 0;
			WP_CLI::line( "Now inspecting or updating {$posts->found_posts} Guest Authors." );

			while( $posts->post_count ) {
				foreach( $posts->posts as $guest_author_id ) {
					$count++;

					$guest_author = $coauthors_plus->guest_authors->get_guest_author_by( 'ID', $guest_author_id );

					if ( ! $guest_author ) {
						WP_CLI::line( 'Failed to load guest author ' . $guest_author_id );

						continue;
					}

					$term = $coauthors_plus->get_author_term( $guest_author );

					if ( empty( $term ) || empty( $term->description ) ) {
						$coauthors_plus->update_author_term( $guest_author );

						WP_CLI::line( "Created author term for Guest Author {$guest_author->user_nicename}" );
					}
				}

				$this->stop_the_insanity();
				
				$args['paged']++;
				$posts = new WP_Query( $args );
			}
		}
		 
		WP_CLI::success( "All done" );
	}

	/**
	 * Remove author terms from revisions, which we've been adding since the dawn of time
	 *
	 * @since 3.0.1
	 *
	 * @subcommand remove-terms-from-revisions
	 */
	public function remove_terms_from_revisions() {
		global $wpdb;

		$ids = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_type='revision' AND post_status='inherit'" );

		WP_CLI::line( "Found " . count( $ids ) . " revisions to look through" );
		$affected = 0;
		foreach( $ids as $post_id ) {

			$terms = wp_get_post_terms( $post_id, 'author' );
			if ( ! $terms )
				continue;

			WP_CLI::line( "#{$post_id}: Removing " . implode( ',', wp_list_pluck( $terms, 'slug' ) ) );
			wp_set_post_terms( $post_id, array(), 'author' );
			$affected++;
		}
		WP_CLI::line( "All done! {$affected} revisions had author terms removed" );
	}

	/**
	 * Subcommand to create guest authors from an author list in a WXR file
	 *
	 * @subcommand create-guest-authors-from-wxr
	 * @synopsis --file=</path/to/file.wxr>
	 */
	public function create_guest_authors_from_wxr( $args, $assoc_args ) {
		global $coauthors_plus;

		$defaults = array(
			'file' => '',
		);
		$this->args = wp_parse_args( $assoc_args, $defaults );

		if ( empty( $this->args['file'] ) || ! is_readable( $this->args['file'] ) )
			WP_CLI::error( 'Please specify a valid WXR file with the --file arg.' );

		if ( ! class_exists( 'WXR_Parser' ) )
			require_once( WP_CONTENT_DIR . '/admin-plugins/wordpress-importer/parsers.php' );

		$parser = new WXR_Parser();
		$import_data = $parser->parse( $this->args['file'] );

		if ( is_wp_error( $import_data ) )
			WP_CLI::error( 'Failed to read WXR file.' );

		// Get author nodes
		$authors = $import_data['authors'];

		foreach ( $authors as $author ) {
			WP_CLI::line( sprintf( 'Processing author %s (%s)', $author['author_login'], $author['author_email'] ) );

			$guest_author_data = array(
				'display_name' => $author['author_display_name'],
				'user_login' => $author['author_login'],
				'user_email' => $author['author_email'],
				'first_name' => $author['author_first_name'],
				'last_name' => $author['author_last_name'],
				'ID' => $author['author_id'],
			);

			$guest_author_id = $this->create_guest_author( $guest_author_data );
		}

		WP_CLI::line( 'All done!' );
	}

	/**
	 * Subcommand to create guest authors from an author list in a CSV file
	 *
	 * @subcommand create-guest-authors-from-csv
	 * @synopsis --file=</path/to/file.csv>
	 */
	public function create_guest_authors_from_csv( $args, $assoc_args ) {
		global $coauthors_plus;

		$defaults = array(
			'file' => '',
		);
		$this->args = wp_parse_args( $assoc_args, $defaults );

		if ( empty( $this->args['file'] ) || ! is_readable( $this->args['file'] ) )
			WP_CLI::error( 'Please specify a valid CSV file with the --file arg.' );

		$file = fopen( $this->args['file'], 'r' );

		if ( ! $file )
			WP_CLI::error( 'Failed to read file.' );

		$authors = array();

		$row = 0;
		while ( false !== ( $data = fgetcsv( $file ) ) ) {
			if ( $row == 0 ) {
				$field_keys = array_map( 'trim', $data );
				// TODO: bail if required fields not found
			} else {
				$row_data = array_map( 'trim', $data );
				$author_data = array();
				foreach( (array) $row_data as $col_num => $val ) {
						// Don't use the value of the field key isn't set
						if ( empty( $field_keys[$col_num] ) )
							continue;
					$author_data[$field_keys[$col_num]] = $val;
				}
				
				$authors[] = $author_data;
			}
			$row++;
		}
		fclose( $file );

		WP_CLI::line( "Found " . count( $authors ) . " authors in CSV" );

		foreach ( $authors as $author ) {
			WP_CLI::line( sprintf( 'Processing author %s (%s)', $author['user_login'], $author['user_email'] ) );

			$guest_author_data = array(
				'display_name' => sanitize_text_field( $author['display_name'] ),
				'user_login' => sanitize_user( $author['user_login'] ),
				'user_email' => sanitize_email( $author['user_email'] ),
			);

			$display_name_space_pos = strpos( $author['display_name'], ' ' );

			if ( false !== $display_name_space_pos && empty( $author['first_name'] ) && empty( $author['last_name'] ) ) {
				$first_name = substr( $author['display_name'], 0, $display_name_space_pos );
				$last_name = substr( $author['display_name'], ( $display_name_space_pos + 1 ) );

				$guest_author_data['first_name'] = sanitize_text_field( $first_name );
				$guest_author_data['last_name'] = sanitize_text_field( $last_name );
			} elseif ( ! empty( $author['first_name'] ) && ! empty( $author['last_name'] ) ) {
				$guest_author_data['first_name'] = sanitize_text_field( $author['first_name'] );
				$guest_author_data['last_name'] = sanitize_text_field( $author['last_name'] );
			}

			$guest_author_id = $this->create_guest_author( $guest_author_data );
		}

		WP_CLI::line( 'All done!' );
	}

	private function create_guest_author( $author ) {
		global $coauthors_plus;
		$guest_author = $coauthors_plus->guest_authors->get_guest_author_by( 'user_email', $author['user_email'], true );

		if ( ! $guest_author ) {
			$guest_author = $coauthors_plus->guest_authors->get_guest_author_by( 'user_login', $author['user_login'], true );
		}

		if ( ! $guest_author ) {
			WP_CLI::line( '-- Not found; creating profile.' );

			$guest_author_id = $coauthors_plus->guest_authors->create( array(
				'display_name' => $author['display_name'],
				'user_login' => $author['user_login'],
				'user_email' => $author['user_email'],
				'first_name' => $author['first_name'],
				'last_name' => $author['last_name'],
			) );

			if ( $guest_author_id ) {
				WP_CLI::line( sprintf( '-- Created as guest author #%s', $guest_author_id ) );

				if ( isset( $author['author_id'] ) )
					update_post_meta( $guest_author_id, '_original_author_id', $author['ID'] );

				update_post_meta( $guest_author_id, '_original_author_login', $author['user_login'] );
			} else {
				WP_CLI::warning( "-- Failed to create guest author." );
			}
		} else {
			WP_CLI::line( sprintf( '-- Author already exists (ID #%s); skipping.', $guest_author->ID ) );
		}
	}

	/**
	 * Clear all of the caches for memory management
	 */
	private function stop_the_insanity() {
		global $wpdb, $wp_object_cache;

		$wpdb->queries = array(); // or define( 'WP_IMPORTING', true );

		if ( !is_object( $wp_object_cache ) )
			return;

		$wp_object_cache->group_ops = array();
		$wp_object_cache->stats = array();
		$wp_object_cache->memcache_debug = array();
		$wp_object_cache->cache = array();

		if( is_callable( $wp_object_cache, '__remoteset' ) )
			$wp_object_cache->__remoteset(); // important
	}
	
}