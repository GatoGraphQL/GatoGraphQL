<?php

declare(strict_types=1);

namespace EverythingElse\PoPSchema\Categories\TypeAPIs;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CategoryTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    public function hasCategory($catObjectOrID, $post_id);
    public function getCategoryPath($category_id);
}
