<?php


namespace Simi\Simiaddress\Observer;

class QuoteSubmitBefore implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $quote = $objectManager->get('Magento\Checkout\Model\Cart')->getQuote();

        $order->getBillingAddress()->setData('area', $quote->getBillingAddress()->getData('area'))->save();
        $order->getBillingAddress()->setData('block', $quote->getBillingAddress()->getData('block'))->save();
        $order->getBillingAddress()->setData('avenue', $quote->getBillingAddress()->getData('avenue'))->save();
        $order->getBillingAddress()->setData('building_no', $quote->getBillingAddress()->getData('building_no'))->save();
        $order->getBillingAddress()->setData('floor', $quote->getBillingAddress()->getData('floor'))->save();
        $order->getBillingAddress()->setData('apartment', $quote->getBillingAddress()->getData('apartment'))->save();
        $order->getBillingAddress()->setData('delivery_instruction', $quote->getBillingAddress()->getData('delivery_instruction'))->save();
        $order->getBillingAddress()->setData('location_name', $quote->getBillingAddress()->getData('location_name'))->save();

        if ($order->getShippingAddress()) {
            $order->getShippingAddress()->setData('area', $quote->getShippingAddress()->getData('area'))->save();
            $order->getShippingAddress()->setData('block', $quote->getShippingAddress()->getData('block'))->save();
            $order->getShippingAddress()->setData('avenue', $quote->getShippingAddress()->getData('avenue'))->save();
            $order->getShippingAddress()->setData('building_no', $quote->getShippingAddress()->getData('building_no'))->save();
            $order->getShippingAddress()->setData('floor', $quote->getShippingAddress()->getData('floor'))->save();
            $order->getShippingAddress()->setData('apartment', $quote->getShippingAddress()->getData('apartment'))->save();
            $order->getShippingAddress()->setData('delivery_instruction', $quote->getShippingAddress()->getData('delivery_instruction'))->save();
            $order->getShippingAddress()->setData('location_name', $quote->getShippingAddress()->getData('location_name'))->save();
        }
        return $this;
    }
}
