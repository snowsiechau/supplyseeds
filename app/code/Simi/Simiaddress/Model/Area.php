<?php

namespace Simi\Simiaddress\Model;

/**
 * Simiconnector Model
 *
 * @method \Simi\Simiaddress\Model\Resource\Page _getResource()
 * @method \Simi\Simiaddress\Model\Resource\Page getResource()
 */
class Area extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\Key $resource
     * @param ResourceModel\Key\Collection $resourceCollection
     * @param \Simi\Simiconnector\Helper\Website $websiteHelper
     * @param AppFactory $app
     * @param PluginFactory $plugin
     * @param DesignFactory $design
     * @param ResourceModel\App\CollectionFactory $appCollection
     * @param ResourceModel\Key\CollectionFactory $keyCollection
     */
    public $simiObjectManager;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Simi\Simiaddress\Model\ResourceModel\Area $resource,
        \Simi\Simiaddress\Model\ResourceModel\Area\Collection $resourceCollection,
        \Magento\Framework\ObjectManagerInterface $simiObjectManager
    ) {
        $this->simiObjectManager = $simiObjectManager;

        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {

        $this->_init('Simi\Simiaddress\Model\ResourceModel\Area');
    }

    /**
     * @return array Status
     */
    public function toOptionStatusHash()
    {
        $status = [
            '1' => __('Enable'),
            '2' => __('Disabled'),
        ];
        return $status;
    }

}
