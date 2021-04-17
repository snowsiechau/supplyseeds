<?php

namespace Simi\Simiaddress\Block\Adminhtml\Area\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    public $simiObjectManager;

    /**
     * @var \Simi\Simiaddress\Model\Area
     */
    public $areaFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Simi\Simiaddress\Model\AreaFactory $areaFactory,
        \Magento\Framework\ObjectManagerInterface $simiObjectManager,
        array $data = []
    ) {

        $this->simiObjectManager = $simiObjectManager;
        $this->areaFactory       = $areaFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    public function _prepareForm()
    {

        $model = $this->_coreRegistry->registry('area');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('Simi_Simiaddress::area_save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('');
        $htmlIdPrefix = $form->getHtmlIdPrefix();

        $fieldset            = $form->addFieldset('base_fieldset', ['legend' => __('Area Information')]);

        $data = $model->getData();
        if ($model->getId()) {
            $fieldset->addField('area_id', 'hidden', ['name' => 'area_id']);
        }

        $fieldset->addField(
            'area_label',
            'text',
            ['name'     => 'area_label',
            'label'    => __('Label'),
            'title'    => __('Label'),
            'required' => true,
            'disabled' => $isElementDisabled]
        );

//        $fieldset->addField(
//            'shipping_rate',
//            'text',
//            ['name'     => 'shipping_rate',
//            'label'    => __('Shipping Rate'),
//            'title'    => __('Shipping Rate'),
//            'required' => true,
//            'disabled' => $isElementDisabled]
//        );

        $fieldset->addField(
            'status',
            'select',
            [
            'name'     => 'status',
            'label'    => __('Status'),
            'title'    => __('Status'),
            'required' => false,
            'disabled' => $isElementDisabled,
            'options'  => $this->areaFactory->create()->toOptionStatusHash(),
                ]
        );

        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Area Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Area Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
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
