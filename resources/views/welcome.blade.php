<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Achievement Management Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            font-family: 'Inter', sans-serif;
            --bg: #F8F8FF;
            --surface: #FFFFFF;
            --surface-muted: #F5F5F5;
            --text: #111827;
            --text-muted: #4B5563;
            --accent: #4F46E5;
            --border: #E5E7EB;
            --radius: 24px;
            --shadow: 0 30px 60px rgba(15, 23, 42, 0.08);
            --shadow-soft: 0 16px 40px rgba(15, 23, 42, 0.08);
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }
        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at top left, rgba(79, 70, 229, 0.12), transparent 28%),
                        linear-gradient(180deg, #FFFFFF 0%, #F8F8FF 100%);
            color: var(--text);
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 64px 64px;
            opacity: 0.36;
            pointer-events: none;
            z-index: 0;
        }

        .page-shell {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .landing-panel {
            width: 100%;
            max-width: 1120px;
            display: grid;
            justify-items: center;
            gap: 2rem;
            padding-top: 1rem;
            grid-template-columns: 1fr;
        }

        .intro {
            width: 100%;
            max-width: 720px;
            padding: 2.2rem 2.4rem;
            background: rgba(255,255,255,0.95);
            border: 1px solid rgba(229,231,235,0.95);
            border-radius: var(--radius);
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(12px);
        }

        .intro h1 {
            margin: 0;
            font-size: clamp(2.4rem, 3.5vw, 3.8rem);
            line-height: 1.02;
            font-weight: 800;
        }

        .intro p {
            margin: 1rem 0 0;
            line-height: 1.75;
            color: var(--text-muted);
            font-size: 1rem;
        }

        .intro .label {
            display: inline-flex;
            align-items: center;
            gap: 0.9rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 1.5rem;
        }

        .cards {
            width: 100%;
            max-width: 1120px;
            display: grid;
            grid-template-columns: 1fr;
            justify-items: center;
            gap: 2.5rem;
            margin-top: 48px;
        }

        a.portal-card,
        .portal-card {
            width: 100%;
            max-width: 520px;
            background: var(--surface-muted);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 2rem;
            min-height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: var(--shadow);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        a.portal-card:hover,
        .portal-card:hover {
            transform: translateY(-1px);
            border-color: rgba(79, 70, 229, 0.25);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.14);
        }

        .portal-card span.icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.1rem;
            height: 3.1rem;
            border-radius: 18px;
            background: rgba(79, 70, 229, 0.12);
            font-size: 1.55rem;
            margin-bottom: 1.3rem;
        }

        .portal-card h2 {
            margin: 0;
            font-size: 1.6rem;
            line-height: 1.1;
            letter-spacing: -0.025em;
        }

        .portal-card p {
            margin: 1rem 0 1.6rem;
            color: var(--text-muted);
            line-height: 1.75;
            font-size: 1rem;
        }

        .portal-card .portal-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.95rem 1.4rem;
            border-radius: 999px;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            box-shadow: 0 14px 25px rgba(79, 70, 229, 0.18);
        }

        .portal-card:hover .portal-btn {
            transform: translateY(-1px);
            background: #4338ca;
        }

        .portal-card:focus-visible .portal-btn {
            outline: 3px solid rgba(79, 70, 229, 0.24);
            outline-offset: 4px;
        }

        .note {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        @media (min-width: 900px) {
            .cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                justify-items: center;
            }
        }

        @media (max-width: 900px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 720px) {
            .intro,
            .portal-card {
                padding: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="landing-panel">
            <section class="intro">
                <div class="label">
                    <span>Portal Access</span>
                </div>
                <h1>Student Achievement Management Portal</h1>
                <p>Manage, submit, and review academic achievements efficiently with a modern portal experience tailored for students and faculty.</p>
                <p class="note">Choose one of the portals below to continue to the appropriate login flow.</p>
            </section>

            <div class="cards">
                <a href="/student/login" class="portal-card">
                    <div>
                        <span class="icon">🎓</span>
                        <h2>Student Portal</h2>
                        <p>Access your student dashboard to submit and track achievements.</p>
                    </div>
                    <div class="portal-btn">Enter Student Portal</div>
                </a>

                <a href="/faculty/login" class="portal-card">
                    <div>
                        <span class="icon">👨‍🏫</span>
                        <h2>Faculty Portal</h2>
                        <p>Review, verify, and manage student achievements.</p>
                    </div>
                    <div class="portal-btn">Enter Faculty Portal</div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
