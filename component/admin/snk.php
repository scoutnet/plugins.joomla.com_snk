<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_snk/res/kalender.css');
$document->addStyleSheet('components/com_snk/res/style.css');
$document->addStyleDeclaration('.icon-48-snk {background-image: url(../media/com_snk/images/sn-48x48.png);}');



// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by Snk
$controller = JController::getInstance('Snk');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
