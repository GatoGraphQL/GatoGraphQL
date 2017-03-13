rm $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/dist/templates/*.tmpl.js
cd $POP_APP_PATH/wp-content/plugins/pop-bootstrapprocessors/js/templates/

handlebars blockgroups/bootstrap/blockgroup-carousel.tmpl -m --extension=tmpl -f ../dist/templates/blockgroup-carousel.tmpl.js
handlebars blockgroups/bootstrap/blockgroup-collapsepanelgroup.tmpl -m --extension=tmpl -f ../dist/templates/blockgroup-collapsepanelgroup.tmpl.js
handlebars blockgroups/bootstrap/blockgroup-tabpanel.tmpl -m --extension=tmpl -f ../dist/templates/blockgroup-tabpanel.tmpl.js
handlebars blockgroups/bootstrap/blockgroup-viewcomponent.tmpl -m --extension=tmpl -f ../dist/templates/blockgroup-viewcomponent.tmpl.js
handlebars pagesections/pagesection-modal.tmpl -m --extension=tmpl -f ../dist/templates/pagesection-modal.tmpl.js
handlebars pagesections/pagesection-pagetab.tmpl -m --extension=tmpl -f ../dist/templates/pagesection-pagetab.tmpl.js
handlebars pagesections/pagesection-tabpane.tmpl -m --extension=tmpl -f ../dist/templates/pagesection-tabpane.tmpl.js
