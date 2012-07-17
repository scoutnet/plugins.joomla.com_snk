<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<?php 
try {
	$text = "Your account is not connected to any Scoutnet User yet. Please use the Login Button to connect your Scoutnet User to this Joomla user.";
	$text .= $this->sn->get_scoutnetConnectLoginButton(JURI::base().'index.php?option=com_snk',true); 
	echo $text;
} catch (com_snk_sn_Exception_MissingConfVar $e) {
 echo $e->getMessage();
}

?>
