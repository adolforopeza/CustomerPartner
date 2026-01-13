<?php
declare(strict_types=1);

namespace Aoropeza\CustomerPartner\Controller\Adminhtml\Partner;

use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;
    protected LoggerInterface $logger;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        LoggerInterface $logger
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('entity_id');

            $model = $this->_objectManager->create(\Aoropeza\CustomerPartner\Model\CustomerPartner::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Customer Partner no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Customer Partner.'));
                $this->dataPersistor->clear('customer_partner');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $th) {
                $this->messageManager->addExceptionMessage($th, __('Something went wrong while saving the Customer Partner.'));
                $this->logger->error("CRON CustomerPartner ERROR: ", [
                    "message" => $th->getMessage(),
                    "code" => $th->getCode(),
                    "file" => $th->getFile(),
                    "line" => $th->getLine(),
                    "trace" => $th->getTraceAsString(),
                ]);
            }

            $this->dataPersistor->set('customer_partner', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

