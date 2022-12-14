#!/bin/sh
echo "Creating menus"
# Create menus
wp menu create "Top Menu" --path=/app/wordpress
wp menu item add-post top-menu 1 --path=/app/wordpress
wp menu item add-post top-menu 2 --title="Page in Menu for testing" --path=/app/wordpress
wp menu item add-custom top-menu Apple https://apple.com --path=/app/wordpress
wp menu location assign top-menu primary

wp menu create "Bottom Menu" --path=/app/wordpress
wp menu item add-post bottom-menu 2 --path=/app/wordpress
wp menu item add-custom bottom-menu Google https://google.com --path=/app/wordpress
wp menu location assign bottom-menu footer

wp menu create "Unassigned Menu" --path=/app/wordpress
wp menu item add-custom unassigned-menu Yahoo https://yahoo.com --path=/app/wordpress
