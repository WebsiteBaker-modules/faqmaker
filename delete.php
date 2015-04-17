<?php

/*
FAQ Maker
Copyright (C) 2008, Nick Willey
nick.willey@gmail.com

This module is free software.
You can redistribute it and/or modify it
under the terms of the GNU General Public License
- version 2 or later, as published by the Free Software Foundation:
http://www.gnu.org/licenses/gpl.html.

This module is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
include('info.php');

//this deletes the entry in the database, when you delete your module from a page
$database->query("DELETE FROM ".TABLE_PREFIX."mod_faqmaker_topics WHERE section_id = '$section_id'");
$database->query("DELETE FROM ".TABLE_PREFIX."mod_faqmaker_questions WHERE section_id = '$section_id'");
$database->query("DELETE FROM ".TABLE_PREFIX."mod_faqmaker_settings WHERE section_id = '$section_id'");

?>
