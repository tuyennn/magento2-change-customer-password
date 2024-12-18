<?php
declare(strict_types=1);

namespace GhoSter\ChangeCustomerPassword\Command;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResource;
use Magento\Framework\App\Area;
use Magento\Framework\App\State as AppState;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command console for pwd change
 */
class CustomerChangePasswordCommand extends Command
{
    /**
     * @var CustomerResource
     */
    private $customerResource;

    /**
     * @var CustomerRegistry
     */
    private $customerRegistry;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var AppState
     */
    private $appState;

    /**
     * CustomerChangePasswordCommand constructor
     *
     * @param StoreManagerInterface $storeManager
     * @param CustomerResource $resource
     * @param CustomerRegistry $customerRegistry
     * @param AppState $state
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        CustomerResource $resource,
        CustomerRegistry $customerRegistry,
        AppState $state
    ) {
        parent::__construct();
        $this->customerFactory = $customerFactory;
        $this->customerResource = $resource;
        $this->customerRegistry = $customerRegistry;
        $this->storeManager = $storeManager;
        $this->appState = $state;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('customer:change-password');
        $this->setDescription('Set a customers password');
        $this->addOption(
            'website',
            'w',
            InputOption::VALUE_OPTIONAL,
            'Website code if customer accounts are website scope'
        );
        $this->addArgument('email', InputArgument::REQUIRED, 'Customer Email');
        $this->addArgument('password', InputArgument::REQUIRED, 'Password to set');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        try {
            $this->appState->setAreaCode(Area::AREA_ADMINHTML);
            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock.DetectedCatch
        } catch (LocalizedException $exception) {
        }

        try {
            $customer = $this->getCustomerByEmail($this->getEmail());
            $customer->setPassword($this->getPassword());
            $this->customerResource->save($customer);
            $output->writeln(sprintf('Updated password for customer "%s".', $this->getEmail()));
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Get input email
     *
     * @return string
     */
    private function getEmail(): string
    {
        return $this->input->getArgument('email') ?? '';
    }

    /**
     * Get input password
     *
     * @return string
     */
    private function getPassword(): string
    {
        return $this->input->getArgument('password') ?? '';
    }

    /**
     * Get input website code
     *
     * @return string
     */
    private function getWebsiteCode(): string
    {
        return $this->input->getOption('website') ?? '';
    }

    /**
     * Get website id by code
     *
     * @param string $code
     * @return int
     * @throws LocalizedException
     */
    private function getWebsiteIdByCode(string $code): int
    {
        $website = $this->storeManager->getWebsite($code);
        if (!$website->getId()) {
            throw new \InvalidArgumentException(sprintf('No website with ID "%s" found.', $code));
        }

        return (int)$website->getId();
    }

    /**
     * Get customer by provided email
     *
     * @param string $email
     * @return Customer
     * @throws LocalizedException
     */
    private function getCustomerByEmail(string $email): Customer
    {
        $websiteId = $this->getWebsiteCode() ? $this->getWebsiteIdByCode($this->getWebsiteCode()) : null;
        $customer = $this->customerRegistry->retrieveByEmail($email, $websiteId);
        if (!$customer->getId()) {
            throw new \InvalidArgumentException(sprintf('No customer with email "%s" found.', $this->getEmail()));
        }

        return $customer;
    }
}
