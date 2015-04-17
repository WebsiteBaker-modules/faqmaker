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

// Get id
if(!isset($_GET['topic_id']) OR !is_numeric($_GET['topic_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$topic_id = $_GET['topic_id'];
}

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Load Language file
if(LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/faqmaker/languages/EN.php');
    if(file_exists(WB_PATH.'/modules/faqmaker/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/faqmaker/languages/'.LANGUAGE.'.php');
    }
}

// Get info on question
$query_topic = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_faqmaker_topics WHERE topic_id='$topic_id'");
$fetch_topic = $query_topic->fetchRow();

?>

<style type="text/css">
.setting_name {
	vertical-align: top;
}
</style>

<form name="modify" action="<?php echo WB_URL; ?>/modules/faqmaker/save_topic.php" method="post" style="margin: 0;">

<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">

<table class="row_a" cellpadding="2" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td class="setting_name" width="80">
		<label for="topic_name" accesskey="n"><b><?php echo $FQTEXT['NAME']; ?>:</b></label>
	</td>
	<td class="setting_name">
		<input type="text" name="topic_name" id="topic_name" value="<?php echo $admin->strip_slashes($fetch_topic['topic_name']); ?>" style="width: 98%;" maxlength="255" />
	</td>
</tr>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left">
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;"></form>
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>

<?php

// Print admin footer
$admin->print_footer();

?>
