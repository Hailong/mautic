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

use Mautic\CoreBundle\Model\AbstractCommonModel;
use MauticPlugin\MauticShareBundle\Entity\Share;

/**
 * Class ShareModel.
 */
class ShareModel extends AbstractCommonModel
{
    /**
     * Get this model's repository.
     *
     * @return \MauticPlugin\MauticShareBundle\Entity\ShareRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('MauticShareBundle:Share');
    }

    public function getLead()
    {
        return $this->factory->getModel('lead')->getCurrentLead();
    }

    public function generateClickThrough()
    {
        $shareCode = hash('sha1', uniqid('page_share'.mt_rand()));

        return $this->encodeArrayForUrl([
            'source' => ['share', 0, $shareCode],
        ]);
    }

    public function generateShareUrl($url, $clickthrough)
    {
        $sep = preg_match('/(\?|&)/', $url) ? '&' : '?';

        return $url.$sep.'ct='.$clickthrough;
    }

    public function extractShareToken(&$url)
    {
        $param = 'ct';
        $token = '';

        $pieces = preg_split('/(\?|&)/', $url);

        foreach ($pieces as $key => $piece) {
            if (strpos($piece, $param.'=') === 0) {
                $token = explode('=', $piece)[1];
                unset($pieces[$key]);
            }
        }

        if ($token) {
            $url = implode('?', array_slice($pieces, 0, 2));
            $url = implode('&', array_merge([$url], array_slice($pieces, 2)));
        }

        return $token;
    }

    public function parseCurrentUrl($currentUrl)
    {
        $cleanUrl            = $currentUrl;
        $currentClickthrough = $this->extractShareToken($cleanUrl);
        $newClickthrough     = $this->generateClickThrough();
        $newShareUrl         = $this->generateShareUrl($cleanUrl, $newClickthrough);

        return [$currentClickthrough, $newClickthrough, $newShareUrl];
    }

    public function parseClickThrough($clickthrough)
    {
        $ct        = null;
        $shareCode = '';

        if (is_array($clickthrough)) {
            $ct = $clickthrough;
        } else {
            try {
                $ct = $this->decodeArrayFromUrl($clickthrough);
            } catch (\InvalidArgumentException $e) {
            }
        }

        if ($ct && isset($ct['source'][2]) && $ct['source'][0] == 'share') {
            $shareCode = $ct['source'][2];
        }

        return $shareCode;
    }

    public function getShareByCode($code)
    {
        $repository = $this->getRepository();

        return $repository->findOneBy(['code' => $code]);
    }

    public function getShareByClickThrough($clickthrough)
    {
        $shareCode = $this->parseClickThrough($clickthrough);
        if ($shareCode) {
            return $this->getShareByCode($shareCode);
        }
    }

    public function readCount($share)
    {
        $share->setReadCount($share->getReadCount() + 1);
        $this->em->persist($share);
        $this->em->flush();
    }

    public function createPageShare($parentClickthrough, $code, $source, $target, $status, $url, $title, $image, $fingerprint)
    {
        $lead = $this->getLead();

        $repository = $this->getRepository();

        $share = new Share();
        $share->setParent($this->getShareByClickThrough($parentClickthrough));
        $share->setLead($lead);
        $share->setSource($source);
        $share->setTarget($target);
        $share->setSharedDate(new \DateTime('now'));
        $share->setStatus($status);
        $share->setUrl($url);
        $share->setTitle($title);
        $share->setImageUrl($image);
        $share->setReadCount(0);
        $share->setCode($code);

        $deviceRepo = $this->factory->getModel('lead')->getDeviceRepository();
        $deviceId   = $deviceRepo->getDeviceByFingerprint($fingerprint);
        if ($deviceId) {
            $device = $deviceRepo->findOneBy(['id' => $deviceId]);
            $share->setDevice($device);
        }

        $this->em->persist($share);
        $this->em->flush();
    }
}
