# Inspect HTTP Request Fields

Addition of fields to retrieve the current HTTP request data.

## Description

The following fields to retrieve the current HTTP request data are added to the GraphQL schema:

### `_httpRequestBody`

Body of the HTTP request.

### `_httpRequestClientHost`

Client host.

### `_httpRequestClientIP`

Retrieves the client IP address. If the server is not properly configured (see below), the response is `null`.

#### Configuration

The client's IP address is retrieved from under the `$_SERVER` global variable, normally from under property `'REMOTE_ADDR'`. However, different platforms may require to use a different property name to retrieve this information.

For instance:

- Cloudflare might use `'HTTP_CF_CONNECTING_IP'`
- AWS might use `'HTTP_X_FORWARDED_FOR'`

The property name to use can be configured in the "Plugin Settings > Server IP Configuration" tab on the Settings page:

![Configuring the $_SERVER property name to retrieve the client IP](../../images/settings-general-client-ip-address-server-property-name.png "Configuring the $_SERVER property name to retrieve the client IP")

### `_httpRequestCookie`

Request cookie value.

### `_httpRequestCookies`

Request cookies.

### `_httpRequestDomain`

Domain of the requested URL.

### `_httpRequestFullURL`

Requested URL (including the query params).

### `_httpRequestHasCookie`

Does the request contain a certain cookie?.

### `_httpRequestHasHeader`

Does the request contain a certain header?.

### `_httpRequestHasParam`

Does the request contain a certain parameter?.

### `_httpRequestHeader`

Request header value.

### `_httpRequestHeaders`

Request headers.

### `_httpRequestHost`

Host of the requested URL.

### `_httpRequestMethod`

Request method.

### `_httpRequestParams`

JSON object with all the params (passed via POST or GET).

Param values can be:

- Strings: `?param=value`
- Arrays: `?someArray[]=1&someArray[]=2`
- Array of arrays: `?someMatrix[0][0]=3&someMatrix[0][1]=4&someMatrix[1][0]=5&someMatrix[1][1]=6`
- Associative arrays (i.e. objects): `?someAssocArray["admins"]=20&someAssocArray["authors"]=30`
- Associative array of arrays: `?someAssocMatrix["admins"][0]=7&someAssocMatrix["admins"][1]=8&someAssocMatrix["authors"][0]=9&someAssocMatrix["authors"][1]=10`
- Other combinations

To get the value of the param, we can use fields `_httpRequestStringParam` and `_httpRequestStringListParam` for the first two cases respectively, but there are no fields for the other cases.

For those, use this field `_httpRequestParams` to obtain the JSON object, and then retrieve the corresponding value from within.

### `_httpRequestProtocol`

Request protocol.

### `_httpRequestQuery`

Query params string.

### `_httpRequestReferer`

Request referer.

### `_httpRequestRequestTime`

Timestamp of the start of the request.

### `_httpRequestScheme`

Scheme of the requested URL.

### `_httpRequestServerIP`

Server IP address.

### `_httpRequestStringListParam`

Value of a param (passed via POST or GET) of type `?param[]=value1&param[]=value2`.

### `_httpRequestStringParam`

Value of a param (passed via POST or GET) of type `?param=value`.

### `_httpRequestURL`

Requested URL (without query params).

### `_httpRequestURLPath`

Asolute path (starting with "/") of the requested URL.

### `_httpRequestUserAgent`

User agent.
