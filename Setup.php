<?php

namespace Kieran\Identity;

use XF\Db\Schema\Create;

class Setup extends \XF\AddOn\AbstractSetup
{
	use \XF\AddOn\StepRunnerInstallTrait;
	use \XF\AddOn\StepRunnerUpgradeTrait;
	use \XF\AddOn\StepRunnerUninstallTrait;
	
	public function installStep1(array $stepParams = [])
	{
        $this->schemaManager()->createTable('xf_kieran_identity_type', function(Create $table)
        {
            $table->addColumn('identity_type_id', 'varchar', 20);
            $table->addColumn('identity_type', 'varchar', 20);
            $table->addColumn('identity_controller', 'varchar', 100);
            $table->addPrimaryKey('identity_type_id');
            $table->addUniqueKey(['identity_type_id'], 'identity_type_id');
        });
	}

    public function installStep2(array $stepParams = [])
    {
        $this->schemaManager()->createTable('xf_kieran_identity', function(Create $table)
        {
            $table->addColumn('identity_id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int');
            $table->addColumn('identity_type_id', 'varchar', 20);
            $table->addColumn('identity_name', 'varchar', 255);
            $table->addColumn('identity_value', 'varchar', 255);
            $table->addColumn('status', 'int')->setDefault(0)->comment('0 = inactive, 1 = active, 2 = deleted');
            $table->addColumn('date', 'int');
            $table->addColumn('last_update', 'int');
            $table->addPrimaryKey('identity_id');
            $table->addUniqueKey(['identity_type_id', 'identity_value', 'user_id'], 'identity_type_identity_value_user_id');
        });
        $this->schemaManager()->createTable('xf_kieran_identity_log', function(Create $table)
        {
            $table->addColumn('identity_log_id', 'int')->autoIncrement();
            $table->addColumn('identity_type_id', 'varchar', 20);
            $table->addColumn('identity_value', 'varchar', 255);
            $table->addColumn('log_type', 'varchar', 40);
            $table->addColumn('identifier', 'varchar', 255);
			$table->addColumn('data', 'text');
            $table->addColumn('date', 'int');
            $table->addPrimaryKey('identity_log_id');
            $table->addUniqueKey(['identity_type_id', 'identity_value', 'identifier'], 'identity_type_id_identity_value_identifier');
        });
    }
	
	public function upgrade(array $stepParams = [])
	{
	}
	
	public function uninstall(array $stepParams = [])
	{
		$this->schemaManager()->dropTable('xf_kieran_identity_type');
		$this->schemaManager()->dropTable('xf_kieran_identity');
		$this->schemaManager()->dropTable('xf_kieran_identity_log');
	}
}