<?php
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;

class PoPSystem_Dataload_CheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_SYSTEMACCESSKEYVALID = 'system-checkpoint-systemaccesskeyvalid';
    public const CHECKPOINT_SYSTEMACCESSIPVALID = 'system-checkpoint-systemaccessipvalid';

    public function getCheckpointsToProcess()
    {
        return array(
            [self::class, self::CHECKPOINT_SYSTEMACCESSKEYVALID],
            [self::class, self::CHECKPOINT_SYSTEMACCESSIPVALID],
        );
    }

    public function process(array $checkpoint)
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_SYSTEMACCESSKEYVALID:
                // Validate the System Access Key has been defined
                if (!POP_SYSTEM_APIKEYS_SYSTEMACCESS) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('systemaccesskeynotdefined');
                }

                // Validate the user has provided the System Access Key as a param in the URL
                $key = $_REQUEST['systemaccesskey'] ?? null;
                if (!$key) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('systemaccesskeyempty');
                }

                // Validate the keys match
                if ($key != POP_SYSTEM_APIKEYS_SYSTEMACCESS) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('systemaccesskeyincorrect');
                }
                break;

            case self::CHECKPOINT_SYSTEMACCESSIPVALID:
                // Validate the System Access IPs has been defined
                if (!POP_SYSTEM_IPS_SYSTEMACCESS) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('systemaccessipsnotdefined');
                }

                // Validate the user's IP
                $ip = getClientIp();
                if (!$ip) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('systemaccessipempty');
                }

                // Validate the keys match
                if (!in_array($ip, POP_SYSTEM_IPS_SYSTEMACCESS)) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('systemaccessipincorrect');
                }
                break;
        }

        return parent::process($checkpoint);
    }
}

