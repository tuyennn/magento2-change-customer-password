<?php
declare(strict_types=1);

namespace GhoSter\ChangeCustomerPassword\Controller\Adminhtml\Password;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class ChangePwdPost for password changing
 */
class ChangePwdPost extends Action implements HttpPostActionInterface
{
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
     * ChangePwdPost constructor.
     *
     * @param Context $context
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerRegistry $customerRegistry
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        Context $context,
        CustomerRepositoryInterface $customerRepository,
        CustomerRegistry $customerRegistry,
        EncryptorInterface $encryptor
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerRegistry = $customerRegistry;
        $this->encryptor = $encryptor;
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
                    $customer = $this->customerRepository->getById($customerId);
                    $customerSecureRegistry = $this->customerRegistry->retrieveSecureData($customerId);
                    $customerSecureRegistry->setRpToken(null);
                    $customerSecureRegistry->setRpTokenCreatedAt(null);
                    $customerSecureRegistry->setPasswordHash($this->createPasswordHash($password));
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
     * Create password hash
     *
     * @param string $password
     * @return string
     */
    protected function createPasswordHash(string $password)
    {
        return $this->encryptor->getHash($password, true);
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Customer::manage');
    }
}
