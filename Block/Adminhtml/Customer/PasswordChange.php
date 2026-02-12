<?php
declare(strict_types=1);

namespace GhoSter\ChangeCustomerPassword\Block\Adminhtml\Customer;

use GhoSter\ChangeCustomerPassword\Model\Config;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;

/**
 * Class PasswordChange for block
 */
class PasswordChange extends Template
{
    /**
     * @var Config
     */
    private $passwordConfig;

    /**
     * PasswordChange constructor.
     *
     * @param Context $context
     * @param Config $passwordConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $passwordConfig,
        array $data = []
    ) {
        $this->passwordConfig = $passwordConfig;
        parent::__construct($context, $data);
    }

    /**
     * Get current customer id
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return (int)$this->getRequest()->getParam('id');
    }

    /**
     * Get minimum password length from config
     *
     * @return int
     */
    public function getMinimumPasswordLength(): int
    {
        return $this->passwordConfig->getMinimumPasswordLength();
    }

    /**
     * Get required number of character classes from config
     *
     * @return int
     */
    public function getRequiredCharacterClassesNumber(): int
    {
        return $this->passwordConfig->getRequiredCharacterClassesNumber();
    }
}
