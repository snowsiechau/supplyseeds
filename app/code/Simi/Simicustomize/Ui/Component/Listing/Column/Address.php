<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Simi\Simicustomize\Ui\Component\Listing\Column;

use Magento\Framework\Escaper;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;

/**
 * Class Address
 */
class Address extends Column
{
    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Escaper $escaper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Escaper $escaper,
        array $components = [],
        array $data = []
    ) {
        $this->escaper = $escaper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    private function appendAddressString($shippingAddress, $shipping_address, $key) {
        // var_dump($shippingAddress->getData('block'));die();
        if ($shippingAddress && $shippingAddress->getData($key)) {
            if ($shipping_address === '') {
                $shipping_address = $shippingAddress->getData($key);
            } else {
               $shipping_address .= ', ' . $shippingAddress->getData($key);
            }
        }
        return $shipping_address;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $collection = $objectManager->create('Magento\Sales\Model\Order'); 
                $order = $collection->loadByIncrementId($item['increment_id']);                
                // var_dump($order->getShippingAddress()->toArray());die();
                $shippingAddress = $order->getShippingAddress();
                $shipping_address = '';
                $shipping_address = $this->appendAddressString($shippingAddress, $shipping_address, "building_no");
                $shipping_address = $this->appendAddressString($shippingAddress, $shipping_address, "block");
                $shipping_address = $this->appendAddressString($shippingAddress, $shipping_address, "street");
                $shipping_address = $this->appendAddressString($shippingAddress, $shipping_address, "area");
                $shipping_address = $this->appendAddressString($shippingAddress, $shipping_address, "city");

                $item['shipping_address'] = $shipping_address;
                $item[$this->getData('name')] = nl2br($this->escaper->escapeHtml($item[$this->getData('name')]));
            }
        }

        return $dataSource;
    }

    /**
     * Prepare component configuration
     * @return void
     */
    public function prepare()
    {
        parent::prepare();
        $this->_data['config']['componentDisabled'] = true;
    }
}
