<?php

namespace Simi\Simiconnector\Controller\Adminhtml\Simicategory;

class Grid extends \Magento\Customer\Controller\Adminhtml\Index
{

    /**
     * Customer grid action
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
