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

function faqmaker_search($func_vars) {
	extract($func_vars, EXTR_PREFIX_ALL, 'func');

	// how many lines of excerpt we want to have at most
	$max_excerpt_num = $func_default_max_excerpt;
	$divider = ".";
	$result = false;

	// fetch all faqmaker-items from this sections
	$table = TABLE_PREFIX."mod_faqmaker_questions";
	$query = $func_database->query("
		SELECT question, answer, question_id, topic_id
		FROM $table
		WHERE section_id='$func_section_id' AND topic_id > '0'
		ORDER BY topic_id, position
	"); // in some older version of faqmaker it was possible to create faq-items with cat_id==0.
	    // those items weren't accessible ever, so ignore them.
	// now call print_excerpt() for every single item
	if($query->numRows() > 0) {
		while($res = $query->fetchRow()) {
			$mod_vars = array(
				'page_link' => $func_page_link,
				'page_link_target' => "&qa_id=".$res['topic_id'].".".$res['question_id'],
				'page_title' => $func_page_title,
				'page_description' => $res['question'], // use question as description
				'page_modified_when' => $func_page_modified_when,
				'page_modified_by' => $func_page_modified_by,
				'text' => $res['question'].$divider.$res['answer'].$divider,
				'max_excerpt_num' => $max_excerpt_num
			);
			if(print_excerpt2($mod_vars, $func_vars)) {
				$result = true;
			}
		}
	}
	
	// now fetch category-titles only
	$table = TABLE_PREFIX."mod_faqmaker_topics";
	$query = $func_database->query("
		SELECT topic_name
		FROM $table
		WHERE section_id='$func_section_id'
	");
	// now call print_excerpt() for every single category, too
	if($query->numRows() > 0) {
		while($res = $query->fetchRow()) {
			$mod_vars = array(
				'page_link' => $func_page_link,
				'page_link_target' => "#wb_section_$func_section_id",
				'page_title' => $func_page_title,
				'page_description' => $func_page_description,
				'page_modified_when' => $func_page_modified_when,
				'page_modified_by' => $func_page_modified_by,
				'text' => $res['topic_name'].$divider,
				'max_excerpt_num' => $max_excerpt_num
			);
			if(print_excerpt2($mod_vars, $func_vars)) {
				$result = true;
			}
		}
	}
	return $result;
}

?>
