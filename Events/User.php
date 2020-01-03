<?php

namespace Kieran\Identity\Events;

class User {

	public static function postSave(\XF\Mvc\Entity\Entity $entity) {
		$types = self::getIdentityTypeRepo()->getIdentityTypes();

		foreach ($types as $type) {
			$repo = \XF::app()->repository($type->identity_controller);
			if (method_exists($repo, 'actionSave')) {
				$repo->actionSave($entity, $type->getIdentitiesForUser($entity->user_id));
			}
		}

	}

	private static function getIdentityTypeRepo()
	{
		return \XF::app()->repository('Kieran\Identity:IdentityType');
	}

}