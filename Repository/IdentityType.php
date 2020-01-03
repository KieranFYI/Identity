<?php

namespace Kieran\Identity\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class IdentityType extends Repository
{

    /* Find all identity types */
    public function getIdentityTypes()
    {
        return $this->finder('Kieran\Identity:Type')
            ->fetch();
    }

    /* Find identity type by name */
    public function findIdentityType($identity_type)
    {
        return $this->finder('Kieran\Identity:Type')
            ->where('identity_type', $identity_type)
            ->fetchOne();
    }

	/* Add an identity */
	public function addIdentityType($identity_type_id, $identity_type, $identity_controller)
	{
		$add = \XF::em()->create('Kieran\Identity:Type');
		$add->bulkSet([
			'identity_type_id' => $identity_type_id,
			'identity_type' => $identity_type,
			'identity_controller' => $identity_controller,
		]);
		$add->save();
	}

}