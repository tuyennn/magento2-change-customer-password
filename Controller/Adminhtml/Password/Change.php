<?php

namespace GhoSter\ChangeCustomerPassword\Controller\Adminhtml\Password;

class Change extends \Magento\Backend\App\Action
{
    protected $_coreRegistry;

    protected $customerRepository;
    protected $customerRegistry;
    protected $encryptor;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor
    )
    {
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