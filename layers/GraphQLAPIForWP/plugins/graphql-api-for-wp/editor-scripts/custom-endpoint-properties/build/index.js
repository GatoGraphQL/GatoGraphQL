(window.webpackJsonp_custom_endpoint_properties=window.webpackJsonp_custom_endpoint_properties||[]).push([[1],{9:function(e,t,n){}}]),function(e){function t(t){for(var r,i,s=t[0],o=t[1],c=t[2],u=0,m=[];u<s.length;u++)i=s[u],Object.prototype.hasOwnProperty.call(l,i)&&l[i]&&m.push(l[i][0]),l[i]=0;for(r in o)Object.prototype.hasOwnProperty.call(o,r)&&(e[r]=o[r]);for(p&&p(t);m.length;)m.shift()();return a.push.apply(a,c||[]),n()}function n(){for(var e,t=0;t<a.length;t++){for(var n=a[t],r=!0,s=1;s<n.length;s++){var o=n[s];0!==l[o]&&(r=!1)}r&&(a.splice(t--,1),e=i(i.s=n[0]))}return e}var r={},l={0:0},a=[];function i(t){if(r[t])return r[t].exports;var n=r[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,i),n.l=!0,n.exports}i.m=e,i.c=r,i.d=function(e,t,n){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)i.d(n,r,function(t){return e[t]}.bind(null,r));return n},i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="";var s=window.webpackJsonp_custom_endpoint_properties=window.webpackJsonp_custom_endpoint_properties||[],o=s.push.bind(s);s.push=t,s=s.slice();for(var c=0;c<s.length;c++)t(s[c]);var p=o;a.push([10,1]),n()}([function(e,t){e.exports=window.wp.element},function(e,t){e.exports=window.wp.i18n},function(e,t){e.exports=window.wp.components},function(e,t){e.exports=window.wp.editor},function(e,t){e.exports=window.wp.plugins},function(e,t){e.exports=window.wp.editPost},function(e,t){e.exports=window.wp.data},function(e,t){e.exports=window.wp.url},function(e,t){e.exports=window.wp.blockEditor},,function(e,t,n){"use strict";n.r(t);var r=n(4),l=n(0),a=n(1),i=n(5),s=n(6),o=n(7),c=n(2),p=n(3),u=n(8);function m(){var e=Object(s.useSelect)((function(e){var t=e(p.store).getCurrentPost(),n=e(p.store).getPermalinkParts(),r=e(u.store).getBlocks(),l=r.filter((function(e){return"graphql-api/custom-endpoint-options"===e.name})).shift(),a=r.filter((function(e){return"graphql-api/endpoint-graphiql"===e.name})).shift(),i=r.filter((function(e){return"graphql-api/endpoint-voyager"===e.name})).shift();return{postSlug:Object(o.safeDecodeURIComponent)(e(p.store).getEditedPostSlug()),postLink:t.link,postLinkHasParams:t.link.indexOf("?")>=0,postStatus:t.status,isPostPublished:"publish"===t.status,permalinkPrefix:null==n?void 0:n.prefix,permalinkSuffix:null==n?void 0:n.suffix,isCustomEndpointEnabled:l.attributes.isEnabled,isGraphiQLClientEnabled:a.attributes.isEnabled,isVoyagerClientEnabled:i.attributes.isEnabled}}),[]),t=e.postSlug,n=e.postLink,r=e.postLinkHasParams,i=e.postStatus,m=e.isPostPublished,b=e.permalinkPrefix,d=e.permalinkSuffix,_=e.isCustomEndpointEnabled,f=e.isGraphiQLClientEnabled,O=e.isVoyagerClientEnabled,j=r?"&":"?",E=m?"🟢":"🟡";return Object(l.createElement)(l.Fragment,null,Object(l.createElement)("div",{className:"editor-post-url"},_&&Object(l.createElement)("p",{className:"notice-message"},Object(l.createElement)(c.Notice,{status:m?"success":"warning",isDismissible:!1},Object(l.createElement)("strong",null,Object(a.__)("Status ","graphql-api"),Object(l.createElement)("code",null,i),":"),Object(l.createElement)("br",null),Object(l.createElement)("span",{className:"notice-inner-message"},m&&Object(a.__)("Available to everyone.","graphql-api"),!m&&Object(a.__)("Available to the Schema editors only.","graphql-api")))),Object(l.createElement)("h3",{className:"editor-post-url__link-label"},_?E:"🔴"," ",Object(a.__)("Custom Endpoint URL")),Object(l.createElement)("p",null,_&&Object(l.createElement)(c.ExternalLink,{className:"editor-post-url__link",href:n,target:"_blank"},Object(l.createElement)(l.Fragment,null,Object(l.createElement)("span",{className:"editor-post-url__link-prefix"},b),Object(l.createElement)("span",{className:"editor-post-url__link-slug"},t),Object(l.createElement)("span",{className:"editor-post-url__link-suffix"},d))),!_&&Object(l.createElement)("span",{className:"disabled-text"},Object(a.__)("Disabled","graphql-api")))),Object(l.createElement)("hr",null),Object(l.createElement)("div",{className:"editor-post-url"},Object(l.createElement)("h3",{className:"editor-post-url__link-label"},"🟡 ",Object(a.__)("View Endpoint Source")),Object(l.createElement)("p",null,Object(l.createElement)(c.ExternalLink,{className:"editor-post-url__link",href:n+j+"view=source",target:"_blank"},Object(l.createElement)(l.Fragment,null,Object(l.createElement)("span",{className:"editor-post-url__link-prefix"},b),Object(l.createElement)("span",{className:"editor-post-url__link-slug"},t),Object(l.createElement)("span",{className:"editor-post-url__link-suffix"},d),Object(l.createElement)("span",{className:"editor-endoint-custom-post-url__link-view"},"?view="),Object(l.createElement)("span",{className:"editor-endoint-custom-post-url__link-view-item"},"source"))))),Object(l.createElement)("hr",null),Object(l.createElement)("div",{className:"editor-post-url"},Object(l.createElement)("h3",{className:"editor-post-url__link-label"},f?E:"🔴"," ",Object(a.__)("GraphiQL client")),Object(l.createElement)("p",null,f&&Object(l.createElement)(c.ExternalLink,{className:"editor-post-url__link",href:n+j+"view=graphiql",target:"_blank"},Object(l.createElement)(l.Fragment,null,Object(l.createElement)("span",{className:"editor-post-url__link-prefix"},b),Object(l.createElement)("span",{className:"editor-post-url__link-slug"},t),Object(l.createElement)("span",{className:"editor-post-url__link-suffix"},d),Object(l.createElement)("span",{className:"editor-endoint-custom-post-url__link-view"},"?view="),Object(l.createElement)("span",{className:"editor-endoint-custom-post-url__link-view-item"},"graphiql"))),!f&&Object(l.createElement)("span",{className:"disabled-text"},Object(a.__)("Disabled","graphql-api")))),Object(l.createElement)("hr",null),Object(l.createElement)("div",{className:"editor-post-url"},Object(l.createElement)("h3",{className:"editor-post-url__link-label"},O?E:"🔴"," ",Object(a.__)("Interactive Schema Client")),Object(l.createElement)("p",null,O&&Object(l.createElement)(c.ExternalLink,{className:"editor-post-url__link",href:n+j+"view=schema",target:"_blank"},Object(l.createElement)(l.Fragment,null,Object(l.createElement)("span",{className:"editor-post-url__link-prefix"},b),Object(l.createElement)("span",{className:"editor-post-url__link-slug"},t),Object(l.createElement)("span",{className:"editor-post-url__link-suffix"},d),Object(l.createElement)("span",{className:"editor-endoint-custom-post-url__link-view"},"?view="),Object(l.createElement)("span",{className:"editor-endoint-custom-post-url__link-view-item"},"schema"))),!O&&Object(l.createElement)("span",{className:"disabled-text"},Object(a.__)("Disabled","graphql-api")))))}n(9);Object(r.registerPlugin)("custom-endpoint-properties-panel",{render:function(){return Object(l.createElement)(i.PluginDocumentSettingPanel,{name:"custom-endpoint-properties-panel",title:Object(a.__)("Custom Endpoint Properties","graphql-api")},Object(l.createElement)(m,null))},icon:"welcome-view-site"})}]);