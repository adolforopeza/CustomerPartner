<?php
declare(strict_types=1);

namespace Aoropeza\CustomerPartner\Controller\Adminhtml\Partner;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
	/** @var PageFactory */
	protected $resultPageFactory;

	/**
	 * ACL resource id
	 */
	public const ADMIN_RESOURCE = 'Aoropeza_CustomerPartner::customer_partner';

	public function __construct(
		Action\Context $context,
		PageFactory $resultPageFactory
	) {
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}

	public function execute(): ResultInterface
	{
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->prepend(__('Customer Partner'));
		return $resultPage;
	}
}

