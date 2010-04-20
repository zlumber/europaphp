<?php

/**
 * @author Trey Shugart
 */

/**
 * A view class for rendering JSON data from bound parameters.
 * 
 * @package Europa
 * @subpackage View
 */
class Europa_View_Json extends Europa_View
{
	/**
	 * Constructs the view and sets parameters.
	 * 
	 * @param array $params
	 */
	public function __construct($params = null)
	{
		$this->_params = $params;
	}
	
	/**
	 * JSON encodes the parameters on the view and returns them.
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return json_encode($this->_params);
	}
}