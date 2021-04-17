<?php

namespace Simi\Simiaddress\Block\Adminhtml\Area;

/**
 * Admin Connector page
 *
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    public $coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {

        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize cms page edit block
     *
     * @return void
     */
    public function _construct()
    {

        $this->_objectId   = 'area_id';
        $this->_blockGroup = 'Simi_Simiaddress';
        $this->_controller = 'adminhtml_area';

        parent::_construct();

        if ($this->_isAllowedAction('Simi_Simiaddress::save')) {
            $this->buttonList->update('save', 'label', __('Save'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                'label'          => __('Save and Continue Edit'),
                'class'          => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
                    ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('Simi_Simiaddress::connector_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->coreRegistry->registry('area')->getId()) {
            return __("Edit Area '%1'", $this->escapeHtml($this->coreRegistry->registry('area')->getId()));
        } else {
            return __('New Area');
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

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    public function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            'simiaddress/*/save',
            ['_current'   => true,
                    'back'       => 'edit',
                    'active_tab' => '{{tab_id}}']
        );
    }

    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    public function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'page_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
                }
            };
           
            document.addEventListener('DOMContentLoaded', function(){
                toogleType();
            }, false);
           
            function toogleType(){
           
                if(type.value == 2){                   
                    document.querySelectorAll('.field-cms_image')[0].style.display = 'none';          
                    document.querySelectorAll('.field-category_id')[0].style.display = 'block';
                    document.querySelectorAll('#category_id')[0].classList.add('required-entry');
                } else {
                    document.querySelectorAll('.field-category_id')[0].style.display = 'none';
                    document.querySelectorAll('#category_id')[0].classList.remove('required-entry');
                    document.querySelectorAll('.field-cms_image')[0].style.display = 'block';
                }
            };
           
        ";
        return parent::_prepareLayout();
    }
}
