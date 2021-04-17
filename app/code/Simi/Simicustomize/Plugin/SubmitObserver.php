<?php

namespace Simi\Simicustomize\Plugin;

class SubmitObserver
{
    public function beforeExecute($subject, $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if ($order->getPayment()->getMethodInstance()->getCode() == 'tap' || $order->getPayment()->getMethodInstance()->getCode() == 'myfatoorah') {
            $order->setCanSendNewEmailFlag(false);
        }

        return [$observer];
    }
}