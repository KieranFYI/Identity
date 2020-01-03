<?php

namespace Kieran\Identity\Repository;

use XF\Mvc\Entity\Repository;
use Kieran\Identity\Pub\Controller\Identity;

abstract class IdentityTypeWrapper extends Repository
{
	abstract public function actionAdd(Identity $controller, $returnURL);

	abstract public function actionValidate(Identity $controller, $returnURL);

}