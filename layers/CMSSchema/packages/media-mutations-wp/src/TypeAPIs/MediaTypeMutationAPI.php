<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutationsWP\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\MediaMutations\Exception\MediaItemCRUDMutationException;
use PoPCMSSchema\MediaMutations\TypeAPIs\MediaTypeMutationAPIInterface;
use WP_Error;

class MediaTypeMutationAPI implements MediaTypeMutationAPIInterface
{
    use BasicServiceTrait;

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromURL(string $url, array $mediaItemData): string|int
    {
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
        $mimeType = $mediaItemData['mimeType'] ?? $this->getFileMimeTypeOrThrowError($url);
        
        $filename = basename($url);        
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
        string $filename,
        string $body,
        array $mediaItemData,
    ): string|int {
		$uploadedFileOrError = \wp_upload_bits($filename, null, $body);
        if ($uploadedFileOrError['error']) {
            /** @var string */
            $errorMessage = $uploadedFileOrError['error'];
            throw new MediaItemCRUDMutationException(
                $errorMessage
            );
        }

        $uploadedFile = $uploadedFileOrError;
        $mimeType = $mediaItemData['mimeType'] ?? $this->getFileMimeTypeOrThrowError($filename);
        
        if (empty($mediaItemData['title'])) {
            $mediaItemData['title'] = $filename;
        }
        
        /** @var string */
        $file = $uploadedFile['file'];
        return $this->createMediaItemFromLocalFile(
            $file,
            basename($file),
            $mimeType,
            $mediaItemData,
        );
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
