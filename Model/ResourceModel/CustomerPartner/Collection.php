<?php
declare(strict_types=1);

namespace Aoropeza\CustomerPartner\Model\ResourceModel\CustomerPartner;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Aoropeza\CustomerPartner\Model\CustomerPartner::class,
            \Aoropeza\CustomerPartner\Model\ResourceModel\CustomerPartner::class
        );
    }
}

