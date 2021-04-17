<?php

/**
 * Adminhtml connector list block
 *
 */

namespace Simi\Simicustomize\Block\Adminhtml;

use Magento\Directory\Model\Currency;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Ordertoday extends \Magento\Backend\Block\Template
{

    /**
     * Constructor
     *
     * @return void
     */
    // public function _construct(
    //     \Magento\Sales\Model\Order $order
    // ) {
    //     $this->order = $order;
    //     $this->_controller = 'adminhtml_ordertotay';
    //     $this->_blockGroup = 'Simi_Simicustomize';
    //     $this->_headerText = __('Order Today');
    //     parent::_construct();
    //     $this->buttonList->remove('add');
    // }

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Sales\Model\Order $order,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        array $data = [],
        Currency $currency = null,
        \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $collectionFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->newOrder = [];
        $this->processingOrder = [];
        $this->pendingOrder = [];
        $this->completeOrder = [];
        $this->order = $order;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->timezoneInterface = $timezoneInterface;
        $this->currency = $currency ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->create(Currency::class);

        $this->simiObjectManager = $objectManager;

        // $this->statuses = $collectionFactory->create()->toOptionHash();
        parent::__construct($context, $data);
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    public function _isAllowedAction($resourceId)
    {
        return true;
    }

    public function getOrders()
    {
        $collection = $this->_orderCollectionFactory->create()
            ->addAttributeToSelect('*');

        //order today
        $from = date("Y-m-d"); // current date
        $collection->addFieldToFilter('created_at', array('from' => $from));
        //end order today

        foreach ($collection as $value) {
            if ($value->getStatus() === 'processing') {
                array_push($this->newOrder, $value);
            } else if ($value->getStatus() === 'confirmed') {
                array_push($this->processingOrder, $value);
            } else if ($value->getStatus() === 'pending' || $value->getStatus() === 'failed') {
                array_push($this->pendingOrder, $value);
            } else if ($value->getStatus() === 'complete') {
                array_push($this->completeOrder, $value);
            }
        }

        //order processing
        // $collection->addFieldToFilter('status', ['in' => ['processing']]);
        //end order processing

        //order pending, failed
        // $collection->addFieldToFilter('status', ['in' => ['pending', 'failed']]);
        //end order pending, failed

        //order complete
        // $collection->addFieldToFilter('status', ['in' => ['complete']]);
        //end order complete

        return $collection;
    }

    public function getNewOrders()
    {
        // foreach ($this->newOrder as $order) {
        //     var_dump($order->getBillingAddress()->getData());
        //     die;
        // }
        return $this->newOrder;
    }

    public function getProcessingOrder()
    {
        return $this->processingOrder;
    }

    public function getPendingOrder()
    {
        return $this->pendingOrder;
    }

    public function getCompleteOrder()
    {
        return $this->completeOrder;
    }

    public function parseIncrementId($order)
    {
        if ($order->getIncrementId()) {
            return $order->getIncrementId();
        }
        return '';
    }

    public function parseCreateAt($order)
    {
        if ($order->getCreatedAt()) {
            $createAt = $this->timezoneInterface->date(new \DateTime($order->getCreatedAt()))->format('M j, Y g:i:s A');
            return $createAt;
        }
        return '';
    }

    public function parseBillingName($order)
    {
        $billingAddress = $order->getBillingAddress()->getData();
        if ($billingAddress) {
            return $billingAddress['firstname'] . ' ' . $billingAddress['lastname'];
        }
        return '';
    }

    public function parseShippingAddess($order)
    {

        if ($order->getShippingAddress()) {
            $shippingAddress = $order->getShippingAddress();
            $shipping_address = '';
            $shipping_address = $this->appendAddressString($shippingAddress, $shipping_address, "building_no");
            $shipping_address = $this->appendAddressString($shippingAddress, $shipping_address, "block");
            $shipping_address = $this->appendAddressString($shippingAddress, $shipping_address, "street");
            $shipping_address = $this->appendAddressString($shippingAddress, $shipping_address, "area");
            $shipping_address = $this->appendAddressString($shippingAddress, $shipping_address, "city");

            return $shipping_address;
        }
        return '';
    }

    public function parsePhoneNumber($order)
    {
        if ($order->getShippingAddress()) {
            $shippingAddress = $order->getShippingAddress()->getData();
            if ($shippingAddress && isset($shippingAddress['telephone'])) {
                return $shippingAddress['telephone'];
            }
        }
        return '';
    }

    public function parseBaseGrandTotal($order)
    {
        $order = $order->getData();
        $currencyCode = isset($order['base_currency_code']) ? $order['base_currency_code'] : null;
        $basePurchaseCurrency = $this->currency->load($currencyCode);
        $baseGrandTotal = $basePurchaseCurrency->format($order['base_grand_total'], [], false);
        return $baseGrandTotal;
    }

    public function parseGrandTotal($order)
    {
        $order = $order->getData();
        $currencyCode = isset($order['order_currency_code']) ? $order['order_currency_code'] : null;
        $purchaseCurrency = $this->currency->load($currencyCode);
        $grandTotal = $purchaseCurrency->format($order['grand_total'], [], false);
        return $grandTotal;
    }

    public function parseStatus($order)
    {
        $status = isset($this->statuses[$order->getStatus()])
            ? $this->statuses[$order->getStatus()]
            : $order->getStatus();

        return $status;
    }

    private function appendAddressString($shippingAddress, $shipping_address, $key)
    {
        if ($shippingAddress && $shippingAddress->getData($key)) {
            if ($shipping_address === '') {
                $shipping_address = $shippingAddress->getData($key);
            } else {
                $shipping_address .= ', ' . $shippingAddress->getData($key);
            }
        }
        return $shipping_address;
    }

    public function getRowUrl($order)
    {
        return $this->getUrl('sales/order/view', [
            'order_id' => $order->getEntityId()
        ]);
    }

    public function getConfirmAndPrintUrl($order)
    {
        return $this->getUrl('simicustomize/ordertoday/confirmorder', [
            'order_id' => $order->getEntityId()
        ]);
    }

    public function getShipUrl($order)
    {
        return $this->getUrl('simicustomize/ordertoday/shiporder', [
            'order_id' => $order->getEntityId()
        ]);
    }

    public function getInvoiceUrl($order)
    {
        $invoice_details = $order->getInvoiceCollection();
        $invoice_id = null;
        foreach ($invoice_details as $_invoice) {
            //you can get details here.
            $invoice_id = $_invoice->getId();
            break;
        }
        if ($invoice_id) {
            return $this->getUrl('pdfinvoice/invoice/print', [
                'invoice_id' => $order->getEntityId()
            ]);
        }
        return null;
    }
}
