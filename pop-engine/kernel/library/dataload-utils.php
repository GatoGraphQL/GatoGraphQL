<?php
namespace PoP\Engine;

class DataloadUtils {

	public static function get_default_dataloader_name_from_subcomponent_data_field($dataloader_or_name, $subcomponent_data_field) {

		if (is_object($dataloader_or_name)) {
			$dataloader = $dataloader_or_name;
		}
		else {
			$dataloader_name = $dataloader_or_name;
			$dataloader_manager = Dataloader_Manager_Factory::get_instance();
			$dataloader = $dataloader_manager->get($dataloader_name);
		}

		$fieldprocessor_manager = FieldProcessor_Manager_Factory::get_instance();
		$fieldprocessor = $fieldprocessor_manager->get($dataloader->get_fieldprocessor());
		$subcomponent_dataloader_name = $fieldprocessor->get_field_default_dataloader($subcomponent_data_field);
		if (!$subcomponent_dataloader_name && \PoP\Engine\Server\Utils::fail_if_subcomponent_dataloader_undefined()) {

			throw new \Exception(sprintf('There is no default dataloader set for field  "%s" from dataloader "%s" and fieldprocessor "%s" (%s)', $subcomponent_data_field, $dataloader_name, $fieldprocessor_name, full_url()));
		}

		return $subcomponent_dataloader_name;
	}
}