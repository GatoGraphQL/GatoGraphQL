<?php

declare(strict_types=1);

namespace EverythingElse\PoPCMSSchema\TagsWP\TypeAPIs;

use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\TaxonomyTermTypeAPI;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractTagTypeAPI extends TaxonomyTermTypeAPI implements TagTypeAPIInterface
{
    public function getTagBase(): string
    {
        $cmsService = CMSServiceFacade::getInstance();
        return (string)$cmsService->getOption($this->getTagBaseOption());
    }

    abstract protected function getTagBaseOption(): string;

    /**
     * @param string[] $tags
     */
    public function setPostTags(string|int $customPostID, array $tags, bool $append = false): void
    {
        \wp_set_post_terms($customPostID, $tags, $this->getTagTaxonomyName(), $append);
    }
}
