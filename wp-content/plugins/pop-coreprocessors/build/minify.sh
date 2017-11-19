
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/bundles/pop-coreprocessors.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/templates&f=blockgroup-blockunits.tmpl.js,block-bare.tmpl.js,block-description.tmpl.js,formcomponent-featuredimage-inner.tmpl.js,formcomponent-featuredimage.tmpl.js,conditionwrapper.tmpl.js,divider.tmpl.js,fetchmore.tmpl.js,status.tmpl.js,submenu.tmpl.js,pagesection-plain.tmpl.js,layout-messagefeedback.tmpl.js,layout-messagefeedbackframe.tmpl.js,messagefeedback-inner.tmpl.js,messagefeedback.tmpl.js,buttongroup.tmpl.js,button.tmpl.js,buttoninner.tmpl.js,anchor-control.tmpl.js,button-control.tmpl.js,controlbuttongroup.tmpl.js,controlgroup.tmpl.js,dropdownbutton-control.tmpl.js,radiobutton-control.tmpl.js,alert.tmpl.js,code.tmpl.js,hideifempty.tmpl.js,message.tmpl.js,multiple.tmpl.js,formcomponent-captcha.tmpl.js,formcomponent-daterange.tmpl.js,formcomponent-inputgroup.tmpl.js,formcomponent-buttongroup.tmpl.js,formcomponent-select.tmpl.js,formcomponent-selectabletypeaheadtrigger.tmpl.js,formcomponent-text.tmpl.js,formcomponent-checkbox.tmpl.js,formcomponent-textarea.tmpl.js,formcomponent-typeahead-fetchlink.tmpl.js,formcomponent-typeahead-selectable.tmpl.js,formgroup.tmpl.js,layout-maxheight.tmpl.js,layout-content.tmpl.js,layout-linkcontent.tmpl.js,layout-author-userphoto.tmpl.js,layout-author-contact.tmpl.js,layout-menu-anchor.tmpl.js,layout-menu-dropdown.tmpl.js,layout-menu-dropdownbutton.tmpl.js,layout-menu-indent.tmpl.js,layout-menu-multitargetindent.tmpl.js,layout-pagetab.tmpl.js,layout-postadditional-multilayout-label.tmpl.js,latestcount.tmpl.js,layout-authorcontent.tmpl.js,layoutpost-date.tmpl.js,layoutpost-status.tmpl.js,layoutpost-typeahead-component.tmpl.js,layoutpost-typeahead-selected.tmpl.js,layout-tag.tmpl.js,layoutstatic-typeahead-component.tmpl.js,layoutuser-quicklinks.tmpl.js,layoutuser-typeahead-component.tmpl.js,layoutuser-mention-component.tmpl.js,layouttag-typeahead-component.tmpl.js,script-append.tmpl.js,script-latestcount.tmpl.js,script-append-comment.tmpl.js,script-lazyloading-remove.tmpl.js,layout-userpostinteraction.tmpl.js,layout-comment.tmpl.js,layout-scriptframe.tmpl.js,layout-dataquery-updatedata.tmpl.js,layout-categories.tmpl.js,layout-embedpreview.tmpl.js,layout-initjs-delay.tmpl.js,layout-fullobjecttitle.tmpl.js,layout-menu-collapsesegmentedbutton.tmpl.js,layout-multiple.tmpl.js,layout-marker.tmpl.js,layout-poststatusdate.tmpl.js,layout-taginfo.tmpl.js,layout-subcomponent.tmpl.js,layout-popover.tmpl.js,layout-segmentedbutton-link.tmpl.js,layout-styles.tmpl.js,layoutpost-authoravatar.tmpl.js,layoutpost-authorname.tmpl.js,layoutuser-typeahead-selected.tmpl.js,layout-fullview.tmpl.js,layout-postthumb.tmpl.js,layout-previewpost.tmpl.js,layout-fulluser.tmpl.js,layout-previewuser.tmpl.js,layout-useravatar.tmpl.js,widget.tmpl.js,sm-item.tmpl.js,sm.tmpl.js,carousel-controls.tmpl.js,carousel-inner.tmpl.js,carousel.tmpl.js,content.tmpl.js,contentmultiple-inner.tmpl.js,contentsingle-inner.tmpl.js,form-inner.tmpl.js,form.tmpl.js,scroll-inner.tmpl.js,scroll.tmpl.js,table-inner.tmpl.js,table.tmpl.js,viewcomponent-button.tmpl.js,viewcomponent-header-commentclipped.tmpl.js,viewcomponent-header-commentpost.tmpl.js,viewcomponent-header-replycomment.tmpl.js,viewcomponent-header-post.tmpl.js,viewcomponent-header-user.tmpl.js,viewcomponent-header-tag.tmpl.js,userloggedin.tmpl.js"

###########################
# JS LIBRARIES
###########################

# rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/*.js
# cd $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/
# cp libraries/helpers.handlebars.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/3rdparties/analytics.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/3rdparties/daterange.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/3rdparties/dynamicmaxheight.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/3rdparties/multiselect.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/3rdparties/perfectscrollbar.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/3rdparties/typeahead.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/3rdparties/waypoints.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/addeditpost.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/block-functions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/bootstrap-carousel.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/controls.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/editor.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/mentions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/featuredimage.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/mediamanager.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/mediamanager-cors.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/tabs.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/cookies.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/functions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/modals.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/system.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/user-account.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/window.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/

# echo ##########################
# echo INCLUDED 3rd PARTY LIBRARY JS FILES
# echo ##########################
# cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/includes/jquery.fullscreen-min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/includes/bootstrap-multiselect.0.9.13.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/includes/jquery.dynamicmaxheight.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/

# # All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
# wget -O $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.bundle.orig.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries&f=helpers.handlebars.js,system.js,addeditpost.js,editor.js,mentions.js,featuredimage.js,mediamanager.js,mediamanager-cors.js,tabs.js,user-account.js,waypoints.js,window.js,multiselect.js,dynamicmaxheight.js,daterange.js,typeahead.js,analytics.js,perfectscrollbar.js,cookies.js,functions.js,block-functions.js,bootstrap-carousel.js,modals.js,controls.js,jquery.fullscreen-min.js,bootstrap-multiselect.0.9.13.js,jquery.dynamicmaxheight.min.js"
# uglifyjs $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.bundle.orig.min.js -o $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.bundle.min.js -c warnings=false -m

cd $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/
uglifyjs libraries/helpers.handlebars.js -o dist/libraries/helpers.handlebars.min.js -c warnings=false -m
#uglifyjs libraries/3rdparties/analytics.js -o dist/libraries/3rdparties/analytics.min.js -c warnings=false -m
uglifyjs libraries/3rdparties/daterange.js -o dist/libraries/3rdparties/daterange.min.js -c warnings=false -m
uglifyjs libraries/3rdparties/dynamicmaxheight.js -o dist/libraries/3rdparties/dynamicmaxheight.min.js -c warnings=false -m
uglifyjs libraries/3rdparties/multiselect.js -o dist/libraries/3rdparties/multiselect.min.js -c warnings=false -m
uglifyjs libraries/3rdparties/perfectscrollbar.js -o dist/libraries/3rdparties/perfectscrollbar.min.js -c warnings=false -m
uglifyjs libraries/3rdparties/typeahead.js -o dist/libraries/3rdparties/typeahead.min.js -c warnings=false -m
uglifyjs libraries/3rdparties/waypoints.js -o dist/libraries/3rdparties/waypoints.min.js -c warnings=false -m
uglifyjs libraries/appshell.js -o dist/libraries/appshell.min.js -c warnings=false -m
uglifyjs libraries/addeditpost.js -o dist/libraries/addeditpost.min.js -c warnings=false -m
# uglifyjs libraries/block-functions.js -o dist/libraries/block-functions.min.js -c warnings=false -m
uglifyjs libraries/bootstrap-carousel.js -o dist/libraries/bootstrap-carousel.min.js -c warnings=false -m
uglifyjs libraries/controls.js -o dist/libraries/controls.min.js -c warnings=false -m
uglifyjs libraries/editor.js -o dist/libraries/editor.min.js -c warnings=false -m
uglifyjs libraries/mentions.js -o dist/libraries/mentions.min.js -c warnings=false -m
uglifyjs libraries/featuredimage.js -o dist/libraries/featuredimage.min.js -c warnings=false -m
uglifyjs libraries/mediamanager.js -o dist/libraries/mediamanager.min.js -c warnings=false -m
uglifyjs libraries/mediamanager-cors.js -o dist/libraries/mediamanager-cors.min.js -c warnings=false -m
uglifyjs libraries/tabs.js -o dist/libraries/tabs.min.js -c warnings=false -m
uglifyjs libraries/cookies.js -o dist/libraries/cookies.min.js -c warnings=false -m
uglifyjs libraries/functions.js -o dist/libraries/functions.min.js -c warnings=false -m
uglifyjs libraries/input-functions.js -o dist/libraries/input-functions.min.js -c warnings=false -m
uglifyjs libraries/embed-functions.js -o dist/libraries/embed-functions.min.js -c warnings=false -m
uglifyjs libraries/print-functions.js -o dist/libraries/print-functions.min.js -c warnings=false -m
uglifyjs libraries/content-functions.js -o dist/libraries/content-functions.min.js -c warnings=false -m
uglifyjs libraries/target-functions.js -o dist/libraries/target-functions.min.js -c warnings=false -m
uglifyjs libraries/socialmedia.js -o dist/libraries/socialmedia.min.js -c warnings=false -m
uglifyjs libraries/embeddable.js -o dist/libraries/embeddable.min.js -c warnings=false -m
uglifyjs libraries/block-dataquery.js -o dist/libraries/block-dataquery.min.js -c warnings=false -m
uglifyjs libraries/blockgroup-dataquery.js -o dist/libraries/blockgroup-dataquery.min.js -c warnings=false -m
uglifyjs libraries/menus.js -o dist/libraries/menus.min.js -c warnings=false -m
uglifyjs libraries/dataset-count.js -o dist/libraries/dataset-count.min.js -c warnings=false -m
uglifyjs libraries/replicate.js -o dist/libraries/replicate.min.js -c warnings=false -m
uglifyjs libraries/forms.js -o dist/libraries/forms.min.js -c warnings=false -m
uglifyjs libraries/links.js -o dist/libraries/links.min.js -c warnings=false -m
uglifyjs libraries/classes.js -o dist/libraries/classes.min.js -c warnings=false -m
uglifyjs libraries/scrolls.js -o dist/libraries/scrolls.min.js -c warnings=false -m
uglifyjs libraries/onlineoffline.js -o dist/libraries/onlineoffline.min.js -c warnings=false -m
uglifyjs libraries/event-reactions.js -o dist/libraries/event-reactions.min.js -c warnings=false -m
uglifyjs libraries/feedback-message.js -o dist/libraries/feedback-message.min.js -c warnings=false -m
uglifyjs libraries/modals.js -o dist/libraries/modals.min.js -c warnings=false -m
uglifyjs libraries/system.js -o dist/libraries/system.min.js -c warnings=false -m
uglifyjs libraries/user-account.js -o dist/libraries/user-account.min.js -c warnings=false -m
uglifyjs libraries/window.js -o dist/libraries/window.min.js -c warnings=false -m

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/*.js
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/libraries/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/libraries/3rdparties/*.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
echo ##########################
echo INCLUDED 3rd PARTY LIBRARY JS FILES
echo ##########################
# cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/includes/jquery.fullscreen-min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/includes/bootstrap-multiselect.0.9.13.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/includes/jquery.dynamicmaxheight.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
wget -O $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/bundles/pop-coreprocessors.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries&f=helpers.handlebars.min.js,system.min.js,appshell.min.js,addeditpost.min.js,editor.min.js,mentions.min.js,featuredimage.min.js,mediamanager.min.js,mediamanager-cors.min.js,tabs.min.js,user-account.min.js,waypoints.min.js,window.min.js,multiselect.min.js,dynamicmaxheight.min.js,daterange.min.js,typeahead.min.js,analytics.min.js,perfectscrollbar.min.js,functions.min.js,input-functions.min.js,embed-functions.min.js,print-functions.min.js,content-functions.min.js,target-functions.min.js,socialmedia.min.js,embeddable.min.js,block-dataquery.min.js,blockgroup-dataquery.min.js,menus.min.js,dataset-count.min.js,replicate.min.js,forms.min.js,links.min.js,classes.min.js,scrolls.min.js,onlineoffline.min.js,event-reactions.min.js,feedback-message.min.js,bootstrap-carousel.min.js,modals.min.js,controls.min.js,jquery.dynamicmaxheight.min.js"

###########################
# CSS
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/css/*.css
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/css/includes/jquery.dynamicmaxheight.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/css/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/css/dist/bundles/pop-coreprocessors.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/css&f=jquery.dynamicmaxheight.css"
