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

	public static function getActivityDetails(array $activities)
	{
		$userIds = [];
		$userData = [];

		$router = \XF::app()->router('public');
		$defaultPhrase = \XF::phrase('viewing_members');

		if (!\XF::visitor()->hasPermission('general', 'viewProfile'))
		{
			return $defaultPhrase;
		}

		foreach ($activities AS $activity)
		{
			$userId = $activity->pluckParam('user_id');
			if ($userId)
			{
				$userIds[$userId] = $userId;
			}
		}

		if ($userIds)
		{
			$users = \XF::em()->findByIds('XF:User', $userIds, 'Privacy');
			foreach ($users AS $user)
			{
				$userData[$user->user_id] = [
					'username' => $user->username,
					'url' => $router->buildLink('members', $user),
				];
			}
		}

		$output = [];

		foreach ($activities AS $key => $activity)
		{
			$userId = $activity->pluckParam('user_id');
			$user = $userId && isset($userData[$userId]) ? $userData[$userId] : null;
			if ($user)
			{
				$output[$key] = [
					'description' => \XF::phrase('viewing_member_profile'),
					'title' => $user['username'],
					'url' => $user['url']
				];
			}
			else
			{
				$output[$key] = $defaultPhrase;
			}
		}

		return $output;
	}

}