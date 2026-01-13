<?php

namespace Aoropeza\CustomerPartner\Block;

use Aoropeza\CustomerPartner\Helper\Config;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Success extends Template
{
    /** @var Config */
    private $configHelper;

    /**
     * @param Context $context
     * @param Config $configHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $configHelper,
        array $data = []
    ) {
        $this->configHelper = $configHelper;
        parent::__construct($context, $data);
    }

    /**
     * Returns title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->configHelper->getSuccessTitle();
    }

    /**
     * Returns congrats
     * @return string
     */
    public function getCongrats(): string
    {
        return $this->configHelper->getCongrats();
    }

    /**
     * Returns success message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->configHelper->getMessage();
    }
}
