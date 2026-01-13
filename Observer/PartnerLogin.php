<?php

namespace Aoropeza\CustomerPartner\Observer;

use Aoropeza\CustomerPartner\Helper\Config;
use Magento\Customer\Model\Session;
use Magento\Framework\UrlInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class PartnerLogin implements ObserverInterface
{


    /** @var Session */
    protected $session;

    /** @var Config */
    protected $config;

    /** @var UrlInterface */
    protected UrlInterface $url;
    protected LoggerInterface $logger;

    /**
     * @param Session $session
     * @param Config $config
     * @param UrlInterface $url
     */
    public function __construct(
        Session $session,
        Config $config,
        UrlInterface $url,
        LoggerInterface $logger
    ) {
        $this->session = $session;
        $this->config = $config;
        $this->url = $url;
        $this->logger = $logger;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     */
    public function execute(
        Observer $observer
    ) {
        try {
            // Only apply module logic if coming from a partner URL
            // Verify that both cookies exist: COOKIE_KEY and ROUTER_URL_KEY
            // If ROUTER_URL_KEY does not exist, it means it is a normal login from customer/account/login
            $hasPartnerCookie = isset($_COOKIE[Config::COOKIE_KEY]);
            $hasPartnerUrl = isset($_COOKIE[Config::ROUTER_URL_KEY]);

            // Only process if both cookies exist (login from partner URL)
            if ($hasPartnerCookie && $hasPartnerUrl) {
                $customer = $observer->getEvent()->getCustomer();
                $customer->setGroupId($_COOKIE[Config::COOKIE_KEY]);
                $customer->save();
                $this->session->setCustomerGroupId($_COOKIE[Config::COOKIE_KEY]);
                // Set target URL for post-login redirect
                $successUrl = $this->url->getUrl(Config::SUCCESS_PAGE);
                $this->session->setBeforeAuthUrl($successUrl);
                $this->session->setAfterAuthUrl($successUrl);
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
    }
}
