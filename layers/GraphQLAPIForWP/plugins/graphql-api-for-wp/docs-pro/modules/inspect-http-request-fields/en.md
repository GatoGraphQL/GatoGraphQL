# Inspect HTTP Request Fields

Addition of field `_httpRequest` to retrieve the current HTTP request data.

<!-- // @todo Complete! -->

## HTTP Request Fields

Type `HTTPRequest` provides the following fields to retrieve the current HTTP request data.

### `clientIP`

Retrieves the client IP address. If the server is not properly configured (see below), the response is `null`.

#### Configuration

The client's IP address is retrieved from under the `$_SERVER` global variable, normally from under property `'REMOTE_ADDR'`. However, different platforms may require to use a different property name to retrieve this information.

For instance:

- Cloudflare might use `'HTTP_CF_CONNECTING_IP'`
- AWS might use `'HTTP_X_FORWARDED_FOR'`

The property name to use can be configured in the "Plugin Settings > Server IP Configuration" tab on the Settings page:

![Configuring the $_SERVER property name to retrieve the client IP](../../images/settings-general-client-ip-address-server-property-name.png "Configuring the $_SERVER property name to retrieve the client IP")
