# Public/Private Schema

Define if the schema metadata is public, and everyone has access to it, or is private, and can be accessed only when the Access Control validations are satisfied.

## How it works

When access to some a field or directive is denied through Access Control, there are 2 ways for the API to behave:

**Public mode**: the fields in the schema are exposed, and when the permission is not satisfied, the user gets an error message with a description of why the permission was rejected. This behavior makes the metadata from the schema always available.

**Private mode**: the schema is customized to every user, containing only the fields available to him or her, and so when attempting to access a forbidden field, the error message says that the field doesn't exist. This behavior exposes the metadata from the schema only to those users who can access it.

<a href="../../images/public-private-schema.gif" target="_blank">![Public/Private schema](../../images/public-private-schema.gif "Public/Private schema")</a>

## How to use

The mode to use can be configured as follows, in order of priority:

✅ (If option `Enable granular control?` in the settings is `on`) Specific mode for a set of fields and directives, defined in the Access Control List

<a href="../../images/settings-enable-granular-control.png" target="_blank">![Enable granular control?](../../images/settings-enable-granular-control.png "Enable granular control?")</a>

<a href="../../images/acl-public-private-schema-mode.png" target="_blank">![Individual Public/Private schema mode](../../images/acl-public-private-schema-mode.png "Individual Public/Private schema mode")</a>

✅ Specific mode for the custom endpoint or persisted query, defined in the schema configuration

<a href="../../images/schema-configuration-public-private-schema-mode.png" target="_blank">![Public/Private schema mode, set in the Schema configuration](../../images/schema-configuration-public-private-schema-mode.png "Public/Private schema mode, set in the Schema configuration")</a>

✅ Default mode, defined in the Settings

If the schema configuration has value `"Default"`, it will use the mode defined in the Settings:

<a href="../../images/default-public-private-schema-mode.png" target="_blank">![Defaul Public/Private schema mode](../../images/default-public-private-schema-mode.png "Defaul Public/Private schema mode")</a>

## Resources

Video demonstrating usage of the public/private schema modes: <https://vimeo.com/413503284>.
