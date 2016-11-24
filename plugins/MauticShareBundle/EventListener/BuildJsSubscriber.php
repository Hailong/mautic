<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticShareBundle\EventListener;

use Mautic\CoreBundle\CoreEvents;
use Mautic\CoreBundle\Event\BuildJsEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class BuildJsSubscriber.
 */
class BuildJsSubscriber extends CommonSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CoreEvents::BUILD_MAUTIC_JS => ['onBuildJs', 257],
        ];
    }

    /**
     * @param BuildJsEvent $event
     */
    public function onBuildJs(BuildJsEvent $event)
    {
        $onShareUrl   = $this->router->generate('mautic_share_on_page_share', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $model        = $this->factory->getModel('share');
        $clickthrough = $model->generateClickThrough();

        // If the current visit has a click-through variable of page sharing,
        // hand it over to the page hits request.
        $ct = $snippet = '';
        parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $params);
        if (isset($params['ct']) && $model->parseClickThrough($params['ct'])) {
            $ct      = $params['ct'];
            $snippet = <<<JS
if (typeof MauticJS.getInput === 'function') {
    var pageview = MauticJS.getInput('send', 'pageview');
    if (typeof pageview[2] !== 'object') {
        pageview[2] = {};
    }
    pageview[2].ct = '{$ct}';
}
JS;
        }

        $js = <<<JS
{$snippet}
MauticJS.share = {
    init: function () {
        if (typeof WeChatJSLoader != 'undefined') {
            var clickthrough = '{$clickthrough}';
            WeChatJSLoader.onGetClickThrough(function() {
                return clickthrough;
            });
            WeChatJSLoader.onShare(function(source, target, status, url, title, image) {
                MauticJS.makeCORSRequest('POST', '{$onShareUrl}', {
                    f: MauticJS.fingerprint,
                    p: '{$ct}',
                    c: clickthrough,
                    s: source,
                    t: target,
                    a: status,
                    u: url,
                    l: title,
                    i: image
                }, function(response, xhr) {
                    clickthrough = response.c;
                });
            });
        }
    }
}

MauticJS.pixelLoaded(MauticJS.share.init);
JS;

        $event->appendJs($js, 'Mautic Page Share JS');
    }
}
