<?php

namespace Simi\Simicustomize\Controller\Adminhtml\Ordertoday;

class Shiporder extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ) {
        return parent::__construct($context);
    }

    public function execute()
    {
        $simiObjectManager = $this->_objectManager;
        $order = $simiObjectManager->create('\Magento\Sales\Model\Order')->load($this->getRequest()->getParam('order_id'));

        // Check if order can be shipped or has already shipped
        if (!$order->canShip()) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('You can\'t create an shipment.')
            );
        }

        // Initialize the order shipment object
        $convertOrder = $this->_objectManager->create('Magento\Sales\Model\Convert\Order');
        $shipment = $convertOrder->toShipment($order);

        // Loop through order items
        foreach ($order->getAllItems() as $orderItem) {
            // Check if order item has qty to ship or is virtual
            if (!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                continue;
            }

            $qtyShipped = $orderItem->getQtyToShip();

            // Create shipment item with qty
            $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);

            // Add shipment item to shipment
            $shipment->addItem($shipmentItem);
        }

        // Register shipment
        $shipment->register();

        $shipment->getOrder()->setIsInProcess(true);

        try {
            // Save created shipment and order
            $shipment->save();
            $shipment->getOrder()->save();

            // Send email
            $this->_objectManager->create('Magento\Shipping\Model\ShipmentNotifier')
                ->notify($shipment);

            $shipment->save();
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __($e->getMessage())
            );
        }

        $this->_redirect('simicustomize/ordertoday');
    }
}
