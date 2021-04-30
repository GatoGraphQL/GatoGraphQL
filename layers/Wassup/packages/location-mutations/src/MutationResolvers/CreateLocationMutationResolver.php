<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Locations\Facades\LocationTypeAPIFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\ErrorHandling\Error;

class CreateLocationMutationResolver extends AbstractMutationResolver
{
    public function execute(array $form_data): mixed
    {
        // Allow EM PoP to initialize the field names as it needs them to populate the object in function getPost($validate = true),
        // in file plugins/events-manager/classes/em-location.php
        $this->hooksAPI->doAction('create_location');

        $pluginapi = \PoP_Locations_APIFactory::getInstance();
        $location = $pluginapi->getNewLocationObject();

        // Load from $_REQUEST and Validate
        if ($pluginapi->getPost($location) && $pluginapi->save($location)) { //EM_location gets the location if submitted via POST and validates it (safer than to depend on JS)
            $locationTypeAPI = LocationTypeAPIFacade::getInstance();
            return $locationTypeAPI->getID($location);
        }
        if ($errors = $pluginapi->getErrors($location)) {
            $error = new Error();
            foreach ($errors as $errorMsg) {
                $error->add('location-error', $errorMsg);
            }
            return $error;
        }
        return false;
    }
}
