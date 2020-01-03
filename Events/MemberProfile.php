<?php

namespace Kieran\Identity\Events;

use DOMDocument;

class MemberProfile {

	private static $user = null;

	public static function preRenderTemplate(\XF\Template\Templater $templater, &$type, &$template, array &$params) {
		
		$hint = $type . ':' . $template;

		if ($hint == 'public:member_view') {
			self::$user = $params['user'];
		}
	}

	public static function postRenderTemplate(\XF\Template\Templater $templater, $type, $template, &$output) {
		
		$hint = $type . ':' . $template;
        $visitor = \XF::visitor();
        
		if ($hint == 'public:member_view') {
			if ($visitor->hasPermission('identities', 'view')) {
				
				$link = '<a href="' . \XF::app()->router()->buildLink('members/identities', self::$user) . '"
							class="tabs-tab"
							id="identities"
							role="tab">' . \XF::phrase('identities') . '</a>';

				$panel = '<li data-href="' . \XF::app()->router()->buildLink('members/identities', self::$user) . '" role="tabpanel" aria-labelledby="identities">
							<div class="blockMessage">' . \XF::phrase('loading...') . '</div>
                        </li>';
                        
                $output = str_replace('<identitytab />', $link, $output);
                $output = str_replace('<identitypanel />', $panel, $output);
			} else {
                $output = str_replace('<identitytab />', '', $output);
                $output = str_replace('<identitypanel />', '', $output);
            }
		}
	}

	public static function routeMatch(\XF\Mvc\Dispatcher $dispatcher, \XF\Mvc\RouteMatch &$match) {

		if ($match->getController() == 'XF:Member') {
			if ($match->getAction() == 'identities') {
				$match->setController('Kieran\Identity:Member');
			} elseif ($match->getAction() == 'identities/delete') {
				$match->setController('Kieran\Identity:Member');
			}
		}
	}

}