#!/bin/sh
echo "Creating menus"
# Create menus
wp menu create "Top Menu"
wp menu item add-post top-menu 1
wp menu item add-post top-menu 2 --title="Page in Menu for testing" --description="Some description" --attr-title="Some attr-title" --target="_blank" --classes="class1 class2 class3" --position=3
wp menu item add-custom top-menu Apple https://apple.com
wp menu location assign top-menu primary

wp menu create "Bottom Menu"
wp menu item add-post bottom-menu 2
wp menu item add-custom bottom-menu Google https://google.com
wp menu item add-custom bottom-menu Amazon https://amazon.com --parent-id=$(wp menu item add-post bottom-menu 1 --title="Ancestor post in Menu for testing" --porcelain)
wp menu item add-custom bottom-menu BBC https://bbc.com --parent-id=$(wp menu item add-post bottom-menu 1 --title="Parent post in Menu for testing" --parent-id=$(wp menu item add-post bottom-menu 1 --title="Most ancestor post in Menu for testing" --porcelain) --porcelain)
wp menu location assign bottom-menu footer

wp menu create "Unassigned Menu"
wp menu item add-custom unassigned-menu Yahoo https://yahoo.com
