<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
/**
 * ControllerBase
 * This is the base controller for all controllers in the application
 *
 * @property \App\Library\Auth auth
 */
class ControllerBase extends Controller
{
   /**
     * 
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        // if (!$this->session->has('IS_LOGIN'))
    }
}
