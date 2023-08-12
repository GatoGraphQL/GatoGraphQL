<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\Constants;

class LicenseProperties
{
    /** The license key used to activate the extension */
    public final const LICENSE_KEY = 'licenseKey';

    /** After executing an operation against the Marketplace Provider's API, store the response */
    public final const API_RESPONSE_PAYLOAD = 'apiResponsePayload';

    /** The status of the license */
    public final const STATUS = 'status';

    /** After activating a license, what's its ID in the Marketplace Provider system */
    public final const INSTANCE_ID = 'instanceID';

    /** The instance where the license is activated */
    public final const INSTANCE_NAME = 'instanceName';

    /** The number of instances that have been activated with the license key */
    public final const ACTIVATION_USAGE = 'activationUsage';

    /** The number of instances that can be activated with the license key */
    public final const ACTIVATION_LIMIT = 'activationLimit';

    /** The name of the product for the license */
    public final const PRODUCT_NAME = 'productName';

    /** The name of the customer who purchased for the license */
    public final const CUSTOMER_NAME = 'customerName';

    /** The email of the customer who purchased for the license */
    public final const CUSTOMER_EMAIL = 'customerEmail';
}
