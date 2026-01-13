<?php
namespace Aoropeza\CustomerPartner\Model\Customer\Group;

use Aoropeza\CustomerPartner\Helper\Config;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject as ObjectConverter;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;

class Options implements OptionSourceInterface
{
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var ObjectConverter
     */
    protected $objectConverter;
    protected Config $config;

    /**
     * @param GroupRepositoryInterface $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ObjectConverter $objectConverter
     */
    public function __construct(
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ObjectConverter $objectConverter,
        Config $config
    ) {
        $this->config = $config;
        $this->groupRepository = $groupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->objectConverter = $objectConverter;
    }

    /**
     * Devuelve el array de grupos de clientes para un campo select.
     *
     * @return array
     * @throws LocalizedException
     */
    public function toOptionArray()
    {

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $groupList = $this->groupRepository->getList($searchCriteria)->getItems();

        $options = $this->objectConverter->toOptionArray(
            $groupList,
            'id',
            'code'
        );
        $exclude = explode(",", $this->config->getExcludedCustomerGroup());
        if (!empty($exclude)) {
            $optionsEx = [];
            foreach ($options as $value) {
                if (!in_array($value['value'], $exclude)) {
                    $optionsEx[] = $value;
                }
            }
            $options = $optionsEx;
        }

        array_unshift(
            $options
        );

        return $options;
    }
}
