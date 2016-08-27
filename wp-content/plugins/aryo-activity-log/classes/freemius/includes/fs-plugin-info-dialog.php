<?php
	/**
	 * @package     Freemius
	 * @copyright   Copyright (c) 2015, Freemius, Inc.
	 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
	 * @since       1.0.6
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * Class FS_Plugin_Info_Dialog
	 *
	 * @author Vova Feldman (@svovaf)
	 * @since  1.1.7
	 */
	class FS_Plugin_Info_Dialog {
		/**
		 * @since 1.1.7
		 *
		 * @var FS_Logger
		 */
		private $_logger;

		/**
		 * @since 1.1.7
		 *
		 * @var Freemius
		 */
		private $_fs;

		function __construct(Freemius $fs) {
			$this->_fs = $fs;

			$this->_logger = FS_Logger::get_logger( WP_FS__SLUG . '_' . $fs->get_slug() . '_info', WP_FS__DEBUG_SDK, WP_FS__ECHO_DEBUG_SDK );

			// Remove default plugin information action.
			remove_all_actions( 'install_plugins_pre_plugin-information' );

			// Override action with custom plugins function for add-ons.
			add_action( 'install_plugins_pre_plugin-information', array( &$this, 'install_plugin_information' ) );

			// Override request for plugin information for Add-ons.
			add_filter(
				'fs_plugins_api',
				array( &$this, '_get_addon_info_filter' ),
				WP_FS__DEFAULT_PRIORITY, 3 );
		}

		/**
		 * Generate add-on plugin information.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  1.0.6
		 *
		 * @param array       $data
		 * @param string      $action
		 * @param object|null $args
		 *
		 * @return array|null
		 */
		function _get_addon_info_filter( $data, $action = '', $args = null ) {
			$this->_logger->entrance();

			$parent_plugin_id = fs_request_get( 'parent_plugin_id', false );

			if ( $this->_fs->get_id() != $parent_plugin_id ||
			     ( 'plugin_information' !== $action ) ||
			     ! isset( $args->slug )
			) {
				return $data;
			}

			// Find add-on by slug.
			$addons         = $this->_fs->get_addons();
			$selected_addon = false;
			foreach ( $addons as $addon ) {
				if ( $addon->slug == $args->slug ) {
					$selected_addon = $addon;
					break;
				}
			}

			if ( false === $selected_addon ) {
				return $data;
			}

			if ( ! isset( $selected_addon->info ) ) {
				// Setup some default info.
				$selected_addon->info                  = new stdClass();
				$selected_addon->info->selling_point_0 = 'Selling Point 1';
				$selected_addon->info->selling_point_1 = 'Selling Point 2';
				$selected_addon->info->selling_point_2 = 'Selling Point 3';
				$selected_addon->info->description     = '<p>Tell your users all about your add-on</p>';
			}

			fs_enqueue_local_style( 'fs_addons', '/admin/add-ons.css' );

			$data = $args;

			$is_free = true;

			// Load add-on pricing.
			$has_pricing  = false;
			$has_features = false;
			$plans        = false;
			$plans_result = $this->_fs->get_api_site_or_plugin_scope()->get( "/addons/{$selected_addon->id}/plans.json" );
			if ( ! isset( $plans_result->error ) ) {
				$plans = $plans_result->plans;
				if ( is_array( $plans ) ) {
					foreach ( $plans as &$plan ) {
						$pricing_result = $this->_fs->get_api_site_or_plugin_scope()->get( "/addons/{$selected_addon->id}/plans/{$plan->id}/pricing.json" );
						if ( ! isset( $pricing_result->error ) ) {
							// Update plan's pricing.
							$plan->pricing = $pricing_result->pricing;

							if ( is_array( $plan->pricing ) && ! empty( $plan->pricing ) ) {
								$is_free = false;
							}

							$has_pricing = true;
						}

						$features_result = $this->_fs->get_api_site_or_plugin_scope()->get( "/addons/{$selected_addon->id}/plans/{$plan->id}/features.json" );
						if ( ! isset( $features_result->error ) &&
						     is_array( $features_result->features ) &&
						     0 < count( $features_result->features )
						) {
							// Update plan's pricing.
							$plan->features = $features_result->features;

							$has_features = true;
						}
					}
				}
			}

			// Fetch latest version from Freemius.
			$latest = $this->_fs->_fetch_latest_version( $selected_addon->id );

			// If not versions found, then assume it's a .org plugin.
			$is_wordpress_org = !$is_free || ( false === $latest );

			if ( $is_free ) {
				if ( $is_wordpress_org ) {

					$data = FS_Plugin_Updater::_fetch_plugin_info_from_repository(
						'plugin_information', (object) array(
							'slug' => $selected_addon->slug,
							'is_ssl' => is_ssl(),
							'fields' => array(
								'banners' => true,
								'reviews' => true,
								'downloaded' => false,
								'active_installs' => true
							)
						) );
				} else {
					$data->download_link = $this->_fs->_get_latest_download_local_url( $selected_addon->id );
				}
			} else {
				$is_wordpress_org    = false;
				$data->checkout_link = $this->_fs->checkout_url();
			}

			if ( ! $is_wordpress_org ) {
// Fetch as much as possible info from local files.
				$plugin_local_data = $this->_fs->get_plugin_data();
				$data->name        = $selected_addon->title;
				$data->author      = $plugin_local_data['Author'];
				$view_vars         = array( 'plugin' => $selected_addon );
				$data->sections    = array(
					'description' => fs_get_template( '/plugin-info/description.php', $view_vars ),
				);

				if ( ! empty( $selected_addon->info->banner_url ) ) {
					$data->banners = array(
						'low' => $selected_addon->info->banner_url,
					);
				}

				if ( ! empty( $selected_addon->info->screenshots ) ) {
					$view_vars                     = array(
						'screenshots' => $selected_addon->info->screenshots,
						'plugin'      => $selected_addon,
					);
					$data->sections['screenshots'] = fs_get_template( '/plugin-info/screenshots.php', $view_vars );
				}

				if ( is_object( $latest ) ) {
					$data->version      = $latest->version;
					$data->last_updated = ! is_null( $latest->updated ) ? $latest->updated : $latest->created;
					$data->requires     = $latest->requires_platform_version;
					$data->tested       = $latest->tested_up_to_version;
				} else {
					// Add dummy version.
					$data->version = '1.0.0';

					// Add message to developer to deploy the plugin through Freemius.
				}
			}

			if ( $has_pricing ) {
				// Add plans to data.
				$data->plans = $plans;

				if ( $has_features ) {
					$view_vars                  = array(
						'plans'  => $plans,
						'plugin' => $selected_addon,
					);
					$data->sections['features'] = fs_get_template( '/plugin-info/features.php', $view_vars );
				}
			}

			$data->is_paid  = ! $is_free;
			$data->external = ! $is_wordpress_org;

			return $data;
		}

		/**
		 * @author Vova Feldman (@svovaf)
		 * @since  1.1.7
		 *
		 * @param object $plan
		 *
		 * @return string
		 */
		private function get_billing_cycle( $plan ) {
			$billing_cycle = 'annual';

			if ( 1 === count( $plan->pricing ) && 1 == $plan->pricing[0]->licenses ) {
				$pricing = $plan->pricing[0];
				if ( isset( $pricing->annual_price ) ) {
					$billing_cycle = 'annual';
				} else if ( isset( $pricing->monthly_price ) ) {
					$billing_cycle = 'monthly';
				} else if ( isset( $pricing->lifetime_price ) ) {
					$billing_cycle = 'lifetime';
				}
			} else {
				foreach ( $plan->pricing as $pricing ) {
					if ( isset( $pricing->annual_price ) ) {
						$billing_cycle = 'annual';
					} else if ( isset( $pricing->monthly_price ) ) {
						$billing_cycle = 'monthly';
					} else if ( isset( $pricing->lifetime_price ) ) {
						$billing_cycle = 'lifetime';
					}
				}
			}

			return $billing_cycle;
		}

		/**
		 * @author Vova Feldman (@svovaf)
		 * @since  1.1.7
		 *
		 * @param object $api
		 *
		 * @return string
		 */
		private function get_plugin_cta( $api ) {
			if ( ( current_user_can( 'install_plugins' ) || current_user_can( 'update_plugins' ) ) ) {

				if ( ! empty( $api->checkout_link ) && isset( $api->plans ) && 0 < is_array( $api->plans ) ) {
					$plan = $api->plans[0];

					return ' <a class="button button-primary right" href="' . esc_url( add_query_arg( array(
						'plugin_id'     => $plan->plugin_id,
						'plan_id'       => $plan->id,
						'pricing_id'    => $plan->pricing[0]->id,
						'billing_cycle' => $this->get_billing_cycle( $plan ),
					), $api->checkout_link ) ) . '" target="_parent">' . __fs( 'purchase', $api->slug ) . '</a>';

					// @todo Add Cart concept.
//			echo ' <a class="button right" href="' . $status['url'] . '" target="_parent">' . __( 'Add to Cart' ) . '</a>';

				} else if ( ! empty( $api->download_link ) ) {
					$status = install_plugin_install_status( $api );


						// Hosted on WordPress.org.
						switch ( $status['status'] ) {
							case 'install':
								if ($api->external &&
								    $this->_fs->is_org_repo_compliant() ||
								    !$this->_fs->is_premium())
								{
									/**
									 * Add-on hosted on Freemius, not yet installed, and core
									 * plugin is wordpress.org compliant. Therefore, require a download
									 * since installing external plugins is not allowed by the wp.org guidelines.
									 */
									return ' <a class="button button-primary right" href="' . esc_url( $api->download_link ) . '" target="_blank">' . __fs( 'download-latest', $api->slug ) . '</a>';
								}
								else {
									if ( $status['url'] ) {
										return '<a class="button button-primary right" href="' . $status['url'] . '" target="_parent">' . __( 'Install Now' ) . '</a>';
									}
								}
								break;
							case 'update_available':
								if ( $status['url'] ) {
									return '<a class="button button-primary right" href="' . $status['url'] . '" target="_parent">' . __( 'Install Update Now' ) . '</a>';
								}
								break;
							case 'newer_installed':
								return '<a class="button button-primary right disabled">' . sprintf( __( 'Newer Version (%s) Installed' ), $status['version'] ) . '</a>';
								break;
							case 'latest_installed':
								return '<a class="button button-primary right disabled">' . __( 'Latest Version Installed' ) . '</a>';
								break;
						}

				}
			}
		}

		/**
		 * Display plugin information in dialog box form.
		 *
		 * Based on core install_plugin_information() function.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  1.0.6
		 */
		function install_plugin_information() {
			global $tab;

			if ( empty( $_REQUEST['plugin'] ) ) {
				return;
			}

			$args = array(
				'slug'   => wp_unslash( $_REQUEST['plugin'] ),
				'is_ssl' => is_ssl(),
				'fields' => array(
					'banners' => true,
					'reviews' => true,
					'downloaded' => false,
					'active_installs' => true
				)
			);

			if ( is_array( $args ) ) {
				$args = (object) $args;
			}

			if ( ! isset( $args->per_page ) ) {
				$args->per_page = 24;
			}

			if ( ! isset( $args->locale ) ) {
				$args->locale = get_locale();
			}

			$api = apply_filters( 'fs_plugins_api', false, 'plugin_information', $args );

			if ( is_wp_error( $api ) ) {
				wp_die( $api );
			}

			$plugins_allowedtags = array(
				'a'       => array(
					'href'   => array(),
					'title'  => array(),
					'target' => array(),
					// Add image style for screenshots.
					'class'  => array()
				),
				'style'   => array(),
				'abbr'    => array( 'title' => array() ),
				'acronym' => array( 'title' => array() ),
				'code'    => array(),
				'pre'     => array(),
				'em'      => array(),
				'strong'  => array(),
				'div'     => array( 'class' => array() ),
				'span'    => array( 'class' => array() ),
				'p'       => array(),
				'ul'      => array(),
				'ol'      => array(),
				'li'      => array( 'class' => array() ),
				'i'       => array( 'class' => array() ),
				'h1'      => array(),
				'h2'      => array(),
				'h3'      => array(),
				'h4'      => array(),
				'h5'      => array(),
				'h6'      => array(),
				'img'     => array( 'src' => array(), 'class' => array(), 'alt' => array() ),
//			'table' => array(),
//			'td' => array(),
//			'tr' => array(),
//			'th' => array(),
//			'thead' => array(),
//			'tbody' => array(),
			);

			$plugins_section_titles = array(
				'description'  => _x( 'Description', 'Plugin installer section title' ),
				'installation' => _x( 'Installation', 'Plugin installer section title' ),
				'faq'          => _x( 'FAQ', 'Plugin installer section title' ),
				'screenshots'  => _x( 'Screenshots', 'Plugin installer section title' ),
				'changelog'    => _x( 'Changelog', 'Plugin installer section title' ),
				'reviews'      => _x( 'Reviews', 'Plugin installer section title' ),
				'other_notes'  => _x( 'Other Notes', 'Plugin installer section title' ),
			);

			// Sanitize HTML
//		foreach ( (array) $api->sections as $section_name => $content ) {
//			$api->sections[$section_name] = wp_kses( $content, $plugins_allowedtags );
//		}

			foreach ( array( 'version', 'author', 'requires', 'tested', 'homepage', 'downloaded', 'slug' ) as $key ) {
				if ( isset( $api->$key ) ) {
					$api->$key = wp_kses( $api->$key, $plugins_allowedtags );
				}
			}

			// Add after $api->slug is ready.
			$plugins_section_titles['features'] = __fs( 'features-and-pricing', $api->slug );

			$_tab = esc_attr( $tab );

			$section = isset( $_REQUEST['section'] ) ? wp_unslash( $_REQUEST['section'] ) : 'description'; // Default to the Description tab, Do not translate, API returns English.
			if ( empty( $section ) || ! isset( $api->sections[ $section ] ) ) {
				$section_titles = array_keys( (array) $api->sections );
				$section        = array_shift( $section_titles );
			}

			iframe_header( __( 'Plugin Install' ) );

			$_with_banner = '';

//	var_dump($api->banners);
			if ( ! empty( $api->banners ) && ( ! empty( $api->banners['low'] ) || ! empty( $api->banners['high'] ) ) ) {
				$_with_banner = 'with-banner';
				$low          = empty( $api->banners['low'] ) ? $api->banners['high'] : $api->banners['low'];
				$high         = empty( $api->banners['high'] ) ? $api->banners['low'] : $api->banners['high'];
				?>
				<style type="text/css">
					#plugin-information-title.with-banner
					{
						background-image: url( <?php echo esc_url( $low ); ?> );
					}

					@media only screen and ( -webkit-min-device-pixel-ratio: 1.5 )
					{
						#plugin-information-title.with-banner
						{
							background-image: url( <?php echo esc_url( $high ); ?> );
						}
					}
				</style>
			<?php
			}

			echo '<div id="plugin-information-scrollable">';
			echo "<div id='{$_tab}-title' class='{$_with_banner}'><div class='vignette'></div><h2>{$api->name}</h2></div>";
			echo "<div id='{$_tab}-tabs' class='{$_with_banner}'>\n";

			foreach ( (array) $api->sections as $section_name => $content ) {
				if ( 'reviews' === $section_name && ( empty( $api->ratings ) || 0 === array_sum( (array) $api->ratings ) ) ) {
					continue;
				}

				if ( isset( $plugins_section_titles[ $section_name ] ) ) {
					$title = $plugins_section_titles[ $section_name ];
				} else {
					$title = ucwords( str_replace( '_', ' ', $section_name ) );
				}

				$class       = ( $section_name === $section ) ? ' class="current"' : '';
				$href        = add_query_arg( array( 'tab' => $tab, 'section' => $section_name ) );
				$href        = esc_url( $href );
				$san_section = esc_attr( $section_name );
				echo "\t<a name='$san_section' href='$href' $class>$title</a>\n";
			}

			echo "</div>\n";

			?>
		<div id="<?php echo $_tab; ?>-content" class='<?php echo $_with_banner; ?>'>
			<div class="fyi">
				<?php if ( isset( $api->plans ) ) : ?>
					<div class="plugin-information-pricing">
						<?php foreach ($api->plans as $plan) : ?>
						<h3 data-plan="<?php echo $plan->id ?>"><?php printf( __fs( 'x-plan', $api->slug ), $plan->title ) ?></h3>
						<?php if ( $api->is_paid ) : ?>
							<ul>
								<?php $billing_cycle = 'annual' ?>
								<?php if ( 1 === count( $plan->pricing ) && 1 == $plan->pricing[0]->licenses ) : ?>
									<?php $pricing = $plan->pricing[0] ?>
									<li><label><?php _efs( 'price', $api->slug ) ?>: $<?php
												if ( isset( $pricing->annual_price ) ) {
													echo $pricing->annual_price . ( $plan->is_block_features ? ' / year' : '' );
													$billing_cycle = 'annual';
												} else if ( isset( $pricing->monthly_price ) ) {
													echo $pricing->monthly_price . ' / mo';
													$billing_cycle = 'monthly';
												} else if ( isset( $pricing->lifetime_price ) ) {
													echo $pricing->lifetime_price;
													$billing_cycle = 'lifetime';
												}
											?></label></li>
								<?php else : ?>
									<?php $first = true;
									foreach ( $plan->pricing as $pricing ) : ?>
										<li><label><input name="pricing-<?php echo $plan->id ?>" type="radio"
										                  value="<?php echo $pricing->id ?>"<?php checked( $first, true ) ?>><?php
													switch ( $pricing->licenses ) {
														case '1':
															_efs( 'license-single-site', $api->slug );
															break;
														case null:
															_efs( 'license-unlimited', $api->slug );
															break;
														default:
															printf( __fs( 'license-x-sites', $api->slug ), $pricing->licenses );
															break;
													}
												?> - $<?php
													if ( isset( $pricing->annual_price ) ) {
														echo $pricing->annual_price . ( $plan->is_block_features ? ' / year' : '' );
														$billing_cycle = 'annual';
													} else if ( isset( $pricing->monthly_price ) ) {
														echo $pricing->monthly_price . ' / mo';
														$billing_cycle = 'monthly';
													} else if ( isset( $pricing->lifetime_price ) ) {
														echo $pricing->lifetime_price;
														$billing_cycle = 'lifetime';
													}
												?></label></li>
										<?php $first = false; endforeach ?>
								<?php endif ?>
							</ul>
						<?php endif ?>
						<?php echo $this->get_plugin_cta($api) ?>
					</div>
				<?php endforeach ?>
				<?php wp_enqueue_script( 'jquery' ); ?>
					<script type="text/javascript">
						(function ($) {
							$('.plugin-information-pricing input[type=radio]').click(function () {
								var checkout_url = '<?php echo esc_url_raw(add_query_arg(array(
								'plugin_id' => $plan->plugin_id,
								'billing_cycle' => $billing_cycle,
							), $api->checkout_link)) ?>&plan_id=' +
									$(this).parents('.plugin-information-pricing').find('h3').attr('data-plan') +
									'&pricing_id=' + $(this).val();

								$('.plugin-information-pricing .button, #plugin-information-footer .button').attr('href', checkout_url);
							});
						})(jQuery);
					</script>
				<?php endif ?>
				<div>
					<h3><?php _efs( 'details', $api->slug ) ?></h3>
					<ul>
						<?php if ( ! empty( $api->version ) ) { ?>
							<li><strong><?php _e( 'Version:' ); ?></strong> <?php echo $api->version; ?></li>
						<?php
						}
							if ( ! empty( $api->author ) ) {
								?>
								<li>
									<strong><?php _e( 'Author:' ); ?></strong> <?php echo links_add_target( $api->author, '_blank' ); ?>
								</li>
							<?php
							}
							if ( ! empty( $api->last_updated ) ) {
								?>
								<li><strong><?php _e( 'Last Updated:' ); ?></strong> <span
										title="<?php echo $api->last_updated; ?>">
				<?php printf( __( '%s ago' ), human_time_diff( strtotime( $api->last_updated ) ) ); ?>
			</span></li>
							<?php
							}
							if ( ! empty( $api->requires ) ) {
								?>
								<li>
									<strong><?php _e( 'Requires WordPress Version:' ); ?></strong> <?php printf( __( '%s or higher' ), $api->requires ); ?>
								</li>
							<?php
							}
							if ( ! empty( $api->tested ) ) {
								?>
								<li><strong><?php _e( 'Compatible up to:' ); ?></strong> <?php echo $api->tested; ?>
								</li>
							<?php
							}
							if ( ! empty( $api->downloaded ) ) {
								?>
								<li>
									<strong><?php _e( 'Downloaded:' ); ?></strong> <?php printf( _n( '%s time', '%s times', $api->downloaded ), number_format_i18n( $api->downloaded ) ); ?>
								</li>
							<?php
							}
							if ( ! empty( $api->slug ) && empty( $api->external ) ) {
								?>
								<li><a target="_blank"
								       href="https://wordpress.org/plugins/<?php echo $api->slug; ?>/"><?php _e( 'WordPress.org Plugin Page &#187;' ); ?></a>
								</li>
							<?php
							}
							if ( ! empty( $api->homepage ) ) {
								?>
								<li><a target="_blank"
								       href="<?php echo esc_url( $api->homepage ); ?>"><?php _e( 'Plugin Homepage &#187;' ); ?></a>
								</li>
							<?php
							}
							if ( ! empty( $api->donate_link ) && empty( $api->contributors ) ) {
								?>
								<li><a target="_blank"
								       href="<?php echo esc_url( $api->donate_link ); ?>"><?php _e( 'Donate to this plugin &#187;' ); ?></a>
								</li>
							<?php } ?>
					</ul>
				</div>
				<?php if ( ! empty( $api->rating ) ) { ?>
					<h3><?php _e( 'Average Rating' ); ?></h3>
					<?php wp_star_rating( array(
						'rating' => $api->rating,
						'type'   => 'percent',
						'number' => $api->num_ratings
					) ); ?>
					<small><?php printf( _n( '(based on %s rating)', '(based on %s ratings)', $api->num_ratings ), number_format_i18n( $api->num_ratings ) ); ?></small>
				<?php
				}

					if ( ! empty( $api->ratings ) && array_sum( (array) $api->ratings ) > 0 ) {
						foreach ( $api->ratings as $key => $ratecount ) {
							// Avoid div-by-zero.
							$_rating = $api->num_ratings ? ( $ratecount / $api->num_ratings ) : 0;
							?>
							<div class="counter-container">
					<span class="counter-label"><a
							href="https://wordpress.org/support/view/plugin-reviews/<?php echo $api->slug; ?>?filter=<?php echo $key; ?>"
							target="_blank"
							title="<?php echo esc_attr( sprintf( _n( 'Click to see reviews that provided a rating of %d star', 'Click to see reviews that provided a rating of %d stars', $key ), $key ) ); ?>"><?php printf( _n( '%d star', '%d stars', $key ), $key ); ?></a></span>
					<span class="counter-back">
						<span class="counter-bar" style="width: <?php echo 92 * $_rating; ?>px;"></span>
					</span>
								<span class="counter-count"><?php echo number_format_i18n( $ratecount ); ?></span>
							</div>
						<?php
						}
					}
					if ( ! empty( $api->contributors ) ) {
						?>
						<h3><?php _e( 'Contributors' ); ?></h3>
						<ul class="contributors">
							<?php
								foreach ( (array) $api->contributors as $contrib_username => $contrib_profile ) {
									if ( empty( $contrib_username ) && empty( $contrib_profile ) ) {
										continue;
									}
									if ( empty( $contrib_username ) ) {
										$contrib_username = preg_replace( '/^.+\/(.+)\/?$/', '\1', $contrib_profile );
									}
									$contrib_username = sanitize_user( $contrib_username );
									if ( empty( $contrib_profile ) ) {
										echo "<li><img src='https://wordpress.org/grav-redirect.php?user={$contrib_username}&amp;s=36' width='18' height='18' />{$contrib_username}</li>";
									} else {
										echo "<li><a href='{$contrib_profile}' target='_blank'><img src='https://wordpress.org/grav-redirect.php?user={$contrib_username}&amp;s=36' width='18' height='18' />{$contrib_username}</a></li>";
									}
								}
							?>
						</ul>
						<?php if ( ! empty( $api->donate_link ) ) { ?>
							<a target="_blank"
							   href="<?php echo esc_url( $api->donate_link ); ?>"><?php _e( 'Donate to this plugin &#187;' ); ?></a>
						<?php } ?>
					<?php } ?>
			</div>
			<div id="section-holder" class="wrap">
	<?php
		if ( ! empty( $api->tested ) && version_compare( substr( $GLOBALS['wp_version'], 0, strlen( $api->tested ) ), $api->tested, '>' ) ) {
			echo '<div class="notice notice-warning"><p>' . '<strong>' . __( 'Warning:' ) . '</strong> ' . __( 'This plugin has not been tested with your current version of WordPress.' ) . '</p></div>';
		} else if ( ! empty( $api->requires ) && version_compare( substr( $GLOBALS['wp_version'], 0, strlen( $api->requires ) ), $api->requires, '<' ) ) {
			echo '<div class="notice notice-warning"><p>' . '<strong>' . __( 'Warning:' ) . '</strong> ' . __( 'This plugin has not been marked as compatible with your version of WordPress.' ) . '</p></div>';
		}

		foreach ( (array) $api->sections as $section_name => $content ) {
			$content = links_add_base_url( $content, 'https://wordpress.org/plugins/' . $api->slug . '/' );
			$content = links_add_target( $content, '_blank' );

			$san_section = esc_attr( $section_name );

			$display = ( $section_name === $section ) ? 'block' : 'none';

			echo "\t<div id='section-{$san_section}' class='section' style='display: {$display};'>\n";
			echo $content;
			echo "\t</div>\n";
		}
	echo "</div>\n";
	echo "</div>\n";
	echo "</div>\n"; // #plugin-information-scrollable
	echo "<div id='$tab-footer'>\n";

	echo $this->get_plugin_cta($api);

	echo "</div>\n";

	iframe_footer();
	exit;
}
	}
