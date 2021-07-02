<?php

if (defined('POP_CLUSTER_INITIALIZED')) {
    include_once 'pop-cluster/load.php';
}

if (defined('POP_SYSTEM_INITIALIZED')) {
    include_once 'pop-system/load.php';
}

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}
