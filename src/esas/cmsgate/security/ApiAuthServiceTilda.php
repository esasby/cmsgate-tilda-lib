<?php


namespace esas\cmsgate\security;


use esas\cmsgate\tilda\RequestParamsTilda;

class ApiAuthServiceTilda extends ApiAuthServiceBySecret
{

    public function generateVerificationSignature($request, $secret)
    {
        $line = $request[RequestParamsTilda::ORDER_ID]
            . '|' . $request[RequestParamsTilda::ORDER_AMOUNT]
            . '|' . $secret
            . '|' . $request[RequestParamsTilda::ORDER_CURRENCY];
        $this->logger->info('Sign values: ' . $line);
        return hash('sha256', $line);
    }
}