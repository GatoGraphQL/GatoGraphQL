<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\Constants;

class MutationInputProperties
{
    public final const NAME = 'name';
    public final const SLUG = 'slug';
    public final const ID = 'id';

    /**
     * Menu items to create/update on the menu.
     *
     * @since x.x.x
     */
    public final const MENU_ITEMS = 'items';

    /**
     * Menu item input properties.
     *
     * @since x.x.x
     */
    public final const ITEM_TYPE = 'itemType';
    public final const OBJECT_TYPE = 'objectType';
    public final const OBJECT_ID = 'objectID';
    public final const LABEL = 'label';
    public final const TITLE_ATTRIBUTE = 'titleAttribute';
    public final const URL = 'url';
    public final const DESCRIPTION = 'description';
    public final const CLASSES = 'classes';
    public final const TARGET = 'target';
    public final const LINK_RELATIONSHIP = 'linkRelationship';
    public final const CHILDREN = 'children';
}
