<?php

namespace Kieran\Identity\Pub\Controller;

use XF\Mvc\ParameterBag;

class Member extends \XF\Pub\Controller\AbstractController
{

	public function actionIdentities(ParameterBag $params) {
		$user = $this->assertUserExists($params->user_id);
		
		
		$types = $this->getIdentityTypeRepo()->getIdentityTypes();

		$viewParams = [
			'types' => $types,
			'user_id' => $user->user_id,
		];

		return $this->view('Kieran\Identity:Identity\View', 'kieran_identity_list', $viewParams);
	}

	public function actionIdentitiesDelete() {
		return $this->redirect($this->router()->buildLink('support/tickets/manage'), \XF::phrase('kieran_support_ticket_delete'));
	}

	protected function assertUserExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('XF:User', $id, $with, $phraseKey);
	}

	public function getIdentityTypeRepo()
	{
		return $this->repository('Kieran\Identity:IdentityType');
	}

}