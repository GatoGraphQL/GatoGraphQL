<?php
use PoP\ComponentModel\Checkpoint\CheckpointError;
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;

class PoPSystem_Dataload_CheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_SYSTEMACCESSKEYVALID = 'system-checkpoint-systemaccesskeyvalid';
    public const CHECKPOINT_SYSTEMACCESSIPVALID = 'system-checkpoint-systemaccessipvalid';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::CHECKPOINT_SYSTEMACCESSKEYVALID],
            [self::class, self::CHECKPOINT_SYSTEMACCESSIPVALID],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?CheckpointError
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_SYSTEMACCESSKEYVALID:
                // Validate the System Access Key has been defined
                if (!POP_SYSTEM_APIKEYS_SYSTEMACCESS) {
                    return new CheckpointError('systemaccesskeynotdefined', 'systemaccesskeynotdefined');
                }

                // Validate the user has provided the System Access Key as a param in the URL
                $key = \PoP\Root\App::query('systemaccesskey');
                if (!$key) {
                    return new CheckpointError('systemaccesskeyempty', 'systemaccesskeyempty');
                }

                // Validate the keys match
                if ($key != POP_SYSTEM_APIKEYS_SYSTEMACCESS) {
                    return new CheckpointError('systemaccesskeyincorrect', 'systemaccesskeyincorrect');
                }
                break;

            case self::CHECKPOINT_SYSTEMACCESSIPVALID:
                // Validate the System Access IPs has been defined
                if (!POP_SYSTEM_IPS_SYSTEMACCESS) {
                    return new CheckpointError('systemaccessipsnotdefined', 'systemaccessipsnotdefined');
                }

                // Validate the user's IP
                $ip = getClientIp();
                if (!$ip) {
                    return new CheckpointError('systemaccessipempty', 'systemaccessipempty');
                }

                // Validate the keys match
                if (!in_array($ip, POP_SYSTEM_IPS_SYSTEMACCESS)) {
                    return new CheckpointError('systemaccessipincorrect', 'systemaccessipincorrect');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}

