<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 16.02.2018
 * Time: 12:47
 */

namespace esas\cmsgate\protocol;

use esas\cmsgate\utils\Logger;

class TildaRq
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * EposRq constructor.
     */
    public function __construct()
    {
        $this->logger = Logger::getLogger(get_class($this));
    }
}