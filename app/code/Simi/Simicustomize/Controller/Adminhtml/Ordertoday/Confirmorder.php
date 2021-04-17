<?php

namespace Simi\Simicustomize\Controller\Adminhtml\Ordertoday;


use Mageplaza\PdfInvoice\Helper\Data;
use Mageplaza\PdfInvoice\Helper\PrintProcess as HelperData;
use Mageplaza\PdfInvoice\Model\Source\Type;

class Confirmorder extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        HelperData $helperData
    ) {
        $this->helperData = $helperData;
        return parent::__construct($context);
    }

    public function execute()
    {
        $simiObjectManager = $this->_objectManager;
        $order = $simiObjectManager->create('\Magento\Sales\Model\Order')->load($this->getRequest()->getParam('order_id'));
        $order->setStatus('confirmed');
        $order->save();        
        $this->_redirect('simicustomize/ordertoday');
    }
}
