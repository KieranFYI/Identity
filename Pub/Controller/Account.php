<?php

namespace Kieran\Identity\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class Account extends AbstractController
{

	public function actionIdentities(ParameterBag $params)
	{
        
		if (!\XF::visitor()->Auth->getAuthenticationHandler())
		{
			return $this->noPermission();
		}

		$types = $this->getIdentityTypeRepo()->getIdentityTypes();

		$viewParams = [
			'types' => $types,
		];

		return $this->view('Kieran\Identity:Identity\View', 'kieran_identity', $viewParams);
    }
    
	public function getIdentityTypeRepo()
	{
		return $this->repository('Kieran\Identity:IdentityType');
	}

	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('managing_account_details');
	}

}