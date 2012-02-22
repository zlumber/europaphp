<?php

namespace Europa\Event;

/**
 * Represents a bound event callback.
 * 
 * @category Events
 * @package  Europa
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
class CallbackEvent implements EventInterface
{
    /**
     * The callable callback that will be triggered.
     * 
     * @var mixed
     */
    private $callback;
    
    /**
     * Constructs a new callback event.
     * 
     * @param mixed $callback The callable callback to trigger.
     * 
     * @return \Europa\Event\CallbackEvent
     */
    public function __construct($callback)
    {
        if (!is_callable($callback, true)) {
            throw new \InvalidArgumentException('Passed callback is not callable.');
        }
        $this->callback = $callback;
    }
    
    /**
     * Calls the callback passing the current object data into it.
     * 
     * @param \Europa\Event\DataInterface $data The data passed to the event at the time of triggering.
     * 
     * @return mixed
     */
    public function trigger(DataInterface $data)
    {
        return call_user_func($this->callback, $data);
    }
}