#!/bin/sh
echo "Creating menus"
# Create menus
wp menu create "Top Menu" --path=/app/wordpress
wp menu item add-post top-menu 1 --path=/app/wordpress
wp menu item add-post top-menu 2 --title="Page in Menu for testing" --description="Some description" --attr-title="Some attr-title" --target="overriding-target" --classes="class1 class2 class3" --position=3 --path=/app/wordpress
wp menu item add-custom top-menu Apple https://apple.com --path=/app/wordpress
wp menu location assign top-menu primary --path=/app/wordpress

wp menu create "Bottom Menu" --path=/app/wordpress
wp menu item add-post bottom-menu 2 --path=/app/wordpress
wp menu item add-custom bottom-menu Google https://google.com --path=/app/wordpress
wp menu item add-custom bottom-menu Amazon https://amazon.com --parent-id=$(wp menu item add-post bottom-menu 1 --title="Ancestor post in Menu for testing" --porcelain --path=/app/wordpress) --path=/app/wordpress
wp menu location assign bottom-menu footer --path=/app/wordpress

wp menu create "Unassigned Menu" --path=/app/wordpress
wp menu item add-custom unassigned-menu Yahoo https://yahoo.com --path=/app/wordpress
