<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Types;

interface CustomPostTypeInterface
{
    /**
     * Return the object's ID
     *
     * @param [type] $object
     * @return void
     */
    public function getID($object);
    public function getContent($id): ?string;
    public function getPlainTextContent($objectOrID): string;
    public function getPermalink($objectOrID): ?string;
    public function getSlug($postObjectOrID): ?string;
    public function getStatus($objectOrID): ?string;
    public function getPublishedDate($objectOrID): ?string;
    public function getModifiedDate($objectOrID): ?string;
    public function getTitle($id): ?string;
    public function getExcerpt($objectOrID): ?string;
}
