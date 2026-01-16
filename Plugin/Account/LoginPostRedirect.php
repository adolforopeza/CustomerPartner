<?php

namespace Aoropeza\CustomerPartner\Plugin\Account;

use Aoropeza\CustomerPartner\Helper\Config;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Controller\Account\LoginPost;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\UrlInterface;

class LoginPostRedirect
{
    private RedirectFactory $resultRedirectFactory;
    private RequestInterface $request;
    private CustomerSession $customerSession;
    private UrlInterface $url;
    private Config $config;

    public function __construct(
        RedirectFactory $resultRedirectFactory,
        RequestInterface $request,
        CustomerSession $customerSession,
        UrlInterface $url,
        Config $config
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->request = $request;
        $this->customerSession = $customerSession;
        $this->url = $url;
        $this->config = $config;
    }

    public function aroundExecute(LoginPost $subject, callable $proceed): ResultInterface
    {
        $result = $proceed();

        // If login failed and we are in Partner context, keep the origin URL
        if (!$this->customerSession->isLoggedIn() && $this->config->isEnable()) {
            return $this->getPartnerRedirect($result);
        }

        return $result;
    }

    /**
     * Get partner redirect if applicable
     *
     * @param ResultInterface $result
     * @return ResultInterface
     */
    private function getPartnerRedirect(ResultInterface $result): ResultInterface
    {
        $partnerUrlKey = $_COOKIE[Config::ROUTER_URL_KEY] ?? '';
        if (!empty($partnerUrlKey)) {
            $redirect = $this->resultRedirectFactory->create();
            $redirectUrl = $this->url->getUrl('partner/' . $partnerUrlKey);
            $redirect->setUrl($redirectUrl);
            return $redirect;
        }
        return $result;
    }
}


