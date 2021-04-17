<?php

namespace Simi\Simiaddress\Controller\Adminhtml\Area;

use Magento\Backend\App\Action;

class Save extends \Magento\Backend\App\Action
{

    /**
     * Save action
     *
     * @return void
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $simiObjectManager = $this->_objectManager;
            $model = $simiObjectManager->create('Simi\Simiaddress\Model\Area');

            $id = $this->getRequest()->getParam('area_id');
            if ($id) {
                $model->load($id);
            }

            $label = $data['area_label'];
            $data['area_code'] = strtolower(str_replace(' ', '_', $label));

            try {
                $model->addData($data)->save();

                $this->messageManager->addSuccess(__('The Data has been saved.'));
                $simiObjectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['area_id' => $model->getId(), '_current' => true]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', ['area_id' => $this->getRequest()->getParam('area_id')]);
            return;
        }
        $this->_redirect('*/*/');
    }

}
