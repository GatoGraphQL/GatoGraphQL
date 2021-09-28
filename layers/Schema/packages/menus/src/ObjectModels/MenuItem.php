<?php

declare(strict_types=1);

namespace PoPSchema\Menus\ObjectModels;

/**
 * Make properties public so they can be accessed directly
 */
class MenuItem
{
    public string | int $id;
    public string | int $objectID;
    public string | int | null $parentID;
    public string $label;
    public string $title;
    public string $url;
    public string $description;
    public array $classes;
    public string $target;
    public string $linkRelationship;
    public function __construct(string | int $id, string | int $objectID, string | int | null $parentID, string $label, string $title, string $url, string $description, array $classes, string $target, string $linkRelationship)
    {
        $this->id = $id;
        $this->objectID = $objectID;
        $this->parentID = $parentID;
        $this->label = $label;
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
        /** @var string[] */
        $this->classes = $classes;
        $this->target = $target;
        $this->linkRelationship = $linkRelationship;
    }
}
