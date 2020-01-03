<?php

namespace Kieran\Identity\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class IdentityLog extends Repository
{

	/* Add an identity */
	public function addIdentityLog($identity_type_id, $identity_value, $log_type, $identifier, $data, $date)
	{
		$add = \XF::em()->create('Kieran\Identity:IdentityLog');
		$add->bulkSet([
			'identity_type_id' => $identity_type_id,
			'identity_value' => $identity_value,
			'log_type' => $log_type,
			'identifier' => $identifier,
			'data' => $data,
			'date' => $date,
		]);
		$add->save();
	}

	/* Add an identity */
	public function findLogByIdentifier($identifier)
	{
		return $this->finder('Kieran\Identity:IdentityLog')
            ->where('identifier', $identifier)
            ->fetchOne();
	}

}