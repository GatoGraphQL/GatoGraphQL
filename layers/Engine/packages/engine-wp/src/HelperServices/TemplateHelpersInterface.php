<?php

declare(strict_types=1);

namespace PoP\EngineWP\HelperServices;

interface TemplateHelpersInterface
{
    public function getGenerateDataAndPrepareAndSendResponseTemplateFile(): string;

    public function getGenerateDataAndPrepareResponseTemplateFile(): string;

    public function getSendResponseTemplateFile(): string;

    /**
     * Add a hook to send the Response to the client
     */
    public function sendResponseToClient(): void;
}
