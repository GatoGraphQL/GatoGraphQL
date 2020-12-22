<?php

const GD_AVATAR_SIZE_40 = 40;
const GD_AVATAR_SIZE_120 = 120;
const GD_AVATAR_SIZE_150 = 150;
PoP_AvatarFoundationManagerFactory::getInstance()->addSizes(
    array(
        GD_AVATAR_SIZE_40,
        GD_AVATAR_SIZE_120,
        GD_AVATAR_SIZE_150,
    )
);
