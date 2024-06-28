<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentProcessors;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Exception\ContentNotExistsException;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginStaticHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\LocaleHelper;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\Root\Environment as RootEnvironment;
use PoP\Root\Services\BasicServiceTrait;

use function sanitize_title;

abstract class AbstractContentParser implements ContentParserInterface
{
    use BasicServiceTrait;

    public final const PATH_URL_TO_DOCS = 'pathURLToDocs';

    protected string $baseDir = '';
    protected string $baseURL = '';
    protected string $docsFolder = '';
    protected string $githubRepoDocsRootPathURL = '';
    protected bool $useDocsFolderInFileDir = true;

    private ?RequestHelperServiceInterface $requestHelperService = null;
    private ?LocaleHelper $localeHelper = null;
    private ?CMSHelperServiceInterface $cmsHelperService = null;

    /**
     * @param string|null $baseDir Where to look for the documentation
     * @param string|null $baseURL URL for the documentation
     * @param string|null $docsFolder folder under which the docs are stored
     * @param string|null $githubRepoDocsRootPathURL GitHub repo URL, to retrieve images for PROD
     */
    public function __construct(
        ?string $baseDir = null,
        ?string $baseURL = null,
        ?string $docsFolder = null,
        ?string $githubRepoDocsRootPathURL = null,
        ?bool $useDocsFolderInFileDir = null,
    ) {
        $this->setBaseDir($baseDir);
        $this->setBaseURL($baseURL);
        $this->setDocsFolder($docsFolder);
        $this->setGitHubRepoDocsRootPathURL($githubRepoDocsRootPathURL);
        $this->setUseDocsFolderInFileDir($useDocsFolderInFileDir);
    }

    final public function setRequestHelperService(RequestHelperServiceInterface $requestHelperService): void
    {
        $this->requestHelperService = $requestHelperService;
    }
    final protected function getRequestHelperService(): RequestHelperServiceInterface
    {
        if ($this->requestHelperService === null) {
            /** @var RequestHelperServiceInterface */
            $requestHelperService = $this->instanceManager->getInstance(RequestHelperServiceInterface::class);
            $this->requestHelperService = $requestHelperService;
        }
        return $this->requestHelperService;
    }
    final public function setLocaleHelper(LocaleHelper $localeHelper): void
    {
        $this->localeHelper = $localeHelper;
    }
    final protected function getLocaleHelper(): LocaleHelper
    {
        if ($this->localeHelper === null) {
            /** @var LocaleHelper */
            $localeHelper = $this->instanceManager->getInstance(LocaleHelper::class);
            $this->localeHelper = $localeHelper;
        }
        return $this->localeHelper;
    }
    final public function setCMSHelperService(CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    final protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        if ($this->cmsHelperService === null) {
            /** @var CMSHelperServiceInterface */
            $cmsHelperService = $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
            $this->cmsHelperService = $cmsHelperService;
        }
        return $this->cmsHelperService;
    }

    /**
     * Inject the dir where to look for the documentation.
     * If null, it uses the default value from the main plugin.
     */
    public function setBaseDir(?string $baseDir = null): void
    {
        $this->baseDir = $baseDir ?? PluginApp::getMainPlugin()->getPluginDir();
    }

    /**
     * Inject the URL where to look for the documentation.
     * If null, it uses the default value from the main plugin.
     */
    public function setBaseURL(?string $baseURL = null): void
    {
        $this->baseURL = $baseURL ?? PluginApp::getMainPlugin()->getPluginURL();
    }

    /**
     * Inject the folder under which the docs are stored.
     * If null, it uses the default value from the main plugin.
     */
    public function setDocsFolder(?string $docsFolder = null): void
    {
        $this->docsFolder = $docsFolder ?? 'docs';
    }

    /**
     * Inject the GitHub repo URL, to retrieve images for PROD.
     * If null, it uses the default value from the main plugin.
     */
    public function setGitHubRepoDocsRootPathURL(?string $githubRepoDocsRootPathURL = null): void
    {
        $this->githubRepoDocsRootPathURL = $githubRepoDocsRootPathURL ?? PluginStaticHelpers::getGitHubRepoDocsRootPathURL();
    }

    /**
     * Use `false` to pass the "docs" folder when requesting
     * the file to read (so can retrieve files from either
     * "docs" or "docs-extensions" folders)
     */
    public function setUseDocsFolderInFileDir(?bool $useDocsFolderInFileDir = null): void
    {
        $this->useDocsFolderInFileDir = $useDocsFolderInFileDir ?? true;
    }

    /**
     * Parse the file's Markdown into HTML Content
     *
     * @param string $relativePathDir Dir relative to the /docs/${lang}/ folder
     * @throws ContentNotExistsException When the file is not found
     * @param array<string,mixed> $options
     */
    public function getContent(
        string $filename,
        string $extension,
        string $relativePathDir = '',
        array $options = []
    ): string {
        // Make sure the relative path ends with "/"
        if ($relativePathDir) {
            $relativePathDir = \trailingslashit($relativePathDir);
        }
        $localeLanguage = $this->getLocaleHelper()->getLocaleLanguage();
        $localizeFile = \trailingslashit($this->getFileDir()) . $relativePathDir . $filename . '/' . $localeLanguage . '.' . $extension;
        if (file_exists($localizeFile)) {
            // First check if the localized version exists
            $file = $localizeFile;
        } else {
            // Otherwise, use the default language version
            $defaultDocsLanguage = $this->getDefaultDocsLanguage();
            $file = \trailingslashit($this->getFileDir()) . $relativePathDir . $filename . '/' . $defaultDocsLanguage . '.' . $extension;
            // Make sure this file exists
            if (!file_exists($file)) {
                throw new ContentNotExistsException(sprintf(
                    \__('File \'%s\' does not exist', 'gatographql'),
                    $file
                ));
            }
        }
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $fileContent = file_get_contents($file);
        if ($fileContent === false) {
            throw new ContentNotExistsException(sprintf(
                \__('File \'%s\' is corrupted', 'gatographql'),
                $file
            ));
        }
        $htmlContent = $this->getHTMLContent($fileContent);
        $pathURL = \trailingslashit($this->baseURL . $this->docsFolder) . $relativePathDir . $filename . '/';
        // Include the images from the GitHub repo, unless we are in DEV
        if (!RootEnvironment::isApplicationEnvironmentDev()) {
            $options[self::PATH_URL_TO_DOCS] = \trailingslashit($this->githubRepoDocsRootPathURL . '/' . $this->docsFolder) . $relativePathDir . $filename . '/';
        }
        return $this->processHTMLContent($htmlContent, $pathURL, $options);
    }

    /**
     * Default language for documentation: English
     */
    public function getDefaultDocsLanguage(): string
    {
        return 'en';
    }

    /**
     * Path where to find the local images
     */
    protected function getFileDir(): string
    {
        return $this->baseDir . ($this->useDocsFolderInFileDir ? '/' . $this->docsFolder : '');
    }

    /**
     * Process the HTML content:
     *
     * - Add the path to the images and anchors
     * - Add classes to HTML elements
     * - Append video embeds
     */
    abstract protected function getHTMLContent(string $fileContent): string;

    /**
     * Process the HTML content:
     *
     * - Add the path to the images and anchors
     * - Add classes to HTML elements
     * - Append video embeds
     *
     * @param array<string,mixed> $options
     */
    protected function processHTMLContent(string $htmlContent, string $pathURL, array $options = []): string
    {
        // Add default values for the options
        $options = array_merge(
            [
                ContentParserOptions::APPEND_PATH_URL_TO_IMAGES => true,
                ContentParserOptions::APPEND_PATH_URL_TO_ANCHORS => true,
                ContentParserOptions::SUPPORT_MARKDOWN_LINKS => true,
                ContentParserOptions::OPEN_EXTERNAL_LINKS_IN_NEW_TAB => true,
                ContentParserOptions::ADD_EXTERNAL_LINK_ICON => true,
                ContentParserOptions::ADD_CLASSES => true,
                ContentParserOptions::EMBED_VIDEOS => true,
                ContentParserOptions::HIGHLIGHT_CODE => true,
                ContentParserOptions::TAB_CONTENT => false,
                ContentParserOptions::REPLACEMENTS => [],
            ],
            $options
        );
        // Convert Markdown links: execute before appending path to anchors
        if ($options[ContentParserOptions::SUPPORT_MARKDOWN_LINKS] ?? null) {
            $openInModal = $options[ContentParserOptions::OPEN_MARKDOWN_LINKS_IN_MODAL] ?? true;
            $htmlContent = $this->convertMarkdownLinks($htmlContent, $openInModal);
        }
        // Open external links in new tab
        if ($options[ContentParserOptions::OPEN_EXTERNAL_LINKS_IN_NEW_TAB] ?? null) {
            $addExternalLinkIcon = $options[ContentParserOptions::ADD_EXTERNAL_LINK_ICON] ?? true;
            $htmlContent = $this->openExternalLinksInNewTab($htmlContent, $addExternalLinkIcon);
        }
        // Add the path to the images
        if ($options[ContentParserOptions::APPEND_PATH_URL_TO_IMAGES] ?? null) {
            // Enable to override the path for images, to read them from
            // the GitHub repo and avoid including them in the plugin
            $imagePathURL = $options[self::PATH_URL_TO_DOCS] ?? $pathURL;
            $htmlContent = $this->appendPathURLToImages($imagePathURL, $htmlContent);
            $htmlContent = $this->appendPathURLToAnchors($imagePathURL, $htmlContent);
        }
        // Add the path to the anchors
        if ($options[ContentParserOptions::APPEND_PATH_URL_TO_ANCHORS] ?? null) {
            $htmlContent = $this->appendPathURLToAnchors($pathURL, $htmlContent);
        }
        // Add classes to HTML elements
        if ($options[ContentParserOptions::ADD_CLASSES] ?? null) {
            $htmlContent = $this->addClasses($htmlContent);
        }
        // Append video embeds
        if ($options[ContentParserOptions::EMBED_VIDEOS] ?? null) {
            $htmlContent = $this->embedVideos($htmlContent);
        }
        // Prettify code
        if ($options[ContentParserOptions::HIGHLIGHT_CODE] ?? null) {
            $htmlContent = $this->highlightCode($htmlContent);
        }
        // Convert the <h2> into tabs
        if ($options[ContentParserOptions::TAB_CONTENT] ?? null) {
            $htmlContent = $this->tabContent($htmlContent);
        }
        /**
         * Replace strings in the code
         * @var array<string,string>
         */
        $replacements = $options[ContentParserOptions::REPLACEMENTS] ?? [];
        if ($replacements !== []) {
            $htmlContent = str_replace(array_keys($replacements), array_values($replacements), $htmlContent);
        }
        return $htmlContent;
    }

    /**
     * Add tabs to the content wherever there is an <h2>
     */
    protected function tabContent(string $htmlContent): string
    {
        $tag = 'h2';
        $firstTagPos = strpos($htmlContent, '<' . $tag . '>');
        // Check if there is any <h2>
        if ($firstTagPos !== false) {
            // If passing a tab, focus on that one, if the tab exists
            $tab = App::query(RequestParams::TAB);

            // Content before the first <h2> does not go within any tab
            $contentStarter = substr(
                $htmlContent,
                0,
                $firstTagPos
            );
            // Add the markup for the tabs around every <h2>
            $regex = sprintf(
                '/<%1$s>(.*?)<\/%1$s>/',
                $tag
            );
            $headers = [];
            $headerNames = [];
            $tabbedPanelPlaceholder = '<div id="%s" class="tab-content" style="display: %s;">';
            $panelContent = preg_replace_callback(
                $regex,
                function (array $matches) use (&$headers, &$headerNames, $tab, $tabbedPanelPlaceholder): string {
                    $isFirstTab = empty($headers);
                    if (!$isFirstTab) {
                        $tabbedPanel = '</div>';
                    } else {
                        $tabbedPanel = '';
                    }
                    $header = $matches[1];
                    $headers[] = $header;
                    $headerName = sanitize_title($header);
                    $headerNames[] = $headerName;
                    $isActiveTab = $tab === null ? $isFirstTab : $headerName === $tab;
                    /** @var string */
                    return $tabbedPanel . sprintf(
                        $tabbedPanelPlaceholder,
                        $headerName,
                        $isActiveTab ? 'block' : 'none'
                    );// . $matches[0];
                },
                substr(
                    $htmlContent,
                    $firstTagPos
                )
            ) . '</div>';

            /**
             * Only now we have all the headerNames.
             * Check if the passed ?tab=... does indeed exist.
             * If it does not, make the first tab as active.
             */
            if ($tab !== null && !in_array($tab, $headerNames)) {
                $panelContent = str_replace(
                    sprintf(
                        $tabbedPanelPlaceholder,
                        $headerNames[0],
                        'none'
                    ),
                    sprintf(
                        $tabbedPanelPlaceholder,
                        $headerNames[0],
                        'block'
                    ),
                    $panelContent
                );
            }

            // Create the tabs
            $panelTabs = '';
            $headersCount = count($headers);

            // If passing a tab, focus on that one, if the tab exists
            if ($tab !== null && in_array($tab, $headerNames)) {
                $activeHeaderName = $tab;
            } else {
                $activeHeaderName = $headerNames[0];
            }

            // This page URL
            $url = admin_url(sprintf(
                'admin.php?page=%s',
                esc_attr(App::request('page') ?? App::query('page', ''))
            ));

            for ($i = 0; $i < $headersCount; $i++) {
                $headerName = $headerNames[$i];
                /**
                 * Also add the tab to the URL, not because it is needed,
                 * but because we can then "Open in new tab" and it will
                 * be focused already on that item.
                 */
                $headerURL = sprintf(
                    '%1$s&%2$s=%3$s',
                    $url,
                    RequestParams::TAB,
                    $headerName
                );
                $panelTabs .= sprintf(
                    '<a data-tab-target="%s" href="%s" class="nav-tab %s">%s</a>',
                    '#' . $headerName,
                    $headerURL,
                    $headerName === $activeHeaderName ? 'nav-tab-active' : '',
                    $headers[$i]
                );
            }

            return
                $contentStarter
                . '<div class="gatographql-tabpanel">'
                .   '<div class="nav-tab-container">'
                .     '<h2 class="nav-tab-wrapper">'
                .       $panelTabs
                .     '</h2>'
                .     '<div class="nav-tab-content">'
                .       $panelContent
                .     '</div>'
                .   '</div>'
                . '</div>';
        }
        return $htmlContent;
    }

    /**
     * Is the anchor pointing to an URL?
     */
    protected function isAbsoluteURL(string $href): bool
    {
        return \str_starts_with($href, 'http://') || \str_starts_with($href, 'https://');
    }

    /**
     * Is the anchor pointing to an email?
     */
    protected function isMailto(string $href): bool
    {
        return \str_starts_with($href, 'mailto:');
    }

    /**
     * Whenever a link points to a .md file, convert it
     * so it works also within the plugin
     */
    protected function convertMarkdownLinks(
        string $htmlContent,
        bool $openInModal = true,
    ): string {
        return (string)preg_replace_callback(
            '/<a.*href="(.*?)\.md".*?>/',
            function (array $matches) use ($openInModal): string {
                // If the element has an absolute route, then no need
                if ($this->isAbsoluteURL($matches[1]) || $this->isMailto($matches[1])) {
                    return $matches[0];
                }
                $doc = $matches[1];
                /**
                 * The doc might be of this kind:
                 *
                 *   "../../release-notes/0.9/en"
                 *
                 * It contains the language. This must be removed.
                 * The result must be:
                 *
                 *   "../../release-notes/0.9"
                 */
                $langPos = strrpos($doc, '/');
                if ($langPos !== false) {
                    $doc = substr($doc, 0, $langPos);
                }

                $elementURLParams = [
                    RequestParams::TAB => RequestParams::TAB_DOCS,
                    RequestParams::DOC => $doc,
                ];
                if ($openInModal) {
                    $elementURLParams['TB_iframe'] = 'true';
                }

                // The URL is the current one, plus attr to open the .md file
                // in a modal window
                $elementURL = \add_query_arg(
                    $elementURLParams,
                    $this->getRequestHelperService()->getRequestedFullURL()
                );

                /** @var string */
                $link = str_replace(
                    "href=\"{$matches[1]}.md\"",
                    "href=\"{$elementURL}\"",
                    $matches[0]
                );
                if (!$openInModal) {
                    return $link;
                }

                // Must also add some classnames
                $classnames = 'thickbox open-plugin-details-modal';
                // 1. If there are classes already
                /** @var string */
                $replacedLink = preg_replace_callback(
                    '/ class="(.*?)"/',
                    function (array $matches) use ($classnames): string {
                        return str_replace(
                            " class=\"{$matches[1]}\"",
                            " class=\"{$matches[1]} {$classnames}\"",
                            $matches[0]
                        );
                    },
                    $link
                );
                // 2. If there were no classes
                if ($replacedLink === $link) {
                    $replacedLink = str_replace(
                        "<a ",
                        "<a class=\"{$classnames}\" ",
                        $link
                    );
                }
                return $replacedLink;
            },
            $htmlContent
        );
    }

    /**
     * Add `target="_blank"` to external links
     */
    protected function openExternalLinksInNewTab(string $htmlContent, bool $addExternalLinkIcon): string
    {
        return (string)preg_replace_callback(
            '/<a (.*?)href="(.*?)"(.*?)>(.*?)<\/a>/',
            function (array $matches) use ($addExternalLinkIcon): string {
                // If the element is not an external link, or already has a target, return
                if (
                    !$this->isAbsoluteURL($matches[2])
                    || $this->getCMSHelperService()->isCurrentDomain($matches[2])
                    || str_contains($matches[1], ' target=')
                    || str_contains($matches[3], ' target=')
                ) {
                    return $matches[0];
                }
                return sprintf(
                    '<a %shref="%s"%s target="_blank">%s</a>',
                    $matches[1],
                    $matches[2],
                    $matches[3],
                    $matches[4] . ($addExternalLinkIcon ? '&#x2197;' : ''),
                );
            },
            $htmlContent
        );
    }

    /**
     * Append video embeds. These are not already in the markdown file
     * because GitHub can't add `<iframe>`. Then, the source only contains
     * a link to the video. This must be identified, and transformed into
     * the embed.
     *
     * The match is produced when a link is pointing to a video in
     * Vimeo or Youtube by the end of the paragraph, with/out a final dot.
     */
    protected function embedVideos(string $htmlContent): string
    {
        // Identify videos from Vimeo
        $htmlContent = (string)preg_replace_callback(
            '/<p>(.*?)<a href="https:\/\/(vimeo.com)\/(.*?)">(.*?)<\/a>\.?<\/p>/',
            function (array $matches): string {
                $playerURL = sprintf('https://player.vimeo.com/video/%s', $matches[3]);
                $videoHTML = sprintf(
                    '<iframe src="%s" width="640" height="480" frameborder="0" allow="fullscreen; picture-in-picture" allowfullscreen></iframe>',
                    $playerURL
                );
                // Keep the link, and append the embed immediately after
                return
                    $matches[0]
                    . '<div class="video-responsive-container">' . $videoHTML . '</div>';
            },
            $htmlContent
        );

        // Identify videos from YouTube
        $htmlContent = (string)preg_replace_callback(
            '/<p>(.*?)<a href="https:\/\/(www.youtube.com)\/watch\?v=(.*?)">(.*?)<\/a>\.?<\/p>/',
            function (array $matches): string {
                $playerURL = sprintf('https://www.youtube.com/embed/%s', $matches[3]);
                $videoHTML = sprintf(
                    '<iframe src="%s" width="768" height="432" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen=""></iframe>',
                    $playerURL
                );
                // Keep the link, and append the embed immediately after
                return
                    $matches[0]
                    . '<div class="video-responsive-container">' . $videoHTML . '</div>';
            },
            $htmlContent
        );

        return $htmlContent;
    }

    /**
     * Use Highlight.js to add styles to <pre><code>
     */
    protected function highlightCode(string $htmlContent): string
    {
        return str_replace(
            '<pre><code class="',
            '<pre class="prettyprint hljs"><code class="hljs language-',
            $htmlContent
        );
    }

    /**
     * Add classes to the HTML elements
     */
    protected function addClasses(string $htmlContent): string
    {
        /**
         * Add class "wp-list-table widefat" to all tables
         */
        return str_replace(
            '<table>',
            '<table class="wp-list-table widefat striped">',
            $htmlContent
        );
    }

    /**
     * Convert relative paths to absolute paths for image URLs
     */
    protected function appendPathURLToImages(string $pathURL, string $htmlContent): string
    {
        return $this->appendPathURLToElement('img', 'src', $pathURL, $htmlContent);
    }

    /**
     * Convert relative paths to absolute paths for image URLs
     */
    protected function appendPathURLToAnchors(string $pathURL, string $htmlContent): string
    {
        return $this->appendPathURLToElement('a', 'href', $pathURL, $htmlContent);
    }

    /**
     * Convert relative paths to absolute paths for elements
     */
    protected function appendPathURLToElement(string $tag, string $attr, string $pathURL, string $htmlContent): string
    {
        /**
         * $regex will become:
         * - /<img.*src="(.*?)".*?>/
         * - /<a.*href="(.*?)".*?>/
         */
        $regex = sprintf(
            '/<%s.*%s="(.*?)".*?>/',
            $tag,
            $attr
        );
        return (string)preg_replace_callback(
            $regex,
            function (array $matches) use ($pathURL, $attr): string {
                // If the element has an absolute route, then no need
                if ($this->isAbsoluteURL($matches[1]) || $this->isMailto($matches[1])) {
                    return $matches[0];
                }
                $elementURL = \trailingslashit($pathURL) . $matches[1];
                return str_replace(
                    "{$attr}=\"{$matches[1]}\"",
                    "{$attr}=\"{$elementURL}\"",
                    $matches[0]
                );
            },
            $htmlContent
        );
    }
}
