<?php

namespace Europa\Filter;
use ArrayIterator;
use IteratorAggregate;
use UnexpectedValueException;

/**
 * Filters a value using more than one filter.
 * 
 * @category Filters
 * @package  Europa
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
abstract class FilterArrayAbstract implements IteratorAggregate
{
    /**
     * The array of filters to use.
     * 
     * @var array
     */
    private $filters = [];

    /**
     * Sets up the filter array. For each item in the configuration, a filter is added and the value used as the constructor configuration.
     * 
     * @param mixed $config The filter array configuration.
     * 
     * @return FilterArrayAbstract
     */
    public function __construct($config = [])
    {
        foreach ($config as $filter => $filterConfig) {
            $this->add(new $filter($filterConfig));
        }
    }

    /**
     * Adds a filter.
     * 
     * @param callable $filter The filter to add.
     * 
     * @return FilterArrayAbstract
     */
    public function add(callable $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * Removes all of the filters.
     * 
     * @return FilterArrayAbstract
     */
    public function clear()
    {
        $this->filters = [];
        return $this;
    }
    
    /**
     * Returns all filters on the filter array.
     * 
     * @return array
     */
    public function getIterator()
    {
        return new ArrayIterator($this->filters);
    }
}