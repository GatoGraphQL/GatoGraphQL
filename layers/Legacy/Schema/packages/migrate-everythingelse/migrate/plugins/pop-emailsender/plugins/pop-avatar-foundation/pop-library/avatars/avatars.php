<?php

const GD_AVATAR_SIZE_40 = 40;
const GD_AVATAR_SIZE_60 = 60;
PoP_AvatarFoundationManagerFactory::getInstance()->addSizes(
    array(
        GD_AVATAR_SIZE_40,
        GD_AVATAR_SIZE_60,
    )
);
