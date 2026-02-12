<?php
declare(strict_types=1);

namespace GhoSter\ChangeCustomerPassword\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const XML_PATH_MINIMUM_PASSWORD_LENGTH = 'customer/password/minimum_password_length';
    private const XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER = 'customer/password/required_character_classes_number';
    private const DEFAULT_MINIMUM_PASSWORD_LENGTH = 8;
    private const DEFAULT_REQUIRED_CHARACTER_CLASSES = 3;
    private const MAX_PASSWORD_LENGTH = 256;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get minimum password length
     *
     * @return int
     */
    public function getMinimumPasswordLength(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_MINIMUM_PASSWORD_LENGTH, ScopeInterface::SCOPE_STORE
        ) ?: self::DEFAULT_MINIMUM_PASSWORD_LENGTH;
    }

    /**
     * Get maximum password length
     *
     * @return int
     */
    public function getMaximumPasswordLength(): int
    {
        return self::MAX_PASSWORD_LENGTH;
    }

    /**
     * Get required character classes number
     *
     * @return int
     */
    public function getRequiredCharacterClassesNumber(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER,
            ScopeInterface::SCOPE_STORE
        ) ?: self::DEFAULT_REQUIRED_CHARACTER_CLASSES;
    }
}
