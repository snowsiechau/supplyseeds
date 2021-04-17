<?php

namespace Simi\Simicustomize\Controller\Adminhtml\Order;

class Processing extends \Magento\Backend\App\Action {

    protected $order;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Sales\Model\Order $order
    ) {
        parent::__construct($context);
        $this->_order = $order;
    }

    /**
     * {@inheritdoc}
     */
    public function execute() {
        $orderId = $this->getRequest()->getParam( 'order_id' );
        if ( $orderId ) {
            try {           
                $order = $this->_order->load( $orderId );
                $order->setStatus(\Magento\Sales\Model\Order::STATE_PROCESSING);
                $order->save();     
                foreach ($order->getAllItems() as $item) {
                    $item->setQtyOrdered($item->getQtyCanceled());
                    $item->setQtyCanceled(0);
                    $item->save();
                }
                $order->setState(\Magento\Sales\Model\Order::STATE_PROCESSING);
                $order->save();

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $invoice = $objectManager->create('Magento\Sales\Model\Service\InvoiceService')->prepareInvoice($order);
                $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
                $invoice->register();
                $invoice->save();
                $transactionSave = $objectManager->create('Magento\Framework\DB\Transaction')->addObject(
                    $invoice
                )->addObject(
                    $invoice->getOrder()
                );
                $transactionSave->save();
                $objectManager->create('Magento\Sales\Model\Order\Email\Sender\InvoiceSender')->send($invoice);
            }
            catch ( \Exception $e ) {
                $this->messageManager->addException( $e, __( 'Something went wrong while saving the data.' ) );
            }
        }
        $this->_redirect( 'sales/order/view', array( 'order_id' => $orderId ) );
    }
}
