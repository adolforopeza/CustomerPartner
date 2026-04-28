<?php

namespace Aoropeza\CustomerPartner\Controller\Index;

use Aoropeza\CustomerPartner\Helper\Config;
use Aoropeza\CustomerPartner\Model\CustomerPartnerRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory as ResultRedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;


class Index implements HttpGetActionInterface
{

    /**
     * @var Session
     */
    protected $session;
    /**
     * @var ResultRedirectFactory
     */
    protected $resultRedirectFactory;
    /**
     * @var ManagerInterface
     */
    protected $messageManager;
    protected Config $config;
    /**
     * @var PageFactory
     */
    private $pageFactory;
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var CustomerPartnerRepository
     */
    protected $customerPartnerRepository;
    /**
     * Summary of logger
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * Summary of sessionManager
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * Summary of __construct
     * @param Session $customerSession
     * @param PageFactory $pageFactory
     * @param ResultRedirectFactory $resultRedirectFactory
     * @param ManagerInterface $messageManager
     * @param RequestInterface $request
     * @param CustomerPartnerRepository $customerPartnerRepository
     * @param SessionManagerInterface $sessionManager
     * @param Config $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        Session $customerSession,
        PageFactory $pageFactory,
        ResultRedirectFactory $resultRedirectFactory,
        ManagerInterface $messageManager,
        RequestInterface $request,
        CustomerPartnerRepository $customerPartnerRepository,
        SessionManagerInterface $sessionManager,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->sessionManager = $sessionManager;
        $this->config = $config;
        $this->session = $customerSession;
        $this->pageFactory = $pageFactory;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
        $this->request = $request;
        $this->customerPartnerRepository = $customerPartnerRepository;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $page = $this->pageFactory->create();
        try {
            $redirectPath = '';
            $redirect = false;
            // Get url_key from parameter passed by Router
            $urlKey = explode("/", $this->request->getParam('url_key') ?? '');
            if (is_array($urlKey)) {
                if (!isset($urlKey[1])) {
                    $redirectPath = '';
                    $redirect = true;
                } else {
                    $redirect = false;
                    $urlKey = $urlKey[1];
                }
            }

            if (!$this->config->isEnable()) {
                $redirectPath = '';
                $redirect = true;
            }

            // If user is already logged in, redirect to customer account
            if ($this->session->isLoggedIn()) {
                $redirectPath = 'customer/account';
                $redirect = true;
            }

            if ($redirect) {
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath($redirectPath);
                return $resultRedirect;
            }


            if ($urlKey) {
                $customerPartner = $this->customerPartnerRepository->getByUrl($urlKey);
                $this->sessionManager->setCustomerPartner($customerPartner->getId());
                // If model is valid and has a name, set the title
                if (is_object($customerPartner) && method_exists($customerPartner, 'getName')) {

                    if ($this->session->isLoggedIn()) {
                        $groupId = $customerPartner->getCustomerGroupId();
                        $customerGroup = $this->session->getCustomer()->getGroupId();
                        if ($customerGroup === $groupId) {
                            /** @var Redirect $resultRedirect */
                            $resultRedirect = $this->resultRedirectFactory->create();
                            $resultRedirect->setPath('');
                            $this->messageManager->addSuccessMessage('You are already in the Secret Sale customer group, enjoy your purchases!');
                            return $resultRedirect;
                        }
                        if ($customerGroup !== $groupId) {
                            $customerGroup = $this->session->getCustomer()->setGroupId($groupId)->save();
                            $this->session->setCustomerGroupId($groupId);
                            $resultRedirect = $this->resultRedirectFactory->create();
                            $resultRedirect->setPath(Config::SUCCESS_PAGE);
                            return $resultRedirect;
                        } else {
                            return $this->pageFactory->create();
                        }
                    }

                    $partnerName = $customerPartner->getName();
                    if ($partnerName) {
                        $page->getConfig()->getTitle()->set('Partner ' . $partnerName);
                    }
                }
            }
        } catch (\Throwable $th) {
            $this->logger->error("CRON CustomerPartner ERROR: ", [
                "message" => $th->getMessage(),
                "code" => $th->getCode(),
                "file" => $th->getFile(),
                "line" => $th->getLine(),
                "trace" => $th->getTraceAsString(),
            ]);
        }
        return $page;
    }


}
