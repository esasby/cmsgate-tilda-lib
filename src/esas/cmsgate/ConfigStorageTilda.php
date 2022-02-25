<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 13:14
 */

namespace esas\cmsgate;

class ConfigStorageTilda extends ConfigStorageCloud
{
    public function getConstantConfigValue($key)
    {
        switch ($key) {
            case ConfigFields::orderStatusPending():
            case ConfigFields::orderPaymentStatusPending():
                return "cmsgate_pending";
            case ConfigFields::orderStatusPayed():
            case ConfigFields::orderPaymentStatusPayed():
                return "cmsgate_payed";
            case ConfigFields::orderStatusFailed():
            case ConfigFields::orderPaymentStatusFailed():
                return "cmsgate_failed";
            case ConfigFields::orderStatusCanceled():
            case ConfigFields::orderPaymentStatusCanceled():
                return "cmsgate_canceled";
            case ConfigFields::useOrderNumber():
                return true;
            default:
                return null;
        }
    }

    public function createCmsRelatedKey($key) {
        return 'ps_' . $key;
    }
}