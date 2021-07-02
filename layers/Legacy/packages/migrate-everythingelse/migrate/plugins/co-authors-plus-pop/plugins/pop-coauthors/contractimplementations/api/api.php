<?php

use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class CAP_PoP_Coauthors_API extends PoP_Coauthors_API_Base implements PoP_Coauthors_API
{
    public function getCoauthors($post_id, array $options = [])
    {
        $coauthors = get_coauthors($post_id);
        if ($return_type = $options['return-type'] ?? null) {
            if ($return_type == ReturnTypes::IDS) {
		        return array_map(function($object) {
			    	return $object->ID;
			    }, $coauthors);
			}
		}

		return $coauthors;

    }
}

/**
 * Initialize
 */
new CAP_PoP_Coauthors_API();
