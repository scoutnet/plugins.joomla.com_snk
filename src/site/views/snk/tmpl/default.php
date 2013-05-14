<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>


<?
$document = & JFactory::getDocument();
JHTML::_('behavior.mootools');

$details = array();
?>
 
<?php JHTML::_('stylesheet', 'kalender.css', 'components/com_snk/assets/'); ?>


<h1>ScoutNet-Kalender <?echo $this->kalenders[0]->get_Name();?></h1>
<div id="snk-<?echo $this->kalenders[0]['ID']?>" class="snk">
	<? if (count($this->optionalKalenders) > 0) {?>
	<div id="snk-stammesAuswahl">
		Zusätzlich diese Kalender Anzeigen:<br> 

		<? foreach ($this->kalenders as $kal) {$addids[] = $kal['ID'];}?>
		<form action="" method="get">
			<? if (isset($option) && trim($option) != '') { ?> <input type="hidden" name="option" value="<?echo $option;?>"><?}?>
			<input type="hidden" name="Itemid" value="<? echo JRequest::getVar('Itemid');?>">
			<input type="hidden" name="view" value="<? echo JRequest::getVar('view');?>">
		<? foreach ($this->optionalKalenders as $kalender) {?>
			<input onchange="form.submit();" <?echo in_array($kalender['ID'],$addids)?"checked":""; ?> name="addids[]" value="<?echo $kalender['ID'];?>" id="add_id_<?echo $kalender['ID'];?>" title="<?echo $kalender->get_Name();?>" type="checkbox" /><label for="add_id_<?echo $kalender['ID'];?>"><?echo $kalender->get_Name();?></label>
		<?}?>
		</form>
	</div>
	<?}?>
	<div class="snk-body">
		<div class="snk-termine">
			<table>
				<tr class="snk-headings-row"> 
				<? if (count($this->kalenders) > 1 ) {?><th class="snk-eintrag-ebene-ueberschrift">Ebene</th><?}?>
					<th class="snk-eintrag-datum-ueberschrift">Datum</th>
					<th class="snk-eintrag-zeit-ueberschrift">Zeit</th>
					<th class="snk-eintrag-titel-ueberschrift">Titel</th>
					<th class="snk-eintrag-stufen-ueberschrift">Stufen</th>
					<th class="snk-eintrag-kategorien-ueberschrift">Kategorien</th>
				</tr>
				<? foreach ($this->events as $event) { 
					$current_monat = gmstrftime("%B '%y",$event['Start']);
					if ($old_monat != $current_monat) {
						$old_monat = $current_monat;
					?>
					<tr>
						<th colspan="6" class="snk-monat-heading"><?echo $current_monat;?></th>
					</tr>
					<? } ?>

					<tr> 
						<? if (count($this->kalenders) > 1 ) {?><td class="snk-eintrag-ebene"><?echo str_replace(" ","&nbsp;",$event['Kalender']->get_long_Name())?></th><?}?>
						<td class="snk-eintrag-datum"><?
							$datum = substr(gmstrftime("%A",$event['Start']),0,2).",&nbsp;".gmstrftime("%d.%m.",$event['Start']);

							if (isset($event['End']) && gmstrftime("%d%m%Y",$event['Start']) != gmstrftime("%d%m%Y",$event['End']) ) {
								$datum .= "&nbsp;-&nbsp;";
								$datum .= substr(gmstrftime("%A",$event['End']),0,2).",&nbsp;".gmstrftime("%d.%m.",$event['End']);
							}   
							echo $datum;
						?></td>
						<td class="snk-eintrag-zeit"><?
							$zeit = ""; 
							if ($event['All_Day'] != 1) {
								$zeit = gmstrftime("%H:%M",$event['Start']);
								if (isset($event['End']) && gmstrftime("%H%M",$event['Start']) != gmstrftime("%H%M",$event['End']) ) {
									$zeit .= "&nbsp;-&nbsp;";
									$zeit .= gmstrftime("%H:%M",$event['End']);
								}   
							}   
							echo $zeit;
						?></td>
						<td class="snk-eintrag-titel"><?
							$showDetails = trim($event['Description']).trim($event['ZIP']).trim($event['Location']).trim($event['Organizer']).trim($event['Target_Group']).trim($event['URL']);
							echo  ($showDetails?'<a href="#" class="snk-termin-link" onclick="snk['.$event['ID'].'].toggle();return false;">':'').nl2br(htmlentities(utf8_Decode($event['Title']))).($showDetails?'</a>':'');
						?></td>
						<td class="snk-eintrag-stufe"><?echo $event->get_Stufen_Images()?></td>
						<td class="snk-eintrag-kategorien"><?echo join(", ",$event['Keywords']);?></td>
					</tr>

					<? if ($showDetails) { 
					$details[] = $event['ID'];
					?>
					<tr id="snk-termin-<?echo $event['ID'];?>-tr" class="snk-termin-infos">
						<td colspan="6">
							<div id="snk-termin-<?echo $event['ID'];?>">
							<dl>
							<? if (trim($event['Description'])) { ?><dt class="snk-eintrag-beschreibung">Beschreibung</dt><dd><?echo trim($event['Description']);?></dd><? } ?>
							<? if (trim($event['ZIP']).trim($event['Location'])) { ?><dt class="snk-eintrag-ort">Ort</dt><dd><?echo trim($event['ZIP'])." ".trim($event['Location']);?></dd><? } ?>
							<? if (trim($event['Organizer'])) { ?><dt class="snk-eintrag-veranstalter">Veranstalter</dt><dd><?echo trim($event['Organizer']);?></dd><? } ?>
							<? if (trim($event['Target_Group'])) { ?><dt class="snk-eintrag-zielgruppe">Zielgruppe</dt><dd><?echo trim($event['Target_Group']);?></dd><? } ?>
							<? if (trim($event['URL'])) { ?><dt class="snk-eintrag-url-label">Link</dt><dd class="snk-eintrag-url"><a target="_blank" href="<?echo htmlentities(utf8_decode($event['URL']));;?>"><?echo (trim($event['URL_Text'])?htmlentities(utf8_decode($event['URL_Text'])):htmlentities(utf8_decode($event['URL'])));?></a></dd><? } ?>
							<dt class="snk-eintrag-autor">Eingetragen von</dt><dd><?echo $event->get_Author_name()?></dd>
							</dl><br>
							</div>
						</td>
					</tr>
					<? } ?>



				<? } ?>
			</table>
		</div>
	</div>
	<div class="snk-footer">
		<div class="snk-hinzufuegen" style="visibility:hidden">
			<a href="https://www.scoutnet.de/community/kalender/events.html?task=create&amp;SSID=<? echo $this->kalenders[0]['ID']?>" target="_top">Termin&nbsp;hinzufügen</a>
		</div>
		<div class="snk-powered-by">
			Powered by <span><a href="http://kalender.scoutnet.de/" target="_top">ScoutNet.DE</a></span>
		</div>
	</div>
</div>
<?
$js = "var snk = Array();
window.onload = function() {\n";
foreach ($details as $event) {
	$js .= "snk[".$event."] = new Fx.Slide('snk-termin-".$event."', {height: true, opacity: true, duration: 500});
	snk[".$event."].hide();\n";
}
$js .= "}";
$document->addScriptDeclaration($js); ?>
