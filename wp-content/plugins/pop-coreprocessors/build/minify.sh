
###########################
# JS TEMPLATES
###########################
rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/templates/*.tmpl.js
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/templates/*.tmpl.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/templates/
wget -O $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.templates.bundle.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/templates&f=blockgroup-blockunits.tmpl.js,block-bare.tmpl.js,block-description.tmpl.js,formcomponent-featuredimage-inner.tmpl.js,formcomponent-featuredimage.tmpl.js,conditionwrapper.tmpl.js,divider.tmpl.js,fetchmore.tmpl.js,status.tmpl.js,submenu.tmpl.js,pagesection-plain.tmpl.js,layout-messagefeedback.tmpl.js,layout-messagefeedbackframe.tmpl.js,messagefeedback-inner.tmpl.js,messagefeedback.tmpl.js,buttongroup.tmpl.js,button.tmpl.js,buttoninner.tmpl.js,anchor-control.tmpl.js,button-control.tmpl.js,controlbuttongroup.tmpl.js,controlgroup.tmpl.js,dropdownbutton-control.tmpl.js,radiobutton-control.tmpl.js,alert.tmpl.js,code.tmpl.js,hideifempty.tmpl.js,message.tmpl.js,multiple.tmpl.js,formcomponent-captcha.tmpl.js,formcomponent-daterange.tmpl.js,formcomponent-inputgroup.tmpl.js,formcomponent-buttongroup.tmpl.js,formcomponent-select.tmpl.js,formcomponent-selectabletypeaheadtrigger.tmpl.js,formcomponent-text.tmpl.js,formcomponent-checkbox.tmpl.js,formcomponent-textarea.tmpl.js,formcomponent-typeahead-fetchlink.tmpl.js,formcomponent-typeahead-selectable.tmpl.js,formgroup.tmpl.js,layout-maxheight.tmpl.js,layout-content.tmpl.js,layout-linkcontent.tmpl.js,layout-author-userphoto.tmpl.js,layout-author-contact.tmpl.js,layout-menu-anchor.tmpl.js,layout-menu-dropdown.tmpl.js,layout-menu-dropdownbutton.tmpl.js,layout-menu-indent.tmpl.js,layout-menu-multitargetindent.tmpl.js,layout-pagetab.tmpl.js,layout-postadditional-multilayout-label.tmpl.js,latestcount.tmpl.js,layout-authorcontent.tmpl.js,layoutpost-date.tmpl.js,layoutpost-status.tmpl.js,layoutpost-typeahead-component.tmpl.js,layoutpost-typeahead-selected.tmpl.js,layout-tag.tmpl.js,layoutstatic-typeahead-component.tmpl.js,layoutuser-quicklinks.tmpl.js,layoutuser-typeahead-component.tmpl.js,layoutuser-mention-component.tmpl.js,layouttag-typeahead-component.tmpl.js,script-append.tmpl.js,script-latestcount.tmpl.js,script-append-comment.tmpl.js,script-lazyloading-remove.tmpl.js,layout-userpostinteraction.tmpl.js,layout-comment.tmpl.js,layout-scriptframe.tmpl.js,layout-dataquery-updatedata.tmpl.js,layout-categories.tmpl.js,layout-embedpreview.tmpl.js,layout-initjs-delay.tmpl.js,layout-fullobjecttitle.tmpl.js,layout-menu-collapsesegmentedbutton.tmpl.js,layout-multiple.tmpl.js,layout-marker.tmpl.js,layout-poststatusdate.tmpl.js,layout-taginfo.tmpl.js,layout-subcomponent.tmpl.js,layout-popover.tmpl.js,layout-segmentedbutton-link.tmpl.js,layout-styles.tmpl.js,layoutpost-authoravatar.tmpl.js,layoutpost-authorname.tmpl.js,layoutuser-typeahead-selected.tmpl.js,layout-fullview.tmpl.js,layout-postthumb.tmpl.js,layout-previewpost.tmpl.js,layout-fulluser.tmpl.js,layout-previewuser.tmpl.js,layout-useravatar.tmpl.js,widget.tmpl.js,sm-item.tmpl.js,sm.tmpl.js,carousel-controls.tmpl.js,carousel-inner.tmpl.js,carousel.tmpl.js,content.tmpl.js,contentmultiple-inner.tmpl.js,contentsingle-inner.tmpl.js,form-inner.tmpl.js,form.tmpl.js,scroll-inner.tmpl.js,scroll.tmpl.js,table-inner.tmpl.js,table.tmpl.js,extension-appendableclass.tmpl.js,viewcomponent-button.tmpl.js,viewcomponent-header-commentclipped.tmpl.js,viewcomponent-header-commentpost.tmpl.js,viewcomponent-header-replycomment.tmpl.js,viewcomponent-header-post.tmpl.js,viewcomponent-header-user.tmpl.js,viewcomponent-header-tag.tmpl.js,userloggedin.tmpl.js"

###########################
# JS LIBRARIES
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/*.js
cd $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/
cp libraries/helpers.handlebars.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/3rdparties/analytics.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/3rdparties/daterange.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/3rdparties/dynamicmaxheight.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/3rdparties/multiselect.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/3rdparties/perfectscrollbar.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/3rdparties/typeahead.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/3rdparties/waypoints.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/addeditpost.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/block-functions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/bootstrap-carousel.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/bootstrap.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/controls.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
# cp libraries/custombootstrap.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/editor.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/mentions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/featuredimage.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/tabs.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/functions.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/modals.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/system.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/user-account.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp libraries/window.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/

echo ##########################
echo INCLUDED 3rd PARTY LIBRARY JS FILES
echo ##########################
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/includes/jquery.fullscreen-min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/includes/bootstrap-multiselect.0.9.13.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/includes/jquery.dynamicmaxheight.min.js $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
# wget -O $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.bundle.orig.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries&f=helpers.handlebars.js,custombootstrap.js,system.js,addeditpost.js,editor.js,mentions.js,featuredimage.js,user-account.js,bootstrap.js,bootstrap-carousel.js,waypoints.js,window.js,multiselect.js,dynamicmaxheight.js,daterange.js,typeahead.js,modals.js,analytics.js,perfectscrollbar.js,functions.js,block-functions.js,controls.js,jquery.fullscreen-min.js,bootstrap-multiselect.0.9.13.js,jquery.dynamicmaxheight.min.js"
wget -O $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.bundle.orig.min.js "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/js/libraries&f=helpers.handlebars.js,system.js,addeditpost.js,editor.js,mentions.js,featuredimage.js,tabs.js,user-account.js,waypoints.js,window.js,multiselect.js,dynamicmaxheight.js,daterange.js,typeahead.js,analytics.js,perfectscrollbar.js,functions.js,block-functions.js,bootstrap-carousel.js,modals.js,controls.js,jquery.fullscreen-min.js,bootstrap-multiselect.0.9.13.js,jquery.dynamicmaxheight.min.js"
uglifyjs $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.bundle.orig.min.js -o $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/js/dist/pop-coreprocessors.bundle.min.js -c warnings=false -m

###########################
# CSS
###########################

rm $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/css/*.css
cp $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/css/includes/jquery.dynamicmaxheight.css $POP_APP_MIN_PATH/$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/css/

# All files together: generate it EXACTLY in this order, as it was taken from scripts_and_styles.php
wget -O $POP_APP_PATH/wp-content/plugins/pop-coreprocessors/css/dist/pop-coreprocessors.bundle.min.css "http://min.localhost/?b=$POP_APP_MIN_FOLDER/plugins/pop-coreprocessors/css&f=jquery.dynamicmaxheight.css"
