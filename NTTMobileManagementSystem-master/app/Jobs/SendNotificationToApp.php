<?php

namespace App\Jobs;

use App\Exceptions\PushNotificationException;
use App\Models\Event;
use App\Models\Message;
use FCM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

/**
 * Class SendNotificationToApp
 * @package App\Jobs
 */
class SendNotificationToApp implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * SendNotificationToApp constructor.
     * @param Event $event
     */
    public function __construct(Event $event) {
        $this->event = $event;
    }

    /**
     *
     * Execute the job.
     *
     * @return void
     *
     * @throws PushNotificationException
     */
    public function handle() {
        try {
            $notificationBuilder = new PayloadNotificationBuilder('New event ' . $this->event->title . ' just published');
            $notificationBuilder->setBody($this->event->id)
                ->setSound('default')
                ->setClickAction('OPEN_EVENT_ACTIVITY');

            $topic = new Topics();
            $topic->topic('Events');

            $topicResponse = FCM::sendToTopic($topic, null, $notificationBuilder->build(), null);

            if (!$topicResponse->isSuccess() || $topicResponse->shouldRetry()) {
                throw new PushNotificationException($topicResponse->error());
            } else {
                $this->event->pushed = true;
                $this->event->save();
            }
        } catch (\Exception $e) {
            if ($e instanceof PushNotificationException) {
                throw $e;
            } else {
                throw new PushNotificationException('an exception caught while processing this request', 0, $e);
            }
        }
    }

    /**
     * The job failed to process.
     *
     * @param  PushNotificationException $exception
     * @return void
     */
    public function failed(PushNotificationException $exception) {
        // create message
        Message::create(['type' => 'error', 'title' => 'push event ' . $this->event->id . ' to mobile app failed', 'content' => $exception->getMessage(), 'url' => '#', 'viewed' => false]);
    }
}
