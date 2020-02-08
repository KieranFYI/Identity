<?php

namespace Kieran\Identity\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Identity extends Repository
{

    public function findIdentityNeedingUpdate()
    {
        return $this->finder('Kieran\Identity:Identity')
            ->where('last_update', '<', \XF::$time - 3600)
			->where('status', '!=', 2)
            ->fetch();
    }

    /* Find all identities by user_id */
    public function findIdentityById($identity_id)
    {
        return $this->finder('Kieran\Identity:Identity')
            ->where('identity_id', '=', $identity_id)
			->where('status', '!=', 2)
            ->fetchOne();
    }

    /* Find all identities by user_id */
	public function findIdentitiesByUserId($user_id)
	{
		return $this->finder('Kieran\Identity:Identity')
            ->where('user_id', $user_id)
			->where('status', '!=', 2)
            ->fetch();
	}

	public function findIdentityByValue($identity_value)
	{
		return $this->finder('Kieran\Identity:Identity')
            ->where('identity_value', "$identity_value")
			->where('status', '!=', 2)
            ->fetchOne();
	}

	public function findIdentityByValueByType($identity_value, $identity_type_id, $ignoreDeleted=true)
	{
		$finder = $this->finder('Kieran\Identity:Identity')
            ->where('identity_value', "$identity_value")
            ->where('identity_type_id', '=', $identity_type_id);

		if ($ignoreDeleted) {
            $finder = $finder->where('status', '!=', 2);
        }

        return $finder->fetchOne();
	}
	
	/* Find identities by user_id and identity_type */
	public function findIdentityByUserIdByType($user_id, $identity_type_id)
	{
		return $this->finder('Kieran\Identity:Identity')
            ->where('user_id', $user_id)
            ->where('identity_type_id', '=', $identity_type_id)
			->where('status', '!=', 2)
            ->fetch();
	}
	
	/* Find identities by user_id and identity_type */
	public function findIdentityByUserIdTypeStatus($user_id, $identity_type_id, $status)
	{
		return $this->finder('Kieran\Identity:Identity')
            ->where('user_id', $user_id)
            ->where('identity_type_id', '=', $identity_type_id)
            ->where('status', $status)
			->where('status', '!=', 2)
            ->fetchOne();
	}
	
	/* Find an identity by identity_type and identity_value */
	public function findIdentityByTypeId($identity_type_id, $identity_value)
	{
		return $this->finder('Kieran\Identity:Identity')
            ->where('identity_type_id', '=', $identity_type_id)
            ->where('identity_value', "$identity_value")
			->where('status', '!=', 2)
            ->fetchOne();
	}
	
	/* Add an identity */
	public function addIdentity($user_id, $identity_type, $identity_name, $identity_value, $status)
	{
		$add = \XF::em()->create('Kieran\Identity:Identity');
		$add->bulkSet([
			'user_id' => $user_id,
			'identity_type_id' => $identity_type->identity_type_id,
			'identity_name' => $identity_name,
			'identity_value' => "$identity_value",
            'status' => $status
		]);
		$add->save();

		return $add;
	}
}