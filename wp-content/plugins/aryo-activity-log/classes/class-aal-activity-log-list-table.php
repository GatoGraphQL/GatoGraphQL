<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WP_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );


class AAL_Activity_Log_List_Table extends WP_List_Table {
	
	protected $_roles = array();
	
	protected $_caps = array();
	
	protected $_allow_caps = array();
	
	protected function _get_allow_caps() {
		if ( empty( $this->_allow_caps ) ) {
			$user = get_user_by( 'id', get_current_user_id() );
			if ( ! $user )
				wp_die( 'No allowed here.' );

			$user_cap   = strtolower( key( $user->caps ) );
			$allow_caps = array();

			foreach ( $this->_caps as $key => $cap_allow ) {
				if ( $key === $user_cap ) {
					$allow_caps = array_merge( $allow_caps, $cap_allow );
					break;
				}
			}

			// TODO: Find better way to Multisite compatibility.
			if ( is_super_admin() || current_user_can( 'view_all_aryo_activity_log' ) )
				$allow_caps = $this->_caps['administrator'];

			if ( empty( $allow_caps ) )
				wp_die( 'No allowed here.' );
			
			$this->_allow_caps = array_unique( $allow_caps );
		}
		return $this->_allow_caps;
	}

	protected function _get_where_by_role() {
		$allow_modules = array();

		foreach ( $this->_roles as $key => $role ) {
			if ( current_user_can( $key ) || current_user_can( 'view_all_aryo_activity_log' ) ) {
				$allow_modules = array_merge( $allow_modules, $role );
			}
		}

		if ( empty( $allow_modules ) )
			wp_die( 'No allowed here.' );

		$allow_modules = array_unique( $allow_modules );

		$where = array();
		foreach ( $allow_modules as $type )
			$where[] .= '`object_type` = \'' . $type . '\'';
		
		$where_caps = array();
		foreach ( $this->_get_allow_caps() as $cap )
			$where_caps[] .= '`user_caps` = \'' . $cap . '\'';

		return 'AND (' . implode( ' OR ', $where ) . ') AND (' . implode( ' OR ', $where_caps ) . ')';
	}
	
	protected function _get_action_label( $action ) {
		return ucwords( str_replace( '_', ' ', __( $action, 'aryo-activity-log' ) ) );
	}

	public function __construct( $args = array() ) {
		parent::__construct(
			array(
				'singular'  => 'activity',
				'screen' => isset( $args['screen'] ) ? $args['screen'] : null,
			)
		);
		
		$this->_roles = apply_filters(
			'aal_init_roles',
			array(
				// admin
				'manage_options' => array( 'Core', 'Export', 'Post', 'Taxonomy', 'User', 'Options', 'Attachment', 'Plugin', 'Widget', 'Theme', 'Menu', 'Comments' ),
				// editor
				'edit_pages'     => array( 'Post', 'Taxonomy', 'Attachment', 'Comments' ),
			)
		);

		$this->_caps = apply_filters(
			'aal_init_caps',
			array(
				'administrator' => array( 'administrator', 'editor', 'author', 'guest' ),
				'editor'        => array( 'editor', 'author', 'guest' ),
				'author'        => array( 'author', 'guest' ),
			)
		);

		add_screen_option(
			'per_page',
			array(
				'default' => 50,
				'label'   => __( 'Activities', 'aryo-activity-log' ),
				'option'  => 'edit_aal_logs_per_page',
			)
		);

		add_filter( 'set-screen-option', array( &$this, 'set_screen_option' ), 10, 3 );
		set_screen_options();
	}

	public function get_columns() {
		$columns = array(
			'date'        => __( 'Date', 'aryo-activity-log' ),
			'author'      => __( 'Author', 'aryo-activity-log' ),
			'ip'          => __( 'IP', 'aryo-activity-log' ),
			'type'        => __( 'Type', 'aryo-activity-log' ),
			'label'       => __( 'Label', 'aryo-activity-log' ),
			'action'      => __( 'Action', 'aryo-activity-log' ),
			'description' => __( 'Description', 'aryo-activity-log' ),
		);

		return $columns;
	}
	
	public function get_sortable_columns() {
		return array(
			'ip' => 'hist_ip',
			'date' => array( 'hist_time', true ),
		);
	}

	public function column_default( $item, $column_name ) {
		$return = '';
		
		switch ( $column_name ) {
			case 'action' :
				$return = $this->_get_action_label( $item->action );
				break;
			case 'date' :
				$return  = sprintf( '<strong>' . __( '%s ago', 'aryo-activity-log' ) . '</strong>', human_time_diff( $item->hist_time, current_time( 'timestamp' ) ) );
				$return .= '<br />' . date( 'd/m/Y', $item->hist_time );
				$return .= '<br />' . date( 'H:i', $item->hist_time );
				break;
			case 'ip' :
				$return = $item->hist_ip;
				break;
			default :
				if ( isset( $item->$column_name ) )
					$return = $item->$column_name;
		}

		$return = apply_filters( 'aal_table_list_column_default', $return, $item, $column_name );
		
		return $return;
	}
	
	public function column_author( $item ) {
		global $wp_roles;
		
		if ( ! empty( $item->user_id ) && 0 !== (int) $item->user_id ) {
			$user = get_user_by( 'id', $item->user_id );
			if ( $user instanceof WP_User && 0 !== $user->ID ) {
				//$user->display_name
				return sprintf(
					'<a href="%s">%s <span class="aal-author-name">%s</span></a><br /><small>%s</small>',
					get_edit_user_link( $user->ID ),
					get_avatar( $user->ID, 40 ),
					$user->display_name,
					isset( $user->roles[0] ) && isset( $wp_roles->role_names[ $user->roles[0] ] ) ? $wp_roles->role_names[ $user->roles[0] ] : __( 'Unknown', 'aryo-activity-log' )
				);
			}
		}
		return sprintf(
			'<span class="aal-author-name">%s</span>',
			__( 'Guest', 'aryo-activity-log' )
		);
	}

	public function column_type( $item ) {
		$return = __( $item->object_type, 'aryo-activity-log' );
		
		$return = apply_filters( 'aal_table_list_column_type', $return, $item );
		return $return;
	}

	public function column_label( $item ) {
		$return = '';
		if ( ! empty( $item->object_subtype ) ) {
			$pt     = get_post_type_object( $item->object_subtype );
			$return = ! empty( $pt->label ) ? $pt->label : $item->object_subtype;
		}

		$return = apply_filters( 'aal_table_list_column_label', $return, $item );
		return $return;
	}
	
	public function column_description( $item ) {
		$return = $item->object_name;
		
		switch ( $item->object_type ) {
			case 'Post' :
				$return = sprintf( '<a href="%s">%s</a>', get_edit_post_link( $item->object_id ), $item->object_name );
				break;
			
			case 'Taxonomy' :
				if ( ! empty( $item->object_id ) )
					$return = sprintf( '<a href="%s">%s</a>', get_edit_term_link( $item->object_id, $item->object_subtype ), $item->object_name );
				break;
			
			case 'Comments' :
				if ( ! empty( $item->object_id ) && $comment = get_comment( $item->object_id ) ) {
					$return = sprintf( '<a href="%s">%s #%d</a>', get_edit_comment_link( $item->object_id ), $item->object_name, $item->object_id );
				}
				break;
			
			case 'Export' :
				if ( 'all' === $item->object_name ) {
					$return = __( 'All', 'aryo-activity-log' );
				} else {
					$pt     = get_post_type_object( $item->object_name );
					$return = ! empty( $pt->label ) ? $pt->label : $item->object_name;
				}
				break;

			case 'Options' :
			case 'Core' :
				$return = __( $item->object_name, 'aryo-activity-log' );
				break;
		}
		
		$return = apply_filters( 'aal_table_list_column_description', $return, $item );
		
		return $return;
	}
	
	public function display_tablenav( $which ) {
		if ( 'top' == $which )
			$this->search_box( __( 'Search', 'aryo-activity-log' ), 'aal-search' );
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
			<?php
			$this->extra_tablenav( $which );
			$this->pagination( $which );
			?>
			<br class="clear" />
		</div>
		<?php
	}

	public function extra_tablenav( $which ) {
		global $wpdb;

		if ( 'top' !== $which )
			return;

		echo '<div class="alignleft actions">';

		$users = $wpdb->get_results( $wpdb->prepare(
			'SELECT DISTINCT %1$s FROM `%2$s`
				WHERE 1 = 1
				' . $this->_get_where_by_role() . '
				GROUP BY `%1$s`
				ORDER BY `%1$s`
			;',
			'user_id',
			$wpdb->activity_log
		) );

		if ( $users ) {
			if ( ! isset( $_REQUEST['capshow'] ) )
				$_REQUEST['capshow'] = '';

			$output = array();
			foreach ( $this->_get_allow_caps() as $cap ) {
				$output[ $cap ] = __( ucwords( $cap ), 'aryo-activity-log' );
			}

			if ( ! empty( $output ) ) {
				echo '<select name="capshow" id="hs-filter-capshow">';
				printf( '<option value="">%s</option>', __( 'All Roles', 'aryo-activity-log' ) );
				foreach ( $output as $key => $value ) {
					printf( '<option value="%s"%s>%s</option>', $key, selected( $_REQUEST['capshow'], $key, false ), $value );
				}
				echo '</select>';
			}
			
			if ( ! isset( $_REQUEST['usershow'] ) )
				$_REQUEST['usershow'] = '';

			$output = array();
			foreach ( $users as $_user ) {
				if ( 0 === (int) $_user->user_id ) {
					$output[0] = __( 'Guest', 'aryo-activity-log' );
					continue;
				}

				$user = get_user_by( 'id', $_user->user_id );
				if ( $user )
					$output[ $user->ID ] = $user->user_nicename;
			}

			if ( ! empty( $output ) ) {
				echo '<select name="usershow" id="hs-filter-usershow">';
				printf( '<option value="">%s</option>', __( 'All Users', 'aryo-activity-log' ) );
				foreach ( $output as $key => $value ) {
					printf( '<option value="%s"%s>%s</option>', $key, selected( $_REQUEST['usershow'], $key, false ), $value );
				}
				echo '</select>';
			}
		}

		$types = $wpdb->get_results( $wpdb->prepare(
			'SELECT DISTINCT %1$s FROM `%2$s`
				WHERE 1 = 1
				' . $this->_get_where_by_role() . '
				GROUP BY `%1$s`
				ORDER BY `%1$s`
			;',
			'object_type',
			$wpdb->activity_log
		) );

		if ( $types ) {
			if ( ! isset( $_REQUEST['typeshow'] ) )
				$_REQUEST['typeshow'] = '';

			$output = array();
			foreach ( $types as $type )
				$output[] = sprintf( '<option value="%1$s"%2$s>%3$s</option>', $type->object_type, selected( $_REQUEST['typeshow'], $type->object_type, false ), __( $type->object_type, 'aryo-activity-log' ) );

			echo '<select name="typeshow" id="hs-filter-typeshow">';
			printf( '<option value="">%s</option>', __( 'All Types', 'aryo-activity-log' ) );
			echo implode( '', $output );
			echo '</select>';
		}


		$actions = $wpdb->get_results( $wpdb->prepare(
			'SELECT DISTINCT %1$s FROM `%2$s`
				WHERE 1 = 1
				' . $this->_get_where_by_role() . '
				GROUP BY `%1$s`
				ORDER BY `%1$s`
			;',
			'action',
			$wpdb->activity_log
		) );

		if ( $actions ) {
			if ( ! isset( $_REQUEST['showaction'] ) )
				$_REQUEST['showaction'] = '';

			$output = array();
			foreach ( $actions as $type )
				$output[] = sprintf( '<option value="%s"%s>%s</option>', $type->action, selected( $_REQUEST['showaction'], $type->action, false ), $this->_get_action_label( $type->action ) );

			echo '<select name="showaction" id="hs-filter-showaction">';
			printf( '<option value="">%s</option>', __( 'All Actions', 'aryo-activity-log' ) );
			echo implode( '', $output );
			echo '</select>';
		}

		// Make sure we get items for filter.
		if ( $users || $types ) {
			if ( ! isset( $_REQUEST['dateshow'] ) )
				$_REQUEST['dateshow'] = '';
			
			$date_options = array(
				'' => __( 'All Time', 'aryo-activity-log' ),
				'today' => __( 'Today', 'aryo-activity-log' ),
				'yesterday' => __( 'Yesterday', 'aryo-activity-log' ),
				'week' => __( 'Week', 'aryo-activity-log' ),
				'month' => __( 'Month', 'aryo-activity-log' ),
			);
			echo '<select name="dateshow" id="hs-filter-date">';
			foreach ( $date_options as $key => $value )
				printf( '<option value="%1$s"%2$s>%3$s</option>', $key, selected( $_REQUEST['dateshow'], $key, false ), $value );
			echo '</select>';
			
			submit_button( __( 'Filter', 'aryo-activity-log' ), 'button', false, false, array( 'id' => 'activity-query-submit' ) );
		}

		echo '</div>';
	}
	
	public function prepare_items() {
		global $wpdb;
	
		$items_per_page        = $this->get_items_per_page( 'edit_aal_logs_per_page', 20 );
		$this->_column_headers = array( $this->get_columns(), get_hidden_columns( $this->screen ), $this->get_sortable_columns() );
		$where                 = ' WHERE 1=1';

		if ( ! isset( $_REQUEST['order'] ) || ! in_array( $_REQUEST['order'], array( 'desc', 'asc' ) ) ) {
			$_REQUEST['order'] = 'DESC';
		}
		if ( ! isset( $_REQUEST['orderby'] ) || ! in_array( $_REQUEST['orderby'], array( 'hist_time', 'hist_ip' ) ) ) {
			$_REQUEST['orderby'] = 'hist_time';
		}
		
		if ( ! empty( $_REQUEST['typeshow'] ) ) {
			$where .= $wpdb->prepare( ' AND `object_type` = \'%s\'', $_REQUEST['typeshow'] );
		}

		if ( isset( $_REQUEST['showaction'] ) && '' !== $_REQUEST['showaction'] ) {
			$where .= $wpdb->prepare( ' AND `action` = \'%s\'', $_REQUEST['showaction'] );
		}

		if ( isset( $_REQUEST['usershow'] ) && '' !== $_REQUEST['usershow'] ) {
			$where .= $wpdb->prepare( ' AND `user_id` = %d', $_REQUEST['usershow'] );
		}

		if ( isset( $_REQUEST['capshow'] ) && '' !== $_REQUEST['capshow'] ) {
			$where .= $wpdb->prepare( ' AND `user_caps` = \'%s\'', strtolower( $_REQUEST['capshow'] ) );
		}

		if ( isset( $_REQUEST['dateshow'] ) && in_array( $_REQUEST['dateshow'], array( 'today', 'yesterday', 'week', 'month' ) ) ) {
			$current_time = current_time( 'timestamp' );

			// Today
			$start_time = mktime( 0, 0, 0, date( 'm', $current_time ), date( 'd', $current_time ), date( 'Y', $current_time ) );;
			$end_time = mktime( 23, 59, 59, date( 'm', $current_time ), date( 'd', $current_time ), date( 'Y', $current_time ) );
			
			if ( 'yesterday' === $_REQUEST['dateshow'] ) {
				$start_time = strtotime( 'yesterday', $start_time );
				$end_time = mktime( 23, 59, 59, date( 'm', $start_time ), date( 'd', $start_time ), date( 'Y', $start_time ) );
			} elseif ( 'week' === $_REQUEST['dateshow'] ) {
				$start_time = strtotime( '-1 week', $start_time );
			} elseif ( 'month' === $_REQUEST['dateshow'] ) {
				$start_time = strtotime( '-1 month', $start_time );
			}
			
			$where .= $wpdb->prepare( ' AND `hist_time` > %1$d AND `hist_time` < %2$d', $start_time, $end_time );
		}

		if ( isset( $_REQUEST['s'] ) ) {
			// Search only searches 'description' fields.
			$where .= $wpdb->prepare( ' AND `object_name` LIKE \'%%%s%%\'', '%' . $wpdb->esc_like( $_REQUEST['s'] ) . '%' );
		}

		$offset = ( $this->get_pagenum() - 1 ) * $items_per_page;

		
		$total_items = $wpdb->get_var( $wpdb->prepare(
			'SELECT COUNT(`histid`) FROM `%1$s`
				' . $where . '
					' . $this->_get_where_by_role(),
			$wpdb->activity_log,
			$offset,
			$items_per_page
		) );
		
		$this->items = $wpdb->get_results( $wpdb->prepare(
			'SELECT * FROM `%1$s`
				' . $where . '
					' . $this->_get_where_by_role() . '
					ORDER BY `%2$s` %3$s
					LIMIT %4$d, %5$d;',
			$wpdb->activity_log,
			$_REQUEST['orderby'],
			$_REQUEST['order'],
			$offset,
			$items_per_page
		) );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $items_per_page,
			'total_pages' => ceil( $total_items / $items_per_page )
		) );
	}
	
	public function set_screen_option( $status, $option, $value ) {
		if ( 'edit_aal_logs_per_page' === $option )
			return $value;
		return $status;
	}

	public function search_box( $text, $input_id ) {

		$search_data = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';

		$input_id = $input_id . '-search-input';
		?>
		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
			<input type="search" id="<?php echo $input_id ?>" name="s" value="<?php echo $search_data; ?>" />
			<?php submit_button( $text, 'button', false, false, array('id' => 'search-submit') ); ?>
		</p>
	<?php
	}
	
}
