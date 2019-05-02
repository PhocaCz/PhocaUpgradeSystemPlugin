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
        $view 		= $app->input->get('view', '');
        $option 	= $app->input->get('option', '');

		if ($format == 'feed') { return true;}
		if ($format == 'pdf') { return true;}
		if ($format == 'raw') { return true;}
		if ($format == 'xml') { return true;}


		$remove_obsolete_bootstrap_js 	= $this->params->get('remove_obsolete_bootstrap_js', 0);
		$style 							= $this->params->get('template_style', '');
		if ((int)$style > 0) {
			$styleActive = $app->getTemplate(true);
			if ((int)$style != (int)$styleActive->id) {
				return true;
			}
		}

        $obsolete_bjs_option 	= $this->params->get('obsolete_bjs_option', '');
        $obsolete_bjs_view 	= $this->params->get('obsolete_bjs_view', '');


        $obsolete_bjs_optionA = array_map('trim', explode(',', $obsolete_bjs_option));// Remove spaces
        $obsolete_bjs_viewA = array_map('trim', explode(',', $obsolete_bjs_view));
        $obsolete_bjs_optionA = array_filter($obsolete_bjs_optionA);// Remove empty values from array
        $obsolete_bjs_viewA = array_filter($obsolete_bjs_viewA);



		$headers = JFactory::getApplication()->getHeaders();



		/*$option = $app->input->get('option', '', 'string');
		if ($option != 'com_content') { return true;}

		$view = $app->input->get('view', '', 'string');
		if ($view != 'article') { return true;}*/

		// STYLE
		$old = array('class="btn ', 'class="btn"', 'class="button"', 'icon-print', 'icon-envelope', 'icon-cog', 'data-toggle="dropdown"', 'input-prepend', 'inpunt-append', 'input-small', 'icon-plus', 'icon-minus', 'icon-user', 'icon-lock', 'add-on', 'input-xlarge', 'input-sm', 'alert-error', 'alert alert-message');

		$new = array('class="btn btn-default ', 'class="btn btn-default"', 'class="btn btn-default"', 'glyphicon glyphicon-print', 'glyphicon glyphicon-send', 'glyphicon glyphicon-cog', 'data-toggle="dropdown" data-hover="dropdown"', 'input-group', 'inpunt-group', 'input-sm', 'glyphicon glyphicon-plus', 'glyphicon glyphicon-minus', 'glyphicon glyphicon-user', 'glyphicon glyphicon-lock', 'input-group-addon', 'form-control', 'input-sm form-control', 'alert-danger', 'alert alert-info');
		$buffer = JFactory::getApplication()->getBody();
		$bufferNew = str_replace($old, $new, $buffer);

		
		// OLD BOOTSTRAP
        if ((in_array($option, $obsolete_bjs_optionA) && empty($obsolete_bjs_viewA))
            || (in_array($option, $obsolete_bjs_optionA) && in_array($view, $obsolete_bjs_viewA))
            || (empty($obsolete_bjs_optionA) && empty($obsolete_bjs_viewA))) {


            if ($remove_obsolete_bootstrap_js == 1 && $format != 'json') {


                //<script src=".../media/jui/js/bootstrap.min.js?..." type="text/javascript"></script>
                $pattern = '/(<script[^>]*src=".*(media\/jui\/js\/(bootstrap.js|bootstrap.min.js)).*"[^>]*><\/script>)/i';
                $bufferNew = preg_replace($pattern, '', $bufferNew);
            }
        }
			/*$dom=new DOMDocument('1.0', 'UTF-8');
			$dom->formatOutput			= false;
			$dom->preserveWhiteSpace	= true;
			$dom->validateOnParse		= false;
			$dom->standalone			= true;
			$dom->strictErrorChecking	= false;
			$dom->recover				= true;
			$dom->encoding				= 'UTF-8';

			@$dom->loadHTML($bufferNew, LIBXML_HTML_NODEFDTD);
			//@$dom->loadHTML(str_replace('<br>', urlencode('<br>'), $bufferNew), LIBXML_HTML_NODEFDTD );
			$xpath = new DOMXpath($dom);
			if (!empty($xpath)) {
				foreach($xpath->query('//script[@src]') as $v) {
					$src = $v->getAttribute('src');
					$pos = strpos($src, 'media/jui/js/bootstrap.min.js');
					if ($pos === false) {
					} else {
						$v->parentNode->removeChild($v);
					}
				}
				$bufferNew = $dom->saveHTML();
			}
			*/


		//$p = print_r($bufferNew, true);
		//echo '<code><pre>' . htmlspecialchars($p) . '</pre></code>';

	/*
		// Move to bottom =========================
		$bottom = '';


		$matches = array();
		$pattern = '/(<script[^>]*src=".*(media\/com_phocacart\/js\/filter\/jquery.ba-bbq.min.js).*"[^>]*><\/script>)/i';
		preg_match($pattern, $bufferNew, $matches);

		if (isset($matches[0]) && $matches[0] != '') {
			$bottom .= $matches[0] . "\n";
			$bufferNew = str_replace($matches[0], '', $bufferNew);
		}


		$matches = array();
		$pattern = '/(<script[^>]*src=".*(media\/com_phocacart\/js\/filter\/filter.js).*"[^>]*><\/script>)/i';
		preg_match($pattern, $bufferNew, $matches);

		if (isset($matches[0]) && $matches[0] != '') {
			$bottom .= $matches[0]. "\n";
			$bufferNew = str_replace($matches[0], '', $bufferNew);
		}

		$matches = array();
		$pattern = '/(<script[^>]*src=".*(media\/com_phocacart\/js\/chosen\/chosen.jquery.min.js).*"[^>]*><\/script>)/i';
		preg_match($pattern, $bufferNew, $matches);

		if (isset($matches[0]) && $matches[0] != '') {
			$bottom .= $matches[0]. "\n";
			$bufferNew = str_replace($matches[0], '', $bufferNew);
		}


		$matches = array();
		$pattern = '/(<script[^>]*src=".*(media\/com_phocacart\/js\/chosen\/chosen.required.js).*"[^>]*><\/script>)/i';
		preg_match($pattern, $bufferNew, $matches);

		if (isset($matches[0]) && $matches[0] != '') {
			$bottom .= $matches[0]. "\n";
			$bufferNew = str_replace($matches[0], '', $bufferNew);
		}


		$bufferNew = str_replace('</body>',$bottom .'</body>', $bufferNew);


		// Move to bottom =========================
	*/

		JFactory::getApplication()->setBody($bufferNew);
		return true;
	}


	public function onAfterRoute()
	{


		$force_template_option 	= $this->params->get('force_template_option', '');
		$force_template_view 	= $this->params->get('force_template_view', '');
		$force_template_id 		= $this->params->get('force_template_id', 17);
		$fId					= (int)$force_template_id;


		if ((int)$force_template_id > 0) {
			$app 		= JFactory::getApplication();
			if ($app->isClient('site')) {
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('s.*')
					->from('#__template_styles as s')
					->where('s.client_id = 0')
					->where('s.id ='.(int)$fId);
				$db->setQuery($query);

				$styles = $db->loadObjectList('id');

				$view 		= $app->input->get('view', '');
				$option 	= $app->input->get('option', '');


				if (($option == $force_template_option && $force_template_view == '')
					||($option == $force_template_option && $view == $force_template_view)) {

					if (isset($styles[$fId]->template) && isset($styles[$fId]->params)) {

						$styles[$fId]->params = new JRegistry($styles[$fId]->params);
						$app->setTemplate($styles[$fId]->template, $styles[$fId]->params);


						// check if gantry framework is already loaded
						if(class_exists('Gantry5\Loader') && class_exists('Gantry\Framework\Gantry')){

							// get the gantry instance
							$gantry = Gantry\Framework\Gantry::instance();
							// update the layout with the new current theme style
							$theme = $gantry['theme'];
							$configurations = $gantry['configurations'];
							$theme->setLayout($configurations->current());
						}
					}

				}

			}
		}
	}

}
?>
