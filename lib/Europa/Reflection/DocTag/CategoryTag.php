<?php

namespace Europa\Reflection\DocTag;
use Europa\Reflection\DocTag;

/**
* Represents a docblock category tag.
*
* @category Reflection
* @package  Europa
* @author   Trey Shugart <treshugart@gmail.com>
* @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
*/
class CategoryTag extends DocTag
{
	/**
	 * Return the tag object type
	 * 
	 * @return string
	 */
    public function tag()
    {
        return 'category';
    }
}
