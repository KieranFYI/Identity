<?php

namespace Kieran\Identity\Repository;

use XF\Mvc\Entity\Repository;

abstract class IdentityTypeWrapper extends Repository
{
	abstract public function actionAdd(\Kieran\Identity\Pub\Controller\Identity $controller, $returnURL);

	abstract public function actionValidate(\Kieran\Identity\Pub\Controller\Identity $controller, $returnURL);

}