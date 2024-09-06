<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeAPIs;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

interface UniversalTagTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    public function getTagURL(string|int|object $tagObjectOrID): ?string;
    public function getTagURLPath(string|int|object $tagObjectOrID): ?string;
    public function getTagName(string|int|object $tagObjectOrID): ?string;
    public function getTagSlug(string|int|object $tagObjectOrID): ?string;
    public function getTagDescription(string|int|object $tagObjectOrID): ?string;
    public function getTagItemCount(string|int|object $tagObjectOrID): ?int;
}
