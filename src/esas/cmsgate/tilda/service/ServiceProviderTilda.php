<?php


namespace esas\cmsgate\tilda\service;


use esas\cmsgate\bridge\service\ServiceProviderBridge;

class ServiceProviderTilda extends ServiceProviderBridge
{
    public function getServiceArray() {
        $services = parent::getServiceArray();
        return $services;
    }
}