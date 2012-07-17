<?php
/**
 * Snk entry point file for Snk Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

// Create the controller
$controller = JController::getInstance('Snk');
$controller->execute( JRequest::getVar('task'));
$controller->redirect();

?>
