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

use Mautic\CoreBundle\Controller\FormController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ShareController.
 */
class ShareController extends FormController
{
    public function rankingAction()
    {
        $model = $this->getModel('share.report');

        return $this->delegateView(
            [
                'viewParameters' => [
                    // 'activeMonitoring' => $monitoringEntity,
                    // 'logs'             => $logs,
                    // 'tmpl'             => $tmpl,
                    // 'security'         => $security,
                    // 'leadStats'        => $chart->render(),
                    // 'monitorLeads'     => $this->forward(
                    //     'MauticSocialBundle:Monitoring:contacts',
                    //     [
                    //         'objectId'   => $monitoringEntity->getId(),
                    //         'page'       => $page,
                    //         'ignoreAjax' => true,
                    //     ]
                    // )->getContent(),
                    // 'dateRangeForm' => $dateRangeForm->createView(),
                ],
                'contentTemplate' => 'MauticShareBundle:Share:top.html.php',
                // 'passthroughVars' => [
                //     'activeLink'    => '#mautic_social_index',
                //     'mauticContent' => 'monitoring',
                // ],
            ]
        );

        // $session = $this->get('session');

        // /** @var \MauticPlugin\MauticSocialBundle\Model\MonitoringModel $model */
        // $model = $this->getModel('social.monitoring');

        // /** @var \MauticPlugin\MauticSocialBundle\Entity\PostCountRepository $postCountRepo */
        // $postCountRepo = $this->getModel('social.postcount')->getRepository();

        // $security         = $this->container->get('mautic.security');
        // $monitoringEntity = $model->getEntity($objectId);

        // //set the asset we came from
        // $page = $session->get('mautic.social.monitoring.page', 1);

        // $tmpl = $this->request->isXmlHttpRequest() ? $this->request->get('tmpl', 'details') : 'details';

        // if ($monitoringEntity === null) {
        //     //set the return URL
        //     $returnUrl = $this->generateUrl('mautic_social_index', ['page' => $page]);

        //     return $this->postActionRedirect(
        //         [
        //             'returnUrl'       => $returnUrl,
        //             'viewParameters'  => ['page' => $page],
        //             'contentTemplate' => 'MauticSocialMonitoringBundle:Monitoring:index',
        //             'passthroughVars' => [
        //                 'activeLink'    => '#mautic_social_index',
        //                 'mauticContent' => 'monitoring',
        //             ],
        //             'flashes' => [
        //                 [
        //                     'type'    => 'error',
        //                     'msg'     => 'mautic.social.monitoring.error.notfound',
        //                     'msgVars' => ['%id%' => $objectId],
        //                 ],
        //             ],
        //         ]
        //     );
        // }

        // // Audit Log
        // $logs = $this->getModel('core.auditLog')->getLogForObject('monitoring', $objectId);

        // $returnUrl = $this->generateUrl(
        //     'mautic_social_action',
        //     [
        //         'objectAction' => 'view',
        //         'objectId'     => $monitoringEntity->getId(),
        //     ]
        // );

        // // Init the date range filter form
        // $dateRangeValues = $this->request->get('daterange', []);
        // $dateRangeForm   = $this->get('form.factory')->create('daterange', $dateRangeValues, ['action' => $returnUrl]);
        // $dateFrom        = new \DateTime($dateRangeForm['date_from']->getData());
        // $dateTo          = new \DateTime($dateRangeForm['date_to']->getData());

        // $chart     = new LineChart(null, $dateFrom, $dateTo);
        // $leadStats = $postCountRepo->getLeadStatsPost(
        //     $dateFrom,
        //     $dateTo,
        //     ['monitor_id' => $monitoringEntity->getId()]
        // );
        // $chart->setDataset($this->get('translator')->trans('mautic.social.twitter.tweet.count'), $leadStats);

        // return $this->delegateView(
        //     [
        //         'returnUrl'      => $returnUrl,
        //         'viewParameters' => [
        //             'activeMonitoring' => $monitoringEntity,
        //             'logs'             => $logs,
        //             'tmpl'             => $tmpl,
        //             'security'         => $security,
        //             'leadStats'        => $chart->render(),
        //             'monitorLeads'     => $this->forward(
        //                 'MauticSocialBundle:Monitoring:contacts',
        //                 [
        //                     'objectId'   => $monitoringEntity->getId(),
        //                     'page'       => $page,
        //                     'ignoreAjax' => true,
        //                 ]
        //             )->getContent(),
        //             'dateRangeForm' => $dateRangeForm->createView(),
        //         ],
        //         'contentTemplate' => 'MauticSocialBundle:Monitoring:'.$tmpl.'.html.php',
        //         'passthroughVars' => [
        //             'activeLink'    => '#mautic_social_index',
        //             'mauticContent' => 'monitoring',
        //         ],
        //     ]
        // );
    }

    public function rankingDataAction()
    {
        $model = $this->getModel('share.report');

        // get cound of shares in db

        // get count of visits in db

        // get cached data from cache

        // compare the count, if more, load

        // consturct the data

        // shares[lead id], share[children], share[readcount]

        // csv: lead id => share count, share share count, read count

        // return csv

        $response = $this->render('MauticShareBundle:Share:ranking.csv.php', [
            'data' => $model->getRankingData(),
        ]);

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="ranking.csv"');

        return $response;
    }

    public function __construct()
    {
        $this->setStandardParameters(
            'focus',
            'plugin:focus:items',
            'mautic_focus',
            'mautic_focus',
            'mautic.focus',
            'MauticFocusBundle:Focus',
            null,
            'focus'
        );
    }

    /**
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction($page = 1)
    {
        return parent::indexStandard($page);
    }

    /**
     * Generates new form and processes post data.
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function newAction()
    {
        return parent::newStandard();
    }

    /**
     * Generates edit form and processes post data.
     *
     * @param int  $objectId
     * @param bool $ignorePost
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction($objectId, $ignorePost = false)
    {
        return parent::editStandard($objectId, $ignorePost);
    }

    /**
     * Displays details on a Focus.
     *
     * @param $objectId
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function viewAction($objectId)
    {
        return parent::viewStandard($objectId, 'focus', 'plugin.focus');
    }

    /**
     * Clone an entity.
     *
     * @param int $objectId
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function cloneAction($objectId)
    {
        return parent::cloneStandard($objectId);
    }

    /**
     * Deletes the entity.
     *
     * @param int $objectId
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($objectId)
    {
        return parent::deleteStandard($objectId);
    }

    /**
     * Deletes a group of entities.
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function batchDeleteAction()
    {
        return parent::batchDeleteStandard();
    }

    /**
     * @param $args
     * @param $view
     */
    public function customizeViewArguments($args, $view)
    {
        if ($view == 'view') {
            /** @var \MauticPlugin\MauticFocusBundle\Entity\Focus $item */
            $item = $args['viewParameters']['item'];

            // For line graphs in the view
            $dateRangeValues = $this->request->get('daterange', []);
            $dateRangeForm   = $this->get('form.factory')->create(
                'daterange',
                $dateRangeValues,
                [
                    'action' => $this->generateUrl(
                        'mautic_focus_action',
                        [
                            'objectAction' => 'view',
                            'objectId'     => $item->getId(),
                        ]
                    ),
                ]
            );

            /** @var \MauticPlugin\MauticFocusBundle\Model\FocusModel $model */
            $model = $this->getModel('focus');
            $stats = $model->getStats(
                $item,
                null,
                new \DateTime($dateRangeForm->get('date_from')->getData()),
                new \DateTime($dateRangeForm->get('date_to')->getData())
            );

            $args['viewParameters']['stats']         = $stats;
            $args['viewParameters']['dateRangeForm'] = $dateRangeForm->createView();

            if ('link' == $item->getType()) {
                $args['viewParameters']['trackables'] = $this->getModel('page.trackable')->getTrackableList('focus', $item->getId());
            }
        }

        return $args;
    }
}
