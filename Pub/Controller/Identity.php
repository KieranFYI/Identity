<?php

namespace Kieran\Identity\Pub\Controller;

use XF\Mvc\ParameterBag;
use \XF\Pub\Controller\AbstractController;

class Identity extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
        return $this->redirect($this->router()->buildLink('account/identities'));
	}

	public function actionLogs(ParameterBag $params)
	{	
		if (!\XF::visitor()->hasPermission('identities', 'view'))
		{
			return $this->noPermission();
		}

		$identity = $this->assertViewableIdentity($params->identity_id);

		$repo = $this->repository($identity->Type->identity_controller);
		if (method_exists($repo, 'actionLogs')) {
			return $repo->actionLogs($this, $identity);
		} else {
			throw $this->exception($this->notFound(\XF::phrase('kieran_identity_logs_not_supported')));
		}
	}
	
	public function actionToggle(ParameterBag $params)
	{	
		$identity = $this->assertViewableIdentity($params->identity_id);
	
		if (!\XF::visitor()->hasPermission('identities', 'delete') && $identity->user_id != \XF::visitor()->user_id)
		{
			return $this->noPermission();
		}
		
		$identities = $this->getIdentityRepo()->findIdentityByUserIdByType($identity->user_id, $identity->identity_type_id);
		
		foreach ($identities as $i) {
			$i->status = $i->identity_id == $identity->identity_id;
			$i->save();
		}
		
		if ($identity->user_id != \XF::visitor()->user_id) {
			return $this->redirect($this->router()->buildLink('/members/' . $identity->user_id . '/#identities'));
		} else {
			return $this->redirect($this->router()->buildLink('account/identities'));
		}
	}

	public function actionDelete(ParameterBag $params)
	{
		if (!\XF::visitor()->hasPermission('identities', 'delete'))
		{
			return $this->noPermission();
		}

		$identity = $this->assertViewableIdentity($params->identity_id);

		if ($this->isPost())
		{
			$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
			$reason = $this->filter('reason', 'str');

			$log = $identity->getRelationOrDefault('DeletionLog');
			$log->setFromUser(\XF::visitor());
			$log->delete_reason = $reason;
			$log->save();

			if ($type == 'hard') {
				$identity->delete();
			} else {
				$identity->status = 2;
				$identity->save();
			}
		
			return $this->redirect($this->router()->buildLink('members/#identities', $identity->User), \XF::phrase('kieran_identity_deleted'));
		}
		else
		{
			$viewParams = [
				'identity' => $identity,
			];

			return $this->view('Kieran\Identity:Identity\Delete', 'kieran_identity_delete', $viewParams);
		}

	}

	public function actionAdd(ParameterBag $params)
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id)
		{
			return $this->noPermission();
		}


		$identity_type = $this->request->filter('identity_type_id', 'str');

		if ($params->identity_type_id) {
			$identity_type = $params->identity_type_id;
		}

		if ($identity_type) {
			$type = $this->assertViewableIdentityType($identity_type);
		
			$repo = $this->repository($type->identity_controller);

			return $repo->actionAdd($this, (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $this->router()->buildLink('identities/add/validate', $type));
		} else {
			$types = $this->getIdentityTypeRepo()->getIdentityTypes();

			$viewParams = [
				'types' => $types,
			];

			return $this->view('Kieran\Identity:Identity\Add', 'kieran_identity_add', $viewParams);
		}
	}

	public function actionAddValidate(ParameterBag $params) {
		$visitor = \XF::visitor();

		if (!$visitor->user_id)
		{
			return $this->noPermission();
		}

		$type = $this->assertViewableIdentityType($params->identity_type_id);
		$repo = $this->repository($type->identity_controller);

		return $repo->actionValidate($this, (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $this->router()->buildLink('identities/add/validate', $type));
	}

	protected function assertViewableIdentity($identity_id)
	{
		$identity = $this->getIdentityRepo()->findIdentityById($identity_id);

		if (!$identity)
		{
			throw $this->exception($this->notFound(\XF::phrase('requested_page_not_found')));
		}

		return $identity;
	}
	
	protected function assertViewableIdentityType($identity_type_id)
	{
		$identity_type = $this->getIdentityTypeRepo()->findIdentityType($identity_type_id);

		if (!$identity_type)
		{
			throw $this->exception($this->notFound(\XF::phrase('requested_page_not_found')));
		}

		return $identity_type;
	}

	public function getIdentityRepo()
	{
		return $this->repository('Kieran\Identity:Identity');
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