<?php

namespace Simi\Simiconnector\Plugin;

class SubmitObserver
{
    public function beforeExecute($subject, $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if ($order->getPayment()->getMethodInstance()->getCode() == 'tap') {
            $order->setCanSendNewEmailFlag(false);
        }

        return [$observer];
    }
}