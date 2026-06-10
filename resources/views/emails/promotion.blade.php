<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Plus Jakarta Sans', Helvetica, Arial, sans-serif; background: #f4f4f5; padding: 0; margin: 0; }
        
        /* Force sharp corners and proper image scaling */
        .message-body img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 0 !important; 
            margin: 20px 0;
            display: block;
        }

        /* 🚨 HIDDEN FILE NAMES: This hides the captions/filenames on uploaded images */
        .message-body figcaption, 
        .attachment__caption, 
        .attachment__name {
            display: none !important;
        }

        /* Modern sharp buttons */
        .btn {
            display: inline-block;
            padding: 14px 32px;
            background-color: #C5A059;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: bold;
            border-radius: 0 !important;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }

        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; margin: 0 !important; }
            .content-padding { padding: 25px !important; }
        }
    </style>
</head>
<body>
    <div class="container" style="max-width: 650px; margin: 40px auto; background: #ffffff; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">

        {{-- DYNAMIC HEADER (Edge to Edge) --}}
        @if(isset($header_path))
            <div style="width: 100%; line-height: 0;">
                <img src="{{ asset('storage/' . $header_path) }}" style="width: 100%; display: block; border-radius: 0;" alt="Orbita Header">
            </div>
        @endif

        {{-- MESSAGE CONTENT --}}
        <div class="message-body content-padding" style="padding: 50px 40px; font-size: 16px; line-height: 1.8; color: #1f2937;">
            {!! $body !!}
        </div>

        {{-- 🔘 DYNAMIC CALL TO ACTION BUTTON --}}
        @if(!empty($action_button['text']) && !empty($action_button['url']))
            <div class="content-padding" style="text-align: center; padding: 0 40px 50px 40px; background: #ffffff;">
                <a href="{{ $action_button['url'] }}" class="btn" style="display: inline-block; padding: 16px 36px; background-color: #C5A059; color: #ffffff; text-decoration: none; font-weight: 800; font-size: 15px; letter-spacing: 1.5px; text-transform: uppercase; border-radius: 0;">
                    {{ $action_button['text'] }}
                </a>
            </div>
        @endif

        {{-- DYNAMIC FOOTER (Edge to Edge) --}}
        @if(isset($footer_path))
            <div style="width: 100%; line-height: 0;">
                <img src="{{ asset('storage/' . $footer_path) }}" style="width: 100%; display: block; border-radius: 0;" alt="Orbita Footer">
            </div>
        @endif

        {{-- SHARP UNSUBSCRIBE SECTION --}}
        <div style="padding: 25px; background: #f9fafb; border-top: 1px solid #e5e7eb; font-size: 11px; color: #6b7280; text-align: center; text-transform: uppercase; letter-spacing: 1px;">
            <p style="margin: 0 0 5px 0; font-weight: bold;">&copy; {{ date('Y') }} Orbita Kenya. Nairobi, Kenya.</p>
            <p style="margin: 0 0 15px 0;">Providing World-Class Hospitality & Security Solutions.</p>
            <a href="{{ $unsubscribe_url ?? '#' }}" style="color: #C5A059; text-decoration: none; border-bottom: 1px solid #C5A059; padding-bottom: 2px;">Unsubscribe Securely</a>
        </div>

    </div>
    
    {{-- Tracking Pixel --}}
    @if(isset($campaign) && isset($subscriber))
        <img src="{{ route('campaign.track', ['campaign_id' => $campaign->id, 'email' => $subscriber->email]) }}" width="1" height="1" style="display:none !important;" />
    @endif
</body>
</html>