<?php

declare(strict_types=1);

namespace GhoSter\ChangeCustomerPassword\Model;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Stdlib\StringUtils;

class PasswordValidator
{
    /**
     * @var Config
     */
    private $passwordConfig;

    /**
     * @var StringUtils
     */
    private $stringHelper;

    /**
     * PasswordValidator constructor.
     *
     * @param Config $passwordConfig
     * @param StringUtils $stringHelper
     */
    public function __construct(Config $passwordConfig, StringUtils $stringHelper)
    {
        $this->passwordConfig = $passwordConfig;
        $this->stringHelper = $stringHelper;
    }

    /**
     * Validate password
     *
     * @param string $password
     *
     * @return void
     * @throws InputException
     */
    public function validate(string $password)
    {
        $this->validateLength($password);
        $this->validateWhitespace($password);
        $this->validateCharacterClasses($password);
    }

    /**
     * Validate password length
     *
     * @param string $password
     *
     * @return void
     * @throws InputException
     */
    private function validateLength(string $password)
    {
        $minLength = $this->passwordConfig->getMinimumPasswordLength();
        if ($this->stringHelper->strlen($password) < $minLength) {
            throw new InputException(
                __(
                    'The password needs at least %1 characters. Create a new password and try again.',
                    $minLength
                )
            );
        }

        $maxLength = $this->passwordConfig->getMaximumPasswordLength();
        if ($this->stringHelper->strlen($password) > $maxLength) {
            throw new InputException(
                __(
                    'Please enter a password with at most %1 characters.',
                    $maxLength
                )
            );
        }
    }

    /**
     * Validate password trailing and leading whitespace
     *
     * @param string $password
     *
     * @return void
     * @throws InputException
     */
    private function validateWhitespace(string $password)
    {
        if ($password !== trim($password)) {
            throw new InputException(
                __("The password can't begin or end with a space. Verify the password and try again.")
            );
        }
    }

    /**
     * Validate password character classes
     *
     * @param string $password
     *
     * @return void
     * @throws InputException
     */
    private function validateCharacterClasses(string $password)
    {
        $requiredCharClassesNumber = $this->passwordConfig->getRequiredCharacterClassesNumber();
        $characterClassesCount = 0;

        if (preg_match('/\d+/', $password)) {
            $characterClassesCount++;
        }

        if (preg_match('/[a-z]+/', $password)) {
            $characterClassesCount++;
        }

        if (preg_match('/[A-Z]+/', $password)) {
            $characterClassesCount++;
        }

        if (preg_match('/[^a-zA-Z0-9]+/', $password)) {
            $characterClassesCount++;
        }

        if ($characterClassesCount < $requiredCharClassesNumber) {
            throw new InputException(
                __(
                    'Minimum of different classes of characters in password is %1.'
                    . ' Classes of characters: Lower Case, Upper Case, Digits, Special Characters.',
                    $requiredCharClassesNumber
                )
            );
        }
    }
}
