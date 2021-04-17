<?php

namespace Simi\Simiaddress\Block\Adminhtml\Area;

/**
 * Adminhtml CustomAddress grid
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Simi\Simiaddress\Model\Area
     */
    public $areaFactory;

    /**
     * @var \Simi\Simiaddress\Model\ResourceModel\Area\CollectionFactory
     */
    public $collectionFactory;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    public $moduleManager;

    /**
     * @var order model
     */
    public $resource;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Simi\Simiaddress\Model\AreaFactory $areaFactory,
        \Simi\Simiaddress\Model\ResourceModel\Area\CollectionFactory $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->moduleManager      = $moduleManager;
        $this->resource          = $resourceConnection;
        $this->areaFactory      = $areaFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('areaGrid');
        $this->setDefaultSort('area_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    public function _prepareCollection()
    {
        $collection = $this->collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    public function _prepareColumns()
    {
        $this->addColumn('simi_area_id', [
            'header' => __('ID'),
            'index'  => 'area_id',
        ]);

        $this->addColumn('area_label', [
            'header' => __('Label'),
            'index'  => 'area_label',
        ]);

        $this->addColumn('status', [
            'type'    => 'options',
            'header'  => __('Status'),
            'index'   => 'status',
            'options' => $this->areaFactory->create()->toOptionStatusHash(),
        ]);

        return parent::_prepareColumns();
    }

    /**
     * Row click url
     *
     * @param \Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', [
                    'area_id' => $row->getId()
        ]);
    }

    /**
     * Get grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/index', ['_current' => true]);
    }
}
