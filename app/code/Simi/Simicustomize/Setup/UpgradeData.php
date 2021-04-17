<?php


namespace Simi\Simicustomize\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
	public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory)
	{
	        $this->eavSetupFactory = $eavSetupFactory;
	}

	public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{
	        $setup->startSetup();
	 
	    	if (version_compare($context->getVersion(), '1.0.1', '<')) {
	            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
	 
	            $eavSetup->addAttribute(
					\Magento\Catalog\Model\Category::ENTITY,
					'best_seller_product',
					[
						'type'         => 'varchar',
						'label'        => 'Best Seller Product',
						'input'        => 'text',
						'sort_order'   => 100,
						'source'       => '',
						'global'       => 1,
						'visible'      => true,
						'required'     => false,
						'user_defined' => false,
						'default'      => null,
						'group'        => '',
						'backend'      => ''
					]
				);
	    	}
	 
	        $setup->endSetup();
	}

	

	// public function install(
	// 	ModuleDataSetupInterface $setup,
	// 	ModuleContextInterface $context
	// )
	// {
	// 	$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

	// 	$eavSetup->addAttribute(
	// 		\Magento\Catalog\Model\Category::ENTITY,
	// 		'best_seller_product',
	// 		[
	// 			'type'         => 'varchar',
	// 			'label'        => 'Best Seller Product',
	// 			'input'        => 'text',
	// 			'sort_order'   => 100,
	// 			'source'       => '',
	// 			'global'       => 1,
	// 			'visible'      => true,
	// 			'required'     => false,
	// 			'user_defined' => false,
	// 			'default'      => null,
	// 			'group'        => '',
	// 			'backend'      => ''
	// 		]
	// 	);
	// }
}