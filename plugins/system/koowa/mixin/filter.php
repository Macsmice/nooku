<?php
/**
 * @version		$Id:proxy.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Mixin
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Filter Command
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Mixin
 */
class KMixinFilter extends KMixinAbstract implements KPatternCommandInterface 
{
 	/**
 	 * Array of filters to be executed on before commands
 	 * 
 	 * $var array
 	 */
	protected $_filters_before = array();
	
	/**
 	 * Array of filters to be executed on after commands
 	 * 
 	 * $var array
 	 */
	protected $_filters_after = array();

	/**
	 * Object constructor
	 *
	 * @param	array 	An optional associative array of configuration settings.
	 * Recognized key values include 'mixer' (this list is not meant to be comprehensive).
	 */
	public function __construct(array $options = array())
	{
		// Initialize the options
        $options = $this->_initialize($options);
        
        parent::__construct($options);
		
		if(is_null($options['command_chain'])) {
			throw new KMixinException('command_chain [KPatternCommandChain] option is required');
		}
	
		//Enque the filter in the mixer's command chain
		$options['command_chain']->enqueue($this, 2);
	}
	
	/**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize(array $options)
    {
        parent::_initialize($options);
    	
    	$defaults = array(
    		'command_chain'	=> null,
    	);

        return array_merge($defaults, $options);
    }
    
	/**
	 * Command handler
	 * 
	 * @param string  The command name
	 * @param mixed   The command arguments
	 *
	 * @return boolean
	 */
	public function execute( $name, $args) 
	{
		$parts = explode('.', $name);
		
		$filters = ($parts[1] == 'before') ? $this->_filters_before :$this->_filters_after;
					
		if (isset($filters[$parts[2]]))
		{ 
			$filters = $filters[$parts[2]];
			
   		 	foreach($filters as $filter) 
   		 	{
        		if ( $this->_mixer->$filter($args) === false) {
        			return false;
        		}
   		 	}
		}
		
		return true;
	}
	
	/**
 	 * Get the registered before filters for a method
 	 *  	  
 	 * @param  	string	The method to return the filters for
 	 * @return  array	A list of registered filters	
 	 */
	public function getFiltersBefore($method)
	{
		$result = array();
		$method = strtolower($method);
		
		if (isset($this->_filters_before[$method]) ) {
       	 	$result = $this->_filters_before[$method];
		}
		
    	return $result;
	}
	
	/**
 	 * Get the registered after filters for a method
 	 *  	  
 	 * @param  	string	The method to return the filters for
 	 * @return  array	A list of registered filters	
 	 */
	public function getFiltersAfter($method)
	{
		$result = array();
		$method = strtolower($method);
		
		if (isset($this->_filters_after[$method]) ) {
       	 	$result = $this->_filters_after[$method];
		}
		
    	return $result;
	}
	
	/**
 	 *  Registers a single filter or an array of filters
 	 * 
 	 * @param  	string			The method to register the filter too
 	 * @param 	string|array	A single filter or an array of filters to register
 	 * @return KMixinFilter
 	 */
	public function registerFilterBefore($method, $filters)
	{
		$method = strtolower($method);
		
		if (!isset($this->_filters_before[$method]) ) {
       	 	$this->_filters_before[$method] = array();	
		}

    	$this->_filters_before[$method] = array_unique(array_merge($this->_filters_before[$method], (array) $filters));
		return $this;
	}
	
	/**
 	 * Unregister a single filter or an array of filters
 	 * 
 	 * @param  	string			The method to unregister the filters from
 	 * @param 	string|array	A single filter or an array of filters to unregister
 	 * @return KMixinFilter
 	 */
	public function unregisterFilterBefore($method, $filters)
	{
		$method = strtolower($method);
		
		if (isset($this->_filters_before[$method]) ) {
       	 	$this->_filters_before[$method] = array_diff($this->_filters_before[$method], (array) $filters);
		}

		return $this;
	}
	
	/**
 	 * Registers a single filter or an array of filters
 	 * 
 	 * @param  	string			The method to register the filter too
 	 * @param 	string|array	A single filter or an array of filters to register
 	 * @return KMixinFilter
 	 */
	public function registerFilterAfter($method, $filters)
	{
		$method = strtolower($method);
		
		if (!isset($this->_filters_after[$method]) ) {
       	 	$this->_filters_after[$method] = array();	
		}

    	$this->_filters_after[$method] = array_unique(array_merge($this->_filters_after[$method], (array) $filters));
    	return $this;
	}
	
	/**
 	 * Unregister a single filter or an array of filters
 	 * 
 	 * @param  	string			The method to unregister the filters from
 	 * @param 	string|array	A single filter or an array of filters to unregister
 	 * @return KMixinFilter
 	 */
	public function unregisterFilterAfter($method, $filters)
	{
		$method = strtolower($method);
		
		if (isset($this->_filters_after[$method]) ) {
       	 	$this->_filters_after[$method] = array_diff($this->_filters_after[$method], (array) $filters);
		}

		return $this;
	}
}