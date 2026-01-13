<?php
declare(strict_types=1);

namespace Aoropeza\CustomerPartner\Api\Data;

interface CustomerPartnerInterface
{

    const CREATED = 'created';
    const NAME = 'name';
    const UPDATED = 'updated';
    const ENTITY_ID = 'entity_id';
    const DESCRIPTION = 'description';
    const CUSTOMER_GROUP_ID = 'customer_group_id';
    const IS_ACTIVE = 'is_active';
    const URL_KEY = 'url_key';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Aoropeza\CustomerPartner\CustomerPartner\Api\Data\CustomerPartnerInterface
     */
    public function setId($id);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Aoropeza\CustomerPartner\CustomerPartner\Api\Data\CustomerPartnerInterface
     */
    public function setEntityId($entityId);

    /**
     * Get url_key
     * @return string|null
     */
    public function getUrlKey();

    /**
     * Set url_key
     * @param string $urlKey
     * @return \Aoropeza\CustomerPartner\CustomerPartner\Api\Data\CustomerPartnerInterface
     */
    public function setUrlKey($urlKey);

    /**
     * Get customer_group_id
     * @return string|null
     */
    public function getCustomerGroupId();

    /**
     * Set customer_group_id
     * @param string $customerGroupId
     * @return \Aoropeza\CustomerPartner\CustomerPartner\Api\Data\CustomerPartnerInterface
     */
    public function setCustomerGroupId($customerGroupId);

    /**
     * Get is_active
     * @return string|null
     */
    public function getIsActive();

    /**
     * Set is_active
     * @param string $isActive
     * @return \Aoropeza\CustomerPartner\CustomerPartner\Api\Data\CustomerPartnerInterface
     */
    public function setIsActive($isActive);

    /**
     * Get created
     * @return string|null
     */
    public function getCreated();

    /**
     * Set created
     * @param string $created
     * @return \Aoropeza\CustomerPartner\CustomerPartner\Api\Data\CustomerPartnerInterface
     */
    public function setCreated($created);

    /**
     * Get updated
     * @return string|null
     */
    public function getUpdated();

    /**
     * Set updated
     * @param string $updated
     * @return \Aoropeza\CustomerPartner\CustomerPartner\Api\Data\CustomerPartnerInterface
     */
    public function setUpdated($updated);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Aoropeza\CustomerPartner\CustomerPartner\Api\Data\CustomerPartnerInterface
     */
    public function setName($name);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Aoropeza\CustomerPartner\CustomerPartner\Api\Data\CustomerPartnerInterface
     */
    public function setDescription($description);
}

