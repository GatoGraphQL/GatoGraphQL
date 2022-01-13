<?php
use Aws\Common\Aws;

use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Mailer_AWS_Engine
{
    protected $s3;
    protected $bucket;
    protected $path;

    public function __construct()
    {

        // Send all emails at the end of the PoP execution
        // Send the queue at the end
        HooksAPIFacade::getInstance()->addAction(
            'popcms:shutdown',
            array($this, 'sendQueue'),
            10000
        );
    }

    protected function init(): void
    {
        if (!$this->s3) {
            $this->s3 = Aws::factory(POP_MAILER_AWS_DIR.'/config/aws-config.php')->get('s3')->registerStreamWrapper();

            $this->bucket = POP_MAILER_AWS_BUCKET;
            $this->path = POP_MAILER_AWS_PATH;
        }
    }

    public function sendQueue()
    {

        // Make sure email sending is enabled
        if (PoP_Mailer_AWS_ServerUtils::sendEmailsDisabled()) {
            return;
        }

        // Instead of sending 1 by 1, upload all emails together into a single file in S3
        if ($queue = PoP_Mailer_EmailQueueFactory::getInstance()->getQueue()) {
            // Lazy init
            $this->init();

            $this->uploadToS3($queue);
        }
    }

    protected function uploadToS3($queue)
    {
        $cmsService = CMSServiceFacade::getInstance();
        // Upload to S3, where a Lambda function will execute to send the emails through SES
        try {
            $url = $cmsService->getHomeURL();
            $configuration_default = HooksAPIFacade::getInstance()->applyFilters(
                'PoP_Mailer_AWS_Engine:uploadToS3:configuration',
                array(
                    'url' => $url,
                    'from' => sprintf(
                        '%s <%s>',
                        PoP_EmailSender_Utils::getFromName(),
                        PoP_EmailSender_Utils::getFromEmail()
                    ),
                    'contentType' => PoP_EmailSender_Utils::getContenttype(),
                    'charset' => PoP_EmailSender_Utils::getCharset(),
                )
            );
            $header_count = 0;
            foreach ($queue as $headers => $emails) {
                      // Allow the PoPTheme Wassup to add the website logo, and the cluster to add the website
                $configuration = $configuration_default;

                // Retrieve the headers once again, which can be a string or an array
                // Taken from https://stackoverflow.com/questions/1828397/how-to-extract-emails-from-full-headers
                $regexp = '/From:\s*(([^\<]*?) <)?<?(.+?)>?\s*\n/i';
                if (preg_match($regexp, $headers, $matches)) {
                    $configuration['from'] = sprintf(
                        '%s <%s>',
                        $matches[2],
                        $matches[3]
                    );
                }

                // Split the emails into chunks of no more than 20. This is so that:
                // 1. if any sending fails, not the whole bunch fails
                // 2. emails are sent using AWS Lambda, which have a max execution time, we don't want to go over it
                $size = 20;
                if (count($emails) > $size) {
                    $chunks = array_chunk($emails, $size);
                } else {
                    $chunks = array($emails);
                }

                for ($chunk_count = 0; $chunk_count < count($chunks); $chunk_count++) {
                    $body = array(
                        'configuration' => $configuration,
                        'emails' => $chunks[$chunk_count],
                    );

                    $filename = ComponentModelComponentInfo::get('time') . '_' . ComponentModelComponentInfo::get('rand') . ($header_count ? '_h'.$header_count : '').($chunk_count ? '_c'.$chunk_count : '').'.json';
                    $result = $this->s3->putObject(
                        array(
                            'ACL'        => 'private',
                            'Bucket'     => $this->bucket,
                            'Key'        => $this->path.$filename,
                            'Body' => json_encode($body),
                        )
                    );
                    // echo PHP_EOL.$filename.': '.json_encode($body).PHP_EOL;
                }

                $header_count++;
            }
        } catch (/*S3Exception*/Exception $e) {
        }
    }
}

/**
 * Initialization
 */
new PoP_Mailer_AWS_Engine();
