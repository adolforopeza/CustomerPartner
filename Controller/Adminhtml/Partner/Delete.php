<?php
declare(strict_types=1);

namespace Aoropeza\CustomerPartner\Controller\Adminhtml\Partner;

class Delete extends \Aoropeza\CustomerPartner\Controller\Adminhtml\Customerpartner
{
    protected \Psr\Log\LoggerInterface $logger;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context, $coreRegistry);
        $this->logger = $logger;
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Aoropeza\CustomerPartner\Model\CustomerPartner::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Customer Partner.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $th) {
                // display error message
                $this->messageManager->addErrorMessage($th->getMessage());
                $this->logger->error("CRON CustomerPartner ERROR: ", [
                    "message" => $th->getMessage(),
                    "code" => $th->getCode(),
                    "file" => $th->getFile(),
                    "line" => $th->getLine(),
                    "trace" => $th->getTraceAsString(),
                ]);
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Customer Partner to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

