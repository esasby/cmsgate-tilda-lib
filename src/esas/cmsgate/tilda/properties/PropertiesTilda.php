<?php


namespace esas\cmsgate\tilda\properties;


use esas\cmsgate\bridge\properties\PropertiesBridge;
use esas\cmsgate\properties\SandboxProperties;
use esas\cmsgate\properties\ViewProperties;

abstract class PropertiesTilda extends PropertiesBridge implements
    SandboxProperties,
    ViewProperties
{
    public abstract function isSandbox();
}