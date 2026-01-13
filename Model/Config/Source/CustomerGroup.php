<?php
declare(strict_types=1);

namespace Aoropeza\CustomerPartner\Model\Config\Source;

use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject as ObjectConverter;

class CustomerGroup implements \Magento\Framework\Option\ArrayInterface
{
    protected GroupRepositoryInterface $groupRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected ObjectConverter $objectConverter;

    /**
     * @param GroupRepositoryInterface $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ObjectConverter $objectConverter
     */
    public function __construct(
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ObjectConverter $objectConverter
    ) {
        $this->objectConverter = $objectConverter;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->groupRepository = $groupRepository;
    }
    public function toOptionArray()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $groupList = $this->groupRepository->getList($searchCriteria)->getItems();

        $options = $this->objectConverter->toOptionArray(
            $groupList,
            'id',
            'code'
        );

        array_unshift(
            $options
        );

        return $options;
    }

    public function toArray()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $groupList = $this->groupRepository->getList($searchCriteria)->getItems();

        $options = $this->objectConverter->toOptionHash(
            $groupList,
            'id',
            'code'
        );

        array_unshift(
            $options
        );

        return $options;
    }
}
