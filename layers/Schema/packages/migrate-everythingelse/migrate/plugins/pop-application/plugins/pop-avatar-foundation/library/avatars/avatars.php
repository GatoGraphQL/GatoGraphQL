<?php

const GD_AVATAR_SIZE_16 = 16;
const GD_AVATAR_SIZE_60 = 60;
PoP_AvatarFoundationManagerFactory::getInstance()->addSizes(
    array(
    	GD_AVATAR_SIZE_16,
        GD_AVATAR_SIZE_60,
    )
);
