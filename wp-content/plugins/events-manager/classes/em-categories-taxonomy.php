<?php
class EM_Categories_Taxonomy{
	public static function init(){
		add_action( EM_TAXONOMY_CATEGORY.'_edit_form_fields', array('EM_Categories_Taxonomy','form'), 10, 1);
		add_action( EM_TAXONOMY_CATEGORY.'_add_form_fields', array('EM_Categories_Taxonomy','form'), 10, 1);
		add_action( 'edited_'.EM_TAXONOMY_CATEGORY, array('EM_Categories_Taxonomy','save'), 10, 2);
		add_action( 'create_'.EM_TAXONOMY_CATEGORY, array('EM_Categories_Taxonomy','save'), 10, 2);
		add_action( 'delete_'.EM_TAXONOMY_CATEGORY, array('EM_Categories_Taxonomy','delete'), 10, 2);
		
		add_filter('manage_edit-'.EM_TAXONOMY_CATEGORY.'_columns' , array('EM_Categories_Taxonomy','columns_add'));
		add_filter('manage_'.EM_TAXONOMY_CATEGORY.'_custom_column' , array('EM_Categories_Taxonomy','columns_output'),10,3);
		
		self::admin_init();
	}

	
	public static function columns_add($columns) {
		//prepend ID after checkbox
	    $columns['cat-id'] = __('ID','events-manager');
	    return $columns;
	}
	
	public static function columns_output( $val, $column, $term_id ) {
		switch ( $column ) {
			case 'cat-id':
				return $term_id;
				break;
		}
		return $val;
	}
	
	public static function admin_init(){
		global $pagenow;
		if($pagenow == 'edit-tags.php' && !empty($_GET['taxonomy']) && $_GET['taxonomy'] == EM_TAXONOMY_CATEGORY){
			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_style( 'thickbox' );
			
			wp_enqueue_script( 'em-categories-admin', plugins_url().'/events-manager/includes/js/categories-admin.js', array( 'jquery','media-upload','thickbox','farbtastic' ) );
		}
	}
	
	public static function form($tag){ 
		$category_color = '#FFFFFF';
		$category_image = '';
		if( $tag != EM_TAXONOMY_CATEGORY ){ //not an add new tag form
			$EM_Category = new EM_Category($tag);
			$category_color = $EM_Category->get_color();
			$category_image = $EM_Category->get_image_url();
			$category_image_id = $EM_Category->get_image_id();
		}
		?>
	    <tr class="form-field">
	        <th scope="row" valign="top"><label for="category-bgcolor"><?php esc_html_e('Color','events-manager'); ?></label></th>
	        <td>
	            <input type="text" name="category_bgcolor" id="category-bgcolor" class="colorwell" value="<?php echo esc_attr($category_color); ?>" style="width:100px;"/><br />
	            <p class="description"><?php echo sprintf(__('Choose a color for your category. You can access this using the %s placeholder.','events-manager'),'<code>#_CATEGORYCOLOR</code>'); ?></p>
	            <div id="picker" style="position:absolute; display:none; background:#DEDEDE"></div>
	        </td>
	    </tr>
	    <tr class="form-field">
	        <th scope="row" valign="top"><label for="category-image"><?php esc_html_e('Image','events-manager'); ?></label></th>
	        <td>
	        	<?php if( !empty($category_image) ): ?>
	        	<p id="category-image-img"><img src="<?php echo $category_image; ?>" /></p>
	        	<?php endif; ?>
	            <input type="text" name="category_image" id="category-image" value="<?php echo esc_attr($category_image); ?>" style="width:300px;" />
	            <input type="hidden" name="category_image_id" id="category-image-id" value="<?php echo esc_attr($category_image); ?>" />
	            <input id="upload_image_button" type="button" value="<?php _e('Choose/Upload Image','events-manager'); ?>" class="button-secondary" style="width:auto;" />
	            <?php if( !empty($category_image) ): ?>
	        	<input id="delete_image_button" type="button" value="<?php _e('Remove Image','events-manager'); ?>" class="button-secondary" style="width:auto;" />
	        	<?php endif; ?>
	            <br />
	            <p class="description"><?php echo sprintf(__('Choose an image for your category, which can be displayed using the %s placeholder.','events-manager'),'<code>#_CATEGORYIMAGE</code>'); ?></p>
	        </td>
	    </tr>
	    <?php
	}
	
	public static function save($term_id, $tt_id){
		global $wpdb;
	    if (!$term_id) return;
		if( !empty($_POST['category_bgcolor']) && preg_match('/^#[a-zA-Z0-9]{6}$/', $_POST['category_bgcolor']) ){
			//get results and save/update
			$prev_settings = $wpdb->get_results('SELECT meta_value FROM '.EM_META_TABLE." WHERE object_id='{$term_id}' AND meta_key='category-bgcolor'");
			if( count($prev_settings) > 0 ){
				$wpdb->update(EM_META_TABLE, array('object_id'=>$term_id,'meta_value'=>$_POST['category_bgcolor']), array('object_id'=>$term_id,'meta_key'=>'category-bgcolor'));
			}else{
				$wpdb->insert(EM_META_TABLE, array('object_id'=>$term_id,'meta_key'=>'category-bgcolor','meta_value'=>$_POST['category_bgcolor']));
			}
		}
		if( !empty($_POST['category_image']) ){
			//get results and save/update
			$prev_settings = $wpdb->get_results('SELECT meta_value FROM '.EM_META_TABLE." WHERE object_id='{$term_id}' AND meta_key='category-image'");
			if( count($prev_settings) > 0 ){
				$wpdb->update(EM_META_TABLE, array('object_id'=>$term_id,'meta_value'=>$_POST['category_image']), array('object_id'=>$term_id,'meta_key'=>'category-image'));
			}else{
				$wpdb->insert(EM_META_TABLE, array('object_id'=>$term_id,'meta_key'=>'category-image','meta_value'=>$_POST['category_image']));
			}
			if( !empty($_POST['category_image_id']) && is_numeric($_POST['category_image_id']) ){
				//get results and save/update
				$prev_settings = $wpdb->get_results('SELECT meta_value FROM '.EM_META_TABLE." WHERE object_id='{$term_id}' AND meta_key='category-image-id'");
				if( count($prev_settings) > 0 ){
					$wpdb->update(EM_META_TABLE, array('object_id'=>$term_id,'meta_value'=>$_POST['category_image_id']), array('object_id'=>$term_id,'meta_key'=>'category-image-id'));
				}else{
					$wpdb->insert(EM_META_TABLE, array('object_id'=>$term_id,'meta_key'=>'category-image-id','meta_value'=>$_POST['category_image_id']));
				}
			}
		}else{
			//check if an image exists, if so remove association
			$prev_settings = $wpdb->get_results('SELECT meta_value FROM '.EM_META_TABLE." WHERE object_id='{$term_id}' AND meta_key='category-image'");
			if( count($prev_settings) > 0 ){
				$wpdb->delete(EM_META_TABLE, array('object_id'=>$term_id,'meta_key'=>'category-image'));
				$wpdb->delete(EM_META_TABLE, array('object_id'=>$term_id,'meta_key'=>'category-image-id'));
			}
		}
	}
	
	public static function delete( $term_id ){
		global $wpdb;
		//delete category image and color
		$wpdb->query('DELETE FROM '.EM_META_TABLE." WHERE object_id='$term_id' AND (meta_key='category-image' OR meta_key='category-bgcolor')");
		//delete all events category relations
		$wpdb->query('DELETE FROM '.EM_META_TABLE." WHERE meta_value='{$term_id}' AND meta_key='event-category'");
	}
}
add_action('admin_init',array('EM_Categories_Taxonomy','init'));