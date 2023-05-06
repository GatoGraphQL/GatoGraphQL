# Access Control Rule: Visitor IP

Grant access to the selected schema elements based on the visitor coming from an allowed IP address

## Configuring the IP addresses

We must define the list of IP addresses that can either access, or are denied access to, the schema elements.

Each entry can either be:

- A regex (regular expression), if it's surrounded by `/` or `#`, or
- The full IP address, otherwise

For instance, any of these entries match IP address `"203.23.88.100"`:

- `203.23.88.100`
- `#^203\.23\.[0-9]{1,3}\.[0-9]{1,3}$#`

And under Behavior, select if to "Allow access" or "Deny access" to the schema for those entries.

![Adding entries in the Visitor IP block](../../images/acl-rule-visitor-ip-block.png "Adding entries in the Visitor IP block")

## Configuring the server

The client's IP address is retrieved from under the `$_SERVER` global variable, normally from under property `'REMOTE_ADDR'`. However, different platforms may require to use a different property name to retrieve this information.

For instance:

- Cloudflare might use `'HTTP_CF_CONNECTING_IP'`
- AWS might use `'HTTP_X_FORWARDED_FOR'`

The property name to use can be configured in the "Plugin Configuration > Server IP Configuration" tab on the Settings page:

![Configuring the $_SERVER property name to retrieve the client IP](../../images/settings-general-client-ip-address-server-property-name.png "Configuring the $_SERVER property name to retrieve the client IP")
