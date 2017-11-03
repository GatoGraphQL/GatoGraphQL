<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_TemplateIDManager {

	private $templatedefinition_counter, $current_namespace, $database;

	function __construct() {

		// Comment Leo 27/07/2017: Since adding the namespace/prefix, no need to start with a letter
		// we just gotta make sure that the prefix starts with a letter
		// However we initially still set it to 10, since it will be reset to 0 when calling set_namespace()
		// Counter: cannot start with a number, or the id will get confused
		// First number is 10, that is "a" in base 36
		$this->templatedefinition_counter = 10;
		// $namespace is a 2-character string, cannot start with a number, to uniquely identify a plugin
		// So we have (36x36)-360=936 possible namespaces/plugins that can be part of the PoP ecosystem
		$this->current_namespace = '';

		$this->database = array();

		// Comment Leo 03/11/2017: added a DB to avoid the website from producing errors each time that new templates are introduced
		// The DB is needed to return the same mangled results for the same incoming templates, over time
		// Otherwise, when a new template is introduced, the website after deploy will produce errors from
		// the cached data in the localStorage and Service Workers (the cached data references templates with a name that is not the right one anymore)
		// Get the database from the file saved in disk
		if (PoP_ServerUtils::use_templatedefinition_constantovertime()) {
			
			global $pop_engine_filejsonstorage, $pop_engine_templateidconstantdbfilegenerator;
			
			// The first time we generate the database, there will be nothing
			if ($internals = $pop_engine_filejsonstorage->get($pop_engine_templateidconstantdbfilegenerator->get_filepath())) {

				$this->database = $internals['database'];

				// Set the counter as the last entry from the database, so it continues from there
				$this->templatedefinition_counter = $internals['templatedefinition-counter'];
			}

			// Also add the action to save the results again in the hard disk, for the build process
			add_action('PoP:system-build:server', array($this, 'system_build_server'));
		}
	}

	function system_build_server() {

		// global $pop_engine_templateidconstantdbfilegenerator;
		// $pop_engine_templateidconstantdbfilegenerator->generate();
		$internals = array(
			'database' => $this->database,
			'templatedefinition-counter' => $this->templatedefinition_counter,
		);

		// Save the DB in the hard disk, $pop_engine_templateidconstantdbfilegenerator
		global $pop_engine_templateidconstantdbfilegenerator, $pop_engine_filejsonstorage;
		$pop_engine_filejsonstorage->save($pop_engine_templateidconstantdbfilegenerator->get_filepath(), $internals);
	}

	// function get_database() {

	// 	return $this->database;
	// }

	function set_namespace($namespace) {

		if (!PoP_ServerUtils::use_templatedefinition_namespaces()) {
			return;
		}

		$this->current_namespace = $namespace;

		// Restart the counter
		$this->templatedefinition_counter = 0;
	}

	/**
	 * Function used to create a definition for a template. Needed for reducing the filesize of the html generated for PROD
	 * Instead of using the name of the $template_id, we use a unique number in base 36, so the name will occupy much lower size
	 * Comment Leo 27/09/2017: Changed from $template_id to only $id so that it can also be used with ResourceLoaders
	 */
	function get_template_definition($id/*$template_id*/, $mirror = false) {

		// If not mangled, then that's it, use the original $template_id
		if (PoP_ServerUtils::is_not_mangled()) {
			return $id;
		}

		// Mirror: it simply returns the $template_id again. It confirms in the code that this decision is deliberate 
		// (not calling function get_template_definition could also be that the developer forgot about it)
		// It is simply used to explicitly say that we need the same name as the template_id, eg: for the filtercomponents,
		// so that in the URL params it shows names that make sense (author=...&search=...)
		if ($mirror) {
			return $id;
		}

		$templatedefinition_type = PoP_ServerUtils::get_templatedefinition_type();

		// Type 0: Use $template_id as the definition
		if ($templatedefinition_type === 0) {
			return $id;
		}

		// Check if there is an entry in the database for this template. If so, use that one already
		if (PoP_ServerUtils::use_templatedefinition_constantovertime()) {
			
			if ($cached = $this->database[$id]) {
				return $cached;
			}
		}

		// Comment Leo: Fix here! The array should be injectable, each plug-in should add its reserved names
		// Reserved definitions: those which are used with the mirroring, and which can conflict with a generated definition
		// Eg: define ('GD_TEMPLATE_FORMCOMPONENT_COMMENTID', PoP_TemplateIDUtils::get_template_definition('cid', true));
		// $reserved = array();
		// if (!PoP_ServerUtils::use_templatedefinition_namespaces()) {
		$reserved = array(
			'pid', // post id
			'uid', // user id
			'lid', // location id
			'cid', // comment id
			'fa', // to avoid template with class "fa"
			'btn', // to avoid template with class "btn"
			// 'tag', // for the pageSection; commented since renaming it to tagpage because of problems with prettify
		);
		// }
		do {

			// Convert the number to base 36 to save chars
			$counter = base_convert($this->templatedefinition_counter, 10, 36);

			// Increase the counter by 1.
			$this->templatedefinition_counter++;

			// If we are not using namespacing, then make sure that the definition does not start with a number
			if (!PoP_ServerUtils::use_templatedefinition_namespaces()) {

				// If we reach a number whose base 36 conversion starts with a number, and not a letter, then skip
				if ($this->templatedefinition_counter == 36) {

					// 36 in base 10 = 10 in base 36
					// 360 in base 10 = a0 in base 36
					$this->templatedefinition_counter = 360;
				}
				elseif ($this->templatedefinition_counter == 1296) {

					// 1296 in base 10 = 100 in base 36
					// 12960 in base 10 = a00 in base 36
					$this->templatedefinition_counter = 12960;
				}
				elseif ($this->templatedefinition_counter == 46656) {

					// 46656 in base 10 = 1000 in base 36
					// 466560 in base 10 = a000 in base 36
					$this->templatedefinition_counter = 466560;
				}
			}
		} 
		while (in_array($counter, $reserved));

		// Get the result
		if ($templatedefinition_type === 2) {
			
			// Type 2: Use base36 $counter as the definition
			$definition = (PoP_ServerUtils::use_templatedefinition_namespaces() ? $this->current_namespace : '').$counter;
		}
		else {
			// Type 1: Use both base36 counter and $template_id as the definition
			// Do not add "-" or "_" to the definition, since some templates cannot support it.
			// Eg: formcomponenteditor, used with wp_editor
			$definition = $counter.$id;
		}

		// Check if there is an entry in the database for this template. If so, use that one already
		if (PoP_ServerUtils::use_templatedefinition_constantovertime()) {
			
			$this->database[$id] = $definition;
		}

		return $definition;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_templateidmanager;
$pop_templateidmanager = new PoP_TemplateIDManager();