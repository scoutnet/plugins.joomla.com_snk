<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item['ID']; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item['ID']); ?>
		</td>
		<td>
			<?php echo $item['Title']; ?>
		</td>
<td>
<?php print_r($item) ?>
</td>
	</tr>
<?php endforeach; ?>
