<?php

/**
 *
 * Copyright © 2016 Simicommerce. All rights reserved.
 */

namespace Simi\Simiconnector\Controller\Index;

class CheckInstall extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {

        //$order = $this->_objectManager->get('Magento\Sales\Api\OrderRepositoryInterface')->get(1192);
        //$order->setStatus('complete')->save();
        // //$item = $quote->getItemById($deleteItemId);
        //     // if ($item && $item->getId()) {
        //     //     $quote->removeItem($deleteItemId);
        //     //     $quoteRepository->save($quote->collectTotals());
        //     // }

        // //2923

       
        // //var_dump($order->getQuoteId());                
        // //die('xxx');
        // $quote = $this->_objectManager->get('\Magento\Quote\Model\Quote')->load(3385);
        // $cart = $this->_objectManager->get('Magento\Checkout\Model\Cart');
        // $quoteItems = $quote->getItemsCollection();
        //  foreach($quoteItems as $item)
        //  {            
        //     $cart->removeItem($item->getId())->save(); 
        //  }

        // die('1223');
        // $customer = $this->_objectManager->get('\Magento\Customer\Model\Customer');
        // $customersession = $this->_objectManager->get('\Magento\Customer\Model\Session');
        // $customer->setWebsiteId(1);
        // $customer->loadByEmail('chau@gg.com');
        // $session = $this->_objectManager->get('Magento\Checkout\Model\Session');
        // $customersession->setCustomerAsLoggedIn($customer);
        // var_dump($session->getQuote()->getId());die();       
        // die('ssss');
        // $arr               = [];
        

      

        $arr['is_install'] = "1";
        $key               = $this->getRequest()->getParam('key');
        if ($key == null || $key == '') {
            $arr["website_key"] = "0";
        } else {
            $simiObjectManager = $this->_objectManager;
            $encodeMethod = 'md5';
            $keySecret = $simiObjectManager
                ->get('\Magento\Framework\App\Config\ScopeConfigInterface')
                ->getValue('simiconnector/general/secret_key');
            $keyEncoded = $encodeMethod($keySecret);
            if ((strcmp($key, $keySecret) == 0) || (strcmp($key, $keyEncoded) == 0)) {
                $arr["website_key"] = "1";
            } else {
                $arr["website_key"] = "0";
            }
        }
        return $this->getResponse()->setBody(json_encode($arr));
    }
}
