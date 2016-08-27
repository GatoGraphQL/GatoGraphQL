<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Data load functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_dataload_posttypes() {

  return apply_filters('gd_dataload:post_types', array('post')); // Allow also Events post types, so these can be fetched from Stories (field references)
}

function gd_dataload_allcontent_categories() {

  return apply_filters('gd_template:allcontent:categories', array());
}

function gd_dataload_latestcounts_categories() {

  return apply_filters('gd_template:latestcounts:categories', array());
}

function gd_dataload_allcontent_taxquery_items() {

	$tax_query_items = array(
		array(
			'taxonomy' => 'category',
			'terms' => gd_dataload_allcontent_categories(),
		)
	);
	return apply_filters('gd_template:allcontent:tax_query_items', $tax_query_items);
}

function gd_dataload_latestcounts_taxquery_items() {

	$tax_query_items = array(
		array(
			'taxonomy' => 'category',
			'terms' => gd_dataload_latestcounts_categories(),
		)
	);
	return apply_filters('gd_template:latestcounts:tax_query_items', $tax_query_items);
}