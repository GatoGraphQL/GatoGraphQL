<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

class ContentParserOptions
{
    public const APPEND_PATH_URL_TO_IMAGES = 'appendPathURLToImages';
    public const SUPPORT_MARKDOWN_LINKS = 'supportMarkdownLinks';
    public const APPEND_PATH_URL_TO_ANCHORS = 'appendPathURLToAnchors';
    public const ADD_CLASSES = 'addClasses';
    public const EMBED_VIDEOS = 'embedVideos';
    public const PRETTIFY_CODE = 'prettifyCode';
    public const TAB_CONTENT = 'tabContent';
}
