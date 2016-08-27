rm $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/dist/templates/*.tmpl.js
cd $POP_APP_PATH/wp-content/plugins/poptheme-wassup/js/templates/

handlebars layouts/layout-link-access.tmpl -m --extension=tmpl -f ../dist/templates/layout-link-access.tmpl.js
handlebars layouts/layout-volunteertag.tmpl -m --extension=tmpl -f ../dist/templates/layout-volunteertag.tmpl.js
handlebars layouts/speechbubble.tmpl -m --extension=tmpl -f ../dist/templates/speechbubble.tmpl.js
handlebars pagesections/pagesection-background.tmpl -m --extension=tmpl -f ../dist/templates/pagesection-background.tmpl.js
handlebars pagesections/pagesection-side.tmpl -m --extension=tmpl -f ../dist/templates/pagesection-side.tmpl.js
handlebars pagesections/pagesection-top.tmpl -m --extension=tmpl -f ../dist/templates/pagesection-top.tmpl.js
handlebars themes/simple/pagesections/pagesection-topsimple.tmpl -m --extension=tmpl -f ../dist/templates/pagesection-topsimple.tmpl.js

##############################
# User Role Editor
##############################
handlebars layouts/ure-layoutuser-profileindividual-details.tmpl -m --extension=tmpl -f ../dist/templates/ure-layoutuser-profileindividual-details.tmpl.js
handlebars layouts/ure-layoutuser-profileorganization-details.tmpl -m --extension=tmpl -f ../dist/templates/ure-layoutuser-profileorganization-details.tmpl.js

