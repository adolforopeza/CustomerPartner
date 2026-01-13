<?php
/**
 * Copyright © Serfe S.A. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Aoropeza\CustomerPartner\Controller\Success;

use Aoropeza\CustomerPartner\Helper\Config;
use Aoropeza\CustomerPartner\Model\CustomerPartnerRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;

/**
 * @author
 */
class Index implements HttpGetActionInterface
{
    /** @var RedirectFactory */
    protected $redirectFactory;
    /** @var Session */
    protected Session $customerSession;
    /** @var Config */
    protected Config $config;
    protected CustomerPartnerRepository $customerPartnerRepository;
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @param PageFactory $pageFactory
     * @param RedirectFactory $redirectFactory
     * @param Session $customerSession
     * @param Config $config
     */
    public function __construct(
        PageFactory $pageFactory,
        RedirectFactory $redirectFactory,
        Session $customerSession,
        Config $config,
        CustomerPartnerRepository $customerPartnerRepository,
    ) {
        $this->customerPartnerRepository = $customerPartnerRepository;
        $this->pageFactory = $pageFactory;
        $this->redirectFactory = $redirectFactory;
        $this->customerSession = $customerSession;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if ($this->config->isEnable()) {
            if (isset($_COOKIE[Config::COOKIE_KEY]) & $this->customerSession->isLoggedIn()) {
                $groupeId = $_COOKIE[Config::COOKIE_KEY];
                unset($_COOKIE[Config::COOKIE_KEY]);
                $parnet = $this->customerPartnerRepository->getCustomerGroup($groupeId);
                $partnerName = $parnet->getName() ?? '';
                $page = $this->pageFactory->create();
                $page->getConfig()->getTitle()->set('Partner ' . $partnerName);
                return $page;
            } else {
                $resultRedirect = $this->redirectFactory->create();
                $resultRedirect->setPath('');
                return $resultRedirect;
            }
        } else {
            $resultRedirect = $this->redirectFactory->create();
            $resultRedirect->setPath('');
            return $resultRedirect;
        }
    }
}
