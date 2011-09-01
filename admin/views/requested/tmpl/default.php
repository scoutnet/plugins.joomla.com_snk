<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<?php 
try {
	$text = "You have requested Permissions to change the Data in this Calender. Please be patient, until the admin onored your request.";
	echo $text;
} catch (com_snk_sn_Exception_MissingConfVar $e) {
 echo $e->getMessage();
}

?>
