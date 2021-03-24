<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Types;

interface CustomPostTypeInterface
{
    /**
     * Return the object's ID
     */
    public function getID(object $object): string | int;
    public function getContent(string | int | object $objectOrID): ?string;
    public function getPlainTextContent(string | int | object $objectOrID): string;
    public function getPermalink(string | int | object $objectOrID): ?string;
    public function getSlug($postObjectOrID): ?string;
    public function getStatus(string | int | object $objectOrID): ?string;
    public function getPublishedDate(string | int | object $objectOrID): ?string;
    public function getModifiedDate(string | int | object $objectOrID): ?string;
    public function getTitle(string | int | object $objectOrID): ?string;
    public function getExcerpt(string | int | object $objectOrID): ?string;
}
