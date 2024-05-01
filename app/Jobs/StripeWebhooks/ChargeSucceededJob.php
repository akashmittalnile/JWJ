<?php

namespace App\Jobs\Stripewebhooks;

use App\Models\UserPlan;
use App\Models\UserPlanDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;


class ChargeSucceededJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
        $charge = $this->webhookCall->payload['data']['object'] ;
        // do your work here
        // echo "<pre>";
        // print_r($charge);
        // echo "</pre>";
        // die();
        if($this->webhookCall->payload['type'] == 'invoice.payment_succeeded')
        {
            $payment = UserPlan::where("subscription_id", $charge['subscription'])->where("status", 1)->first();
            $payment->transaction_id = $charge['charge'] ?? null;
            $payment->save();
        }

        // you can access the payload of the webhook call with `$this->webhookCall->payload`
    }
}
