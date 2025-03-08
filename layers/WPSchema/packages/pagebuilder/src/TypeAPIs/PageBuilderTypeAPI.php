<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\TypeAPIs;

use PoPWPSchema\SchemaCommons\StaticHelpers\WordPressStaticHelpers;
use PoP\Root\Services\AbstractBasicService;

class PageBuilderTypeAPI extends AbstractBasicService implements PageBuilderTypeAPIInterface
{
    /**
     * The Gutenberg editor is enabled when the Classic editor
     * plugin is not enabled
     *
     * @see https://wordpress.org/plugins/classic-editor/
     */
    public function isGutenbergEditorEnabled(): bool
    {
        return !WordPressStaticHelpers::isWordPressPluginActive('classic-editor/classic-editor.php');
    }
}
