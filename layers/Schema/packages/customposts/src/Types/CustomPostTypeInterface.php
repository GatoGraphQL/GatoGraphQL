<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Types;

interface CustomPostTypeInterface
{
    /**
     * Return the object's ID
     */
    public function getID(object $object): mixed;
    public function getContent(mixed $objectOrID): ?string;
    public function getPlainTextContent(mixed $objectOrID): string;
    public function getPermalink(mixed $objectOrID): ?string;
    public function getSlug($postObjectOrID): ?string;
    public function getStatus(mixed $objectOrID): ?string;
    public function getPublishedDate(mixed $objectOrID): ?string;
    public function getModifiedDate(mixed $objectOrID): ?string;
    public function getTitle(mixed $objectOrID): ?string;
    public function getExcerpt(mixed $objectOrID): ?string;
}
