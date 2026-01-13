<?php
declare(strict_types=1);

namespace Aoropeza\CustomerPartner\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CustomerPartnerRepositoryInterface
{

    /**
     * Save customer_partner
     * @param \Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterface $customerPartner
     * @return \Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterface $customerPartner
    );

    /**
     * Retrieve customer_partner
     * @param string $entityid
     * @return \Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($entityid);

    /**
     * Retrieve customer_partner
     * @param string $urlkey
     * @return \Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByUrl($urlkey);

    /**
     * Retrieve customer_partner
     * @param string $groupeId
     * @return \Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerGroup($groupeId);

    /**
     * Retrieve customer_partner matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aoropeza\CustomerPartner\Api\Data\CustomerPartnerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete customer_partner
     * @param \Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterface $customerPartner
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterface $customerPartner
    );

    /**
     * Delete customer_partner by ID
     * @param string $entityid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityid);
}

