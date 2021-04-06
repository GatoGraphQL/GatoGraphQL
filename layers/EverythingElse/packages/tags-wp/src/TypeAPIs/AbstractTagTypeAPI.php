<?php

declare(strict_types=1);

namespace PoPSchema\TagsWP\TypeAPIs;

use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;
use PoP\Engine\Facades\CMS\CMSServiceFacade;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractTagTypeAPI extends TaxonomyTypeAPI implements TagTypeAPIInterface
{
    public function getTagBase(): string
    {
        $cmsService = CMSServiceFacade::getInstance();
        return (string)$cmsService->getOption($this->getTagBaseOption());
    }

    abstract protected function getTagBaseOption(): string;
}
