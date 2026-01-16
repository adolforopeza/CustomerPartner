<?php
namespace Aoropeza\CustomerPartner\Plugin\Account;

use Aoropeza\CustomerPartner\Helper\Config;
use Magento\Framework\UrlInterface;

class CaptchaRedirect
{
    private UrlInterface $url;
    private Config $config;

    /**
     * Summary of __construct
     * @param UrlInterface $url
     * @param Config $config
     */
    public function __construct(
        UrlInterface $url,
        Config $config
    ) {
        $this->url = $url;
        $this->config = $config;
    }

    /**
     * Plugin for Captcha Observer to handle redirect on captcha failure
     *
     * @param \Magento\Captcha\Observer\CheckUserLoginObserver $subject
     * @param callable $proceed
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Magento\Captcha\Observer\CheckUserLoginObserver
     */
    public function aroundExecute(
        \Magento\Captcha\Observer\CheckUserLoginObserver $subject,
        callable $proceed,
        \Magento\Framework\Event\Observer $observer
    ) {
        $proceed($observer);

        if ($this->config->isEnable()) {
            $partnerUrlKey = $_COOKIE[Config::ROUTER_URL_KEY] ?? '';
            if (!empty($partnerUrlKey)) {
                $controller = $observer->getControllerAction();
                $response = $controller->getResponse();
                // Check if a redirect was already set (meaning captcha failed)
                if ($response->isRedirect()) {
                    $redirectUrl = $this->url->getUrl('partner/' . $partnerUrlKey);
                    $response->setRedirect($redirectUrl);
                }
            }
        }
        return $subject;
    }
}
