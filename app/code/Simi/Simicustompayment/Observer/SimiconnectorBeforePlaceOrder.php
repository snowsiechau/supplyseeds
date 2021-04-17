<?php

/**
 * Created by PhpStorm.
 * User: trueplus
 * Date: 4/19/16
 * Time: 08:52
 */

namespace Simi\Simicustompayment\Observer;

use Magento\Framework\Event\ObserverInterface;

class SimiconnectorBeforePlaceOrder implements ObserverInterface {

    public $simiObjectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $simiObjectManager
    ) {
        $this->simiObjectManager = $simiObjectManager;
    }
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $orderObject = $observer->getObject();
        $data = $orderObject->order_placed_info;
        $paymentCode = $orderObject->_getOnepage()->getQuote()->getPayment()->getMethodInstance()->getCode();
        if ($paymentCode == "myfatoorah") {
            $data = $orderObject->order_placed_info;
            $data['url_action'] = $this->getOrderPlaceRedirectUrl();
            $orderObject->order_placed_info = $data;
            $orderObject->place_order = false;
        }
    }

    public function getOrderPlaceRedirectUrl() {

        $checkoutSession = $this->simiObjectManager->get('Magento\Checkout\Model\Session');

        $quote = $this->simiObjectManager->get('Magento\Checkout\Model\Cart')->getQuote();
        // $checkoutSession->getQuote()->setBillingAddress($billingAddress);

        return $this->simiObjectManager->get('Magento\Framework\UrlInterface')
            ->getUrl('simicustompayment/api/placement', array('_secure' => true,
                'Payment' => 'myfatoorah',
            // 'OrderID' => base64_encode($order_id),
            // 'Amount' => base64_encode($checkoutSession->getAmount()),
            // 'CurrencyCode' => base64_encode($checkoutSession->getCurrencycode()),
            // 'TransactionType' => base64_encode($checkoutSession->getTransactiontype()),
            // 'TransactionDateTime' => base64_encode($checkoutSession->getTransactiondatetime()),
            // 'CallbackURL' => base64_encode($checkoutSession->getCallbackurl()),
            // 'OrderDescription' => base64_encode($checkoutSession->getOrderdescription()),
            'CustomerName' => base64_encode($quote->getCustomerFirstname() . ' ' . $quote->getCustomerLastname()),
            'CustomerEmail' => base64_encode($quote->getCustomerEmail()),
            // 'Address1' => base64_encode($checkoutSession->getAddress1()),
            // 'Address2' => base64_encode($checkoutSession->getAddress2()),
            // 'Address3' => base64_encode($checkoutSession->getAddress3()),
            // 'Address4' => base64_encode($checkoutSession->getAddress4()),
            // 'City' => base64_encode($checkoutSession->getCity()),
            // 'State' => base64_encode($checkoutSession->getState()),
            // 'PostCode' => base64_encode($checkoutSession->getPostcode()),
            // 'LastRealOrderId' => base64_encode($checkoutSession->getLastRealOrderId()),
            'Quoteid' => base64_encode($quote->getId())
        ));
    }

}
