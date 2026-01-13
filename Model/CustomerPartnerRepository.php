<?php
declare(strict_types=1);

namespace Aoropeza\CustomerPartner\Model;

use Aoropeza\CustomerPartner\Api\CustomerPartnerRepositoryInterface;
use Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterface;
use Aoropeza\CustomerPartner\Api\Data\CustomerPartnerInterfaceFactory;
use Aoropeza\CustomerPartner\Api\Data\CustomerPartnerSearchResultsInterfaceFactory;
use Aoropeza\CustomerPartner\Model\ResourceModel\CustomerPartner as ResourceCustomerPartner;
use Aoropeza\CustomerPartner\Model\ResourceModel\CustomerPartner\CollectionFactory as CustomerPartnerCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class CustomerPartnerRepository implements CustomerPartnerRepositoryInterface
{

    /**
     * @var CustomerPartner
     */
    protected $searchResultsFactory;

    /**
     * @var ResourceCustomerPartner
     */
    protected $resource;

    /**
     * @var CustomerPartnerInterfaceFactory
     */
    protected $customerPartnerFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var CustomerPartnerCollectionFactory
     */
    protected $customerPartnerCollectionFactory;

    protected LoggerInterface $logger;


    /**
     * @param ResourceCustomerPartner $resource
     * @param CustomerPartnerInterfaceFactory $customerPartnerFactory
     * @param CustomerPartnerCollectionFactory $customerPartnerCollectionFactory
     * @param CustomerPartnerSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceCustomerPartner $resource,
        CustomerPartnerInterfaceFactory $customerPartnerFactory,
        CustomerPartnerCollectionFactory $customerPartnerCollectionFactory,
        CustomerPartnerSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        LoggerInterface $logger
    ) {
        $this->resource = $resource;
        $this->customerPartnerFactory = $customerPartnerFactory;
        $this->customerPartnerCollectionFactory = $customerPartnerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function save(
        CustomerPartnerInterface $customerPartner
    ) {
        try {
            $this->resource->save($customerPartner);
        } catch (\Exception $th) {
            $this->logger->error("CRON CustomerPartner ERROR: ", [
                "message" => $th->getMessage(),
                "code" => $th->getCode(),
                "file" => $th->getFile(),
                "line" => $th->getLine(),
                "trace" => $th->getTraceAsString(),
            ]);
            throw new CouldNotSaveException(__(
                'Could not save the customerPartner: %1',
                $th->getMessage()
            ));
        }
        return $customerPartner;
    }

    /**
     * @inheritDoc
     */
    public function get($entityid)
    {
        $customerPartner = $this->customerPartnerFactory->create();
        $this->resource->load($customerPartner, $entityid);
        if (!$customerPartner->getId()) {
            throw new NoSuchEntityException(__('customer_partner with id "%1" does not exist.', $entityid));
        }
        return $customerPartner;
    }

    /**
     * @inheritDoc
     */
    public function getByUrl($urlkey)
    {
        $customerPartner = $this->customerPartnerFactory->create();
        $this->resource->load($customerPartner, $urlkey, 'url_key');

        if (!$customerPartner->getId()) {
            $customerPartner = [];
        }
        return $customerPartner;
    }

    /**
     * @inheritDoc
     */
    public function getCustomerGroup($groupeId)
    {
        $customerPartner = $this->customerPartnerFactory->create();
        $this->resource->load($customerPartner, $groupeId, 'customer_group_id');

        if (!$customerPartner->getId()) {
            $customerPartner = [];
        }
        return $customerPartner;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->customerPartnerCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(
        CustomerPartnerInterface $customerPartner
    ) {
        try {
            $customerPartnerModel = $this->customerPartnerFactory->create();
            $this->resource->load($customerPartnerModel, $customerPartner->getEntityId());
            $this->resource->delete($customerPartnerModel);
        } catch (\Exception $th) {
            $this->logger->error("CRON CustomerPartner ERROR: ", [
                "message" => $th->getMessage(),
                "code" => $th->getCode(),
                "file" => $th->getFile(),
                "line" => $th->getLine(),
                "trace" => $th->getTraceAsString(),
            ]);
            throw new CouldNotDeleteException(__(
                'Could not delete the customer_partner: %1',
                $th->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($entityid)
    {
        return $this->delete($this->get($entityid));
    }
}

