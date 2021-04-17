<?php

namespace Simi\Simiaddress\Model\ResourceModel;

/**
 * Simiaddress Resource Model
 */
class Area extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('simiaddress_area', 'area_id');
    }
}
