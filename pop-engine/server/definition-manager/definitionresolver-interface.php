<?php

interface PoP_DefinitionResolver {

	function get_definition($name, $group);
	function get_data_to_persist();
	function set_persisted_data($persisted_data);
}