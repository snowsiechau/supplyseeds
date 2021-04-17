<?php

/**
 *
 * Copyright © 2016 Simicommerce. All rights reserved.
 */

namespace Simi\Simicustompayment\Controller\Api;

class Placement extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        $simiObjectManager = $this->_objectManager;
        $session = $simiObjectManager->get('Magento\Customer\Model\Session');
        $checkoutSession = $simiObjectManager->create('Magento\Checkout\Model\Session');

        if($this->getRequest()->getParam('Payment') && $this->getRequest()->getParam('Payment') == 'myfatoorah') {
            $checkoutSession->setCustomerName(base64_decode($this->getRequest()->getParam('CustomerName')));
            $checkoutSession->setCustomerEmail(base64_decode($this->getRequest()->getParam('CustomerEmail')));
            $quoteId = base64_decode($this->getRequest()->getParam('Quoteid'));
            $checkoutSession->replaceQuote($simiObjectManager->get('\Magento\Quote\Model\Quote')->load($quoteId));

            $simiObjectManager->get('\Magento\Framework\Registry')->register('quote_id', $this->getRequest()->getParam('Quoteid'));

            $this->_redirect('myfatoorah/actions/redirect', array(
                '_secure' => true,
                'quote_id' => $this->getRequest()->getParam('Quoteid')
            ));
        } else {
            $checkoutSession->setOrderid(base64_decode($this->getRequest()->getParam('OrderID')));
            $checkoutSession->setLastOrderId(base64_decode($this->getRequest()->getParam('OrderID')));
            $checkoutSession->setMerchantid(base64_decode(($this->getRequest()->getParam('MerchantID'))));
            $checkoutSession->setAmount(base64_decode($this->getRequest()->getParam('Amount')));
            $checkoutSession->setCurrencycode(base64_decode($this->getRequest()->getParam('CurrencyCode')));
            $checkoutSession->setTransactiontype(base64_decode($this->getRequest()->getParam('TransactionType')));
            $checkoutSession->setTransactiondatetime(base64_decode($this->getRequest()->getParam('TransactionDateTime')));
            $checkoutSession->setOrderdescription(base64_decode($this->getRequest()->getParam('OrderDescription')));
            $checkoutSession->setCity(base64_decode($this->getRequest()->getParam('City')));
            $checkoutSession->setState(base64_decode($this->getRequest()->getParam('State')));
            $checkoutSession->setPostcode(base64_decode($this->getRequest()->getParam('PostCode')));
            $checkoutSession->setLastRealOrderId(base64_decode($this->getRequest()->getParam('LastRealOrderId')));

            $this->_redirect('tap/Standard/Redirect');

        }
    }
}
