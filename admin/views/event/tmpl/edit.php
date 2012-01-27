<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
//JHtml::_('behavior.calender');

JHtml::_('behavior.formvalidation');
?>

	<div id="typo3-docbody">
		<div id="typo3-docbody-content">
			<div id="typo3-inner-docbody">
				<img src="components/com_snk/res/bg_ecke.gif" id="corner" alt="Ecke">
				<div id="typo3-inner-docbody-content">
				<h1><?=JText::_(COM_SNK_EVENT_MANAGER_LABEL)?></h1>
					<div class="community">
						<div id="snk"> 
							<script type="text/javascript">
								//<![CDATA[
								function validate_eventForm(frm) {
									var value = '';
									var errFlag = new Array();
									var _qfGroups = {};
									_qfMsg = '';

									value = frm.elements['Title'].value;
									if (value == '' && !errFlag['Title']) {
										errFlag['Title'] = true;
										_qfMsg = _qfMsg + '\n - Bitte Titel eingeben';
									}

									if (_qfMsg != '') {
										_qfMsg = 'Invalid information entered.' + _qfMsg;
										_qfMsg = _qfMsg + '\nPlease correct these fields.';
										alert(_qfMsg);
										return false;
									}
									return true;
								}
							//]]>
							</script>

							<p style="display:none">
								<span style="color:red; font-size:90%">###INFO###</span>
							</p>
<form action="<?php echo JRoute::_('index.php?option=com_snk&layout=edit&id='.(int) $this->item['ID']); ?>"
      method="post" name="adminForm" id="snk-form"  class="form-validate">
	<fieldset class="adminform">
								<div>
		<input type="hidden" name="task" value="event.edit" />
		<?php echo JHtml::_('form.token'); ?>
						<input name="jform[ID]" type="hidden" value="<?=$this->item['ID']?>">
									<table border="0">
										<tbody>
											<tr>
												<td valign="top">
													<table border="0" id="kalender-input-field-table">
														<tbody>
<? if ($this->item['ID'] != -1 && isset($this->item['Created_by']) && trim ($this->item['Created_by']) != '') { ?>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_CREATED_BY_LABEL');?></b></td>
																<td valign="top" align="left"><?=$this->item['Created_by']?></td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_CREATED_AT_LABEL');?></b></td>
																<td valign="top" align="left"><?=$this->item['Created_at']?></td>
															</tr>
<? } ?>
<? if ($this->item['ID'] != -1 && isset($this->item['Modified_by']) && trim ($this->item['Modified_by']) != '') { ?>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_LAST_MODIFIED_BY_LABEL');?></b></td>
																<td valign="top" align="left"><?=$this->item['Modified_by']?></td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_LAST_MODIFIED_AT_LABEL');?></b></td>
																<td valign="top" align="left"><?=$this->item['Modified_at']?><td>
															</tr>
<? } ?>
															<tr>
															<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_TITLE_LABEL')?></b><span class="star">*</span></td>
															<td valign="top" align="left"><input type="text" name="jform[Title]" id="jform_Title" value="<?=$this->item['Title']?>" class="inputbox required" size="40" aria-required="true" required="required" aria-invalid="false"></td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_START_DATE_LABEL');?></b><span class="star">*</span></td>
																<td valign="top" align="left">
<?echo JHtml::_('calendar',$this->item['Start_Date'],"jform[Start_Date]","jform_Start_Date",'%Y-%m-%d',array('size'=>'40','class'=>'inputbox required'));?>
</td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_START_TIME_LABEL');?></b></td>
																<td valign="top" align="left"><input type="text" name="jform[Start_Time]" id="jform_Start_Time" value="<?=$this->item['Start_Time']?>" class="inputbox validate-time" size="40" aria-invalid="false"></td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_END_DATE_LABEL');?></b></td>
																<td valign="top" align="left">
<?echo JHtml::_('calendar',$this->item['End_Date'],"jform[End_Date]","jform_End_Date",'%Y-%m-%d',array('size'=>'40','class'=>'inputbox'));?>
</td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_END_TIME_LABEL');?></b></td>
																<td valign="top" align="left"><input type="text" name="jform[End_Time]" id="jform_End_Time" value="<?=$this->item['End_Time']?>" class="inputbox validate-time" size="40" aria-invalid="false"><td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_LOCATION_LABEL');?></b></td>
																<td valign="top" align="left"><input type="text" name="jform[Location]" id="jform_Location" value="<?=$this->item['Location']?>" class="inputbox" size="40"/></td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_ORGANIZER_LABEL');?></b></td>
																<td valign="top" align="left"><input type="text" name="jform[Organizer]" id="jform_Organizer" value="<?=$this->item['Organizer']?>" class="inputbox" size="40"/></td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_TARGET_GROUP_LABEL');?></b></td>
																<td valign="top" align="left"><input type="text" name="jform[Target_Group]" id="jform_Target_Group" value="<?=$this->item['Target_Group']?>" class="inputbox" size="40"/></td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_ZIP_LABEL');?></b></td>
																<td valign="top" align="left"><input type="text" name="jform[ZIP]" id="jform_ZIP" value="<?=$this->item['ZIP']?>" class="inputbox" size="40"/></td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_LINK_TEXT_LABEL');?></b></td>
																<td valign="top" align="left"><input type="text" name="jform[Link_Text]" id="jform_Link_Text" value="<?=$this->item['Link_Text']?>" class="inputbox" size="40"/></td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_LINK_URL_LABEL');?></b></td>
																<td valign="top" align="left"><input type="text" name="jform[Link_URL]" id="jform_Link_URL" value="<?=$this->item['Link_URL']?>" class="inputbox" size="40"/></td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_INFO_LABEL');?></b></td>
																<td valign="top" align="left"><textarea name="jform[Description]" id="jform_Description" rows="5" class="inputbox" aria-invalid="false"><?=$this->item['Description']?></textarea></td>
															</tr>
														</tbody>
													</table>
												</td>
												<td>
													<table border="0">
														<tbody>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_KEYWORDS_LABEL');?></b></td>
																<td valign="top" align="left">

<?  foreach ($this->keywords as $id=>$name) { ?>
<input name="jform[keywords][<?=$id?>]" type="checkbox" value="1" id="kw_<?=$id?>" <?=(is_array($this->item['Keywords']) && array_key_exists($id,$this->item['Keywords']))?'checked':''?>><label for="kw_<?=$id?>"><?=$name?></label><br>
<?  } ?>
</td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_OWN_KEYWORDS_LABEL');?></b></td>
																<td valign="top" align="left">
<input maxlength="255" name="jform[custum_keywords][]" type="text"><br>
<input maxlength="255" name="jform[custum_keywords][]" type="text">
    </td>
															</tr>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_GROUP_OR_LEADER_LABEL');?></b></td>
																<td valign="top" align="left">

<? foreach ($this->forced_kategories as $id=>$name) {?>
	<input name="jform[keywords][<?=$id?>]" type="checkbox" value="1" id="kw_<?=$id?>" <?=(is_array($this->item['Keywords']) && array_key_exists($id,$this->item['Keywords']))?'checked':''?>><label for="kw_<?=$id?>"><?=$name?></label><br>
<? } ?>
</td>
															</tr>
														</tbody>
													</table>
												</td>
<? if (isset($this->dpsg_edu)) {?>
												<td>
													<table border="0">
														<tbody>
															<tr>
																<td align="right" valign="top"><b><?=JText::_('COM_SNK_EVENT_DPSG_EDU_LABEL');?></b></td>
																<td valign="top" align="left">

<? foreach ($this->dpsg_edu as $id=>$name) { ?>
	<input name="jform[keywords][<?=$id?>]" type="checkbox" value="1" id="kw_<?=$id?>" <?=(is_array($this->item['Keywords']) && array_key_exists($id,$this->item['Keywords']))?'checked':''?>><label for="kw_<?=$id?>"><?=$name?></label><br>
	<? } ?>

</td>
															</tr>
														</tbody>
													</table>
												</td>
	<? } ?>
											</tr>
											<tr>
												<td></td>
												<td></td>
											</tr>
										</tbody>
									</table>
									<table border="0">
										<tbody><tr>
												<td></td>
												<td align="left" valign="top"><span class="star">*</span> <?=JText::_('COM_SNK_EVENT_MANDATORY_LABEL');?></td>
											</tr>
										</tbody>
									</table>
								</div>
<?JHtml::_('calendar',"jform[Start_Date]",'',"jform_Start_Date",'%Y-%m-%d',array());?>
	</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

