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

// Get header and footer
$query_submitted = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_faqmaker_submitted_questions WHERE section_id='$section_id'");
$fetch_submitted = $query_submitted->fetchRow();
?>

<style type="text/css">
.setting_name {
	vertical-align: top;
}
</style>

<?php
// check if backend.css file needs to be included into the <body></body> of modify.php
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/faqmaker/backend.css")) {
        echo '<style type="text/css">';
        include(WB_PATH .'/modules/faqmaker/backend.css');
        echo "\n</style>\n";
}
?>

<h2><?php echo $FQTEXT['SUBMITTED_QUESTIONS']; ?></h2>
                <table cellpadding="2" cellspacing="0" border="0" width="100%">
		<tr><td><h3><?php echo $TEXT['NAME']; ?></h3></td><td><h3><?php echo $FQTEXT['EMAIL_ADDRESS']; ?></h3></td><td><h3><?php echo $FQTEXT['QUESTION']; ?></h3></td><td><h3><?php echo $TEXT['DATE']; ?></h3></td><td></td></tr>
                <?php
$query_submitted = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_faqmaker_submitted_questions WHERE section_id='$section_id' ORDER BY submitted_when ASC");
                if($query_submitted->numRows() > 0) {
                        $row = 'a';
                        while($question = $query_submitted->fetchRow()) {
                        ?>
                        <tr class="row_<?php echo $row; ?>" height="20">
                                <td width="16%">
					<?php echo $admin->strip_slashes(strip_tags($question['name'])); ?>                              
                                </td>
				<td width="16%">
					<?php echo $admin->strip_slashes(strip_tags($question['email'])); ?>
				</td>
                                <td width="48%">
                                        <?php echo $admin->strip_slashes(strip_tags($question['submitted_question'])); ?>
                                </td>
				<td width="17%">
					<?php echo gmdate(DATE_FORMAT, $question['submitted_when']); ?>
				</td>
				<td width="3%">
                                        <a href="#" onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/faqmaker/delete_submitted_question.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&submission_id=<?php echo $question['submission_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="X" />
					</a>
				</td>
			</tr>
                        <!-- question end -->
                        <?php
                        // Alternate row color
                        if($row == 'a') { $row = 'b'; } else { $row = 'a'; }
                }
        } else {
                echo '<tr><td><i>'.$TEXT['NONE_FOUND'].'</i></td></tr>';
        }
        ?>
</table>

<table cellpadding="10" cellspacing="0" border="0" width="100%">
<tr>
	<td align="center">
		<input type="button" value="<?php echo $TEXT['BACK']; ?>" onClick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>

<?php
// Print admin footer
$admin->print_footer();

?>
