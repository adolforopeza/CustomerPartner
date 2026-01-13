<?php
declare(strict_types=1);

namespace Aoropeza\CustomerPartner\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerPartner extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('customer_partner', 'entity_id');
    }
}

