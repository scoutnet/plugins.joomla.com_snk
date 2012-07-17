<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_SNK_SNK_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JText::_('COM_SNK_SNK_HEADING_DATE'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_SNK_SNK_HEADING_TITLE'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_SNK_SNK_HEADING_CREATED'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_SNK_SNK_HEADING_LAST_MODIFIED'); ?>
	</th>
</tr>
