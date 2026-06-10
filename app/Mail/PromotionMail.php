<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class PromotionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Campaign $campaign,
        public Subscriber $subscriber
    ) {
        // Data is passed from the CampaignResource blast loop
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->campaign->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        /**
         * 🛡️ THE ULTIMATE IMAGE FIX:
         * This regex hunts down ANY image source that contains "/storage/", 
         * strips away local domains (like localhost), and forces your live URL.
         */
        $appUrl = 'https://orbitakenya.com'; 
        
        $processedBody = preg_replace(
            '/src="[^"]*?\/storage\/([^"]+)"/', 
            'src="' . $appUrl . '/storage/$1"', 
            $this->campaign->content
        );

        return new Content(
            view: 'emails.promotion',
            with: [
                'body' => $processedBody,
                'header_path' => $this->campaign->header?->image_path,
                'footer_path' => $this->campaign->footer?->image_path,
                'unsubscribe_url' => url('/unsubscribe/' . $this->subscriber->id),
                'action_button' => $this->campaign->action_button,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $mailAttachments = [];
        
        // 📎 Attach actual documents (PDFs, Excel, etc.) to the email
        if (!empty($this->campaign->attachments) && is_array($this->campaign->attachments)) {
            foreach ($this->campaign->attachments as $filePath) {
                // Fetches the file securely from your public storage disk
                $mailAttachments[] = Attachment::fromStorageDisk('public', $filePath);
            }
        }
        
        return $mailAttachments;
    }
}