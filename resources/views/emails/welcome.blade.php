<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: #0f0f11;
            font-family: 'Georgia', serif;
            color: #e5e5e5;
            padding: 40px 16px;
        }

        .wrapper {
            max-width: 560px;
            margin: 0 auto;
        }

        /* Logo / Brand */
        .brand {
            text-align: center;
            margin-bottom: 36px;
        }

        .brand-name {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #ffffff;
        }

        .brand-dot {
            color: #a78bfa;
        }

        /* Card */
        .card {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border: 1px solid rgba(167, 139, 250, 0.2);
            border-radius: 16px;
            overflow: hidden;
        }

        /* Hero banner */
        .hero {
            background: linear-gradient(135deg, #312e81 0%, #4c1d95 50%, #1e1b4b 100%);
            padding: 48px 40px;
            text-align: center;
            position: relative;
        }

        .hero-icon {
            font-size: 52px;
            margin-bottom: 16px;
            display: block;
        }

        .hero-title {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .hero-subtitle {
            font-size: 14px;
            color: rgba(255,255,255,0.7);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Body */
        .body {
            padding: 36px 40px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 12px;
        }

        .text {
            font-size: 14px;
            line-height: 1.7;
            color: #a1a1aa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            margin-bottom: 24px;
        }

        /* Feature list */
        .features {
            background: rgba(167, 139, 250, 0.07);
            border: 1px solid rgba(167, 139, 250, 0.15);
            border-radius: 10px;
            padding: 20px 24px;
            margin-bottom: 28px;
            list-style: none;
        }

        .features li {
            font-size: 13px;
            color: #c4b5fd;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 6px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .features li + li {
            border-top: 1px solid rgba(167, 139, 250, 0.08);
        }

        .features li::before {
            content: "✦";
            color: #a78bfa;
            font-size: 10px;
            flex-shrink: 0;
        }

        /* CTA Button */
        .cta-wrap {
            text-align: center;
            margin-bottom: 28px;
        }

        .cta {
            display: inline-block;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: #ffffff !important;
            text-decoration: none;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            font-weight: 600;
            padding: 14px 36px;
            border-radius: 8px;
            letter-spacing: 0.3px;
        }

        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin: 28px 0;
        }

        .note {
            font-size: 12px;
            color: #52525b;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            text-align: center;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 24px 40px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .footer-text {
            font-size: 12px;
            color: #3f3f46;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
        }

        .footer-text a {
            color: #6d28d9;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">

        {{-- Brand --}}
        <div class="brand">
            <div class="brand-name">Rentdevous<span class="brand-dot">.</span></div>
        </div>

        <div class="card">

            {{-- Hero --}}
            <div class="hero">
                <span class="hero-icon">🏡</span>
                <div class="hero-title">Welcome aboard,<br>{{ $user->name }}!</div>
                <div class="hero-subtitle">Your account is ready. Let's find your perfect place.</div>
            </div>

            {{-- Body --}}
            <div class="body">
                <div class="greeting">Hi {{ explode(' ', $user->name)[0] }},</div>

                <p class="text">
                    Thank you for joining <strong style="color:#c4b5fd">Rentdevous</strong> — the modern way to discover and book
                    your next rental property. We're excited to have you with us.
                </p>

                <p class="text">Here's what you can do now:</p>

                <ul class="features">
                    <li>Browse hundreds of verified rental properties</li>
                    <li>Save your favourites for later comparison</li>
                    <li>Send inquiries and make reservations instantly</li>
                    <li>Leave reviews and share your experience</li>
                </ul>

                <div class="cta-wrap">
                    <a href="{{ url('/renter/home') }}" class="cta">Explore Properties →</a>
                </div>

                <hr class="divider">

                <p class="note">
                    If you did not create this account, you can safely ignore this email.<br>
                    No further action is required on your part.
                </p>
            </div>

            {{-- Footer --}}
            <div class="footer">
                <div class="footer-text">
                    © {{ date('Y') }} Rentdevous. All rights reserved.<br>
                    You're receiving this email because you registered at
                    <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>.
                </div>
            </div>

        </div>

    </div>
</body>
</html>
