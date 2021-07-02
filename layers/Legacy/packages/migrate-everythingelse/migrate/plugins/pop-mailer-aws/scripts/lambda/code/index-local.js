// Code and idea copied from https://github.com/eleven41/aws-lambda-send-ses-email
// To download the node modules, follow instructions here: https://docs.aws.amazon.com/lambda/latest/dg/with-s3-example-deployment-pkg.html
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
// var ses = new aws.SES({
//   "region": staticConfig.ses.region
// });
			
exports.handler = function(event, context, callback) {

	var srcBucket = event.Records[0].s3.bucket.name;
    // Object key may have spaces or unicode non-ASCII characters.
    var srcKey = decodeURIComponent(event.Records[0].s3.object.key.replace(/\+/g, " ")); 
// 	console.log("Event: " + JSON.stringify(event));
	var file, emails, emailsConfig, websiteConfigKey, isHTML, templateKey, stylesKey, templateBody;

    // Download the file from S3, extract into JSON, and send the email
    async.waterfall(
    	[
	    	function readTags(next) {

	        	var params = {
					Bucket: srcBucket,
	                Key: srcKey
				};
				s3.getObjectTagging(params, next);
			},
	        function validateTags(response, next) {

				if (
					response.TagSet.length && 
					response.TagSet[0].Key == 'Status' &&
					response.TagSet[0].Value == 'Sent'
					) {
					// Do nothing, the emails have already been sent
					context.succeed('The emails had been sent already, no need to do anything.');
					return callback(null, "message");
				}

				next();
			},
	        function download(next) {

	            // Download the file from S3 into a buffer.
	            s3.getObject(
	            	{
	                    Bucket: srcBucket,
	                    Key: srcKey
	                },
	                next
	            );
	        },
	        function process(response, next) {
	        
	        	file = JSON.parse(response.Body.toString());
	        	emails = file.emails;
	        	emailsConfig = file.configuration;
				
				// Check required parameters
				if (emails === null || emailsConfig === null) {
					context.fail('Bad Request: Missing required data: ' + response.Body.toString());
					return;
				}
				if (emails.length === 0) {
					// Nothing to do
					context.fail('Bad Request: No emails provided: ' + response.Body.toString());
					return;
				}
				
				// Obtain the websiteConfiguration: either a generic one, or a specific one for the website, if such exists and was provided in emailsConfig
				websiteConfigKey = staticConfig.websiteConfig.path + (emailsConfig.website !== null ? emailsConfig.website+'/' : '') + staticConfig.websiteConfig.key;

				fs.readFile('./'+websiteConfigKey, 'utf8', next);
	        },
	        function readFile(response, next) {
	        
	    		var websiteConfig = JSON.parse(response);

				// From the website config, can read the location of the templates and styles files
				isHTML = (emailsConfig.contentType == 'text/html');
				templateKey = websiteConfig.templates.path + (isHTML ? websiteConfig.templates.keys.html : websiteConfig.templates.keys.txt);
				stylesKey = websiteConfig.styles.path + websiteConfig.styles.key;
				
				fs.readFile('./'+templateKey, 'utf8', next);
	        },
	        function readStyles(response, next) {
	    
				templateBody = response;
				fs.readFile('./'+stylesKey, 'utf8', next);
	        },
	        function processEmails(response, next) {
	    
				var styles = JSON.parse(response);
				var totalEmails = emails.length;
				var successEmails = 0;
				for (var i = 0; i < emails.length; i++) {
				// async.forEach(emails, function(email, i, cb) {

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

					// Send the emails
					var ses = new aws.SES({
					  "region": staticConfig.ses.region
					});
					ses.sendEmail(params, function(err, data) {
						if (err) {
							console.log(err, err.stack);
							context.fail('Internal Error: An email could not be sent.');
						}
						else { 
							
							successEmails++;
							if (successEmails == totalEmails) {
								next();
							}
						}
					});
				}
	        },
	        function setTags(next) {
	        
				// Add a tag to the object to indicate the emails have been sent
				// This is to avoid the "at least one" policy, avoiding that it sends the emails more than once
				var params = {
					Bucket: srcBucket,
	                Key: srcKey,
					Tagging: {
						TagSet: [
							{
								Key: 'Status', 
								Value: 'Sent'
							}
						]
					}
				};
				s3.putObjectTagging(params, next);
			},
	        function end(next) {

				context.succeed('The emails were successfully sent.');
			}
		], 
		function (err) {

            if (err) {
                console.error('Unable to send emails due to an error: ' + err);
            } 
            else {
//                 console.log(
//                     'Successfully sent email'
//                 );
            }

            callback(null, "message");
        }
    );
};