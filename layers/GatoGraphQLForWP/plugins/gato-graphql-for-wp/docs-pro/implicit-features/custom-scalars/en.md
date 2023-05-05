# Custom Scalars Pack

Collection of additional custom scalar types.

## Custom Scalars

The following custom scalar types are made available in the GraphQL schema:

### `Domain`

Domain scalar, such as `https://mysite.com` or `http://www.mysite.org`

### `IP`

IP scalar, including both IPv4 (such as `192.168.0.1`) and IPv6 (such as `2001:0db8:85a3:08d3:1319:8a2e:0370:7334`

### `IPv4`

IPv4 scalar, such as `192.168.0.1`

### `IPv6`

IPv6 scalar, such as `2001:0db8:85a3:08d3:1319:8a2e:0370:7334`

### `MACAddress`

MAC (media access control) address scalar, such as `00:1A:C2:7B:00:47`

### `PositiveFloat`

A positive float or 0

### `PositiveInt`

A positive integer or 0

### `PhoneNumber`

Phone number scalar, such as `+1-212-555-0149`

### `StrictlyPositiveFloat`

A positive float (> 0)

### `StrictlyPositiveInt`

A positive integer (> 0)

### `StringValueJSONObject`

A JSON Object where values are strings.

### `UUID`

UUID (universally unique identifier) scalar, such as `25770975-0c3d-4ff0-ba27-a0f98fe9b052`

## Introspection

When installing the GraphQL API PRO plugin, these custom scalars will always be available for your use to extend the GraphQL schema.

However, please notice that only when a custom scalar type is referenced will it appear on the GraphQL schema, as [defined by the spec for built-in scalars](https://spec.graphql.org/October2021/#sec-Scalars.Built-in-Scalars):

> When returning the set of types from the `__Schema` introspection type, all referenced built-in scalars must be included. If a built-in scalar type is not referenced anywhere in a schema (there is no field, argument, or input field of that type) then it must not be included.
