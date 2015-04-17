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

// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
@include_once(WB_PATH .'/framework/module.functions.php');

// Load Language file
if(LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/faqmaker/languages/EN.php');
    if(file_exists(WB_PATH.'/modules/faqmaker/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/faqmaker/languages/'.LANGUAGE.'.php');
    }
}

// Get header and footer
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_faqmaker_settings WHERE section_id='$section_id'");
$fetch_settings = $query_settings->fetchRow();
?>

<script language="javascript" type="text/javascript">
function toggle_show_ask_question() {
        if( document.settings.show_ask_link_true.checked == true ) {
                document.getElementById('row_question_destination').style.display = '';
                document.getElementById('row_use_captcha').style.display = '';
		if( document.settings.question_destination_db.checked == true ) {
			document.getElementById('row_email_to').style.display = 'none';
		} else {
	                document.getElementById('row_email_to').style.display = '';
		}
        } else {
	        document.getElementById('row_question_destination').style.display = 'none';
                document.getElementById('row_use_captcha').style.display = 'none';
                document.getElementById('row_email_to').style.display = 'none';
        }
}
</script>

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

<h2><?php echo $FQTEXT['SETTINGS']; ?></h2>
<?php
// include the button to edit the optional module CSS files (function added with WB 2.7)
// Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
// Place this call outside of any <form></form> construct!!!
if(function_exists('edit_module_css')) {
        edit_module_css('faqmaker');
}
?>


<form name="settings" action="<?php echo WB_URL; ?>/modules/faqmaker/save_settings.php" method="post" style="margin: 0;">

<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">

<table class="row_a" cellpadding="2" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td colspan="2"><strong><?php echo $FQTEXT['GENERAL']; ?></strong></td>
</tr>
<tr>
	<td class="setting_name" style="width: 200px">
		<?php echo $TEXT['HEADER']; ?>:
	</td>
	<td class="setting_name">
		<textarea name="header" style="width: 98%;"><?php echo $admin->strip_slashes($fetch_settings['header']); ?></textarea>
	</td>
</tr>
<tr>
        <td class="setting_name" style="width: 200px">
                <?php echo $FQTEXT['MAIN_MESSAGE']; ?>:
        </td>
        <td class="setting_name">
                <textarea name="message" style="width: 98%; height:50px;"><?php echo $admin->strip_slashes($fetch_settings['message']); ?></textarea>
        </td>
</tr>
<tr>
	<td class="setting_name" style="width: 200px">
		<?php echo $TEXT['FOOTER']; ?>:
	</td>
	<td class="setting_name">
		<textarea name="footer" style="width: 98%;"><?php echo $admin->strip_slashes($fetch_settings['footer']); ?></textarea>
	</td>
</tr>
<tr>
        <td class="setting_name" style="width: 200px">
                <?php echo $FQTEXT['DISPLAY'] ." ". $FQTEXT['LAST_UPDATED']; ?>:
        </td>
        <td>
                <input type="radio" name="show_details" id="show_details_true" value="1"<?php if($fetch_settings['show_details'] == 1) { echo ' checked'; } ?> />
                <label for="show_details_true"><?php echo $TEXT['ENABLED']; ?></label>
                <input type="radio" name="show_details" id="show_details_false" value="0"<?php if($fetch_settings['show_details'] == 0) { echo ' checked'; } ?> />
                <label for="show_details_false"><?php echo $TEXT['DISABLED']; ?></label>
        </td>
</tr>
<tr>
        <td colspan="2"><strong><br><?php echo $FQTEXT['ASK_QUESTION'] ." ". $TEXT['SETTINGS']; ?></strong></td>
</tr>
<?php
if ($fetch_settings['show_ask_link'] == 1) {
        $show_ask_visibility = " ";
} else {
        $show_ask_visibility = "none";
}
?>
<tr>
        <td class="setting_name" style="width: 200px">
                <?php echo $FQTEXT['DISPLAY'] ." ". $FQTEXT['ASK_QUESTION'] ." ". $FQTEXT['LINK']; ?>:
        </td>
        <td>
                <input type="radio" name="show_ask_link" id="show_ask_link_true" onClick="javascript: toggle_show_ask_question();" value="1"<?php if($fetch_settings['show_ask_link'] == 1) { echo ' checked'; } ?> />
                <label for="show_ask_link_true"><?php echo $TEXT['ENABLED']; ?></label>
                <input type="radio" name="show_ask_link" id="show_ask_link_false" onClick="javascript: toggle_show_ask_question();" value="0"<?php if($fetch_settings['show_ask_link'] == 0) { echo ' checked'; } ?> />
                <label for="show_ask_link_false"><?php echo $TEXT['DISABLED']; ?></label>
        </td>
</tr>
<tr id="row_use_captcha" style="display:<?php echo $show_ask_visibility; ?>;">
        <td class="setting_name" style="width: 200px">
                <?php echo $TEXT['CAPTCHA_VERIFICATION']; ?>:
        </td>
        <td>
                <input type="radio" name="use_captcha" id="use_captcha_true" onClick="javascript: toggle_show_ask_question();" "value="1"<?php if($fetch_settings['use_captcha'] == 1) { echo ' checked'; } ?> />
                <label for="use_captcha_true"><?php echo $TEXT['ENABLED']; ?></label>
                <input type="radio" name="use_captcha" id="use_captcha_false" onClick="javascript: toggle_show_ask_question();" value="0"<?php if($fetch_settings['use_captcha'] == 0) { echo ' checked'; } ?> />
                <label for="use_captcha_false"><?php echo $TEXT['DISABLED']; ?></label>
        </td>
</tr>
<tr id="row_question_destination" style="display:<?php echo $show_ask_visibility; ?>">
        <td class="setting_name" style="width: 200px">
                <?php echo $FQTEXT['QUESTION_DESTINATION']; ?>:
        </td>
        <td class="setting_name">
		 <input type="radio" name="question_destination" id="question_destination_db" onClick="javascript: toggle_show_ask_question();" value="1"<?php if($fetch_settings['question_destination'] == 1) { echo ' checked'; } ?> />
                <label for="question_destination_db"><?php echo $FQTEXT['DATABASE']; ?></label>
                <input type="radio" name="question_destination" id="question_destination_email" onClick="javascript: toggle_show_ask_question();" value="0"<?php if($fetch_settings['question_destination'] == 0) { echo ' checked'; } ?> />
                <label for="question_destination_email"><?php echo $FQTEXT['EMAIL']; ?></label>

        </td>
</tr>
<?php
if ($fetch_settings['question_destination'] == 1 || $fetch_settings['show_ask_link'] == 0) {
        $show_email_visibility = "none";
} else {
        $show_email_visibility = " ";
}
?>
<tr id="row_email_to" style="display:<?php echo $show_email_visibility; ?>">
        <td class="setting_name" style="width: 200px">
                <?php echo $FQTEXT['EMAIL_TO']; ?>:
        </td>
        <td class="setting_name">
                <textarea name="email_to" style="width: 250px; height: 14px;"><?php echo $admin->strip_slashes($fetch_settings['email_to']); ?></textarea>
        </td>
</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left">
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;"></form>
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onClick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>

<?php
// Print admin footer
$admin->print_footer();

?>
