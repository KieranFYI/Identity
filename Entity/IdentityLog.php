<?php

namespace Kieran\Identity\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class IdentityLog extends Entity
{

	public function getIdentitiesForUser($user_id)
	{
		return $this->repository('Kieran\Identity:Identity')->findIdentityByUserIdByType($user_id, $this->identity_type_id);
	}

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_kieran_identity_log';
        $structure->shortName = 'Kieran\Identity:IdentityLog';
        $structure->primaryKey = 'identity_log_id';
        $structure->columns = [
			'identity_log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => false, 'changeLog' => false],
            'identity_type_id' => ['type' => self::STR, 'maxLength' => 20],
            'identity_value' => ['type' => self::STR, 'maxLength' => 255],
            'log_type' => ['type' => self::STR, 'maxLength' => 40],
            'identifier' => ['type' => self::STR, 'maxLength' => 255],
            'data' => ['type' => self::JSON_ARRAY, 'default' => []],
            'date' => ['type' => self::UINT, 'default' => \XF::$time],
        ];
		$structure->relations = [
            'Identity' => [
                'entity' => 'Kieran\Identity:Identity',
                'type' => self::TO_ONE,
                'conditions' => 'identity_id'
            ],
		];

        return $structure;
    }
}