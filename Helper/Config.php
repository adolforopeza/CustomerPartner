<?php

namespace Aoropeza\CustomerPartner\Helper;

use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
class Config extends AbstractHelper
{

    const PARNER_ENABLE = 'partner/general/enable';
    const PARNER_EXCLUDE_GROUP = 'partner/settings/exclude_customer_group';
    const PARNER_TITLE = 'partner/success/title';
    const PARNER_CONGRAT = 'partner/success/congrats';
    const PARNER_MESSAGE = 'partner/success/message';
    const NEW_ACCOUNT_REGISTER = 'is_new_account';
    const SUCCESS_PAGE = 'partner/success';
    const COOKIE_KEY = 'partner';
    const ROUTER_NAME = 'partner';
    const ROUTER_URL_KEY = 'partner_url';

    protected GroupRepositoryInterface $groupRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    /**
     * @param Context $context
     * @param GroupRepositoryInterface $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        Context $context,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {

        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->groupRepository = $groupRepository;
        parent::__construct($context);

    }

    public function isEnable()
    {
        return (boolean) $this->scopeConfig->getValue(self::PARNER_ENABLE, ScopeInterface::SCOPE_STORE);
    }

    public function getSuccessTitle()
    {
        return $this->scopeConfig->getValue(self::PARNER_TITLE, ScopeInterface::SCOPE_STORE);
    }

    public function getCongrats()
    {
        return $this->scopeConfig->getValue(self::PARNER_CONGRAT, ScopeInterface::SCOPE_STORE);
    }

    public function getMessage()
    {
        return $this->scopeConfig->getValue(self::PARNER_MESSAGE, ScopeInterface::SCOPE_STORE);
    }

    public function getExcludedCustomerGroup()
    {
        $exclude = $this->scopeConfig->getValue(self::PARNER_EXCLUDE_GROUP, ScopeInterface::SCOPE_STORE);
        return empty($exclude) ? "0" : $exclude;
    }


}
