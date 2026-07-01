<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Question</title>
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
        .message-box { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 1rem; margin-top: 0.5rem; font-size: 0.85rem; line-height: 1.6; color: #94a3b8; white-space: pre-wrap; }
        .btn { display: inline-block; padding: 0.6rem 1.5rem; background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); border-radius: 6px; color: #a5b4fc; font-size: 0.8rem; text-decoration: none; margin-top: 0.5rem; }
        .footer { text-align: center; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); font-size: 0.75rem; color: rgba(255,255,255,0.2); }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-icon">💬</div>
            <h1>New Question</h1>
            <p>A user has submitted a new question</p>
        </div>

        <div class="card">
            <h2>User</h2>
            <div class="row">
                <span class="label">Name</span>
                <span class="value">{{ $conversation->user->name }}</span>
            </div>
            <div class="row">
                <span class="label">Email</span>
                <span class="value">{{ $conversation->user->email }}</span>
            </div>
        </div>

        <div class="card">
            <h2>Subject</h2>
            <p style="font-size:1rem;font-weight:600;color:#f1f5f9;">{{ $conversation->subject }}</p>
            <h2 style="margin-top:1rem;">Message</h2>
            <div class="message-box">{{ $conversation->messages()->oldest()->first()?->message ?? '—' }}</div>
        </div>

        <div style="text-align:center;">
            <a href="{{ route('admin.conversations.show', $conversation) }}" class="btn">View in Admin Panel</a>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Workshop') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
