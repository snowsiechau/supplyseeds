<?php


namespace Simi\Simiaddress\Setup;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    private $eavSetupFactory;

    private $eavConfig;

    private $attributeResource;

    /**
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Customer\Model\ResourceModel\Attribute $attributeResource
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->attributeResource = $attributeResource;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $salesSetup = $objectManager->create('Magento\Sales\Setup\SalesSetup');

            $salesSetup->addAttribute('order_address', 'area', ['type' =>'varchar']);
            $salesSetup->addAttribute('order_address', 'block', ['type' =>'varchar']);
            $salesSetup->addAttribute('order_address', 'avenue', ['type' =>'varchar']);
            $salesSetup->addAttribute('order_address', 'building_no', ['type' =>'varchar']);
            $salesSetup->addAttribute('order_address', 'floor', ['type' =>'varchar']);
            $salesSetup->addAttribute('order_address', 'apartment', ['type' =>'varchar']);
            $salesSetup->addAttribute('order_address', 'delivery_instruction', ['type' =>'text']);
            $salesSetup->addAttribute('order_address', 'location_name', ['type' =>'text']);

            $quoteSetup = $objectManager->create('Magento\Quote\Setup\QuoteSetup');
            $quoteSetup->addAttribute('quote_address', 'area', ['type' =>'varchar']);
            $quoteSetup->addAttribute('quote_address', 'block', ['type' =>'varchar']);
            $quoteSetup->addAttribute('quote_address', 'avenue', ['type' =>'varchar']);
            $quoteSetup->addAttribute('quote_address', 'building_no', ['type' =>'varchar']);
            $quoteSetup->addAttribute('quote_address', 'floor', ['type' =>'varchar']);
            $quoteSetup->addAttribute('quote_address', 'apartment', ['type' =>'varchar']);
            $quoteSetup->addAttribute('quote_address', 'delivery_instruction', ['type' =>'text']);
            $quoteSetup->addAttribute('quote_address', 'location_name', ['type' =>'text']);
        }

        if (version_compare($context->getVersion(), '1.0.6') < 0) {
            $customField = "simi_phone";
            $customFieldLabel = "Telephone";
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->removeAttribute(Customer::ENTITY, $customField);
            $attributeSetId = $eavSetup->getDefaultAttributeSetId(Customer::ENTITY);
            $attributeGroupId = $eavSetup->getDefaultAttributeGroupId(Customer::ENTITY);
            $eavSetup->addAttribute(Customer::ENTITY, $customField, [
                'type' => 'varchar',
                'label' => $customFieldLabel,
                'input' => 'text',
                'required' => true,
                'visible' => true,
                'user_defined' => true,
                'sort_order' => 990,
                'position' => 990,
                'system' => 0,
            ]);
            $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, $customField);
            $attribute->setData('attribute_set_id', $attributeSetId);
            $attribute->setData('attribute_group_id', $attributeGroupId);
            $attribute->setData('used_in_forms', [
                'adminhtml_customer',
                'customer_account_create',
                'customer_account_edit'
            ]);
            $this->attributeResource->save($attribute);
        }

        if (version_compare($context->getVersion(), '1.0.7') < 0) {
            $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'simi_phone');
            $attribute->setData('is_used_in_grid', 1);
            $attribute->setData('is_visible_in_grid', 1);
            $this->attributeResource->save($attribute);
        }

    }
}
