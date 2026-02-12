<?php
declare(strict_types=1);

namespace GhoSter\ChangeCustomerPassword\Controller\Adminhtml\Password;

use Exception;
use GhoSter\ChangeCustomerPassword\Model\PasswordValidator;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\AddressRegistry;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\InputException;

/**
 * Class ChangePwdPost for password changing
 */
class ChangePwdPost extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'GhoSter_ChangeCustomerPassword::change_password';

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var CustomerRegistry
     */
    protected $customerRegistry;

    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * @var AddressRegistry
     */
    private $addressRegistry;

    /**
     * @var PasswordValidator
     */
    private $passwordValidator;

    /**
     * ChangePwdPost constructor.
     *
     * @param Context $context
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerRegistry $customerRegistry
     * @param EncryptorInterface $encryptor
     * @param AddressRegistry|null $addressRegistry
     * @param PasswordValidator|null $passwordValidator
     */
    public function __construct(
        Context $context,
        CustomerRepositoryInterface $customerRepository,
        CustomerRegistry $customerRegistry,
        EncryptorInterface $encryptor,
        ?AddressRegistry $addressRegistry = null,
        ?PasswordValidator $passwordValidator = null
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerRegistry = $customerRegistry;
        $this->encryptor = $encryptor;
        $this->addressRegistry = $addressRegistry ?: ObjectManager::getInstance()->get(AddressRegistry::class);
        $this->passwordValidator = $passwordValidator ?: ObjectManager::getInstance()->get(PasswordValidator::class);
        parent::__construct($context);
    }

    /**
     * Change pwd action
     *
     * @return Redirect
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $customerId = (int)$this->getRequest()->getPost('customer_id');
        $password = trim($this->getRequest()->getPost('new_customer_pwd'));

        if ($customerId) {
            try {
                if (empty($password)) {
                    $this->messageManager->addErrorMessage(__('Password can not be empty'));
                } else {
                    $this->passwordValidator->validate($password);
                    $customer = $this->customerRepository->getById($customerId);
                    $customerSecureRegistry = $this->customerRegistry->retrieveSecureData($customerId);
                    $customerSecureRegistry->setRpToken(null);
                    $customerSecureRegistry->setRpTokenCreatedAt(null);
                    $customerSecureRegistry->setPasswordHash($this->createPasswordHash($password));

                    // No need to validate customer and customer address while saving customer reset password token
                    $this->disableAddressValidation($customer);
                    $this->setIgnoreValidationFlag($customer);

                    $this->customerRepository->save($customer, $this->createPasswordHash($password));
                    $this->messageManager->addSuccessMessage(__('Password has been updated successfully.'));
                }

            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Error: %1', $e->getMessage()));
            }

            return $resultRedirect->setPath('*/index/edit', ['id' => $customerId]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Disable Customer Address Validation
     *
     * @param CustomerInterface $customer
     * @throws NoSuchEntityException
     */
    private function disableAddressValidation($customer)
    {
        foreach ($customer->getAddresses() as $address) {
            $addressModel = $this->addressRegistry->retrieve($address->getId());
            $addressModel->setShouldIgnoreValidation(true);
        }
    }

    /**
     * Set ignore_validation_flag for reset password flow to skip unnecessary address and customer validation
     *
     * @param Customer $customer
     * @return void
     */
    private function setIgnoreValidationFlag($customer)
    {
        $customer->setData('ignore_validation_flag', true);
    }

    /**
     * Create password hash
     *
     * @param string $password
     * @return string
     */
    protected function createPasswordHash(string $password)
    {
        return $this->encryptor->getHash($password, true);
    }
}
