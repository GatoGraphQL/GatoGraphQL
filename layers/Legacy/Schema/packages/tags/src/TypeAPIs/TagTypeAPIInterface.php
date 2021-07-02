<?php

declare(strict_types=1);

namespace EverythingElse\PoPSchema\Tags\TypeAPIs;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface TagTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    public function getTagBase(): string;
    public function setPostTags(string | int $customPostID, array $tags, bool $append = false): void;
}
