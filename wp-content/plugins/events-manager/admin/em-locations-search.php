<?php
/*
 * This page will search for either a specific location via GET "id" variable 
 * or will search for events by name via the GET "q" variable.
 */
//FIXME just plug loc search into ajax catcher
require_once('../../../../wp-load.php');
global $wpdb;

$locations_table = EM_LOCATIONS_TABLE;
$location_cond = ( !current_user_can('edit_others_locations') && !current_user_can('read_others_locations') ) ? "AND location_owner=".get_current_user_id() : '';

$term = (isset($_GET['term'])) ? '%'.$_GET['term'].'%' : '%'.$_GET['q'].'%';
$sql = $wpdb->prepare("
	SELECT 
		location_id AS `id`,
		Concat( location_name, ', ', location_address, ', ', location_town)  AS `label`,
		location_name AS `value`,
		location_address AS `address`, 
		location_town AS `town`, 
		location_state AS `state`,
		location_region AS `region`,
		location_postcode AS `postcode`,
		location_country AS `country`
	FROM $locations_table 
	WHERE ( `location_name` LIKE %s ) $location_cond LIMIT 10
", $term);

$locations_array = $wpdb->get_results($sql);
echo EM_Object::json_encode($locations_array);
/*
$return_string_array = array();
foreach($locations_array as $location){
	$return_string_class = array();
	foreach($location as $key => $value ){
		$return_string_class[] = "$key : '".addslashes($value)."'";
	}
	$return_string_array[] = '{'. implode(',', $return_string_class) .'}'; 
}
echo '['. implode(',', $return_string_array) .']';
*/
?>