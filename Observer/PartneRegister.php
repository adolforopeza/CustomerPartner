<?php

namespace Aoropeza\CustomerPartner\Observer;

use Aoropeza\CustomerPartner\Helper\Config;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;

class PartneRegister implements ObserverInterface
{
    /** @var Registry $coreRegistry */
    protected $coreRegistry;

    /**
     * Constructor
     *
     * @param Registry $registry
     */

    public function __construct(
        Registry $registry
    ) {
        $this->coreRegistry = $registry;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD)
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $this->coreRegistry->register(Config::NEW_ACCOUNT_REGISTER, true);
    }
}
