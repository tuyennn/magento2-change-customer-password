<?php

namespace GhoSter\ChangeCustomerPassword\Controller\Adminhtml\Password;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\Encryption\EncryptorInterface;

/**
 * Class Change
 * @package GhoSter\ChangeCustomerPassword\Controller\Adminhtml\Password
 */
class Change extends Action
{
    /** @var Registry */
    protected $_coreRegistry;

    /** @var CustomerRepositoryInterface */
    protected $customerRepository;

    /** @var CustomerRegistry */
    protected $customerRegistry;

    /** @var EncryptorInterface */
    protected $encryptor;


    /**
     * Change constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerRegistry $customerRegistry
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        CustomerRepositoryInterface $customerRepository,
        CustomerRegistry $customerRegistry,
        EncryptorInterface $encryptor
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->customerRepository = $customerRepository;
        $this->customerRegistry = $customerRegistry;
        $this->encryptor = $encryptor;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {

            $customerId = $this->getRequest()->getParam('customer_id');
            try {

                $password = trim($this->getRequest()->getParam('new_customer_pwd'));

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

            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Error: %1', $e->getMessage()));
            }

            return $resultRedirect->setPath('*/index/edit', ['id' => $this->getRequest()->getParam('customer_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    public function createPasswordHash($password)
    {
        return $this->encryptor->getHash($password, true);
    }

    protected function _isAllowed()
    {
        return true;
    }
}