<?php
/**
 * Copyright 2010-2013 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace Aws\Sns\MessageValidator;

use Aws\Common\Exception\RequiredExtensionNotLoadedException;
use Aws\Sns\MessageValidator\Exception\CannotGetPublicKeyFromCertificateException;
use Aws\Sns\MessageValidator\Exception\CertificateFromUnrecognizedSourceException;
use Aws\Sns\MessageValidator\Exception\InvalidMessageSignatureException;
use Aws\Sns\MessageValidator\Exception\SnsMessageValidatorException;
use Guzzle\Http\Url;
use Guzzle\Http\Client;

/**
 * This class uses openssl to verify SNS messages to ensure that they were sent by AWS.
 */
class MessageValidator
{
    /**
     * @var Client The HTTP client used to fetch the certificate
     */
    protected $client;

    /** @var string */
    private $hostPattern;

    /**
     * @var string  A pattern that will match all regional SNS endpoints, e.g.:
     *                  - sns.<region>.amazonaws.com        (AWS)
     *                  - sns.us-gov-west-1.amazonaws.com   (AWS GovCloud)
     *                  - sns.cn-north-1.amazonaws.com.cn   (AWS China)
     */
    private static $defaultHostPattern
        = '/^sns\.[a-zA-Z0-9\-]{3,}\.amazonaws\.com(\.cn)?$/';

    /**
     * Constructs the Message Validator object and ensures that openssl is installed
     *
     * @param Client|null $client
     * @param string|null $hostPattern The host must match this regex pattern
     *
     * @throws RequiredExtensionNotLoadedException If openssl is not installed
     */
    public function __construct(Client $client = null, $hostPattern = null)
    {
        if (!extension_loaded('openssl')) {
            //@codeCoverageIgnoreStart
            throw new RequiredExtensionNotLoadedException('The openssl extension is required to use the SNS Message '
                . 'Validator. Please install this extension in order to use this feature.');
            //@codeCoverageIgnoreEnd
        }

        $this->client = $client ?: new Client();
        $this->hostPattern = $hostPattern ?: self::$defaultHostPattern;
    }

    /**
     * Validates a message from SNS to ensure that it was delivered by AWS
     *
     * @param Message $message The message to validate
     *
     * @throws CannotGetPublicKeyFromCertificateException If the certificate cannot be retrieved
     * @throws CertificateFromUnrecognizedSourceException If the certificate's source cannot be verified
     * @throws InvalidMessageSignatureException           If the message's signature is invalid
     */
    public function validate(Message $message)
    {
        // Get the cert's URL and ensure it is from AWS
        $certUrl = Url::factory($message->get('SigningCertURL'));
        $this->validateUrl($certUrl);

        // Get the cert itself and extract the public key
        $certificate = $this->client->get((string) $certUrl)->send()->getBody();
        $publicKey = openssl_get_publickey($certificate);
        if (!$publicKey) {
            throw new CannotGetPublicKeyFromCertificateException();
        }

        // Verify the signature of the message
        $stringToSign = $message->getStringToSign();
        $incomingSignature = base64_decode($message->get('Signature'));
        if (!openssl_verify($stringToSign, $incomingSignature, $publicKey, OPENSSL_ALGO_SHA1)) {
            throw new InvalidMessageSignatureException();
        }
    }

    private function validateUrl(Url $url)
    {
        if ($url->getScheme() !== 'https' ||
            substr($url, -4) !== '.pem' ||
            !preg_match($this->hostPattern, $url->getHost())
        ) {
            throw new CertificateFromUnrecognizedSourceException();
        }
    }

    /**
     * Determines if a message is valid and that is was delivered by AWS. This method does not throw exceptions and
     * returns a simple boolean value.
     *
     * @param Message $message The message to validate
     *
     * @return bool
     */
    public function isValid(Message $message)
    {
        try {
            $this->validate($message);
            return true;
        } catch (SnsMessageValidatorException $e) {
            return false;
        }
    }
}
