<?php


namespace esas\cmsgate\tilda\service;


use esas\cmsgate\bridge\security\CmsAuthService;
use esas\cmsgate\bridge\service\ServiceProviderBridge;
use esas\cmsgate\tilda\security\CmsAuthServiceTilda;

class ServiceProviderTilda extends ServiceProviderBridge
{
    public function getServiceArray() {
        $services = parent::getServiceArray();
        $services[CmsAuthService::class] = new CmsAuthServiceTilda();
        return $services;
    }
}