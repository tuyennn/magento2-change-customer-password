<?php

namespace GhoSter\ChangeCustomerPassword\Block\Adminhtml\Customer;

class PasswordChange extends \Magento\Backend\Block\Template
{
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    public function getCustomerId()
    {
        return (int)$this->getRequest()->getParam('id');
    }
}