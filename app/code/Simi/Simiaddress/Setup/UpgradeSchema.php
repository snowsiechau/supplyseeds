<?php

/**
 * Copyright © 2018 Simi. All rights reserved.
 */

namespace Simi\Simiaddress\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        //handle all possible upgrade versions

        if(!$context->getVersion()) {
            //no previous version found, installation, InstallSchema was just executed
            //be careful, since everything below is true for installation !
        }

        if (version_compare($context->getVersion(), '1.0.5') < 0) {
            $table_firstlogin = $setup->getTable('simiaddress_area');
            $this->checkTableExist($setup, $table_firstlogin, 'simiaddress_area');
            $table_video = $setup->getConnection()->newTable(
                $table_firstlogin
            )->addColumn(
                'area_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Area Id'
            )->addColumn(
                'area_label',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Area Label'
            )->addColumn(
                'area_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Area Code'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Status'
            );
            $setup->getConnection()->createTable($table_video);
        }

        $setup->endSetup();
    }

    public function checkTableExist($installer, $table_key_name, $table_name)
    {
        if ($installer->getConnection()->isTableExists($table_key_name) == true) {
            $installer->getConnection()
                ->dropTable($installer->getConnection()->getTableName($table_name));
        }
    }
}
