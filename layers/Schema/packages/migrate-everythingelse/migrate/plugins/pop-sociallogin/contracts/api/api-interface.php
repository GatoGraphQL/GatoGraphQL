<?php

interface PoP_SocialLogin_API
{
    public function getProvider($user_id);
    public function getNetworklinks();
}
