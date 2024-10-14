<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentProcessors;

class ContentParserOptions
{
    public final const APPEND_PATH_URL_TO_IMAGES = 'appendPathURLToImages';
    public final const SUPPORT_MARKDOWN_LINKS = 'supportMarkdownLinks';
    public final const OPEN_MARKDOWN_LINKS_IN_MODAL = 'openMarkdownLinksInModal';
    public final const OPEN_EXTERNAL_LINKS_IN_NEW_TAB = 'openExternalLinksInNewTab';
    public final const ADD_EXTERNAL_LINK_ICON = 'addExternalLinkIcon';
    public final const APPEND_PATH_URL_TO_ANCHORS = 'appendPathURLToAnchors';
    public final const ADD_CLASSES = 'addClasses';
    public final const EMBED_VIDEOS = 'embedVideos';
    public final const HIGHLIGHT_CODE = 'highlightCode';
    public final const TAB_CONTENT = 'tabContent';
    public final const REPLACEMENTS = 'replacements';
}
