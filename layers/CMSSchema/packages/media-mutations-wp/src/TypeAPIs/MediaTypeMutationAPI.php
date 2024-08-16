<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutationsWP\TypeAPIs;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\MediaMutations\Exception\MediaItemCRUDMutationException;
use PoPCMSSchema\MediaMutations\TypeAPIs\MediaTypeMutationAPIInterface;
use WP_Error;

use function add_post_meta;
use function get_attached_file;
use function get_post_meta;
use function get_post;
use function update_attached_file;
use function wp_get_attachment_metadata;
use function wp_insert_attachment;
use function wp_slash;
use function wp_update_attachment_metadata;

class MediaTypeMutationAPI implements MediaTypeMutationAPIInterface
{
    use BasicServiceTrait;

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromExistingMediaItem(
        string|int $existingMediaItemID,
        array $mediaItemData,
    ): string|int|null {
        $existingMediaItem = get_post($existingMediaItemID, ARRAY_A);

		if ($existingMediaItem === null || $existingMediaItem === []) {
			return null;
		}

		unset($existingMediaItem['ID']);
		$mediaItemID = wp_insert_attachment(wp_slash($existingMediaItem));

		/**
         * Copy over:
         *
         * - Metadata
         * - Attached file
         * - Alternative text
         */
		$data = wp_get_attachment_metadata($existingMediaItemID, true);
		if ($data !== false) {
			wp_update_attachment_metadata($mediaItemID, wp_slash($data));
		}
        $file = get_attached_file($existingMediaItemID, true);
		if ($file !== false) {
			update_attached_file($mediaItemID, wp_slash($file));
		}
		$alternativeText = get_post_meta($existingMediaItemID, '_wp_attachment_image_alt', true);
		if ($alternativeText) {
			add_post_meta($mediaItemID, '_wp_attachment_image_alt', wp_slash($alternativeText));
		}

        return $mediaItemID;
    }

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param string|null $filename Override the filename from the URL, or pass `null` to use filename from URL
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromURL(
        string $url,
        ?string $filename,
        array $mediaItemData,
    ): string|int {
        require_once ABSPATH . 'wp-admin/includes/file.php';

        $downloadedFileOrError = \download_url($url);

        if (\is_wp_error($downloadedFileOrError)) {
            /** @var WP_Error */
            $wpError = $downloadedFileOrError;
            throw new MediaItemCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        $downloadedFile = $downloadedFileOrError;

        /**
         * Either use the provided filename or, if `null`,
         * use the same one as from the URL.
         *
         * The URL might contain params (eg: with the dynamically
         * generated image by DALL-E).
         *
         * Remove the URL params to expose the extension, or
         * `wp_check_filetype` won't figure out the mime type
         */
        $filename ??= basename(GeneralUtils::getURLWithoutQueryParams($url));

        /**
         * The mime type is retrieved from the filename extension
         * always, because that's what `wp_handle_sideload` does.
         *
         * Then, we need to always add the corresponding extension
         * to the file name.
         *
         * If the filename has no extension, get it from the "mimeType"
         * input param.
         */
        $filename = $this->maybeAddExtensionToFilename(
            $filename,
            $mediaItemData['mimeType'] ?? null,
        );
        $mimeType = $this->getFileMimeTypeOrThrowError($filename);

        if (empty($mediaItemData['title'])) {
            $mediaItemData['title'] = $filename;
        }

        $mediaItemID = $this->createMediaItemFromLocalFile(
            $downloadedFile,
            $filename,
            $mimeType,
            $mediaItemData,
        );

        \unlink($downloadedFile);

        return $mediaItemID;
    }

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromContents(
        string $body,
        string $filename,
        array $mediaItemData,
    ): string|int {
        $filename = $this->maybeAddExtensionToFilename(
            $filename,
            $mediaItemData['mimeType'] ?? null,
        );
        $mimeType = $this->getFileMimeTypeOrThrowError($filename);

        $uploadedFileOrError = \wp_upload_bits($filename, null, $body);
        if ($uploadedFileOrError['error']) {
            /** @var string */
            $errorMessage = $uploadedFileOrError['error'];
            throw new MediaItemCRUDMutationException(
                $errorMessage
            );
        }
        $uploadedFile = $uploadedFileOrError;

        if (empty($mediaItemData['title'])) {
            $mediaItemData['title'] = $filename;
        }

        /** @var string */
        $file = $uploadedFile['file'];
        return $this->createMediaItemFromLocalFile(
            $file,
            $filename,
            $mimeType,
            $mediaItemData,
        );
    }

    /**
     * If the file doesn't contain an extension (even when we are
     * providing the mime type), we will get an error:
     *
     *   > Sorry, you are not allowed to upload this file type.
     *
     * Then, append the extension if missing.
     */
    protected function maybeAddExtensionToFilename(
        string $filename,
        ?string $explicitMimeType,
    ): string {
        if ($explicitMimeType === null || $explicitMimeType === '') {
            return $filename;
        }

        if (strrpos($filename, '.') !== false) {
            return $filename;
        }

        $extension = \wp_get_default_extension_for_mime_type($explicitMimeType);
        if ($extension === false) {
            return $filename;
        }

        return $filename . '.' . $extension;
    }

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    protected function createMediaItemFromLocalFile(
        string $file,
        string $filename,
        string $mimeType,
        array $mediaItemData
    ): string|int {
        require_once ABSPATH . 'wp-admin/includes/file.php';

        $fileData = [
            'name' => \sanitize_file_name($filename),
            'type' => $mimeType,
            'tmp_name' => $file,
            'error' => 0,
            'size' => filesize($file),
        ];

        $uploadedFile = \wp_handle_sideload(
            $fileData,
            [
                'test_form' => false,
            ]
        );

        if (isset($uploadedFile['error'])) {
            /** @var string */
            $errorMessage = $uploadedFile['error'];
            throw new MediaItemCRUDMutationException(
                $errorMessage
            );
        }

        /** @var string */
        $uploadedFilename = $uploadedFile['file'];

        $customPostID = 0;
        if (isset($mediaItemData['customPostID'])) {
            $customPostID = $mediaItemData['customPostID'];
            unset($mediaItemData['customPostID']);
        }

        if (empty($mediaItemData['title'])) {
            $mediaItemData['title'] = sanitize_file_name(basename($uploadedFilename));
        }

        $mediaItemData['mimeType'] = $mimeType;

        $mediaItemData = $this->convertMediaItemCreationArgs($mediaItemData);

        $mediaItemIDOrError = \wp_insert_attachment(
            $mediaItemData,
            $uploadedFilename,
            $customPostID,
            true
        );

        if (\is_wp_error($mediaItemIDOrError)) {
            /** @var WP_Error */
            $wpError = $mediaItemIDOrError;
            throw new MediaItemCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        $mediaItemID = $mediaItemIDOrError;

        $this->addImageMetaData(
            $mediaItemID,
            $uploadedFilename,
            $mediaItemData,
        );

        return $mediaItemID;
    }

    /**
     * @throws MediaItemCRUDMutationException If the mime type is not allowed
     */
    protected function getFileMimeTypeOrThrowError(string $filename): string
    {
        // Get the mime type from the file, and check it's allowed
        $mimeTypeCheck = \wp_check_filetype(sanitize_file_name(basename($filename)));
        if (!$mimeTypeCheck['type']) {
            throw new MediaItemCRUDMutationException(
                $this->__('The file\'s mime type is not allowed', 'media-mutations')
            );
        }
        /** @var string */
        $mimeType = $mimeTypeCheck['type'];
        return $mimeType;
    }

    /**
     * @param array<string,mixed> $mediaItemData
     * @return array<string,mixed>
     */
    protected function convertMediaItemCreationArgs(array $mediaItemData): array
    {
        if (isset($mediaItemData['authorID'])) {
            $mediaItemData['post_author'] = $mediaItemData['authorID'];
            unset($mediaItemData['authorID']);
        }
        if (isset($mediaItemData['title'])) {
            $mediaItemData['post_title'] = $mediaItemData['title'];
            unset($mediaItemData['title']);
        }
        if (isset($mediaItemData['slug'])) {
            $mediaItemData['post_name'] = $mediaItemData['slug'];
            unset($mediaItemData['slug']);
        }
        if (isset($mediaItemData['caption'])) {
            $mediaItemData['post_excerpt'] = $mediaItemData['caption'];
            unset($mediaItemData['caption']);
        }
        if (isset($mediaItemData['description'])) {
            $mediaItemData['post_content'] = $mediaItemData['description'];
            unset($mediaItemData['description']);
        }
        if (isset($mediaItemData['mimeType'])) {
            $mediaItemData['post_mime_type'] = $mediaItemData['mimeType'];
            unset($mediaItemData['mimeType']);
        }
        return $mediaItemData;
    }

    /**
     * Update the image metadata, including the dimensions
     * to generate the thumbnails
     *
     * @param array<string,mixed> $mediaItemData
     */
    protected function addImageMetaData(
        string|int $mediaItemID,
        string $filename,
        array $mediaItemData
    ): void {
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $mediaItemMetaData = \wp_generate_attachment_metadata((int) $mediaItemID, $filename);
        \wp_update_attachment_metadata((int) $mediaItemID, $mediaItemMetaData);

        $altText = $mediaItemData['altText'] ?? null;
        if (!empty($altText)) {
            \update_post_meta((int) $mediaItemID, '_wp_attachment_image_alt', $altText);
        }
    }
}
