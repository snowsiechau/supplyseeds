<?php

namespace Simi\Simiaddress\Controller\Adminhtml\Area;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
{
    public $coreRegistry = null;

    public $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {

        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry     = $registry;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return $this
     */
    private function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(
            'Simi_Simiconnector::simiconnector_manage'
        )->addBreadcrumb(
            __('Area'),
            __('Area')
        )->addBreadcrumb(
            __('Manage Area'),
            __('Manage Area')
        );
        return $resultPage;
    }

    /**
     * Edit CMS page
     *
     * @return void
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id    = $this->getRequest()->getParam('area_id');
        $simiObjectManager = $this->_objectManager;
        $model = $simiObjectManager->create('Simi\Simiaddress\Model\Area');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This area no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        // 3. Set entered data if was error when we do save
        $data = $simiObjectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        $this->coreRegistry->register('area', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Area') : __('New Area'),
            $id ? __('Edit Area') : __('New Area')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Area'));
        $resultPage->getConfig()->getTitle()
                ->prepend($model->getId() ? $model->getId() : __('New Area'));
        return $resultPage;
    }
}
