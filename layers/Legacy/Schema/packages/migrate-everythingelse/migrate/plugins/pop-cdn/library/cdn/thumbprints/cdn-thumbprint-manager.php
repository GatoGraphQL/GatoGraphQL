<?php

use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;

class PoP_CDN_ThumbprintManager
{
    public $thumbprints;

    public function __construct()
    {
        $this->thumbprints = array();
    }

    public function add($thumbprint)
    {
        $this->thumbprints[$thumbprint->getName()] = $thumbprint;
    }

    public function getThumbprints()
    {
        return array_keys($this->thumbprints);
    }

    public function getThumbprintValue($name)
    {
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $thumbprint = $this->thumbprints[$name];
        if (!$thumbprint) {
            throw new \PoP\Root\Exception\GenericException(
                sprintf(
                    'Error: there is no thumbprint with name \'%s\' (%s)',
                    $name,
                    $requestHelperService->getRequestedFullURL()
                )
            );
        }
        $query = $thumbprint->getQuery();

        // Get the ID for the last modified object
        if ($results = $thumbprint->executeQuery($query)) {
            $object_id = $results[0];

            // The thumbprint is the modification date timestamp
            return (int) $thumbprint->getTimestamp($object_id);
        }

        return '';

        // // Add an id, so that we can easily identify what entity the thumbprint value belongs to
        // // It would normally be the first letter of the name (eg: 'post' => 'p'), but because post and page
        // // both start with 'p', that doesn't work, so if that happens, switch to next letter

        // return $id.$value;
    }
}

/**
 * Initialize
 */
global $pop_cdn_thumbprint_manager;
$pop_cdn_thumbprint_manager = new PoP_CDN_ThumbprintManager();
