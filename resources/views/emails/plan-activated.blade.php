<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Activated</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #0a0a12; color: #e2e8f0; }
        .wrapper { max-width: 520px; margin: 0 auto; padding: 2.5rem 1.5rem; }
        .header { text-align: center; padding: 2rem 0 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .header-icon { font-size: 3rem; margin-bottom: 0.75rem; }
        .header h1 { font-size: 1.4rem; font-weight: 600; color: #f1f5f9; letter-spacing: 0.3px; }
        .card { background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%); border: 1px solid rgba(255,255,255,0.07); border-radius: 10px; padding: 1.5rem; margin: 1.5rem 0; text-align: center; }
        .plan-name { font-size: 1.5rem; font-weight: 700; color: #fff; margin: 0.5rem 0; }
        .plan-icon { font-size: 3rem; }
        .details { display: flex; justify-content: center; gap: 2rem; margin: 1.25rem 0; }
        .detail-item { text-align: center; }
        .detail-value { font-size: 1.1rem; font-weight: 600; color: #e2e8f0; }
        .detail-label { font-size: 0.7rem; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 0.15rem; }
        .btn { display: inline-block; margin-top: 1rem; padding: 0.75rem 2rem; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: #fff; border-radius: 8px; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: transform 0.2s; }
        .btn:hover { transform: translateY(-1px); }
        .footer { text-align: center; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); font-size: 0.75rem; color: rgba(255,255,255,0.2); }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-icon">⚒️</div>
            <h1>Plan Activated!</h1>
        </div>

        <div class="card">
            <div class="plan-icon">{{ $request->plan->icon }}</div>
            <div class="plan-name">{{ $request->plan->name }} Plan</div>
            <p style="color:rgba(255,255,255,0.45);font-size:0.85rem;margin-top:0.25rem;">Your upgrade request has been approved.</p>

            <div class="details">
                <div class="detail-item">
                    <div class="detail-value">{{ $request->plan->max_sessions === -1 ? '∞' : $request->plan->max_sessions }}</div>
                    <div class="detail-label">Sessions</div>
                </div>
                <div class="detail-item">
                    <div class="detail-value">{{ $request->plan->max_objects_per_scene === -1 ? '∞' : $request->plan->max_objects_per_scene }}</div>
                    <div class="detail-label">Objects</div>
                </div>
                @if ($request->plan->duration_days)
                    <div class="detail-item">
                        <div class="detail-value">{{ $request->plan->duration_days }}</div>
                        <div class="detail-label">Days</div>
                    </div>
                @elseif ($request->plan->max_sessions === -1)
                    <div class="detail-item">
                        <div class="detail-value">∞</div>
                        <div class="detail-label">Duration</div>
                    </div>
                @endif
            </div>

            <a href="{{ route('app') }}" class="btn">Launch Workshop</a>
        </div>

        <div class="footer">
            <p>If you have any questions, contact the admin team.</p>
            <p style="margin-top:0.25rem;">&copy; {{ date('Y') }} {{ config('app.name', 'Workshop') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
