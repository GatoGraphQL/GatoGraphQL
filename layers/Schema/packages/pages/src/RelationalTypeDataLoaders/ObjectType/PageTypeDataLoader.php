<?php

declare(strict_types=1);

namespace PoPSchema\Pages\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class PageTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    protected PageTypeAPIInterface $pageTypeAPI;

    #[Required]
    public function autowirePageTypeDataLoader(
        PageTypeAPIInterface $pageTypeAPI,
    ): void {
        $this->pageTypeAPI = $pageTypeAPI;
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->pageTypeAPI->getPages($query, $options);
    }
}
