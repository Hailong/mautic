<?php

/*
 * @copyright   2016 Mautic, Inc. All rights reserved
 * @author      Mautic, Inc
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticShareBundle\Controller;

use Mautic\CoreBundle\Controller\AjaxController as CommonAjaxController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class class PublicController extends CommonController.
 */
class AjaxController extends CommonAjaxController
{
    public function onShareAction(Request $request)
    {
        $data = [];
        if ($this->get('mautic.security')->isAnonymous()) {
            $pct         = $request->request->get('p');
            $ct          = $request->request->get('c');
            $source      = $request->request->get('s');
            $target      = $request->request->get('t');
            $status      = $request->request->get('a');
            $url         = $request->request->get('u');
            $title       = $request->request->get('l');
            $image       = $request->request->get('i');
            $fingerprint = $request->request->get('f');

            $model     = $this->getModel('share');
            $shareCode = $model->parseClickThrough($ct);

            $model->createPageShare($pct, $shareCode, $source, $target, $status, $url, $title, $image, $fingerprint);

            $data = ['c' => $model->generateClickThrough()];
        }

        return new JsonResponse($data);
    }
}
