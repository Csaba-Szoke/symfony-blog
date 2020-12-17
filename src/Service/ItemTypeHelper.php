<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class ItemTypeHelper
{
    public function getActiveItemType($request, $searchOptionsHelper)
    {
        if ($request->get('itemType')) {
            $cookie = Cookie::create('itemType', $request->get('itemType'));
            $response = new Response();
            $response->headers->setCookie($cookie);
            $response->sendHeaders();
        }

        if (isset($response)) {
            foreach ($response->headers->getCookies() as $cookie) {
                if ($cookie->getName() == 'itemType') {
                    $activeItemType = $searchOptionsHelper->getValue('itemType', $cookie->getValue());
                }
            }
        } else {
            $activeItemType = $searchOptionsHelper->getValue('itemType', $request->cookies->get('itemType'));
        }

        return $activeItemType;
    }
}
