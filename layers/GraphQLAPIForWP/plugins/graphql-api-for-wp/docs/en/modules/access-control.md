# Access Control

Manage who can access every field and directive in the schema through Access Control Lists.

GraphQL API ships with the following access control rules:

- Disable access
- Grant access if the user is logged-in or out
- Grant access if the user has some role
- Grant access if the user has some capability

Custom access control rules can be added.

## How to use

Every Access Control List contains one or many entries, each of them with the following elements:

- The fields to grant or deny access to
- The directives to grant or deny access to
- The list of rules to validate

<a href="../../images/access-control-list.png" target="_blank">![Creating an Access Control List](../../images/access-control-list.png "Creating an Access Control List")</a>

If module `Public/Private Schema` is enabled, and option `Enable granular control?` in the settings is `on`, there is an additional element:

- Public/Private Schema: behavior when access is denied

<a href="../../images/settings-enable-granular-control.png" target="_blank">![Enable granular control?](../../images/settings-enable-granular-control.png "Enable granular control?")</a>

<a href="../../images/public-private-individual-control.png" target="_blank">![Individual Public/Private schema mode](../../images/public-private-individual-control.png "Individual Public/Private schema mode")</a>

Every entry is created by selecting the fields and directives, and configuring the rules:

<a href="../../images/access-control.gif" target="_blank">![Creating an Access Control List](../../images/access-control.gif "Creating an Access Control List")</a>

Validation for fields from an interface is carried on all types implementing the interface.

<a href="../../images/selecting-field-from-interface.png" target="_blank">![Creating an Access Control List](../../images/selecting-field-from-interface.png "Selecting a field from an interface")</a>

## How it works

Whenever the requested query, either executed through a custom endpoint or as a persisted query, contains one of the selected fields or directives, the corresponding list of rules is evaluated. If any rule is not satisfied, access to that field or directive is denied.

If module `Public/Private Schema` is enabled, when access to some field or directive is denied, there are 2 ways for the API to behave:

- **Public mode**: Provide an error message to the user, indicating why access is denied
- **Private mode**: The error message indicates that the field or directive does not exist

If this module is not enabled, the default behavior ir `Public`.

<a href="../../images/public-private-schema.gif" target="_blank">![Public/Private schema](../../images/public-private-schema.gif "Public/Private schema")</a>

## Resources

Video showing how access to different fields is granted or not, according to the configuration and the user executing the query: <https://vimeo.com/413503383>.
