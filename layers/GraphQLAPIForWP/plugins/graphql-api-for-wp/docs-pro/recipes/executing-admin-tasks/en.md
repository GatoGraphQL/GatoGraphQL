# Executing admin tasks

Using cron
127.0.0.1 works:
    curl --insecure https://127.0.0.1/graphql-query/accessing-field-with-visitor-ip-acl-rule/
    {"errors":[{"message":"The client IP address must satisfy constraint '#^172\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$#' to access field 'authorEmail' for type 'Comment' (your IP address is '127.0.0.1')","locations":[{"line":9,"column":5}],"extensions":{"path":["authorEmail","comment(by: {id: 2}) { ... }","query { ... }"],"type":"Comment","field":"authorEmail","id":2,"code":"PoPSchema\/VisitorIPAccessControl@e6"}},{"message":"The client IP address must satisfy constraint '#^255\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$#' to access field 'karma' for type 'Comment' (your IP address is '127.0.0.1')","locations":[{"line":15,"column":5}],"extensions":{"path":["karma","comment(by: {id: 2}) { ... }","query { ... }"],"type":"Comment","field":"karma","id":2,"code":"PoPSchema\/VisitorIPAccessControl@e6"}}],"data":{"comment":{"id":2,"approved":true,"authorEmail":null,"authorURL":"http:\/\/graphql-api.lndo.site","karma":null}}}
Execute internal jobs
    Talk about wp-cron, persisted queries, and visitor IP