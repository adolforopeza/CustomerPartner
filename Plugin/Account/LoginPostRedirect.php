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
            $referer = $_COOKIE[Config::ROUTER_URL_KEY] ?? '';
            if (!empty($referer)) {
                $hasPartnerContext = isset($_COOKIE[Config::COOKIE_KEY]) && (strpos($referer, '/partner/') !== false);

                if ($hasPartnerContext) {
                    $redirect = $this->resultRedirectFactory->create();
                    $redirectUrl = $referer ?: 'customer/account/login';
                    $redirectUrl = $this->url->getUrl($redirectUrl);
                    $redirect->setUrl($redirectUrl);
                    return $redirect;
                }
            }
        }

        return $result;
    }
}


