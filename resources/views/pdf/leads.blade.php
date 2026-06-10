<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Leads Export</title>
    <style>
        @page { margin: 30px 40px; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10px; 
            color: #333; 
            background: #fff;
        }

        /* --- UTILITIES --- */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        /* --- HEADER --- */
        table { width: 100%; border-collapse: collapse; }
        .header-table { margin-bottom: 20px; border-bottom: 2px solid #d48d56; padding-bottom: 15px; }
        .header-table td { vertical-align: middle; }
        
        .report-title {
            font-size: 24px; font-weight: bold; color: #1a2b4b;
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;
        }
        .report-meta { font-size: 11px; color: #555; }

        /* --- DATA TABLE --- */
        .data-table { width: 100%; margin-top: 15px; }
        .data-table th, .data-table td { 
            border: 1px solid #cbd5e1; 
            padding: 8px 10px; 
            text-align: left; 
        }
        .data-table th { 
            background-color: #1a2b4b; 
            color: #ffffff; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 10px;
        }
        .data-table tr:nth-child(even) { background-color: #f8fafc; }
        .data-table tr:hover { background-color: #f1f5f9; }

        /* --- STATUS BADGES (Fallback to text colors for DOMPDF) --- */
        .status { font-weight: bold; font-size: 9px; }
        .status-new { color: #64748b; }
        .status-contacted { color: #0284c7; }
        .status-meeting { color: #d97706; }
        .status-proposal { color: #4338ca; }
        .status-won { color: #16a34a; }
        .status-lost { color: #dc2626; }

        /* --- FOOTER --- */
        .footer {
            position: fixed; 
            bottom: -10px; 
            left: 0; 
            right: 0;
            text-align: center; 
            font-size: 9px; 
            color: #777; 
            border-top: 1px solid #cbd5e1; 
            padding-top: 5px;
        }
    </style>
</head>
<body>

    {{-- DYNAMIC LOGO (Using Base64 for guaranteed DOMPDF rendering) --}}
    @php
        $logoPath = '/home/orbitate/public_html/images/orbita-logo.png';
        $logoSrc = '';
        if (file_exists($logoPath)) {
            $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" style="max-height: 50px; max-width: 200px;" alt="Orbita Kenya">
                @else
                    <h1 style="color:#1a2b4b; margin:0; font-size: 24px;">ORBITA KENYA</h1>
                @endif
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="report-title">Leads Pipeline Report</div>
                <div class="report-meta">
                    <strong>Exported By:</strong> {{ auth()->user() ? auth()->user()->name : 'System Admin' }}<br>
                    <strong>Date:</strong> {{ date('F j, Y, g:i A') }}<br>
                    <strong>Total Records:</strong> {{ count($leads) }}
                </div>
            </td>
        </tr>
    </table>

    {{-- LEADS DATA TABLE --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20%;">Client / Company</th>
                <th style="width: 15%;">Contact Person</th>
                <th style="width: 15%;">Position</th>
                <th style="width: 20%;">Contact Info</th>
                <th style="width: 15%; text-align: right;">Est. Value (KES)</th>
                <th style="width: 15%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $pipelineTotal = 0; @endphp
            
            @forelse($leads as $lead)
                @php 
                    $value = (float)($lead->estimated_value ?? 0);
                    $pipelineTotal += $value;
                @endphp
                <tr>
                    <td class="text-bold">{{ $lead->company_name ?? 'N/A' }}
                        @if($lead->region)
                            <br><span style="font-size: 8px; color: #777; font-weight: normal;">{{ $lead->region }}</span>
                        @endif
                    </td>
                    <td>{{ $lead->contact_person ?? 'N/A' }}</td>
                    <td>{{ $lead->contact_position ?? '-' }}</td>
                    <td style="line-height: 1.4;">
                        @if($lead->phone) 📞 {{ $lead->phone }} <br> @endif
                        @if($lead->email) ✉️ {{ $lead->email }} @endif
                    </td>
                    <td class="text-right text-bold" style="color: #1a2b4b;">
                        {{ number_format($value, 2) }}
                    </td>
                    <td class="text-center uppercase status status-{{ strtolower($lead->status ?? 'new') }}">
                        {{ $lead->status ?? 'NEW' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #777;">
                        No leads selected for export.
                    </td>
                </tr>
            @endforelse
        </tbody>
        
        {{-- PIPELINE TOTAL ROW --}}
        @if(count($leads) > 0)
        <tfoot>
            <tr>
                <td colspan="4" class="text-right text-bold" style="background-color: #1a2b4b; color: white;">TOTAL PIPELINE VALUE:</td>
                <td class="text-right text-bold" style="background-color: #1a2b4b; color: white;">KES {{ number_format($pipelineTotal, 2) }}</td>
                <td style="background-color: #1a2b4b;"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        Orbita Kenya CRM - Confidential Lead Data &copy; {{ date('Y') }}
    </div>

</body>
</html>