<?php
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

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

    public function validateCheckpoint(array $checkpoint): ?FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_SYSTEMACCESSKEYVALID:
                // Validate the System Access Key has been defined
                if (!POP_SYSTEM_APIKEYS_SYSTEMACCESS) {
                    return new FeedbackItemResolution('systemaccesskeynotdefined', 'systemaccesskeynotdefined');
                }

                // Validate the user has provided the System Access Key as a param in the URL
                $key = \PoP\Root\App::query('systemaccesskey');
                if (!$key) {
                    return new FeedbackItemResolution('systemaccesskeyempty', 'systemaccesskeyempty');
                }

                // Validate the keys match
                if ($key != POP_SYSTEM_APIKEYS_SYSTEMACCESS) {
                    return new FeedbackItemResolution('systemaccesskeyincorrect', 'systemaccesskeyincorrect');
                }
                break;

            case self::CHECKPOINT_SYSTEMACCESSIPVALID:
                // Validate the System Access IPs has been defined
                if (!POP_SYSTEM_IPS_SYSTEMACCESS) {
                    return new FeedbackItemResolution('systemaccessipsnotdefined', 'systemaccessipsnotdefined');
                }

                // Validate the user's IP
                $ip = getClientIp();
                if (!$ip) {
                    return new FeedbackItemResolution('systemaccessipempty', 'systemaccessipempty');
                }

                // Validate the keys match
                if (!in_array($ip, POP_SYSTEM_IPS_SYSTEMACCESS)) {
                    return new FeedbackItemResolution('systemaccessipincorrect', 'systemaccessipincorrect');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}

