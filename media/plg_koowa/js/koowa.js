/**
 * @version		$Id$
 * @category    Koowa
 * @package     Koowa_Media
 * @subpackage  Javascript
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Global Koowa namespace
 */
var Koowa = {};

/**
 * Table class
 */
Koowa.Table = {
	sorting: function(order, dir, action) 
	{
		var form = document.adminForm;
	
		form.filter_order.value 	= order;
		form.filter_direction.value	= dir;
		submitform( action );
	}
}


/**
 * Form class
 */
Koowa.Form = 
{
	
	addField: function(name, value)
	{
		var el = document.createElement('input');
		el.setAttribute('name', name)
		el.setAttribute('value', value)
		el.setAttribute('type', 'hidden');
		document.adminForm.appendChild(el);	
	},
	
	/**
	 * Submit the grid's form
	 *
	 * @param	Method	[get|post]
	 */
	submit: function(method)
	{
		var f = document.adminForm;
		f.method = method.toLowerCase();
		f.submit();
	}
} 
 
/**
 * Grid class
 */
Koowa.Grid = 
{
	order: function (row_id, change) 
	{
		var form = document.adminForm;
		form.id.value= row_id;
		form.order_change.value	= change;
		form.action.value = 'order';
		form.submit();
	},
	
	
	/**
	 * Find the first selected checkbox id in the grid
	 *
	 * @return 	integer	The item's id or false if no item is selected
	 */
	getFirstSelected: function()
	{
		// check if there's an item selected
		if(!document.adminForm.boxchecked.value) return false;
		
		var inputs = $(document.adminForm).getElements('input[name^=cid]');
		for (var i=0; i < inputs.length; i++) {
		   if (inputs[i].checked) {
		      return inputs[i].value;
		   }
		}
	}
}

function $get(key, defaultValue) 
{
	return location.search.get(key, defaultValue);
}	

String.extend(
{
 
	get : function(key, defaultValue)
	{
		if(key == "") return;
	
		var uri   = this.parseUri();
		if($defined(uri['query'])) 
		{
			var query = uri['query'].parseQueryString();
			if($defined(query[key])) {
				return query[key]
			}
		}
		
		return defaultValue;
	},
	
	parseQueryString: function() 
	{
		var vars = this.split(/[&;]/);
		var rs = {};
		if (vars.length) vars.each(function(val) {
			var keys = val.split('=');
			if (keys.length && keys.length == 2) rs[keys[0]] = encodeURIComponent(keys[1]);
		});
		
		return rs;
	},
 
	parseUri: function()
	{
		var bits = this.match(/^(?:([^:\/?#.]+):)?(?:\/\/)?(([^:\/?#]*)(?::(\d*))?)((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[\?#]|$)))*\/?)?([^?#\/]*))?(?:\?([^#]*))?(?:#(.*))?/);
		return (bits)
			? bits.associate(['uri', 'scheme', 'authority', 'domain', 'port', 'path', 'directory', 'file', 'query', 'fragment'])
			: null;
	}
});