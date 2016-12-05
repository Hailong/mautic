<?php

/*
 * @copyright   2016 Mautic, Inc. All rights reserved
 * @author      Mautic, Inc
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticShareBundle\Model;

// use Doctrine\Common\Annotations\AnnotationRegistry;

use Mautic\CoreBundle\Model\AbstractCommonModel;
use MauticPlugin\MauticShareBundle\Entity\Share;

/**
 * Class ReportModel.
 */
class ReportModel extends AbstractCommonModel
{
    public function __construct()
    {
        // AnnotationRegistry::registerFile(__DIR__ . '/../../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
    }

    // public function countTableRows($entityClass)
    // {
    //     $qb = $this->em->createQueryBuilder();
    //     $qb->select('count(record.id)');
    //     $qb->from($entityClass, 'record');

    //     return $qb->getQuery()->getSingleScalarResult();
    // }

    public function updateData()
    {
        $data = apcu_fetch('mautic.share.data');

        if (!$data) {
            $data = [
                'last_share_id' => 0,
                'last_hit_id'   => 0,
                'page_shares'   => [],
                'page_hits'     => [],
            ];
        }

        // read more shares from DB
        $repo  = $this->em->getRepository('MauticShareBundle:Share');
        $query = $repo->createQueryBuilder('s')
            ->where('s.id > :last_share_id')
            ->setParameter('last_share_id', $data['last_share_id'])
            ->orderBy('s.id', 'DESC')
            ->getQuery();

        $moreShares = $query->getResult();

        foreach ($moreShares as $share) {
            if ($share->getId() > $data['last_share_id']) {
                $data['last_share_id'] = $share->getId();
            }

            $data['page_shares'][$share->getId()] = [
                'lead_id'   => $share->getLead() ? $share->getLead()->getId() : 0,
                'parent_id' => $share->getParent() ? $share->getParent()->getId() : 0,
            ];
        }

        // read more hits from DB
        $repo  = $this->em->getRepository('MauticPageBundle:Hit');
        $query = $repo->createQueryBuilder('h')
            ->where('h.id > :last_hit_id')
            ->andwhere('h.source = \'share\'')
            ->setParameter('last_hit_id', $data['last_hit_id'])
            ->orderBy('h.id', 'DESC')
            ->getQuery();

        $moreHits = $query->getResult();

        foreach ($moreHits as $hit) {
            if ($hit->getId() > $data['last_hit_id']) {
                $data['last_hit_id'] = $hit->getId();
            }

            $data['page_hits'][$hit->getId()] = [
                'source_id' => $hit->getSourceId(),
            ];
        }

        if ($moreShares || $moreHits) {
            // No sure if it would cause deadlock! Need to re-visit this code later.
            if (apcu_delete('mautic.share.data') || !apcu_exists('mautic.share.data')) {
                return apcu_entry('mautic.share.data', function ($key) use ($data) {
                    return $data;
                });
            }
        }

        return $data;
    }

    public function getRankingData()
    {
        $rawData = $this->updateData();

        $readCount = [];
        foreach ($rawData['page_hits'] as $hit) {
            if (!isset($readCount[$hit['source_id']])) {
                $readCount[$hit['source_id']] = 0;
            }

            $readCount[$hit['source_id']] += 1;
        }

        $rankingData = [];
        foreach ($rawData['page_shares'] as $shareId => $share) {
            if (!isset($rankingData[$share['lead_id']])) {
                $rankingData[$share['lead_id']] = [
                    'share_count' => 0,
                    'read_count'  => 0,
                ];
            }

            $rankingData[$share['lead_id']]['share_count'] += 1;
            $rankingData[$share['lead_id']]['read_count'] += isset($readCount[$shareId]) ? $readCount[$shareId] : 0;
            $rankingData[$share['lead_id']]['child_share_count'] = 0;
        }

        return $rankingData;
    }
}
