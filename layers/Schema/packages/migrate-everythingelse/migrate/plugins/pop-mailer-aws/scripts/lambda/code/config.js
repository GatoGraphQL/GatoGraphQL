"use strict";

// This config below must be overriden by the website implementation
var config = {
	"ses" : {
		"region" : "us-east-1"
	},
	"bucket" : "pop-mailer",
	"websiteConfig" : {
		"path" : "config/",
		"key" : "config.js"
	}
}

module.exports = config
