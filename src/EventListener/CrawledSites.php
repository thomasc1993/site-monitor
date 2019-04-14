<?php

namespace App\EventListener;

use App\Event\SitesCrawledEvent;
use App\Utils\Slack\Handler;
use App\Utils\Slack\Payload;

final class CrawledSites
{
    public function onSitesCrawled(SitesCrawledEvent $event)
    {
        $slackPayload = new Payload();
        $slackPayload->setText('The following sites were just reported as down:');
        $attachment = $slackPayload->newAttachment();
        $attachment->setColor('danger');
        $slackPayload->addAttachment($attachment);

        $sitesDown = false;
        $crawledSites = $event->getCrawledSites();
        foreach ($crawledSites as $crawledSite) {
            if ($crawledSite->getStatus() === 'down') {
                $field = $attachment->newField();
                $field->setValue('<' . $crawledSite->getUrl() . '|' . $crawledSite->getName() . '>');
                $attachment->addField($field);
                $sitesDown = true;
            }
        }

        if ($sitesDown) {
            $slack = new Handler($slackPayload);
            $slack->postToSlack();
        }
    }
}