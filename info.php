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

/**
* Version History:
*
* v1.0 - 07.09.2008
* 	Initial Public Release
*
* v1.1 - 07.10.2008
*	changed: removed many fixed messages
*
* v1.2 - 07.22.2008
*	added: option to store questions in database or have sent via email. New table submitted_questions created.
*	       changed layout of modify.php to show submitted questions link. New file view_submitted.php created 
*	       to view questions. 
*	added: option to disable 'Ask a Question' link.
*	fixed: captcha working with WB 2.7
* added: check for empty question field in 'Ask a Question'.
* added: email validation for non-empty email field.
*
**/


$module_directory = 'faqmaker';
$module_name = 'F.A.Q. Maker';
$module_function = 'page';
$module_version = '1.21';
$module_platform = '2.7.x';
$module_author = 'Nick Willey';
$module_license = 'GNU General Public License';
$module_description = 'FAQ manager based on the original FAQ Baker.';

?>
