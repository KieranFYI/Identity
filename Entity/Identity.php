<?php

namespace Kieran\Identity\Entity;
    
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Identity extends Entity
{
    public function canView()
    {
        $visitor = \XF::visitor();

        if (!$visitor->user_id)
        {
            return false;
        }
    }

    public function canDelete($type)
    {
		if ($type == 'hard') {
			return \XF::visitor()->hasPermission('identities', 'hard_delete');
		}

        return \XF::visitor()->hasPermission('identities', 'delete');
    }

    public function getIdentityType()
    {
        return $this->Type->identity_type;
    }

    public static function getStructure(Structure $structure)
	{
        $structure->table = 'xf_kieran_identity';
        $structure->shortName = 'Kieran\Identity:Identity';
        $structure->primaryKey = 'identity_id';
        $structure->columns = [
			'identity_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => false, 'changeLog' => false],
            'user_id' =>  ['type' => self::UINT],
			'identity_type_id' => ['type' => self::STR, 'maxLength' => 20],
			'identity_name' =>  ['type' => self::STR, 'maxLength' => 255],
			'identity_value' =>  ['type' => self::STR, 'maxLength' => 255],
			'status' =>  ['type' => self::UINT, 'default' => 0],
			'date' => ['type' => self::UINT, 'default' => \XF::$time],
			'last_update' => ['type' => self::UINT, 'default' => \XF::$time],
        ];
        $structure->getters = [
            'identity_type' => true,
        ];
        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_id', '=', '$user_id']
                ],
                'primary' => true
            ],
            'Type' => [
                'entity' => 'Kieran\Identity:Type',
                'type' => self::TO_ONE,
                'conditions' => 'identity_type_id'
            ],
            'Logs' => [
                'entity' => 'Kieran\Identity:IdentityLog',
                'type' => self::TO_MANY,
                'conditions' => ['identity_type_id', 'identity_value'],
				'order' => ['date', 'desc'],
            ],
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'identity'],
					['content_id', '=', '$identity_id']
				],
				'primary' => true
			],
        ];

        return $structure;
    }
}