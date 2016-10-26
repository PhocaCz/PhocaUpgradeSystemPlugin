<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

class plgSystemPhocaUpgrade extends JPlugin
{	
	
	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onAfterRender() {
		
		$app 	= JFactory::getApplication();
		if ($app->getName() != 'site') { return true;}
		
		$format = $app->input->get('format', '', 'string');
		if ($format == 'feed') { return true;}
		
		$style = $this->params->get('template_style', '');
		if ((int)$style > 0) {
			$styleActive = $app->getTemplate(true);
			if ((int)$style != (int)$styleActive->id) {
				return true;
			}
		}
		/*$option = $app->input->get('option', '', 'string');
		if ($option != 'com_content') { return true;}
		
		$view = $app->input->get('view', '', 'string');
		if ($view != 'article') { return true;}*/

		$old = array('class="btn ', 'class="btn"', 'class="button"', 'icon-print', 'icon-envelope', 'icon-cog', 'data-toggle="dropdown"', 'input-prepend', 'inpunt-append', 'input-small', 'icon-plus', 'icon-minus', 'icon-user', 'icon-lock', 'add-on', 'input-xlarge', 'input-sm', 'alert-error', 'alert alert-message');
		
		$new = array('class="btn btn-default ', 'class="btn btn-default"', 'class="btn btn-default"', 'glyphicon glyphicon-print', 'glyphicon glyphicon-send', 'glyphicon glyphicon-cog', 'data-toggle="dropdown" data-hover="dropdown"', 'input-group', 'inpunt-group', 'input-sm', 'glyphicon glyphicon-plus', 'glyphicon glyphicon-minus', 'glyphicon glyphicon-user', 'glyphicon glyphicon-lock', 'input-group-addon', 'form-control', 'input-sm form-control', 'alert-danger', 'alert alert-info');
		$buffer = JResponse::getBody();
		$bufferNew = str_replace($old, $new, $buffer);
		JResponse::setBody($bufferNew);
		return true;
	}
}
?>