<?php

namespace Kieran\Identity\Cron;

class Update
{
	public static function update()
	{
		
		$identities = self::getIdentityRepo()->findIdentityNeedingUpdate();

		foreach ($identities as $identity) {
			$repo = \XF::repository($identity->Type->identity_controller);
			if (method_exists($repo, 'actionUpdate')) {
				$repo->actionUpdate($identity);
			}

			$identity->last_update = \XF::$time;
			$identity->save();
			if ((time() - \XF::$time) > 50) {
				break;
			}
		}
	}

	public static function collectLogs()
	{
		
		$types = self::getIdentityTypeRepo()->getIdentityTypes();
		foreach ($types as $type) {
			$repo = \XF::repository($type->identity_controller);
			if (method_exists($repo, 'actionCollectLogs')) {
				$repo->actionCollectLogs($type);
			}

			if ((time() - \XF::$time) > 50) {
				break;
			}
		}
	}

	private static function getIdentityRepo()
	{
		return \XF::repository('Kieran\Identity:Identity');
	}

	private static function getIdentityTypeRepo()
	{
		return \XF::repository('Kieran\Identity:IdentityType');
	}
}