<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Template Select Helper
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class KTemplateHelperPaginator extends KTemplateHelperSelect
{
	/**
	 * Render item pagination
	 * 
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 * @see  	http://developer.yahoo.com/ypatterns/navigation/pagination/
	 */
	public function pagination($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'total'   => 0,
			'state'   => null,
			'display' => 4
		));
		
		$html = '';
		$html .= '<style src="media://lib_koowa/css/koowa.css" />';
	
		// Paginator object
		$paginator = KFactory::tmp('lib.koowa.model.paginator')->setData(
				array('total'  => $config->total,
					  'offset' => $config->state->offset,
					  'limit'  => $config->state->limit,
					  'display' => $config->display)
		);

		// Get the paginator data
		$list = $paginator->getList();

		$html .= '<div class="-koowa-pagination">';
		$html .= '<div class="limit">'.JText::_('Display NUM').' '.$this->limit($config->toArray()).'</div>';
		$html .=  $this->_pages($list);
		$html .= '<div class="count"> '.JText::_('Page').' '.$paginator->current.' '.JText::_('of').' '.$paginator->count.'</div>';
		$html .= '</div>';

		return $html;
	}
	
	/**
	 * Render a select box with limit values
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return 	string	Html select box
	 */
	public function limit($config = array())
	{
		$config = new KConfig($config);
		
		$html = '';
		
		$html .= KTemplateHelper::factory('behavior')->mootools();
		$html .= 
			"<script>
				window.addEvent('domready', function(){ $$('select.-koowa-redirect').addEvent('change', function(){ window.location = this.value;}); });
			</script>";
		

		// Modify the url to include the limit
		$url   = clone KRequest::url();
		$query = $url->getQuery(true);
		$offset = array_key_exists('offset', $query) ? $query['offset'] : 0;

		$selected = '';
		foreach(array(10 => 10, 20 => 20, 50 => 50, 100 => 100, 0 => 'all' ) as $value => $text)
		{
			$query['limit'] = $value;
			$query['offset']= $value ? $value * floor($offset/$value) : 0;
			$redirect       = (string) $url->setQuery($query);

			if($value == $config->state->limit) {
				$selected = $redirect;
			}

			$options[] = $this->option(array('text' => $text, 'value' => $redirect));
		}

		$attribs = array('class' => '-koowa-redirect');
		$html .= $this->optionlist(array('options' => $options, 'name' => 'limitredirect', 'attribs' => $attribs, 'selected' => $selected));
		return $html;
	}

	/**
	 * Render a list of pages links
	 *
	 * @param	araay 	An array of page data
	 * @return	string	Html
	 */
	protected function _pages($pages)
	{
		$html = '<ul class="pages">';

		$html .= '<li class="first">&laquo; '.$this->_link($pages['first'], 'First').'</li>';
		$html .= '<li class="previous">&lt; '.$this->_link($pages['previous'], 'Prev').'</li>';

		foreach($pages['pages'] as $page) {
			$html .= '<li>'.$this->_link($page, $page->page).'</li>';
		}

		$html .= '<li class="next">'.$this->_link($pages['next'], 'Next').' &gt;</li>';
		$html .= '<li class="previous">'.$this->_link($pages['last'], 'Last').' &raquo;</li>';

		$html .= '</ul>';
		return $html;
	}

	/**
	 * Render a page link
	 *
	 * @param	object The page data
	 * @param	string The link title
	 * @return	string	Html
	 */
	protected function _link($page, $title)
	{
		$url   = clone KRequest::url();
		$query = $url->getQuery(true);

		$query['limit']  = $page->limit;
		$query['offset'] = $page->offset;

		$class = $page->current ? 'class="active"' : '';

		if($page->active && !$page->current) {
			$html = '<a href="'.(string) $url->setQuery($query).'" '.$class.'>'.JText::_($title).'</a>';
		} else {
			$html = '<span '.$class.'>'.JText::_($title).'</span>';
		}

		return $html;
	}
}