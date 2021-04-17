<?php

namespace Gateway\Tap\Controller\Standard;
use Magento\Sales\Model\Order\Payment\Transaction;
use Magento\Sales\Model\Order\Payment\Transaction\ManagerInterface;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Sales\Api\Data\CreditmemoInterface;
use Magento\Sales\Api\Data\OrderPaymentExtensionInterface;
use Magento\Sales\Api\Data\OrderPaymentExtensionInterfaceFactory;
// use Magento\Sales\Model\Service\InvoiceService;
use Magento\Customer\Model\Session;
use Magento\Vault\Api\Data\PaymentTokenFactoryInterface;

use Magento\Vault\Api\Data\PaymentTokenInterface;

  
class Response extends \Gateway\Tap\Controller\Tap
{

	
	public function createTransaction($order_info , $paymentData = array() )
	{
        try {
            //get payment object from order object
            $payment = $order_info->getPayment();
            $payment->setLastTransId($paymentData);
            $payment->setTransactionId($paymentData);
            $payment->setAdditionalInformation(
                [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $paymentData]
            );
            $formatedPrice =$order_info->getBaseCurrency()->formatTxt(
                $order_info->getGrandTotal()
            );
 
            $message = __('The authorized amount is %1.', $formatedPrice);
            //get the object of builder class
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$transactionBuilder = $objectManager->get('\Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface');
            $trans = $transactionBuilder;

            $transaction = $trans->setPayment($payment)
            ->setOrder($order_info)
            ->setTransactionId($paymentData)
            ->setAdditionalInformation(
                [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $paymentData]
            )
            ->setFailSafe(true)
            //build method creates the transaction and returns the object
            ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE);
 
            $payment->addTransactionCommentsToOrder(
                $transaction,
                $message
            );
            $payment->setParentTransactionId(null);
            $payment->save();
            $order_info->save();
 
            //return  $transaction->save()->getTransactionId();
        } catch (Exception $e) {
            //log errors here
        }
	}




	public function execute()
	{
		$resultRedirect = $this->resultRedirectFactory->create();
		$debug_mode =  $this->getTapHelper()->getConfiguration('payment/tap/debug');
		if ($debug_mode == 1)
			$live_secret_key = $this->getTapHelper()->getConfiguration('payment/tap/test_secret_key');
		else {
			$live_secret_key = $this->getTapHelper()-> getConfiguration('payment/tap/live_secret_key');
		}
		if (empty($_REQUEST['tap_id'])) {
			$returnUrl = $this->getTapHelper()->getUrl('checkout/cart');
			$this->messageManager->addError(__("Transaction unsccessful"));
			return $resultRedirect->setUrl($returnUrl);
		}
		$returnUrl = $this->getTapHelper()->getUrl('checkout/onepage/success');
		$resultRedirect = $this->resultRedirectFactory->create();
		//$ref = $_REQUEST['tap_id'];
		$ref = $this->getRequest()->getParam('tap_id');
		$transaction_mode = substr($ref, 0, 4);
		//var_dump($transaction_mode);exit;
		if ($transaction_mode == 'auth') {
			$curl_url = 'https://api.tap.company/v2/authorize/';
		}
		else  {
			$curl_url = 'https://api.tap.company/v2/charges/';
		}
		$comment 	= 	"";
		$successFlag= 	false;

			$curl = curl_init();

			curl_setopt_array($curl, array(
  						CURLOPT_URL => $curl_url.$ref,
  							CURLOPT_RETURNTRANSFER => true,
  							CURLOPT_ENCODING => "",
  							CURLOPT_MAXREDIRS => 10,
  							CURLOPT_TIMEOUT => 30,
  							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  							CURLOPT_CUSTOMREQUEST => "GET",
  							CURLOPT_POSTFIELDS => "{}",
  							CURLOPT_HTTPHEADER => array(
    							"authorization: Bearer $live_secret_key"
  							),
						)
			);

			$response = curl_exec($curl);
			//var_dump($response);exit;
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
  				echo "cURL Error #:" . $err;
			} 
			else {
				$response = json_decode($response);
				
				if (isset($response->source->payment_type)) {
					$payment_type = $response->source->payment_type;
					if (isset($response->status)) {
						$charge_status = $response->status;
					}
					if ($payment_type == 'CREDIT') {
						if (isset($response->card) && isset($response->card->last_four)) {
							$last_four = $response->card->last_four;
						}
	  					$payment_type = 'CREDIT CARD';
	  				}
				}
			}
			

			
			$lastorderId = $this->_checkoutSession->getLastOrderId();
			$lastorderId = $lastorderId;

  			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
  			// $order_info = $objectManager->create('\Magento\Sales\Model\OrderRepository')->get($lastorderId);
  			$order_info        = $objectManager->create('Magento\Sales\Model\Order')
                    ->loadByIncrementId($lastorderId);
  			$payment = $order_info->getPayment();
			


			if ($charge_status == 'DECLINED' || $charge_status == 'CANCELLED' || $charge_status == 'FAILED') {
				$returnUrl = $this->getTapHelper()->getUrl('checkout/cart');

				
            	$order_info->setIsCustomerNotified(true);

				$qoute = $this->getQuote();
				//$this->getCheckoutSession()->setForceOrderMailSentOnSuccess(true);
				$this->getCheckoutSession()->restoreQuote($qoute);
				$qoute->setIsActive(true);
				$order_info->cancel();
				$order_info->save();
				$qoute->setIsActive(true);
				$this->messageManager->addError(__("Transaction Failed"));
				
				return $resultRedirect->setUrl($returnUrl);
			}
		
  			

		if ($_REQUEST['tap_id'] && $transaction_mode !== 'auth' && $charge_status == 'CAPTURED' || $charge_status == 'INITIATED')
		{
			
			$reffer = $_REQUEST['tap_id'];
			$tid = '';
			//$transaction_id = $this->createTransaction($order ,$reffer );
				$orderState = \Magento\Sales\Model\Order::STATE_PROCESSING;
				$orderStatus = \Magento\Sales\Model\Order::STATE_PROCESSING;
				$order_info->setState($orderState)
                            ->setStatus($orderStatus)
                                ->addStatusHistoryComment("Tap Transaction Successful")
                                ->setIsCustomerNotified(true);

				
			
				$objectManager2 = \Magento\Framework\App\ObjectManager::getInstance();
				$invioce = $objectManager2->get('\Magento\Sales\Model\Service\InvoiceService')->prepareInvoice($order_info);
				$invioce->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
				$invioce->register();
				
				$transaction = $payment->addTransaction(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_AUTH, null, true, ""
    			);
            	$invioce->setTransactionId($reffer);
            	$invioce->save();


             	$payment->setTransactionId($reffer);
    			$payment->setParentTransactionId($payment->getTransactionId());
    			$transaction = $payment->addTransaction(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_AUTH, null, true, ""
    			);
    			$transaction->setIsClosed(true);

    			$comment .=  '<br/><b>Tap payment successful</b><br/><br/>Tap ID - '.$_REQUEST['tap_id'].'<br/><br/>Order ID - '.$lastorderId.'<br/><br/>Payment Type - Credit Card<br/><br/>Payment ID - '.$_REQUEST['tap_id'];

       //      	$payment->setTransactionId($ref);
    			// $payment->setParentTransactionId($payment->getTransactionId());
    			// $transaction = $payment->addTransaction(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_AUTH, null, true, ""
    			// );
    			//$transaction->setIsClosed(true);

    			//clear checkout session				
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			if ($order_info->getCustomerGroupId() != '0') {						
				$customerId = $order_info->getCustomerId();
				$customer = $objectManager->get('\Magento\Customer\Model\ResourceModel\CustomerRepository')->getById($customerId);
				$storeIds = $objectManager->get('\Magento\Store\Model\StoreManagerInterface')->getWebsite($customer->getWebsiteId())->getStoreIds();
				try {
					$quote = $objectManager->get('\Magento\Quote\Model\QuoteFactory')->create()->setSharedStoreIds($storeIds)->loadByCustomer($customerId);
				} catch (NoSuchEntityException $e) {
					$quote = $objectManager->get('\Magento\Quote\Model\QuoteFactory')->create()->setSharedStoreIds($storeIds);
				}
				$quote->delete();
			} else {
				$cartObject = $objectManager->create('Magento\Checkout\Model\Cart')->truncate();
				$cartObject->saveQuote();
				$this->_checkoutSession->clearQuote();
			}

			//end
			
			$returnUrl = $this->getTapHelper()->getUrl('checkout/onepage/success');
		}
		else if ($_REQUEST['tap_id'] && $transaction_mode == 'auth' ) 
		{
			//var_dump($order_idd);exit;
			//var_dump($order_idd);exit;
			$comment .=  '<br/><b>Tap payment successful</b><br/><br/>Tap ID - '.$_REQUEST['tap_id'].'<br/><br/>Order ID - '.$lastorderId.'<br/><br/>Payment Type - Credit Card<br/><br/>Payment ID - '.$_REQUEST['tap_id'];
			$order_info->setStatus($order_info::STATE_PAYMENT_REVIEW);
			$transaction_id = $this->createTransaction($order_info, $_REQUEST['tap_id']);
			$transaction = $order_info->setTransactionId($_REQUEST['tap_id']);
			$transaction = $payment->addTransaction(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_AUTH, null, true, ""
					);
			$transaction->save();
			$transaction->setIsClosed(false);

		}
		else if ($charge_status !== 'CAPTURED' )
		{
			$errorMsg = 'It seems some issue in card authentication. Transaction Failed.';
			$order_info->setStatus($order::STATE_PENDING_PAYMENT);
			$comment = $errorMsg;
		}
		$this->addOrderHistory($order_info,$comment);
  		$order_info->save();
		return $resultRedirect->setUrl($returnUrl);
	}
}
