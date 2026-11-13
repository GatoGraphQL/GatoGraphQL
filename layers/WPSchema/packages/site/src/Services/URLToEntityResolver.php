<?php

declare(strict_types=1);

namespace PoPWPSchema\Site\Services;

use PoPWPSchema\Site\Constants\HookNames;
use PoPWPSchema\Site\Constants\URLEntityTypes;
use PoP\Root\App;
use PoP\Root\Services\AbstractBasicService;
use WP;
use WP_MatchesMapRegex;
use WP_Rewrite;
use WP_Taxonomy;
use WP_Term;
use WP_User;
use wpdb;

use function apply_filters;
use function array_fill;
use function array_filter;
use function array_flip;
use function array_intersect_key;
use function array_map;
use function array_merge;
use function array_pop;
use function array_unique;
use function array_values;
use function attachment_url_to_postid;
use function basename;
use function count;
use function count_user_posts;
use function current_user_can;
use function dirname;
use function explode;
use function get_author_posts_url;
use function get_option;
use function get_page_by_path;
use function get_post_meta;
use function get_post_type;
use function get_post_type_archive_link;
use function get_post_type_object;
use function get_post_types;
use function get_taxonomies;
use function get_term;
use function get_term_link;
use function get_terms;
use function get_user_by;
use function home_url;
use function implode;
use function in_array;
use function is_array;
use function is_numeric;
use function is_post_publicly_viewable;
use function is_string;
use function is_taxonomy_viewable;
use function ksort;
use function parse_str;
use function parse_url;
use function preg_match;
use function preg_quote;
use function preg_replace;
use function rawurldecode;
use function rawurlencode;
use function str_starts_with;
use function strlen;
use function strtolower;
use function substr;
use function trim;
use function url_to_postid;
use function wp_get_attachment_metadata;
use function wp_get_upload_dir;

class URLToEntityResolver extends AbstractBasicService implements URLToEntityResolverInterface
{
    /**
     * The query vars that point to a single custom post, which
     * `url_to_postid()` already resolves.
     */
    protected const CUSTOM_POST_QUERY_VARS = ['p', 'page_id', 'attachment_id', 'name', 'pagename', 'attachment'];

    /**
     * The extensions an image can be converted to (for its resized copies,
     * when the site outputs them in another format, such as WebP).
     */
    protected const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'heic'];

    /**
     * Resolves, in order: the homepage, a file under the uploads folder
     * (media), what `url_to_postid()` resolves (a custom post, or media for
     * an attachment page), and else the query vars that the rewrite rules
     * (or the query string, for plain permalinks) give the URL: a taxonomy
     * term, a user, or a custom post type archive.
     *
     * A term, a user and an archive are confirmed by comparing their own
     * URL with the requested one, which discards a false positive (such as
     * a child term under some other parent) and costs a lookup by slug or
     * ID only, whatever the number of terms or users.
     *
     * It resolves only what the user may see: a custom post or a media item
     * that is publicly viewable (or that the user can read), a term of a
     * publicly queryable taxonomy, and a user who has published posts (or
     * any user, for a user who can list them). It also leaves out the
     * custom post types and taxonomies that the schema does not let be
     * queried.
     *
     * @return array<string,string|int>|null
     */
    public function resolveURLToEntity(string $url): ?array
    {
        $url = trim($url);
        $entity = null;
        if ($url !== '') {
            $entity = $this->resolveInternalURLToEntity($this->getAbsoluteURL($url));
        }
        /** @var array<string,string|int>|null */
        $entity = App::applyFilters(
            HookNames::URL_TO_ENTITY,
            $entity,
            $url
        );
        return $entity;
    }

    /**
     * A path from the root of the site (`/contact/`) is read as the URL
     * with the site's own scheme and host.
     */
    protected function getAbsoluteURL(string $url): string
    {
        if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
            $homeURLParts = parse_url(home_url('/'));
            if (is_array($homeURLParts) && isset($homeURLParts['scheme'], $homeURLParts['host'])) {
                $port = isset($homeURLParts['port']) ? ':' . $homeURLParts['port'] : '';
                return $homeURLParts['scheme'] . '://' . $homeURLParts['host'] . $port . $url;
            }
        }
        return $url;
    }

    /**
     * @return array<string,string|int>|null
     */
    protected function resolveInternalURLToEntity(string $url): ?array
    {
        $comparableURL = $this->getComparableURL($url);
        if (!$this->isInternalURL($comparableURL)) {
            return null;
        }

        $pageURL = $this->removePaginationAndFeedFromURL($comparableURL);
        if ($this->isHomepageURL($pageURL)) {
            return [
                'type' => URLEntityTypes::HOMEPAGE,
                'id' => $this->getFrontPageID(),
            ];
        }

        $mediaID = $this->getMediaIDFromFileURL($url);
        if ($mediaID === 0 && $comparableURL !== $url) {
            $mediaID = $this->getMediaIDFromFileURL($comparableURL);
        }
        if ($mediaID !== 0) {
            return $this->getCustomPostEntity($mediaID);
        }

        $customPostID = url_to_postid($url);
        if ($customPostID === 0 && $comparableURL !== $url) {
            $customPostID = url_to_postid($comparableURL);
        }
        if ($customPostID !== 0) {
            return $this->getCustomPostEntity($customPostID);
        }

        $rewriteRuleQueryVars = $this->getRewriteRuleQueryVars($url);
        if ($rewriteRuleQueryVars === [] && $comparableURL !== $url) {
            $rewriteRuleQueryVars = $this->getRewriteRuleQueryVars($comparableURL);
        }
        $queryStringVars = $this->splitURL($url)['queryVars'];
        /** @var array<string,string> */
        $queryVars = array_filter(
            [
                ...$queryStringVars,
                ...$rewriteRuleQueryVars,
            ],
            fn (mixed $value): bool => is_string($value) && $value !== ''
        );
        if ($queryVars === []) {
            return null;
        }

        /**
         * The URL is compared as it is first, and then without its
         * pagination and feed, so that a term or a user whose slug is the
         * name of a feed (such as `/category/rss/`) is not taken for the
         * feed of its parent.
         */
        foreach (array_unique([$comparableURL, $pageURL]) as $entityPageURL) {
            /**
             * A plain permalink (`/?cat=5`, `/?author_name=jane`) names its
             * entity in the query string, and WordPress serves it whatever
             * the permalink structure, so the entity's own URL (which may be
             * a pretty one) need not be the same.
             */
            $isPlainPermalink = $rewriteRuleQueryVars === [] && $this->isHomePath($entityPageURL);
            $entity = $this->getTaxonomyTermEntity($queryVars, $entityPageURL, $isPlainPermalink)
                ?? $this->getUserEntity($queryVars, $entityPageURL, $isPlainPermalink)
                ?? $this->getCustomPostTypeArchiveEntity($queryVars, $entityPageURL, $isPlainPermalink);
            if ($entity !== null) {
                return $entity;
            }
        }
        return null;
    }

    /**
     * The homepage, also with query params that WordPress does not read
     * (such as `?utm_source=newsletter`). One that WordPress reads (such as
     * `?s=` or `?cat=`) points to something else.
     */
    protected function isHomepageURL(string $url): bool
    {
        if ($this->getPublicQueryVars($this->splitURL($url)['queryVars']) !== []) {
            return false;
        }
        return $this->areSameURL($this->getComparableURL(home_url('/')), $url);
    }

    /**
     * The query vars that WordPress reads from a URL, out of the given ones.
     * Without WordPress's request object, all of them count.
     *
     * @param array<string,string> $queryVars
     * @return array<string,string>
     */
    protected function getPublicQueryVars(array $queryVars): array
    {
        global $wp;
        if (!($wp instanceof WP)) {
            return $queryVars;
        }
        /** @var string[] */
        $publicQueryVars = apply_filters('query_vars', $wp->public_query_vars);
        return array_intersect_key($queryVars, array_flip($publicQueryVars));
    }

    protected function isHomePath(string $url): bool
    {
        $homeURLParts = $this->splitURL($this->getComparableURL(home_url('/')));
        return $this->normalizePath($this->splitURL($url)['path']) === $this->normalizePath($homeURLParts['path']);
    }

    protected function getComparableURL(string $url): string
    {
        $comparableURL = App::applyFilters(
            HookNames::URL_TO_ENTITY_COMPARABLE_URL,
            $url
        );
        return is_string($comparableURL) ? $comparableURL : $url;
    }

    protected function isInternalURL(string $url): bool
    {
        $urlHost = parse_url($url, PHP_URL_HOST);
        $homeHost = parse_url(home_url('/'), PHP_URL_HOST);
        if (!is_string($urlHost) || !is_string($homeHost)) {
            return false;
        }
        return $this->normalizeHost($urlHost) === $this->normalizeHost($homeHost);
    }

    protected function normalizeHost(string $host): string
    {
        $host = strtolower($host);
        return str_starts_with($host, 'www.') ? substr($host, 4) : $host;
    }

    protected function getFrontPageID(): int
    {
        if (get_option('show_on_front') !== 'page') {
            return 0;
        }
        return (int) get_option('page_on_front');
    }

    /**
     * The URL of a file under the uploads folder: the file of a media item,
     * or a copy that WordPress made of an image, which resolves to the
     * image it was made from. That is a resized copy (`photo-300x200.jpg`),
     * also in another format (`photo-300x200.webp`), and the original of an
     * image that WordPress scaled down (`photo.jpg`, whose media item holds
     * `photo-scaled.jpg`) or that was edited (`photo.jpg`, whose media item
     * holds `photo-e1700000000000.jpg`).
     */
    protected function getMediaIDFromFileURL(string $url): int
    {
        $uploadDir = wp_get_upload_dir();
        $uploadsBaseURL = $uploadDir['baseurl'] ?? '';
        if ($uploadsBaseURL === '') {
            return 0;
        }
        $fileURL = (string) preg_replace('/[?#].*$/', '', $url);
        $uploadsPath = parse_url($uploadsBaseURL, PHP_URL_PATH);
        $filePath = parse_url($fileURL, PHP_URL_PATH);
        if (!is_string($uploadsPath) || !is_string($filePath) || !str_starts_with($filePath, $uploadsPath . '/')) {
            return 0;
        }
        $mediaID = attachment_url_to_postid($fileURL);
        if ($mediaID !== 0) {
            return $mediaID;
        }
        return $this->getMediaIDFromDerivedFilePath(
            rawurldecode(substr($filePath, strlen($uploadsPath . '/')))
        );
    }

    /**
     * The media item that a file derived from its own (a resized, scaled,
     * edited or converted copy) belongs to, looked up among the media items
     * whose file has the same name, and confirmed by the file being one
     * that its metadata lists.
     *
     * @param string $relativeFilePath The path of the file under the uploads folder
     */
    protected function getMediaIDFromDerivedFilePath(string $relativeFilePath): int
    {
        $fileName = basename($relativeFilePath);
        $matches = [];
        if (preg_match('/^(.+?)(?:-\d+x\d+)?\.([a-zA-Z0-9]+)$/', $fileName, $matches) !== 1) {
            return 0;
        }
        $baseName = (string) preg_replace('/-scaled$/', '', $matches[1]);
        $directory = dirname($relativeFilePath);
        $directoryPrefix = $directory === '.' ? '' : $directory . '/';

        $extensions = array_unique([strtolower($matches[2]), ...self::IMAGE_EXTENSIONS]);
        $candidateFilePaths = [];
        foreach ($extensions as $extension) {
            $candidateFilePaths[] = $directoryPrefix . $baseName . '.' . $extension;
            $candidateFilePaths[] = $directoryPrefix . $baseName . '-scaled.' . $extension;
        }

        global $wpdb;
        if (!($wpdb instanceof wpdb)) {
            return 0;
        }
        $placeholders = implode(', ', array_fill(0, count($candidateFilePaths), '%s'));
        /** @var string */
        $sql = $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND (meta_value IN ({$placeholders}) OR meta_value LIKE %s) LIMIT 20",
            [
                ...$candidateFilePaths,
                $wpdb->esc_like($directoryPrefix . $baseName . '-e') . '%',
            ]
        );
        $mediaIDs = array_map('intval', $wpdb->get_col($sql));
        foreach ($mediaIDs as $mediaID) {
            if (in_array($fileName, $this->getMediaFileNames($mediaID), true)) {
                return $mediaID;
            }
        }
        return 0;
    }

    /**
     * The names of the files that a media item holds: its own, its
     * original (before WordPress scaled it down), its resized copies (also
     * in other formats), and, for an edited image, those it had before.
     *
     * @return string[]
     */
    protected function getMediaFileNames(int $mediaID): array
    {
        $fileNames = [];
        $metadata = wp_get_attachment_metadata($mediaID);
        if (is_array($metadata)) {
            if (isset($metadata['file']) && is_string($metadata['file'])) {
                $fileNames[] = basename($metadata['file']);
            }
            if (isset($metadata['original_image']) && is_string($metadata['original_image'])) {
                $fileNames[] = $metadata['original_image'];
            }
            $fileNames = array_merge($fileNames, $this->getSizeFileNames($metadata['sizes'] ?? null));
        }
        $fileNames = array_merge($fileNames, $this->getSizeFileNames(get_post_meta($mediaID, '_wp_attachment_backup_sizes', true)));
        return array_values(array_unique($fileNames));
    }

    /**
     * The names of the files under the sizes of a media item's metadata,
     * plus those of each size in other formats (under `sources`).
     *
     * @return string[]
     */
    protected function getSizeFileNames(mixed $sizes): array
    {
        if (!is_array($sizes)) {
            return [];
        }
        $fileNames = [];
        foreach ($sizes as $size) {
            if (!is_array($size)) {
                continue;
            }
            if (isset($size['file']) && is_string($size['file'])) {
                $fileNames[] = $size['file'];
            }
            if (!isset($size['sources']) || !is_array($size['sources'])) {
                continue;
            }
            foreach ($size['sources'] as $source) {
                if (is_array($source) && isset($source['file']) && is_string($source['file'])) {
                    $fileNames[] = $source['file'];
                }
            }
        }
        return $fileNames;
    }

    /**
     * @return array<string,string|int>|null
     */
    protected function getCustomPostEntity(int $customPostID): ?array
    {
        $customPostType = get_post_type($customPostID);
        if ($customPostType === false || !$this->isCustomPostVisible($customPostID)) {
            return null;
        }
        if ($customPostType === 'attachment') {
            return [
                'type' => URLEntityTypes::MEDIA,
                'id' => $customPostID,
            ];
        }
        if (!$this->isQueryableCustomPostType($customPostType)) {
            return null;
        }
        return [
            'type' => URLEntityTypes::CUSTOM_POST,
            'id' => $customPostID,
            'customPostType' => $customPostType,
        ];
    }

    /**
     * `url_to_postid()` returns the ID that a URL such as `/?p=123` names,
     * for a post of any status and type, so the resolver checks that the
     * user may see the post.
     */
    protected function isCustomPostVisible(int $customPostID): bool
    {
        return is_post_publicly_viewable($customPostID)
            || current_user_can('read_post', $customPostID);
    }

    protected function isQueryableCustomPostType(string $customPostType): bool
    {
        $queryableCustomPostTypes = App::applyFilters(
            HookNames::URL_TO_ENTITY_QUERYABLE_CUSTOM_POST_TYPES,
            null
        );
        return !is_array($queryableCustomPostTypes) || in_array($customPostType, $queryableCustomPostTypes, true);
    }

    /**
     * A taxonomy that is publicly queryable (as only those have pages of
     * their own), and that the schema lets be queried.
     */
    protected function isResolvableTaxonomy(string $taxonomy): bool
    {
        if (!is_taxonomy_viewable($taxonomy)) {
            return false;
        }
        $queryableTaxonomies = App::applyFilters(
            HookNames::URL_TO_ENTITY_QUERYABLE_TAXONOMIES,
            null
        );
        return !is_array($queryableTaxonomies) || in_array($taxonomy, $queryableTaxonomies, true);
    }

    /**
     * A user with published posts has a public author page. Any other user
     * resolves only for a user who can list the users.
     */
    protected function isUserVisible(WP_User $user): bool
    {
        if (current_user_can('list_users')) {
            return true;
        }
        /** @var string[] */
        $publicPostTypes = array_values(get_post_types(['public' => true]));
        return (int) count_user_posts($user->ID, $publicPostTypes, true) > 0;
    }

    /**
     * The query vars that the rewrite rules give the URL's path, matched
     * the way `url_to_postid()` does.
     *
     * @return array<string,mixed>
     */
    protected function getRewriteRuleQueryVars(string $url): array
    {
        global $wp_rewrite;
        if (!($wp_rewrite instanceof WP_Rewrite)) {
            return [];
        }
        $rewriteRules = $wp_rewrite->wp_rewrite_rules();
        if (!is_array($rewriteRules) || $rewriteRules === []) {
            return [];
        }

        $request = $this->getRequestPath($url, $wp_rewrite);
        if ($request === null) {
            return [];
        }

        foreach ($rewriteRules as $match => $query) {
            $matches = [];
            if (preg_match('#^' . $match . '#', $request, $matches) !== 1) {
                continue;
            }
            $varMatch = [];
            if (
                $wp_rewrite->use_verbose_page_rules
                && preg_match('/pagename=\$matches\[([0-9]+)\]/', $query, $varMatch) === 1
                && get_page_by_path($matches[(int) $varMatch[1]]) === null
            ) {
                continue;
            }
            $query = (string) preg_replace('!^.+\?!', '', $query);
            $query = WP_MatchesMapRegex::apply($query, $matches);
            $queryVars = [];
            parse_str($query, $queryVars);
            return $queryVars;
        }
        return [];
    }

    /**
     * The path of the URL relative to the site's home, as the rewrite rules
     * expect it, or `null` when it is outside the home.
     */
    protected function getRequestPath(string $url, WP_Rewrite $wpRewrite): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!is_string($path)) {
            $path = '/';
        }
        $homePath = parse_url(home_url('/'), PHP_URL_PATH);
        $homePath = is_string($homePath) ? trim($homePath, '/') : '';
        $path = trim($path, '/');
        if ($homePath !== '') {
            if ($path !== $homePath && !str_starts_with($path . '/', $homePath . '/')) {
                return null;
            }
            $path = trim(substr($path, strlen($homePath)), '/');
        }
        if ($wpRewrite->using_index_permalinks()) {
            $path = (string) preg_replace('#^' . preg_quote($wpRewrite->index, '#') . '/?#', '', $path);
        }
        return $path;
    }

    /**
     * Pagination (`/page/2/`) and feeds (`/feed/`, `/feed/atom/`) are
     * views of the entity, so they point to the entity itself.
     */
    protected function removePaginationAndFeedFromURL(string $url): string
    {
        global $wp_rewrite;
        $paginationBase = 'page';
        $feedBase = 'feed';
        if ($wp_rewrite instanceof WP_Rewrite) {
            $paginationBase = $wp_rewrite->pagination_base;
            $feedBase = $wp_rewrite->feed_base;
        }
        $feeds = '(?:feed|rdf|rss|rss2|atom)';
        $suffixRegex = '#/(?:' . preg_quote($paginationBase, '#') . '/\d+|' . preg_quote($feedBase, '#') . '(?:/' . $feeds . ')?|' . $feeds . ')/?$#';

        $urlParts = $this->splitURL($url);
        $path = $urlParts['path'];
        do {
            $previousPath = $path;
            $path = (string) preg_replace($suffixRegex, '/', $path);
        } while ($path !== $previousPath);

        $queryVars = $urlParts['queryVars'];
        unset($queryVars['paged'], $queryVars['feed']);

        return $this->joinURL($urlParts['origin'], $path, $queryVars);
    }

    /**
     * @param array<string,string> $queryVars
     * @return array<string,string|int>|null
     */
    protected function getTaxonomyTermEntity(array $queryVars, string $url, bool $isPlainPermalink): ?array
    {
        foreach ($this->getTaxonomyTermCandidates($queryVars) as $term) {
            $termURL = get_term_link($term);
            if (!is_string($termURL)) {
                continue;
            }
            if (!$isPlainPermalink && !$this->areSameURL($this->getComparableURL($termURL), $url)) {
                continue;
            }
            return [
                'type' => URLEntityTypes::TAXONOMY_TERM,
                'id' => $term->term_id,
                'taxonomy' => $term->taxonomy,
            ];
        }
        return null;
    }

    /**
     * The terms whose slug (or ID, as `?cat=5` names it) the query vars
     * hold, looked up by that slug or ID only.
     *
     * @param array<string,string> $queryVars
     * @return WP_Term[]
     */
    protected function getTaxonomyTermCandidates(array $queryVars): array
    {
        return array_values(array_filter(
            $this->getAllTaxonomyTermCandidates($queryVars),
            fn (WP_Term $term): bool => $this->isResolvableTaxonomy($term->taxonomy)
        ));
    }

    /**
     * @param array<string,string> $queryVars
     * @return WP_Term[]
     */
    protected function getAllTaxonomyTermCandidates(array $queryVars): array
    {
        if (isset($queryVars['cat']) && is_numeric($queryVars['cat'])) {
            $term = get_term((int) $queryVars['cat'], 'category');
            return $term instanceof WP_Term ? [$term] : [];
        }
        if (isset($queryVars['tag_id']) && is_numeric($queryVars['tag_id'])) {
            $term = get_term((int) $queryVars['tag_id'], 'post_tag');
            return $term instanceof WP_Term ? [$term] : [];
        }

        $slugsByTaxonomy = [];
        if (isset($queryVars['taxonomy'], $queryVars['term'])) {
            $slugsByTaxonomy[$queryVars['taxonomy']] = $queryVars['term'];
        }
        /** @var WP_Taxonomy[] */
        $taxonomies = get_taxonomies(['publicly_queryable' => true], 'objects');
        foreach ($taxonomies as $taxonomy) {
            if (!is_string($taxonomy->query_var) || $taxonomy->query_var === '' || !isset($queryVars[$taxonomy->query_var])) {
                continue;
            }
            $slugsByTaxonomy[$taxonomy->name] = $queryVars[$taxonomy->query_var];
        }

        $candidates = [];
        foreach ($slugsByTaxonomy as $taxonomyName => $slugPath) {
            $slugs = explode('/', trim(rawurldecode($slugPath), '/'));
            $slug = array_pop($slugs);
            if ($slug === null || $slug === '') {
                continue;
            }
            /** @var array<string,mixed> */
            $termsQueryArgs = App::applyFilters(
                HookNames::URL_TO_ENTITY_TERMS_QUERY_ARGS,
                [
                    'taxonomy' => $taxonomyName,
                    'slug' => $slug,
                    'hide_empty' => false,
                ]
            );
            $terms = get_terms($termsQueryArgs);
            if (!is_array($terms)) {
                continue;
            }
            foreach ($terms as $term) {
                if ($term instanceof WP_Term) {
                    $candidates[] = $term;
                }
            }
        }
        return $candidates;
    }

    /**
     * @param array<string,string> $queryVars
     * @return array<string,string|int>|null
     */
    protected function getUserEntity(array $queryVars, string $url, bool $isPlainPermalink): ?array
    {
        $user = null;
        if (isset($queryVars['author_name'])) {
            $user = get_user_by('slug', rawurldecode($queryVars['author_name']));
        } elseif (isset($queryVars['author']) && is_numeric($queryVars['author'])) {
            $user = get_user_by('id', (int) $queryVars['author']);
        }
        if (!($user instanceof WP_User) || !$this->isUserVisible($user)) {
            return null;
        }
        if (!$isPlainPermalink && !$this->areSameURL($this->getComparableURL(get_author_posts_url($user->ID)), $url)) {
            return null;
        }
        return [
            'type' => URLEntityTypes::USER,
            'id' => $user->ID,
        ];
    }

    /**
     * @param array<string,string> $queryVars
     * @return array<string,string|int>|null
     */
    protected function getCustomPostTypeArchiveEntity(array $queryVars, string $url, bool $isPlainPermalink): ?array
    {
        if (!isset($queryVars['post_type'])) {
            return null;
        }
        $customPostType = $queryVars['post_type'];
        $customPostTypeObject = get_post_type_object($customPostType);
        if ($customPostTypeObject === null || !$customPostTypeObject->has_archive) {
            return null;
        }
        foreach (self::CUSTOM_POST_QUERY_VARS as $customPostQueryVar) {
            if (isset($queryVars[$customPostQueryVar])) {
                return null;
            }
        }
        $archiveURL = get_post_type_archive_link($customPostType);
        if (!is_string($archiveURL)) {
            return null;
        }
        if (!$isPlainPermalink && !$this->areSameURL($this->getComparableURL($archiveURL), $url)) {
            return null;
        }
        return [
            'type' => URLEntityTypes::CUSTOM_POST_TYPE_ARCHIVE,
            'customPostType' => $customPostType,
        ];
    }

    /**
     * Two URLs point to the same place when they have the same host
     * (with or without `www.`, over any scheme) and the same path (with or
     * without the trailing slash, and with or without percent-encoding).
     * The query string counts only when the entity's own URL has one (as
     * plain permalinks do, such as `?cat=5`): then the other URL must carry
     * the same params, plus any others.
     */
    protected function areSameURL(string $entityURL, string $url): bool
    {
        $entityURLParts = $this->splitURL($entityURL);
        $urlParts = $this->splitURL($url);
        if ($this->normalizeHost($entityURLParts['host']) !== $this->normalizeHost($urlParts['host'])) {
            return false;
        }
        if ($this->normalizePath($entityURLParts['path']) !== $this->normalizePath($urlParts['path'])) {
            return false;
        }
        foreach ($entityURLParts['queryVars'] as $name => $value) {
            if (($urlParts['queryVars'][$name] ?? null) !== $value) {
                return false;
            }
        }
        return true;
    }

    protected function normalizePath(string $path): string
    {
        return strtolower(trim(rawurldecode($path), '/'));
    }

    /**
     * @return array{origin:string,host:string,path:string,queryVars:array<string,string>}
     */
    protected function splitURL(string $url): array
    {
        $urlParts = parse_url($url);
        if (!is_array($urlParts)) {
            $urlParts = [];
        }
        $host = $urlParts['host'] ?? '';
        $origin = '';
        if ($host !== '') {
            $origin = ($urlParts['scheme'] ?? 'https') . '://' . $host . (isset($urlParts['port']) ? ':' . $urlParts['port'] : '');
        }
        $queryVars = [];
        if (isset($urlParts['query']) && $urlParts['query'] !== '') {
            parse_str($urlParts['query'], $queryVars);
        }
        /** @var array<string,string> */
        $queryVars = array_filter(
            $queryVars,
            fn (mixed $value): bool => is_string($value)
        );
        ksort($queryVars);
        return [
            'origin' => $origin,
            'host' => $host,
            'path' => $urlParts['path'] ?? '/',
            'queryVars' => $queryVars,
        ];
    }

    /**
     * @param array<string,string> $queryVars
     */
    protected function joinURL(string $origin, string $path, array $queryVars): string
    {
        $url = $origin . $path;
        if ($queryVars === []) {
            return $url;
        }
        $queryParts = [];
        foreach ($queryVars as $name => $value) {
            $queryParts[] = rawurlencode($name) . '=' . rawurlencode($value);
        }
        return $url . '?' . implode('&', $queryParts);
    }
}
