<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->events as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item['ID']; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item['ID']); ?>
		</td>
<td>
			<?php 
$start_date = substr(gmstrftime("%A",$item['Start']),0,2).",&nbsp;".gmstrftime("%d.%m.%Y",$item['Start']);
$end_date = '';
							        
if (isset($item['End']) && gmstrftime("%d%m%Y",$item['Start']) != gmstrftime("%d%m%Y",$item['End']) ) {
	$end_date = substr(gmstrftime("%A",$item['End']),0,2).",&nbsp;".gmstrftime("%d.%m.%Y",$item['End']);
}
    
$start_time = '';
$end_time = '';
if ($item['All_Day'] != 1) { 
	$start_time = gmstrftime("%H:%M",$item['Start']);

	if (isset($item['End']) && gmstrftime("%H%M",$item['Start']) != gmstrftime("%H%M",$item['End']) ) {
		$end_time = gmstrftime("%H:%M",$item['End']);
	}
}
    
$date_with_time = $start_date.(($start_time != '')?',&nbsp;'.$start_time:'').(($end_date.$end_time != '')?' '.JText::_('COM_SNK_EVENT_TO').' ':'').($end_date != ''?$end_date:'').(($end_date != '' && $end_time != '')?',&nbsp;':'').($end_time != ''?$end_time:'');
    

echo $date_with_time;
?>
</td>
		<td>
			<?php echo $item['Title']; ?>
		</td>
<td>
<?=$item['Created_By']?>, <?=gmstrftime("%d.%m.%Y %H:%M",$item['Created_At'])?>
</td>
<td>
<? if (trim($item['Last_Modified_By']) != '') { ?>
<?=$item['Last_Modified_By']?>, <?=gmstrftime("%d.%m.%Y %H:%M",$item['Last_Modified_At'])?>
<? } else {?>-<?}?>
</td>
</tr>
<?php endforeach; ?>
