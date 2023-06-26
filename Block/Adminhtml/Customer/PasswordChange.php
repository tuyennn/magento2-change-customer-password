<?php
declare(strict_types=1);

namespace GhoSter\ChangeCustomerPassword\Block\Adminhtml\Customer;

use Magento\Backend\Block\Template;

/**
 * Class PasswordChange for block
 */
class PasswordChange extends Template
{
    /**
     * Get current customer id
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return (int)$this->getRequest()->getParam('id');
    }
}
