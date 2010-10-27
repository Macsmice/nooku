<?php
/**
 * @version		$Id: default.php 2057 2010-05-15 20:48:00Z johanjanssens $
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Template Helper Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @uses   		KFactory
 */
class KTemplateHelperDate extends KTemplateHelperAbstract
{
	/**
	 * Returns formated date according to current local and adds time offset
	 *
	 * @param	string	A date in ISO 8601 format or a unix time stamp
	 * @param	string	format optional format for strftime
	 * @returns	string	formated date
	 * @see		strftime
	 */
	public function format($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'date'   	 => '',
			'format'	 => '%A, %d %B %Y',
			'gmt_offset' => 0,
 		));
 		
 		if(!is_numeric($config->date)) {
 			$config->date =  strtotime($config->date);
 		}
 		
		return strftime($config->format, $config->date + 3600 * $config->offset);
	}
}
