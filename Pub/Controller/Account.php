<?php

namespace Kieran\Identity\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class Account extends AbstractController
{

	public function actionIdentities(ParameterBag $params)
	{

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

}