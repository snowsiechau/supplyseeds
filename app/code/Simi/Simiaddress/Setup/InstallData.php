<?php


namespace Simi\Simiaddress\Setup;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType(AddressMetadataInterface::ENTITY_TYPE_ADDRESS);
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'area', [
            'type' => 'varchar',
            'label' => 'Area',
            'input' => 'text',
            'required' => true,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 190,
            'position' => 190,
            'system' => 0,
        ]);
        try {
            $attributeFullName = $customerSetup->getEavConfig()->getAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'area')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address'],
                ]);
            $attributeFullName->save();
        } catch (LocalizedException $e) {

        } catch (\Exception $e) {

        }

        $customerSetup->addAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'block', [
            'type' => 'varchar',
            'label' => 'Block',
            'input' => 'text',
            'required' => true,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 200,
            'position' => 200,
            'system' => 0,
        ]);
        try {
            $attributeGovernate = $customerSetup->getEavConfig()->getAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'block')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address'],
                ]);
            $attributeGovernate->save();
        } catch (LocalizedException $e) {

        } catch (\Exception $e) {

        }

        $customerSetup->addAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'avenue', [
            'type' => 'varchar',
            'label' => 'Avenue',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 230,
            'position' => 230,
            'system' => 0,
        ]);
        try {
            $attributeAvenue = $customerSetup->getEavConfig()->getAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'avenue')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address'],
                ]);
            $attributeAvenue->save();
        } catch (LocalizedException $e) {

        } catch (\Exception $e) {

        }

        $customerSetup->addAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'building_no', [
            'type' => 'varchar',
            'label' => 'Building No',
            'input' => 'text',
            'required' => true,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 240,
            'position' => 240,
            'system' => 0,
        ]);
        try {
            $attributeBuilding = $customerSetup->getEavConfig()->getAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'building_no')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address'],
                ]);
            $attributeBuilding->save();
        } catch (LocalizedException $e) {

        } catch (\Exception $e) {

        }

        $customerSetup->addAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'floor', [
            'type' => 'varchar',
            'label' => 'Floor',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 250,
            'position' => 250,
            'system' => 0,
        ]);
        try {
            $attributeFloor = $customerSetup->getEavConfig()->getAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'floor')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address'],
                ]);
            $attributeFloor->save();
        } catch (LocalizedException $e) {

        } catch (\Exception $e) {

        }

        $customerSetup->addAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'apartment', [
            'type' => 'varchar',
            'label' => 'Apartment',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 260,
            'position' => 260,
            'system' => 0,
        ]);
        try {
            $attributeFlat = $customerSetup->getEavConfig()->getAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'apartment')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address'],
                ]);
            $attributeFlat->save();
        } catch (LocalizedException $e) {

        } catch (\Exception $e) {

        }

        $customerSetup->addAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'delivery_instruction', [
            'type' => 'varchar',
            'label' => 'Delivery Instruction',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 270,
            'position' => 270,
            'system' => 0,
        ]);
        try {
            $attributePaci = $customerSetup->getEavConfig()->getAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'delivery_instruction')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address'],
                ]);
            $attributePaci->save();
        } catch (LocalizedException $e) {

        } catch (\Exception $e) {

        }

        $customerSetup->addAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'location_name', [
            'type' => 'varchar',
            'label' => 'Location Name',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 280,
            'position' => 280,
            'system' => 0,
        ]);
        try {
            $attributeNotes = $customerSetup->getEavConfig()->getAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, 'location_name')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address'],
                ]);
            $attributeNotes->save();
        } catch (LocalizedException $e) {

        } catch (\Exception $e) {

        }

    }
}
