<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 04.07.2019
 * Time: 12:07
 */

namespace esas\cmsgate\tilda\controllers;


use esas\cmsgate\controllers\Controller;
use esas\cmsgate\Registry;
use esas\cmsgate\wrappers\ConfigWrapper;
use Exception;

abstract class ControllerTilda extends Controller
{
    /**
     * @var ConfigWrapper
     */
    protected $configWrapper;


    /**
     * ControllerTilda constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->configWrapper = Registry::getRegistry()->getConfigWrapper();
    }

    public function checkOrderWrapper(&$orderWrapper) {
        if (is_numeric($orderWrapper)) //если передан orderId
            $orderWrapper = Registry::getRegistry()->getOrderWrapper($orderWrapper);
        if (empty($orderWrapper) || empty($orderWrapper->getOrderNumber())) {
            throw new Exception("Incorrect method call! orderWrapper is null or not well initialized");
        }
        return $orderWrapper;
    }
}