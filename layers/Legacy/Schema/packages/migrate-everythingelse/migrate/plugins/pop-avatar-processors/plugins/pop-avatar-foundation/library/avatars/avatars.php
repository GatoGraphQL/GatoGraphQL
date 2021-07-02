<?php

const GD_AVATAR_SIZE_26 = 26; // 26: Wordpress Top Bar Login Menu
const GD_AVATAR_SIZE_40 = 40;
const GD_AVATAR_SIZE_60 = 60;
const GD_AVATAR_SIZE_82 = 82;
const GD_AVATAR_SIZE_120 = 120;
PoP_AvatarFoundationManagerFactory::getInstance()->addSizes(
    array(
        GD_AVATAR_SIZE_26,
        GD_AVATAR_SIZE_40,
        GD_AVATAR_SIZE_60,
        GD_AVATAR_SIZE_82,
        GD_AVATAR_SIZE_120,
    )
);
