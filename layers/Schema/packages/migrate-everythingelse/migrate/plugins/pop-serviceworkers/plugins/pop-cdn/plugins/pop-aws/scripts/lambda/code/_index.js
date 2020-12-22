var aws = require('aws-sdk');
var async = require('async');
var fs = require('fs')

// Perform the substitutions
var mark = require('markup-js');

// General static configuration
var staticConfig = require('./config.js');

// Function to merge javascript objects, taken from https://jonlabelle.com/snippets/view/javascript/javascript-merge-objects
var mergeObjects = function () {
    var i      = 0,
        len    = arguments.length,
        result = {},
        key,
        obj;
 
    for (; i < len; ++i) {
        obj = arguments[i];
 
        for (key in obj) {
            // If that key has already been set, and this new value is null, then don't merge
            if (obj[key] !== null || result[key] === null) {
                result[key] = obj[key];
            }
        }
    }
 
    return result;
};

var s3 = new aws.S3();
            
// SES Region must be explicitly provided, since there is no SES in Singapore, so it will fail not passing that parameter
var ses = new aws.SES(
    {
        "region": staticConfig.ses.region
    }
);
            
exports.handler = function (event, context, callback) {

    var emailSent = function (err, data) {
        if (err) {
            console.log(err, err.stack);
            context.fail('Internal Error: The email could not be sent.');
        } else {
            // successful response
            // console.log(data);
            context.succeed('The emails were successfully sent.');
        }
    };

    var srcBucket = event.Records[0].s3.bucket.name;
    // Object key may have spaces or unicode non-ASCII characters.
    var srcKey = decodeURIComponent(event.Records[0].s3.object.key.replace(/\+/g, " "));
    //     console.log("Event: " + JSON.stringify(event));

    // Download the file from S3, extract into JSON, and send the email
    async.waterfall(
        [
    function download(next)
        {
        // Download the file from S3 into a buffer.
        s3.getObject(
        {
            Bucket: srcBucket,
            Key: srcKey
            },
        next
    );
    },
    function process(response, next)
        {
        
        var file = JSON.parse(response.Body.toString());
        var emails = file.emails;
        var emailsConfig = file.configuration;
            
        // Check required parameters
        if (emails === null || emailsConfig === null) {
            context.fail('Bad Request: Missing required data: ' + response.Body.toString());
            return;
        }
        if (emails.length === 0) {
            // Nothing to do
            return;
        }
            
        // Obtain the websiteConfiguration: either a generic one, or a specific one for the website, if such exists and was provided in emailsConfig
        var websiteConfigKey = staticConfig.websiteConfig.path + (emailsConfig.website !== null ? emailsConfig.website+'/' : '') + staticConfig.websiteConfig.key;

        fs.readFile(
        './'+websiteConfigKey,
        'utf8',
        function (err,data) {

            if (err) {
                context.fail('Cannot read file "'+websiteConfigKey+'": ' + err);
                return;
            }

            var websiteConfig = JSON.parse(data);

            // From the website config, can read the location of the templates and styles files
            var isHTML = (emailsConfig.contentType == 'text/html');
            var templateKey = websiteConfig.templates.path + (isHTML ? websiteConfig.templates.keys.html : websiteConfig.templates.keys.txt);
            var stylesKey = websiteConfig.styles.path + websiteConfig.styles.key;
                
            fs.readFile(
            './'+templateKey,
            'utf8',
            function (err,data) {

                if (err) {
                    context.fail('Cannot read file "'+templateKey+'": ' + err);
                    return;
                }

                var templateBody = data;
                //                    console.log("Template Body: " + templateBody);

                fs.readFile(
                './'+stylesKey,
                'utf8',
                function (err,data) {

                    if (err) {
                        context.fail('Cannot read file "'+stylesKey+'": ' + err);
                        return;
                    }
                    
                    var styles = JSON.parse(data);

                    // We have everything we need!
                    // Merge all configurations together, so that the later arguments override the previous ones
                    //                                     var configuration = mergeObjects(websiteConfig, emailsConfig, {"styles" : styles});
                    //                                     var configuration = mergeObjects(emailsConfig, {"styles" : styles});
                        
                    for (var i = 0; i < emails.length; i++) {
                          var email = emails[i];

                          // Add also the configuration (eg: url)
                          var emailBody = mark.up(templateBody, mergeObjects(emailsConfig, {"styles" : styles}, email));

                          var params = {
                                Destination: {
                                    ToAddresses: email.to // Must be an array
                                },
                                Message: {
                                    Subject: {
                                        Data: email.subject,
                                        Charset: emailsConfig.charset
                                    }
                                },
                                Source: emailsConfig.from
                        };
                        if (email.cc) {
                              params.Destination.CcAddresses = email.cc; // Must be an array
                        }
                        if (email.bcc) {
                            params.Destination.BccAddresses = email.bcc; // Must be an array
                        }
                        if (email.replyTo) {
                            params.Destination.ReplyToAddresses = [email.replyTo]; // Must be a string
                        }

                        if (isHTML) {
                            params.Message.Body = {
                                Html: {
                                    Data: emailBody,
                                    Charset: emailsConfig.charset
                                }
                            };
                        } else {
                            params.Message.Body = {
                                Text: {
                                    Data: emailBody,
                                    Charset: emailsConfig.charset
                                }
                            };
                        }

                        // Send the email
                        ses.sendEmail(params, emailSent);
                    }
                }
            );
            }
        );
        }
    );
    }],
        function (err) {
            if (err) {
                console.error(
                    'Unable to send email due to an error: ' + err
                );
            } else {
                //                 console.log(
                //                     'Successfully sent email'
                //                 );
            }

            callback(null, "message");
        }
    );
};