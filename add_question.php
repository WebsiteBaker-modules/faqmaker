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

require('../../config.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Load Language file
if(LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/faqmaker/languages/EN.php');
    if(file_exists(WB_PATH.'/modules/faqmaker/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/faqmaker/languages/'.LANGUAGE.'.php');
    }
}

// Check if any categories exist
$query_topics = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_faqmaker_topics WHERE topic_name != '' AND section_id='".$section_id."'");
if($query_topics->numRows() > 0) {
	

	// Insert new row into database
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_faqmaker_questions (section_id,page_id,question_id,topic_id,question,answer,position) VALUES ('$section_id','$page_id','0','0','','','0')");

	// Get the id
	$question_id = $database->get_one("SELECT LAST_INSERT_ID()");

	// Say that a new record has been added, then redirect to modify page
	if($database->is_error()) {
		$admin->print_error($database->get_error(), WB_URL.'/modules/faqmaker/modify_question.php?page_id='.$page_id.'&section_id='.$section_id.'&question_id='.$question_id);
	} else {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/faqmaker/modify_question.php?page_id='.$page_id.'&section_id='.$section_id.'&question_id='.$question_id);
	}
	
} else {

	$admin->print_error($FQTEXT['ADD_ONE'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	
}

// Print admin footer
$admin->print_footer();

?>
