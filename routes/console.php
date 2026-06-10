<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Models\Campaign;
use App\Models\Subscriber;
use App\Mail\PromotionMail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 🚀 THE ORBITA MARKETING ENGINE
// This wakes up every minute, checks for scheduled campaigns, and blasts them out.
Schedule::call(function () {
    // 1. Find campaigns that are "scheduled" and the time has officially passed
    $campaigns = Campaign::where('status', 'scheduled')
        ->where('scheduled_at', '<=', now())
        ->get();

    foreach ($campaigns as $campaign) {
        // 2. Lock the campaign immediately so we don't accidentally send it twice
        $campaign->update(['status' => 'sending']);

        $emails = $campaign->marketingList->emails ?? [];
        $log = $campaign->status_log ?? [];
        
        // If the list is empty, just mark it complete and move on
        if (empty($emails)) {
            $campaign->update(['status' => 'completed', 'sent_at' => now()]);
            continue;
        }

        // 3. Loop through and send the emails
        foreach ($emails as $email) {
            try {
                // Find subscriber or create a dummy one for the email template
                $tempSub = Subscriber::where('email', $email)->first() ?? new Subscriber(['email' => $email, 'id' => 0]);
                
                // Personalize the email body
                $personalizedCampaign = clone $campaign;
                $personalizedCampaign->content = str_replace('[email]', $email, $campaign->content);

                // Send it
                Mail::to($email)->send(
                    (new PromotionMail($personalizedCampaign, $tempSub))
                        ->from('info@orbitakenya.com', 'Orbita Kenya')
                );
                
                // Log success
                $log[$email] = ['status' => 'delivered', 'at' => now()->toDateTimeString()];
            } catch (\Exception $e) {
                // Log failure
                $log[$email] = ['status' => 'failed', 'error' => $e->getMessage()];
            }
        }

        // 4. Mark the campaign as completely finished and save the logs
        $campaign->update([
            'status' => 'completed',
            'sent_at' => now(),
            'status_log' => $log
        ]);
    }
})->everyMinute();