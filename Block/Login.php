<?php

namespace Aoropeza\CustomerPartner\Block;

use Aoropeza\CustomerPartner\Helper\Config;
use Aoropeza\CustomerPartner\Model\CustomerPartnerRepository as CustomerPartnerModel;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Block\Form\Login as MagentoLogin;
use Magento\Customer\Block\Form\Login\Info as MagentoLoginInfo;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Cms\Model\Template\FilterProvider;


class Login extends Template
{
    /**
     * Summary of configHelper
     * @var Config
     */
    private $configHelper;

    /**
     * Summary of magentoLogin
     * @var MagentoLogin
     */
    private $magentoLogin;

    /**
     * Summary of magentoLoginInfo
     * @var MagentoLoginInfo
     */
    private $magentoLoginInfo;

    /**
     * Summary of sessionManager
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * Summary of customerPartnerModel
     * @var CustomerPartnerModel
     */
    protected $customerPartnerModel;

    /**
     * Summary of filterProvider
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * Summary of __construct
     * @param Template\Context $context
     * @param Config $configHelper
     * @param MagentoLogin $magentoLogin
     * @param MagentoLogin\Info $magentoLoginInfo
     * @param SessionManagerInterface $sessionManager
     * @param CustomerPartnerModel $customerPartnerModel
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $configHelper,
        MagentoLogin $magentoLogin,
        MagentoLoginInfo $magentoLoginInfo,
        SessionManagerInterface $sessionManager,
        CustomerPartnerModel $customerPartnerModel,
        FilterProvider $filterProvider,
        array $data = []
    ) {
        $this->filterProvider = $filterProvider;
        $this->customerPartnerModel = $customerPartnerModel;
        $this->sessionManager = $sessionManager;
        $this->magentoLogin = $magentoLogin;
        $this->magentoLoginInfo = $magentoLoginInfo;
        $this->configHelper = $configHelper;
        parent::__construct($context, $data);
    }

    /**
     * Returns title
     * @return string
     */
    public function getSuccessTitle(): string
    {
        return $this->configHelper->getSuccessTitle();
    }

    /**
     * Returns congrats
     * @return string
     */
    public function getCongrats(): string
    {
        return $this->configHelper->getCongrats();
    }

    /**
     * Returns message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->configHelper->getMessage();
    }

    public function getHtmlContent($content)
    {
        return $this->filterProvider->getPageFilter()->filter($content);
    }


    public function getDescription()
    {
        $id = $this->sessionManager->getCustomerPartner();
        $customerPartner = $this->customerPartnerModel->get($id);
        return $customerPartner->getDescription() ?? null;
    }

    public function getShortDescription()
    {
        $id = $this->sessionManager->getCustomerPartner();
        $customerPartner = $this->customerPartnerModel->get($id);
        return $customerPartner->getShortDescription() ?? null;
    }

    /**
     * Retrieve form posting url
     *
     * @return string
     */
    public function getPostActionUrl()
    {
        return $this->magentoLogin->getPostActionUrl();
    }

    /**
     * Retrieve password forgotten url
     *
     * @return string
     */
    public function getForgotPasswordUrl()
    {
        return $this->magentoLogin->getForgotPasswordUrl();
    }

    /**
     * Retrieve username for form field
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->magentoLogin->getUsername();
    }

    /**
     * Check if autocomplete is disabled on storefront
     *
     * @return bool
     */
    public function isAutocompleteDisabled()
    {
        return $this->magentoLogin->isAutocompleteDisabled();
    }

    /**
     * Return registration
     *
     * @return \Magento\Customer\Model\Registration
     */
    public function getRegistration()
    {
        return $this->magentoLoginInfo->getRegistration();
    }

    /**
     * Retrieve create new account url
     *
     * @return string
     */
    public function getCreateAccountUrl()
    {
        return $this->magentoLoginInfo->getCreateAccountUrl();
    }

}
