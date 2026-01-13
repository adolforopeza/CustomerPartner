<?php
declare(strict_types=1);

namespace Aoropeza\CustomerPartner\Api\Data;

interface CustomerPartnerSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get customer_partner list.
     * @return \Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterface[]
     */
    public function getItems();

    /**
     * Set url_key list.
     * @param \Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

