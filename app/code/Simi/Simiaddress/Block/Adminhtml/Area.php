<?php

/**
 * Adminhtml simiconnector list block
 *
 */

namespace Simi\Simiaddress\Block\Adminhtml;

class Area extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * Constructor
     *
     * @return void
     */
    public function _construct()
    {
        $this->_controller     = 'adminhtml_area';
        $this->_blockGroup     = 'Simi_Simiaddress';
        $this->_headerText     = __('Area/Region/Province');
        $this->_addButtonLabel = __('Add New Area');
        parent::_construct();
        if ($this->_isAllowedAction('Simi_Simiaddress::save')) {
            $this->buttonList->update('add', 'label', __('Add New Area'));
        } else {
            $this->buttonList->remove('add');
        }
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
}
