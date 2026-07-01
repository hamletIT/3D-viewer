<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade Request</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #0a0a12; color: #e2e8f0; }
        .wrapper { max-width: 560px; margin: 0 auto; padding: 2rem 1.5rem; }
        .header { text-align: center; padding: 2rem 0 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .header-icon { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .header h1 { font-size: 1.3rem; font-weight: 600; color: #f1f5f9; letter-spacing: 0.3px; }
        .header p { font-size: 0.8rem; color: rgba(255,255,255,0.35); margin-top: 0.25rem; }
        .card { background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%); border: 1px solid rgba(255,255,255,0.07); border-radius: 10px; padding: 1.5rem; margin: 1.5rem 0; }
        .card h2 { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.25); margin-bottom: 1rem; }
        .row { display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.04); font-size: 0.85rem; }
        .row:last-child { border-bottom: none; }
        .row .label { color: rgba(255,255,255,0.35); }
        .row .value { color: #e2e8f0; font-weight: 500; }
        .plan-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.25rem 0.75rem; background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.2); border-radius: 6px; font-size: 0.85rem; }
        .footer { text-align: center; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); font-size: 0.75rem; color: rgba(255,255,255,0.2); }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-icon">⚒️</div>
            <h1>New Upgrade Request</h1>
            <p>A user wants to upgrade their plan</p>
        </div>

        <div class="card">
            <h2>User Details</h2>
            <div class="row">
                <span class="label">Name</span>
                <span class="value">{{ $request->user->name }}</span>
            </div>
            <div class="row">
                <span class="label">Email</span>
                <span class="value">{{ $request->user->email }}</span>
            </div>
            <div class="row">
                <span class="label">Role</span>
                <span class="value" style="text-transform:capitalize;">{{ $request->user->role }}</span>
            </div>
            <div class="row">
                <span class="label">Registered</span>
                <span class="value">{{ $request->user->created_at->format('M j, Y') }}</span>
            </div>
        </div>

        <div class="card">
            <h2>Request Details</h2>
            <div class="row">
                <span class="label">Plan</span>
                <span class="value"><span class="plan-badge">{{ $request->plan->icon }} {{ $request->plan->name }}</span></span>
            </div>
            <div class="row">
                <span class="label">Price</span>
                <span class="value">${{ number_format($request->plan->price, 2) }}</span>
            </div>
            <div class="row">
                <span class="label">Max Sessions</span>
                <span class="value">{{ $request->plan->max_sessions === -1 ? '∞ Unlimited' : $request->plan->max_sessions }}</span>
            </div>
            <div class="row">
                <span class="label">Max Objects</span>
                <span class="value">{{ $request->plan->max_objects_per_scene === -1 ? '∞ Unlimited' : $request->plan->max_objects_per_scene }}</span>
            </div>
            <div class="row">
                <span class="label">Duration</span>
                <span class="value">{{ $request->plan->duration_days ? $request->plan->duration_days . ' days' : ($request->plan->max_sessions === -1 ? 'Lifetime' : 'N/A') }}</span>
            </div>
        </div>

        <div class="card">
            <h2>Contact Information</h2>
            <div class="row">
                <span class="label">Name</span>
                <span class="value">{{ $request->name }}</span>
            </div>
            <div class="row">
                <span class="label">Email</span>
                <span class="value">{{ $request->email }}</span>
            </div>
            <div class="row">
                <span class="label">Phone</span>
                <span class="value">{{ $request->phone }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Review this request in the <a href="{{ route('admin.upgrade-requests.index') }}" style="color:rgba(99,102,241,0.6);text-decoration:none;">admin panel</a></p>
            <p style="margin-top:0.25rem;">&copy; {{ date('Y') }} {{ config('app.name', 'Workshop') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
