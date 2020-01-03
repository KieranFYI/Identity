<?php

namespace Kieran\Identity\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Type extends Entity
{

	public function getIdentitiesForUser($user_id)
	{
		return $this->repository('Kieran\Identity:Identity')->findIdentityByUserIdByType($user_id, $this->identity_type_id);
	}

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_kieran_identity_type';
        $structure->shortName = 'Kieran\Identity:Type';
        $structure->primaryKey = 'identity_type_id';
        $structure->columns = [
            'identity_type_id' => ['type' => self::STR, 'maxLength' => 20, 'nullable' => false, 'changeLog' => false],
            'identity_type' => ['type' => self::STR, 'maxLength' => 20],
            'identity_controller' =>  ['type' => self::STR, 'maxLength' => 100, 'default' => ''],
        ];
		$structure->relations = [
            'Identities' => [
                'entity' => 'Kieran\Identity:Identity',
                'type' => self::TO_MANY,
                'conditions' => 'identity_type_id'
            ],
		];

        return $structure;
    }
}