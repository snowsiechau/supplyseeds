<?php

/**
 * Connector data helper
 */

namespace Simi\Simiaddress\Helper;

class Address extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * Store manager
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $simiObjectManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $simiObjectManager
    ) {

        $this->simiObjectManager = $simiObjectManager;
        parent::__construct($context);
    }

    public function getAreas() {
        $areaCollection = $this->simiObjectManager->create('Simi\Simiaddress\Model\Area')
                                ->getCollection()
                                ->addFieldToFilter('status', 1);
        $areaArr = array();
        foreach ($areaCollection as $area) {
            $areaArr[] = $area->toArray();
        }
        return $areaArr;
    }

    public function getAreaByCode($code) {
        $area = $this->simiObjectManager->create('Simi\Simiaddress\Model\Area')
            ->getCollection()
            ->addFieldToFilter('area_code', $code)
            ->getFirstItem();
        return $area;
    }

    public function getAreaNameByCode($code) {
        $area = $this->getAreaByCode($code);
        if($area) {
            return $area->getAreaLabel();
        } else {
            return '';
        }
    }

}
