This folder has files containing various templates which events manager uses to output its information. 

Almost every aspect of the public interface (and some parts of the admin interface!) can be modified. Please individual folders for more information

Any of these files in this folder can be individually overriden in your theme folder, making it upgrade safe without having to hack core plugin files directly.

To override a file, copy (not move) the file to "wp-content/themes/yourtheme/plugins/events-manager/filepath within this folder/" and edit as needed.

For example:

copy templates/events-single.php

to wp-content/themes/yourtheme/plugins/events-manager/templates/events-single.php

Whilst we intend to keep changes to a minimum, it may be inevitable that we need to update these files to add new features or fix a 
reported bug, please keep this in mind when updating.