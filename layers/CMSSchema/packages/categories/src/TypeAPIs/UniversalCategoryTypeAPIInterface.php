<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeAPIs;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

interface UniversalCategoryTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    public function getCategorySlug(string|int|object $catObjectOrID): ?string;
    public function getCategorySlugPath(string|int|object $catObjectOrID): ?string;
    public function getCategoryName(string|int|object $catObjectOrID): ?string;
    public function getCategoryParentID(string|int|object $catObjectOrID): string|int|null;
    public function getCategoryURL(string|int|object $catObjectOrID): ?string;
    public function getCategoryURLPath(string|int|object $catObjectOrID): ?string;
    public function getCategoryDescription(string|int|object $catObjectOrID): ?string;
    public function getCategoryItemCount(string|int|object $catObjectOrID): ?int;
}
