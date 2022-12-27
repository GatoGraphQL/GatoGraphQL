<?php

declare(strict_types=1);

namespace EverythingElse\PoPCMSSchema\PostCategoriesWP\TypeAPIs;

use EverythingElse\PoPCMSSchema\CategoriesWP\TypeAPIs\AbstractCategoryTypeAPI;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostCategoryTypeAPI extends AbstractCategoryTypeAPI implements PostCategoryTypeAPIInterface
{
    protected function getCategoryBaseOption(): string
    {
        return 'category_base';
    }
}
