<?php

namespace GhoSter\ChangeCustomerPassword\Block\Adminhtml\Customer;

use Magento\Backend\Block\Template;
use Magento\Framework\Registry;

/**
 * Class PasswordChange
 * @package GhoSter\ChangeCustomerPassword\Block\Adminhtml\Customer
 */
class PasswordChange extends Template
{
    protected $_coreRegistry;

    /**
     * PasswordChange constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    public function getCustomerId()
    {
        return (int)$this->getRequest()->getParam('id');
    }
}