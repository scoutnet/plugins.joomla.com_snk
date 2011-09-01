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
$start_date = substr(strftime("%A",$item['Start']),0,2).",&nbsp;".strftime("%d.%m.%Y",$item['Start']);
$end_date = '';
							        
if (isset($item['End']) && strftime("%d%m%Y",$item['Start']) != strftime("%d%m%Y",$item['End']) ) {
	$end_date = substr(strftime("%A",$item['End']),0,2).",&nbsp;".strftime("%d.%m.%Y",$item['End']);
}
    
$start_time = '';
$end_time = '';
if ($item['All_Day'] != 1) { 
	$start_time = strftime("%H:%M",$item['Start']);

	if (isset($item['End']) && strftime("%H%M",$item['Start']) != strftime("%H%M",$item['End']) ) {
		$end_time = strftime("%H:%M",$item['End']);
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
<?=$item['Created_By']?>, <?=strftime("%d.%m.%Y %H:%M",$item['Created_At'])?>
</td>
<td>
<? if (trim($item['Last_Modified_By']) != '') { ?>
<?=$item['Last_Modified_By']?>, <?=strftime("%d.%m.%Y %H:%M",$item['Last_Modified_At'])?>
<? } else {?>-<?}?>
</td>
</tr>
<?php endforeach; ?>
