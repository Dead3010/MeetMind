<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="theme-color" content="#1e3a8a">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="MeetMind">
  <link rel="manifest" href="/manifest.json">
  <link rel="apple-touch-icon" href="/icons/icon192.png">
  <title>MeetMind</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Inter', sans-serif;
      background: #f1f5f9;
      color: #1e293b;
      height: 100dvh;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    /* ── Header ── */
    .app-header {
      background: #1e3a8a;
      padding: 14px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
    }

    .header-logo {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .header-dot {
      width: 9px; height: 9px;
      background: #60a5fa;
      border-radius: 50%;
      box-shadow: 0 0 8px rgba(96,165,250,0.7);
    }

    .header-title {
      font-size: 18px;
      font-weight: 800;
      color: #ffffff;
      letter-spacing: -0.3px;
    }

    .header-title span { color: #60a5fa; }

    .header-user {
      width: 34px; height: 34px;
      background: rgba(255,255,255,0.2);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; font-weight: 700; color: white;
      cursor: pointer;
    }

    /* ── Screens ── */
    .screen { display: none; flex: 1; overflow-y: auto; }
    .screen.active { display: flex; flex-direction: column; }

    /* ── Bottom Nav ── */
    .bottom-nav {
      background: #ffffff;
      border-top: 1px solid #e2e8f0;
      display: flex;
      flex-shrink: 0;
      padding-bottom: env(safe-area-inset-bottom);
    }

    .nav-btn {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 10px 0;
      gap: 4px;
      border: none;
      background: none;
      color: #94a3b8;
      font-size: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: color 0.15s;
    }

    .nav-btn .nav-icon { font-size: 20px; }
    .nav-btn.active { color: #1e3a8a; }
    .nav-btn.active .nav-icon { filter: none; }

    /* ── Auth Screen ── */
    #screen-auth {
      background: #1e3a8a;
      justify-content: center;
      align-items: center;
      padding: 24px;
    }

    .auth-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 28px 22px;
      width: 100%;
      max-width: 380px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .auth-logo {
      text-align: center;
      margin-bottom: 24px;
    }

    .auth-logo-dot {
      width: 14px; height: 14px;
      background: #1e3a8a;
      border-radius: 50%;
      display: inline-block;
      margin-right: 6px;
      box-shadow: 0 0 10px rgba(30,58,138,0.4);
      vertical-align: middle;
    }

    .auth-logo-text {
      font-size: 22px;
      font-weight: 800;
      color: #1e293b;
      vertical-align: middle;
    }

    .auth-logo-text span { color: #1e3a8a; }

    .auth-subtitle {
      font-size: 12px;
      color: #94a3b8;
      text-align: center;
      margin-top: 4px;
      margin-bottom: 24px;
    }

    .auth-form { display: none; }
    .auth-form.active { display: block; }

    .form-label {
      font-size: 10px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 12px;
    }

    .input-group { position: relative; margin-bottom: 12px; }

    .input-field {
      width: 100%;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 12px 14px;
      font-size: 14px;
      color: #1e293b;
      outline: none;
      transition: border 0.2s;
    }

    .input-field:focus { border-color: #93c5fd; box-shadow: 0 0 0 3px rgba(30,58,138,0.08); }
    .input-field::placeholder { color: #cbd5e1; }
    .input-field.has-eye { padding-right: 44px; }

    .eye-btn {
      position: absolute; right: 12px; top: 50%;
      transform: translateY(-50%);
      background: none; border: none;
      color: #94a3b8; cursor: pointer; font-size: 16px;
    }

    .btn-primary {
      width: 100%;
      background: #1e3a8a;
      color: white;
      border: none;
      border-radius: 12px;
      padding: 13px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
      margin-bottom: 10px;
    }

    .btn-primary:hover { background: #1e40af; }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

    .auth-toggle {
      text-align: center;
      font-size: 12px;
      color: #94a3b8;
      margin-top: 8px;
    }

    .auth-toggle a { color: #1e3a8a; font-weight: 600; cursor: pointer; }

    .status-msg {
      font-size: 12px;
      text-align: center;
      height: 18px;
      margin-bottom: 8px;
    }

    .status-msg.success { color: #16a34a; }
    .status-msg.error   { color: #ef4444; }

    /* ── Record Screen ── */
    #screen-record {
      padding: 16px;
      gap: 12px;
      background: #f1f5f9;
    }

    .record-status-bar {
      background: #ffffff;
      border-radius: 14px;
      padding: 12px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border: 1px solid #e2e8f0;
    }

    .status-text {
      font-size: 12px;
      color: #94a3b8;
      font-style: italic;
    }

    .timer {
      font-size: 13px;
      color: #1e3a8a;
      font-family: monospace;
      font-weight: 700;
    }

    .record-btn-wrap {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      padding: 8px 0;
    }

    .record-btn {
      width: 90px; height: 90px;
      border-radius: 50%;
      border: none;
      background: linear-gradient(135deg, #1e3a8a, #2563eb);
      color: white;
      font-size: 32px;
      cursor: pointer;
      box-shadow: 0 8px 24px rgba(30,58,138,0.35);
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .record-btn:hover { transform: scale(1.05); }
    .record-btn.recording {
      background: linear-gradient(135deg, #dc2626, #ef4444);
      box-shadow: 0 8px 24px rgba(239,68,68,0.4);
      animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
      0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.4); }
      50% { box-shadow: 0 0 0 12px rgba(239,68,68,0); }
    }

    .record-label {
      font-size: 13px;
      font-weight: 600;
      color: #64748b;
    }

    .transcript-box {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 14px;
      flex: 1;
      min-height: 120px;
      overflow-y: auto;
    }

    .transcript-label {
      font-size: 10px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
    }

    .transcript-text {
      font-size: 13px;
      color: #475569;
      line-height: 1.7;
    }

    .transcript-placeholder { color: #cbd5e1; font-style: italic; }
    .t-final { color: #334155; }
    .t-interim { color: #94a3b8; font-style: italic; }

    .result-box {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 14px;
      display: none;
    }

    .result-title {
      font-size: 15px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 8px;
      line-height: 1.4;
    }

    .badges { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }

    .badge {
      font-size: 11px;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 20px;
      border: 1px solid transparent;
    }

    .badge-urgent  { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
    .badge-high    { background: #fff7ed; color: #f97316; border-color: #fed7aa; }
    .badge-normal  { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
    .badge-low     { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }
    .badge-topic   { background: #faf5ff; color: #7c3aed; border-color: #e9d5ff; }
    .badge-sent    { background: #f0f9ff; color: #0284c7; border-color: #bae6fd; }
    .badge-dur     { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }

    .result-section { margin-bottom: 10px; }
    .result-section-label {
      font-size: 10px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 4px;
    }

    .result-section-body { font-size: 13px; color: #475569; line-height: 1.6; }

    .result-list {
      padding-left: 18px;
      font-size: 13px;
      color: #475569;
      line-height: 1.9;
    }

    .priority-note {
      font-size: 12px;
      color: #d97706;
      background: #fffbeb;
      border: 1px solid #fde68a;
      border-radius: 8px;
      padding: 8px 10px;
      margin-top: 8px;
    }

    .action-row {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .btn-action {
      flex: 1;
      min-width: 100px;
      padding: 11px 10px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: all 0.2s;
      text-align: center;
    }

    .btn-save {
      background: #1e3a8a;
      color: white;
    }

    .btn-save:hover { background: #1e40af; }
    .btn-save:disabled { opacity: 0.6; cursor: not-allowed; }

    .btn-generate {
      background: #f3e8ff;
      color: #7c3aed;
      border: 1px solid #e9d5ff;
    }

    .btn-generate:hover { background: #ede9fe; }
    .btn-generate:disabled { opacity: 0.6; cursor: not-allowed; }

    .btn-clear {
      background: #f1f5f9;
      color: #64748b;
      border: 1px solid #e2e8f0;
    }

    .btn-clear:hover { background: #e2e8f0; }

    /* ── Dashboard Screen ── */
    #screen-dashboard {
      background: #f1f5f9;
      padding: 16px;
      gap: 12px;
    }

    .stats-row {
      display: flex;
      gap: 10px;
    }

    .stat-box {
      flex: 1;
      background: #1e3a8a;
      border-radius: 14px;
      padding: 14px 10px;
      text-align: center;
    }

    .stat-num { font-size: 22px; font-weight: 800; color: #ffffff; line-height: 1; margin-bottom: 4px; }
    .stat-lbl { font-size: 10px; color: rgba(255,255,255,0.65); font-weight: 600; }

    .search-box {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 11px 14px;
      font-size: 13px;
      color: #1e293b;
      outline: none;
      width: 100%;
      transition: border 0.2s;
    }

    .search-box:focus { border-color: #93c5fd; }
    .search-box::placeholder { color: #cbd5e1; }

    .chips { display: flex; gap: 6px; flex-wrap: wrap; }

    .chip {
      font-size: 11px;
      font-weight: 600;
      padding: 5px 12px;
      border-radius: 20px;
      border: 1px solid #e2e8f0;
      background: #ffffff;
      color: #94a3b8;
      cursor: pointer;
      transition: all 0.15s;
    }

    .chip:hover { border-color: #93c5fd; color: #1e3a8a; }
    .chip.active { background: #1e3a8a; color: #ffffff; border-color: #1e3a8a; }

    .meetings-list { display: flex; flex-direction: column; gap: 10px; }

    .meeting-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 14px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .meeting-card:hover { border-color: #93c5fd; box-shadow: 0 2px 12px rgba(30,58,138,0.08); }

    .mc-top {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 8px;
      margin-bottom: 8px;
    }

    .mc-title { font-size: 13px; font-weight: 700; color: #1e293b; flex: 1; line-height: 1.4; }

    .mc-delete {
      background: none;
      border: none;
      color: #cbd5e1;
      font-size: 16px;
      cursor: pointer;
      padding: 0;
      flex-shrink: 0;
      transition: color 0.15s;
    }

    .mc-delete:hover { color: #ef4444; }

    .mc-meta { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }

    .mc-badge {
      font-size: 10px;
      font-weight: 600;
      padding: 2px 8px;
      border-radius: 20px;
      border: 1px solid transparent;
    }

    .p-urgent { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
    .p-high   { background: #fff7ed; color: #f97316; border-color: #fed7aa; }
    .p-normal { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
    .p-low    { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }
    .t-badge  { background: #faf5ff; color: #7c3aed; border-color: #e9d5ff; }
    .s-badge  { background: #f0f9ff; color: #0284c7; border-color: #bae6fd; }

    .mc-date { font-size: 10px; color: #cbd5e1; }

    .mc-expanded {
      display: none;
      margin-top: 12px;
      padding-top: 12px;
      border-top: 1px solid #f1f5f9;
    }

    .mc-expanded.open { display: block; }

    .exp-label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
    .exp-text  { font-size: 12px; color: #64748b; line-height: 1.6; margin-bottom: 10px; }
    .exp-list  { padding-left: 16px; font-size: 12px; color: #64748b; line-height: 1.9; margin-bottom: 10px; }

    .btn-view-app {
      display: none;
      font-size: 11px;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 20px;
      background: #faf5ff;
      color: #7c3aed;
      border: 1px solid #e9d5ff;
      cursor: pointer;
      margin-top: 4px;
    }

    .empty-state {
      text-align: center;
      padding: 50px 20px;
    }

    .empty-icon { font-size: 3rem; margin-bottom: 12px; }
    .empty-text { font-size: 13px; color: #94a3b8; line-height: 1.8; }

    .btn-danger-full {
      width: 100%;
      background: #fef2f2;
      color: #ef4444;
      border: 1px solid #fecaca;
      border-radius: 12px;
      padding: 11px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-danger-full:hover { background: #fee2e2; }

    /* ── Settings Screen ── */
    #screen-settings {
      background: #f1f5f9;
      padding: 16px;
      gap: 12px;
    }

    .settings-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 16px;
    }

    .user-card {
      background: #1e3a8a;
      border-radius: 14px;
      padding: 16px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .user-avatar {
      width: 48px; height: 48px;
      background: rgba(255,255,255,0.2);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px; font-weight: 700; color: white;
      flex-shrink: 0;
    }

    .user-name { font-size: 16px; font-weight: 700; color: #ffffff; }
    .user-label { font-size: 11px; color: rgba(255,255,255,0.6); margin-top: 2px; }

    .settings-label {
      font-size: 10px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 10px;
    }

    .how-to-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin-bottom: 10px;
      font-size: 13px;
      color: #475569;
      line-height: 1.5;
    }

    .how-to-num {
      width: 22px; height: 22px;
      background: #1e3a8a;
      color: white;
      border-radius: 50%;
      font-size: 11px;
      font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }

    .btn-logout {
      width: 100%;
      background: #fef2f2;
      color: #ef4444;
      border: 1px solid #fecaca;
      border-radius: 12px;
      padding: 12px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-logout:hover { background: #fee2e2; }

    /* ── Misc ── */
    .divider { border: none; border-top: 1px solid #f1f5f9; margin: 4px 0; }

    /* ── Audio Visualizer ── */
    .visualizer-wrap { display: none; padding: 4px 0; }
    #visualizer { width: 100%; height: 48px; border-radius: 8px; background: #f8fafc; border: 1px solid #e2e8f0; display: block; }

    /* ── Lang Selector ── */
    .lang-selector { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 4px 8px; font-size: 11px; color: #475569; outline: none; cursor: pointer; }

    /* ── Pause Btn ── */
    .btn-pause { background: #fff7ed; color: #f97316; border: 1px solid #fed7aa; }
    .btn-pause:hover { background: #ffedd5; }

    /* ── Style Chips ── */
    .style-row { display: none; gap: 6px; flex-wrap: wrap; align-items: center; padding: 2px 0; }
    .style-label { font-size: 11px; color: #7c3aed; font-weight: 700; }
    .style-chip { font-size: 11px; font-weight: 600; padding: 5px 12px; border-radius: 20px; border: 1px solid #e9d5ff; background: #faf5ff; color: #7c3aed; cursor: pointer; transition: all 0.15s; }
    .style-chip.active { background: #7c3aed; color: white; border-color: #7c3aed; }

    @media (min-width: 480px) {
      body { max-width: 480px; margin: 0 auto; }
    }
  </style>
</head>
<body>

<!-- Header -->
<div class="app-header" id="app-header" style="display:none">
  <div class="header-logo">
    <div class="header-dot"></div>
    <div class="header-title">Meet<span>Mind</span></div>
  </div>
  <div class="header-user" id="header-avatar">?</div>
</div>

<!-- Auth Screen -->
<div class="screen active" id="screen-auth">
  <div class="auth-card">
    <div class="auth-logo">
      <span class="auth-logo-dot"></span>
      <span class="auth-logo-text">Meet<span>Mind</span></span>
    </div>
    <div class="auth-subtitle">AI Meeting Assistant</div>

    <!-- Login -->
    <div class="auth-form active" id="form-login">
      <div class="form-label">Login to your account</div>
      <div class="input-group">
        <input type="email" class="input-field" id="login-email" placeholder="Email address">
      </div>
      <div class="input-group">
        <input type="password" class="input-field has-eye" id="login-password" placeholder="Password">
        <button class="eye-btn" data-target="login-password">👁</button>
      </div>
      <div class="status-msg" id="login-msg"></div>
      <button class="btn-primary" id="login-btn">Login</button>
      <div class="auth-toggle">No account? <a id="to-register">Register</a></div>
    </div>

    <!-- Register -->
    <div class="auth-form" id="form-register">
      <div class="form-label">Create your account</div>
      <div class="input-group">
        <input type="text" class="input-field" id="reg-name" placeholder="Your name">
      </div>
      <div class="input-group">
        <input type="email" class="input-field" id="reg-email" placeholder="Email address">
      </div>
      <div class="input-group">
        <input type="password" class="input-field has-eye" id="reg-password" placeholder="Password (min 6 chars)">
        <button class="eye-btn" data-target="reg-password">👁</button>
      </div>
      <div class="status-msg" id="register-msg"></div>
      <button class="btn-primary" id="register-btn">Create Account</button>
      <div class="auth-toggle">Have an account? <a id="to-login">Login</a></div>
    </div>
  </div>
</div>

<!-- Record Screen -->
<div class="screen" id="screen-record">
  <div class="record-status-bar">
    <span class="status-text" id="rec-status">Ready to record</span>
    <select class="lang-selector" id="lang-selector">
      <option value="en-US">🇬🇧 EN</option>
      <option value="hi-IN">🇮🇳 HI</option>
      <option value="">🌐 Auto</option>
    </select>
    <span class="timer" id="rec-timer">00:00</span>
  </div>

  <div class="record-btn-wrap">
    <button class="record-btn" id="record-btn">🎙</button>
    <div class="record-label" id="record-label">Tap to Start</div>
    <button class="btn-action btn-pause" id="pause-btn" style="display:none;margin-top:6px;min-width:90px;">⏸ Pause</button>
  </div>
  <div class="visualizer-wrap" id="visualizer-wrap">
    <canvas id="visualizer"></canvas>
  </div>

  <div class="transcript-box">
    <div class="transcript-label">Live Transcript</div>
    <div class="transcript-text" id="transcript-content">
      <span class="transcript-placeholder">Transcript will appear here when recording starts…</span>
    </div>
  </div>

  <div class="result-box" id="result-box"></div>

  <div class="style-row" id="style-row">
    <span class="style-label">Style:</span>
    <span class="style-chip active" data-style="modern">🎨 Modern</span>
    <span class="style-chip" data-style="corporate">🏢 Corporate</span>
    <span class="style-chip" data-style="minimal">⬜ Minimal</span>
  </div>
  <div class="action-row" id="action-row" style="display:none">
    <button class="btn-action btn-save" id="save-btn">✓ Save</button>
    <button class="btn-action btn-generate" id="generate-btn">🚀 Generate</button>
    <button class="btn-action btn-clear" id="clear-btn">↺ Clear</button>
  </div>
  <div class="action-row" id="preview-action-row" style="display:none">
    <button class="btn-action btn-generate" id="download-btn">⬇ Download</button>
    <button class="btn-action btn-generate" id="regenerate-btn">🔄 Regen</button>
  </div>
</div>

<!-- Dashboard Screen -->
<div class="screen" id="screen-dashboard">
  <div class="stats-row">
    <div class="stat-box"><div class="stat-num" id="stat-total">0</div><div class="stat-lbl">Meetings</div></div>
    <div class="stat-box"><div class="stat-num" id="stat-urgent">0</div><div class="stat-lbl">Urgent</div></div>
    <div class="stat-box"><div class="stat-num" id="stat-topics">0</div><div class="stat-lbl">Topics</div></div>
  </div>

  <input type="text" class="search-box" id="search-box" placeholder="🔍  Search meetings…">

  <div class="chips" id="priority-chips">
    <div class="chip active" data-filter="all">All</div>
    <div class="chip" data-filter="Urgent">🔴 Urgent</div>
    <div class="chip" data-filter="High">🟡 High</div>
    <div class="chip" data-filter="Normal">🟢 Normal</div>
    <div class="chip" data-filter="Low">⚪ Low</div>
  </div>

  <div class="chips" id="topic-chips">
    <div class="chip active" data-topic="all">All Topics</div>
    <div class="chip" data-topic="Product">Product</div>
    <div class="chip" data-topic="Sales">Sales</div>
    <div class="chip" data-topic="HR">HR</div>
    <div class="chip" data-topic="Engineering">Eng</div>
    <div class="chip" data-topic="Finance">Finance</div>
    <div class="chip" data-topic="Marketing">Mktg</div>
  </div>

  <div class="meetings-list" id="meetings-list"></div>

  <button class="btn-danger-full" id="clear-all-btn" style="display:none">🗑 Clear All Meetings</button>
</div>

<!-- Settings Screen -->
<div class="screen" id="screen-settings">
  <div class="user-card">
    <div class="user-avatar" id="settings-avatar">?</div>
    <div>
      <div class="user-name" id="settings-name">—</div>
      <div class="user-label">Logged in</div>
    </div>
  </div>

  <div class="settings-card">
    <div class="settings-label">How to use</div>
    <div class="how-to-item"><div class="how-to-num">1</div><div>Open MeetMind before your meeting starts</div></div>
    <div class="how-to-item"><div class="how-to-num">2</div><div>Tap the mic button to start recording</div></div>
    <div class="how-to-item"><div class="how-to-num">3</div><div>Tap again to stop — AI will auto-analyze</div></div>
    <div class="how-to-item"><div class="how-to-num">4</div><div>Save to dashboard or generate an app preview</div></div>
  </div>

  <button class="btn-logout" id="logout-btn">🚪 Logout</button>
</div>

<!-- Bottom Nav -->
<nav class="bottom-nav" id="bottom-nav" style="display:none">
  <button class="nav-btn active" data-screen="record">
    <span class="nav-icon">🎙</span>
    <span>Record</span>
  </button>
  <button class="nav-btn" data-screen="dashboard">
    <span class="nav-icon">📊</span>
    <span>Meetings</span>
  </button>
  <button class="nav-btn" data-screen="settings">
    <span class="nav-icon">⚙️</span>
    <span>Settings</span>
  </button>
</nav>

<script>
  const API = '/api';

  // ── Auth helpers ────────────────────────────────────────────────────────────
  function getToken() { return localStorage.getItem('mm_token'); }
  function getName()  { return localStorage.getItem('mm_name'); }

  function setLoggedIn(token, name) {
    localStorage.setItem('mm_token', token);
    localStorage.setItem('mm_name', name || 'User');
    showApp(name);
  }

  function showApp(name) {
    document.getElementById('screen-auth').classList.remove('active');
    document.getElementById('app-header').style.display = 'flex';
    document.getElementById('bottom-nav').style.display = 'flex';
    const initial = (name || 'U')[0].toUpperCase();
    document.getElementById('header-avatar').textContent = initial;
    document.getElementById('settings-avatar').textContent = initial;
    document.getElementById('settings-name').textContent = name || 'User';
    switchScreen('record');
    loadDashboard();
  }

  function showAuth() {
    document.getElementById('app-header').style.display = 'none';
    document.getElementById('bottom-nav').style.display = 'none';
    document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
    document.getElementById('screen-auth').classList.add('active');
  }

  function apiHeaders() {
    return { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + getToken() };
  }

  // ── Screen switching ────────────────────────────────────────────────────────
  function switchScreen(name) {
    document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
    document.getElementById('screen-' + name).classList.add('active');
    document.querySelectorAll('.nav-btn').forEach(b => {
      b.classList.toggle('active', b.dataset.screen === name);
    });
    if (name === 'dashboard') loadDashboard();
  }

  document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.addEventListener('click', () => switchScreen(btn.dataset.screen));
  });

  // ── Init ────────────────────────────────────────────────────────────────────
  if (getToken()) showApp(getName());

  // ── Login ───────────────────────────────────────────────────────────────────
  document.getElementById('login-btn').addEventListener('click', async () => {
    const email    = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value;
    const msg      = document.getElementById('login-msg');
    if (!email || !password) { showMsg(msg, 'Fill all fields.', 'error'); return; }
    const btn = document.getElementById('login-btn');
    btn.disabled = true; btn.textContent = 'Logging in…';
    try {
      const res  = await fetch(`${API}/login`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ email, password }) });
      const data = await res.json();
      if (data.token) { showMsg(msg, 'Welcome back!', 'success'); setTimeout(() => setLoggedIn(data.token, data.user?.name), 500); }
      else showMsg(msg, data.message || 'Login failed', 'error');
    } catch { showMsg(msg, 'Cannot reach server.', 'error'); }
    btn.disabled = false; btn.textContent = 'Login';
  });

  // ── Register ────────────────────────────────────────────────────────────────
  document.getElementById('register-btn').addEventListener('click', async () => {
    const name     = document.getElementById('reg-name').value.trim();
    const email    = document.getElementById('reg-email').value.trim();
    const password = document.getElementById('reg-password').value;
    const msg      = document.getElementById('register-msg');
    if (!name || !email || !password) { showMsg(msg, 'Fill all fields.', 'error'); return; }
    if (password.length < 6) { showMsg(msg, 'Password min 6 chars.', 'error'); return; }
    const btn = document.getElementById('register-btn');
    btn.disabled = true; btn.textContent = 'Creating…';
    try {
      const res  = await fetch(`${API}/register`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ name, email, password }) });
      const data = await res.json();
      if (data.token) { showMsg(msg, 'Account created!', 'success'); setTimeout(() => setLoggedIn(data.token, data.user?.name), 500); }
      else showMsg(msg, data.message || 'Register failed', 'error');
    } catch { showMsg(msg, 'Cannot reach server.', 'error'); }
    btn.disabled = false; btn.textContent = 'Create Account';
  });

  // ── Logout ──────────────────────────────────────────────────────────────────
  document.getElementById('logout-btn').addEventListener('click', async () => {
    try { await fetch(`${API}/logout`, { method: 'POST', headers: apiHeaders() }); } catch {}
    localStorage.removeItem('mm_token');
    localStorage.removeItem('mm_name');
    showAuth();
  });

  // ── Form toggles ────────────────────────────────────────────────────────────
  document.getElementById('to-register').addEventListener('click', () => {
    document.getElementById('form-login').classList.remove('active');
    document.getElementById('form-register').classList.add('active');
  });

  document.getElementById('to-login').addEventListener('click', () => {
    document.getElementById('form-register').classList.remove('active');
    document.getElementById('form-login').classList.add('active');
  });

  // ── Password toggles ────────────────────────────────────────────────────────
  document.querySelectorAll('.eye-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const inp = document.getElementById(btn.dataset.target);
      inp.type = inp.type === 'password' ? 'text' : 'password';
    });
  });

  // ── Recording ───────────────────────────────────────────────────────────────
  let recognition    = null;
  let isListening    = false;
  let isPaused       = false;
  let fullTranscript = [];
  let currentSegment = '';
  let timerInterval  = null;
  let timerSeconds   = 0;
  let analysisResult = null;
  let lastSavedId    = null;
  let generatedHtml  = null;
  let selectedStyle  = 'modern';

  // Wake Lock
  let wakeLock = null;
  async function acquireWakeLock() {
    if ('wakeLock' in navigator) {
      try { wakeLock = await navigator.wakeLock.request('screen'); } catch {}
    }
  }
  function releaseWakeLock() { if (wakeLock) { wakeLock.release(); wakeLock = null; } }

  // Audio Visualizer
  let audioCtx = null, analyser = null, vizStream = null, animFrame = null;
  const vizCanvas = document.getElementById('visualizer');
  const vizCtx    = vizCanvas.getContext('2d');

  function startVisualizer(stream) {
    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    analyser = audioCtx.createAnalyser();
    analyser.fftSize = 256;
    audioCtx.createMediaStreamSource(stream).connect(analyser);
    document.getElementById('visualizer-wrap').style.display = 'block';
    drawViz();
  }

  function drawViz() {
    animFrame = requestAnimationFrame(drawViz);
    const buf = new Uint8Array(analyser.frequencyBinCount);
    analyser.getByteFrequencyData(buf);
    vizCanvas.width = vizCanvas.offsetWidth;
    vizCtx.clearRect(0, 0, vizCanvas.width, vizCanvas.height);
    const w = vizCanvas.width / buf.length;
    buf.forEach((v, i) => {
      const h = (v / 255) * vizCanvas.height;
      vizCtx.fillStyle = `hsl(${220 + (v / 255) * 40},70%,55%)`;
      vizCtx.fillRect(i * w, vizCanvas.height - h, w - 1, h);
    });
  }

  function stopVisualizer() {
    cancelAnimationFrame(animFrame);
    document.getElementById('visualizer-wrap').style.display = 'none';
    if (vizStream) { vizStream.getTracks().forEach(t => t.stop()); vizStream = null; }
    if (audioCtx) { audioCtx.close(); audioCtx = null; }
  }

  // Speaker detection
  let lastSpeechTime = 0;
  let currentSpeaker = 1;
  const SPEAKER_GAP  = 2000;

  function setStatus(msg) { document.getElementById('rec-status').textContent = msg; }

  function updateTranscript(final, interim) {
    document.getElementById('transcript-content').innerHTML =
      `<span class="t-final">${esc(final)}</span><span class="t-interim"> ${esc(interim)}</span>`;
  }

  document.getElementById('record-btn').addEventListener('click', () => {
    if (!isListening) startRecording();
    else stopAndAnalyze();
  });

  // Pause / Resume
  document.getElementById('pause-btn').addEventListener('click', () => {
    if (!isListening) return;
    if (!isPaused) {
      isPaused = true;
      recognition.stop();
      clearInterval(timerInterval);
      document.getElementById('pause-btn').textContent = '▶ Resume';
      setStatus('Paused');
    } else {
      isPaused = false;
      recognition.start();
      timerInterval = setInterval(() => {
        timerSeconds++;
        const m = String(Math.floor(timerSeconds / 60)).padStart(2, '0');
        const s = String(timerSeconds % 60).padStart(2, '0');
        const el = document.getElementById('rec-timer');
        if (el) el.textContent = `${m}:${s}`;
      }, 1000);
      document.getElementById('pause-btn').textContent = '⏸ Pause';
      setStatus('Recording…');
    }
  });

  async function startRecording() {
    const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SR) { setStatus('Speech recognition not supported.'); return; }

    try {
      vizStream = await navigator.mediaDevices.getUserMedia({ audio: true });
      startVisualizer(vizStream);
    } catch {}

    acquireWakeLock();
    lastSpeechTime = 0; currentSpeaker = 1;

    recognition = new SR();
    recognition.continuous     = true;
    recognition.interimResults = true;
    recognition.lang = document.getElementById('lang-selector').value;

    recognition.onstart = () => {
      isListening = true;
      document.getElementById('record-btn').classList.add('recording');
      document.getElementById('record-btn').textContent = '⏹';
      document.getElementById('record-label').textContent = 'Tap to Stop';
      document.getElementById('pause-btn').style.display = 'inline-flex';
      setStatus('Recording…');
      timerSeconds = 0;
      clearInterval(timerInterval);
      timerInterval = setInterval(() => {
        timerSeconds++;
        const m = String(Math.floor(timerSeconds / 60)).padStart(2, '0');
        const s = String(timerSeconds % 60).padStart(2, '0');
        const el = document.getElementById('rec-timer');
        if (el) el.textContent = `${m}:${s}`;
      }, 1000);
    };

    recognition.onresult = (event) => {
      const now = Date.now();
      let interim = '';
      for (let i = event.resultIndex; i < event.results.length; i++) {
        const t = event.results[i][0].transcript;
        if (event.results[i].isFinal) {
          if (lastSpeechTime && (now - lastSpeechTime) > SPEAKER_GAP) {
            currentSpeaker = currentSpeaker === 1 ? 2 : 1;
          }
          lastSpeechTime = now;
          fullTranscript.push(`[Speaker ${currentSpeaker}] ${t}`);
          currentSegment = '';
        } else { interim = t; currentSegment = interim; }
      }
      updateTranscript(fullTranscript.join(' '), interim);
    };

    recognition.onerror = (e) => {
      if (e.error === 'not-allowed') { recognition = null; setStatus('❌ Mic blocked — allow mic access'); }
      else if (e.error !== 'no-speech') setStatus('Mic error: ' + e.error);
    };

    recognition.onend = () => { if (isListening && !isPaused) recognition.start(); };
    recognition.start();
  }

  function stopRecording() {
    isListening = false; isPaused = false;
    clearInterval(timerInterval);
    if (recognition) { recognition.stop(); recognition = null; }
    stopVisualizer();
    releaseWakeLock();
    document.getElementById('record-btn').classList.remove('recording');
    document.getElementById('record-btn').textContent = '🎙';
    document.getElementById('record-label').textContent = 'Tap to Start';
    document.getElementById('pause-btn').style.display = 'none';
    document.getElementById('pause-btn').textContent = '⏸ Pause';
  }

  async function stopAndAnalyze() {
    stopRecording();
    const transcript = (fullTranscript.join(' ') + ' ' + currentSegment).trim();
    if (!transcript || transcript.length < 10) { setStatus('Nothing recorded.'); return; }

    setStatus('Analyzing with AI…');
    document.getElementById('record-btn').disabled = true;

    try {
      const res  = await fetch(`${API}/analyze`, { method: 'POST', headers: apiHeaders(), body: JSON.stringify({ transcript }) });
      const data = await res.json();
      if (data.error) { setStatus('Error: ' + data.error); document.getElementById('record-btn').disabled = false; return; }

      analysisResult = data;
      renderResult(data);
      setStatus('Saving to dashboard…');

      const meeting = { date: new Date().toISOString(), transcript, ...data };
      const saveRes  = await fetch(`${API}/meetings`, { method: 'POST', headers: apiHeaders(), body: JSON.stringify(meeting) });
      const saved    = await saveRes.json();

      if (saved.id) { lastSavedId = saved.id; setStatus('✓ Saved to dashboard!'); }
      else { setStatus('Analysis done — save manually.'); }

      document.getElementById('action-row').style.display = 'flex';
      document.getElementById('style-row').style.display = 'flex';
    } catch (e) {
      setStatus('Failed: ' + e.message);
    }

    document.getElementById('record-btn').disabled = false;
  }

  // ── Result render ───────────────────────────────────────────────────────────
  function renderResult(data) {
    const pMap = { Urgent: 'badge-urgent', High: 'badge-high', Normal: 'badge-normal', Low: 'badge-low' };
    const actions   = (data.action_items  || []).map(a => `<li>${esc(a)}</li>`).join('');
    const decisions = (data.key_decisions || []).map(d => `<li>${esc(d)}</li>`).join('');

    const convFlow = (data.conversation_flow || []).map(c => `
      <div style="display:flex;gap:8px;margin-bottom:10px;align-items:flex-start;">
        <span style="font-size:10px;font-weight:700;padding:3px 8px;border-radius:20px;white-space:nowrap;flex-shrink:0;
          background:${c.speaker==='You'?'#eff6ff':'#f0fdf4'};
          color:${c.speaker==='You'?'#1e3a8a':'#16a34a'};
          border:1px solid ${c.speaker==='You'?'#bfdbfe':'#bbf7d0'}">
          ${esc(c.speaker)}
        </span>
        <span style="font-size:12px;color:#475569;line-height:1.6">${esc(c.text)}</span>
      </div>`).join('');

    const speakerSents = data.speaker_sentiments ? Object.entries(data.speaker_sentiments).map(([s,v]) => `
      <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #f1f5f9;">
        <span style="font-size:12px;font-weight:600;color:#334155">${esc(s)}</span>
        <span style="font-size:11px;color:#64748b;background:#f8fafc;padding:2px 8px;border-radius:20px;border:1px solid #e2e8f0">${esc(v)}</span>
      </div>`).join('') : '';

    const momPoints = (data.mom?.discussion_points || []).map(p => `<li>${esc(p)}</li>`).join('');
    const momAttendees = (data.mom?.attendees || []).join(', ');

    const box = document.getElementById('result-box');
    box.style.display = 'block';
    box.innerHTML = `
      <div class="result-title">${esc(data.title)}</div>
      <div class="badges">
        <span class="badge ${pMap[data.priority] || 'badge-low'}">${esc(data.priority)}</span>
        <span class="badge badge-topic">${esc(data.topic)}</span>
        <span class="badge badge-sent">💬 ${esc(data.sentiment)}</span>
        ${data.duration_estimate ? `<span class="badge badge-dur">⏱ ${esc(data.duration_estimate)}</span>` : ''}
      </div>

      <div class="result-section"><div class="result-section-label">📋 Summary</div><div class="result-section-body">${esc(data.summary)}</div></div>

      ${convFlow ? `<div class="result-section"><div class="result-section-label">💬 Conversation Flow</div>${convFlow}</div>` : ''}

      ${speakerSents ? `<div class="result-section"><div class="result-section-label">😊 Speaker Sentiments</div>${speakerSents}</div>` : ''}

      ${data.conclusion ? `<div class="result-section"><div class="result-section-label">🏁 Conclusion</div><div class="result-section-body">${esc(data.conclusion)}</div></div>` : ''}

      ${data.mom ? `
      <div class="result-section">
        <div class="result-section-label">📝 MOM</div>
        ${momAttendees ? `<div style="font-size:11px;color:#64748b;margin-bottom:6px">👥 ${esc(momAttendees)}</div>` : ''}
        ${data.mom.agenda ? `<div style="font-size:11px;color:#64748b;margin-bottom:8px">📌 ${esc(data.mom.agenda)}</div>` : ''}
        ${momPoints ? `<ul class="result-list">${momPoints}</ul>` : ''}
      </div>` : ''}

      ${actions   ? `<div class="result-section"><div class="result-section-label">✅ Action Items</div><ul class="result-list">${actions}</ul></div>` : ''}
      ${decisions ? `<div class="result-section"><div class="result-section-label">🔑 Key Decisions</div><ul class="result-list">${decisions}</ul></div>` : ''}

      <div class="priority-note">⚡ ${esc(data.priority_reason)}</div>
    `;
  }

  // ── Save btn (manual fallback) ───────────────────────────────────────────────
  document.getElementById('save-btn').addEventListener('click', async () => {
    if (!analysisResult) return;
    const btn = document.getElementById('save-btn');
    btn.disabled = true; btn.textContent = 'Saving…';
    try {
      const transcript = (fullTranscript.join(' ') + ' ' + currentSegment).trim();
      const meeting = { date: new Date().toISOString(), transcript, ...analysisResult };
      const res  = await fetch(`${API}/meetings`, { method: 'POST', headers: apiHeaders(), body: JSON.stringify(meeting) });
      const data = await res.json();
      if (data.id) { lastSavedId = data.id; btn.textContent = '✓ Saved!'; btn.style.background = '#16a34a'; }
      else { btn.textContent = '✓ Save'; btn.disabled = false; }
    } catch { btn.textContent = '✓ Save'; btn.disabled = false; }
  });

  // ── Style Selector ───────────────────────────────────────────────────────────
  document.querySelectorAll('.style-chip').forEach(c => {
    c.addEventListener('click', () => {
      document.querySelectorAll('.style-chip').forEach(x => x.classList.remove('active'));
      c.classList.add('active');
      selectedStyle = c.dataset.style;
    });
  });

  // ── Generate / Regenerate ────────────────────────────────────────────────────
  async function doGenerate() {
    const transcript = (fullTranscript.join(' ') + ' ' + currentSegment).trim();
    if (!transcript || transcript.length < 20) { setStatus('Record some transcript first.'); return; }
    const btn = document.getElementById('generate-btn');
    btn.disabled = true; btn.textContent = '⏳ Generating…';
    setStatus('Generating app preview…');
    try {
      const res  = await fetch(`${API}/generate-frontend`, {
        method: 'POST', headers: apiHeaders(),
        body: JSON.stringify({ transcript, style: selectedStyle })
      });
      const data = await res.json();
      if (data.error) { setStatus('Generate failed: ' + data.error); btn.disabled = false; btn.textContent = '🚀 Generate'; return; }

      generatedHtml = data.html;

      if (lastSavedId) {
        await fetch(`${API}/meetings/${lastSavedId}/preview`, {
          method: 'POST', headers: apiHeaders(), body: JSON.stringify({ html: data.html })
        });
      }

      const blob = new Blob([data.html], { type: 'text/html' });
      window.open(URL.createObjectURL(blob), '_blank');
      setStatus('App preview saved ✓');
      document.getElementById('preview-action-row').style.display = 'flex';
    } catch (e) { setStatus('Failed: ' + e.message); }
    btn.disabled = false; btn.textContent = '🚀 Generate';
  }

  document.getElementById('generate-btn').addEventListener('click', doGenerate);

  document.getElementById('regenerate-btn').addEventListener('click', async () => {
    document.getElementById('preview-action-row').style.display = 'none';
    await doGenerate();
  });

  document.getElementById('download-btn').addEventListener('click', () => {
    if (!generatedHtml) return;
    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([generatedHtml], { type: 'text/html' }));
    a.download = 'meetmind-preview.html';
    a.click();
  });

  // ── Clear ────────────────────────────────────────────────────────────────────
  document.getElementById('clear-btn').addEventListener('click', () => {
    fullTranscript = []; currentSegment = ''; analysisResult = null; lastSavedId = null; generatedHtml = null;
    document.getElementById('transcript-content').innerHTML = '<span class="transcript-placeholder">Transcript will appear here when recording starts…</span>';
    document.getElementById('result-box').style.display = 'none';
    document.getElementById('result-box').innerHTML = '';
    document.getElementById('action-row').style.display = 'none';
    document.getElementById('style-row').style.display = 'none';
    document.getElementById('preview-action-row').style.display = 'none';
    document.getElementById('rec-timer').textContent = '00:00';
    const saveBtn = document.getElementById('save-btn');
    saveBtn.textContent = '✓ Save'; saveBtn.disabled = false; saveBtn.style.background = '';
    setStatus('Cleared');
  });

  // ── Dashboard ────────────────────────────────────────────────────────────────
  let allMeetings  = [];
  let activeFilter = 'all';
  let activeTopic  = 'all';
  let searchQuery  = '';

  async function loadDashboard() {
    try {
      const res  = await fetch(`${API}/meetings`, { headers: apiHeaders() });
      allMeetings = await res.json();
      if (!Array.isArray(allMeetings)) allMeetings = [];
    } catch { allMeetings = []; }
    renderDashboard();
  }

  function renderDashboard() {
    let list = allMeetings;
    if (activeFilter !== 'all') list = list.filter(m => m.priority === activeFilter);
    if (activeTopic  !== 'all') list = list.filter(m => m.topic    === activeTopic);
    if (searchQuery) {
      const q = searchQuery.toLowerCase();
      list = list.filter(m =>
        (m.title || '').toLowerCase().includes(q) ||
        (m.summary || '').toLowerCase().includes(q) ||
        (m.topic || '').toLowerCase().includes(q) ||
        (m.action_items || []).some(a => a.toLowerCase().includes(q))
      );
    }

    document.getElementById('stat-total').textContent  = allMeetings.length;
    document.getElementById('stat-urgent').textContent = allMeetings.filter(m => m.priority === 'Urgent').length;
    document.getElementById('stat-topics').textContent = new Set(allMeetings.map(m => m.topic)).size;
    document.getElementById('clear-all-btn').style.display = allMeetings.length ? 'block' : 'none';

    const el = document.getElementById('meetings-list');
    if (list.length === 0) {
      el.innerHTML = `<div class="empty-state"><div class="empty-icon">${allMeetings.length ? '🔍' : '📋'}</div><div class="empty-text">${allMeetings.length ? 'No meetings match your filter.' : 'No meetings yet.<br>Start recording!'}</div></div>`;
      return;
    }

    el.innerHTML = list.map(m => buildCard(m)).join('');

    el.querySelectorAll('.meeting-card').forEach(card => {
      card.querySelector('.mc-top').addEventListener('click', e => {
        if (e.target.classList.contains('mc-delete')) return;
        card.querySelector('.mc-expanded').classList.toggle('open');
      });

      card.querySelector('.mc-delete').addEventListener('click', async e => {
        e.stopPropagation();
        await fetch(`${API}/meetings/${card.dataset.id}`, { method: 'DELETE', headers: apiHeaders() });
        loadDashboard();
      });

      const previewBtn = card.querySelector('.btn-view-app');
      if (previewBtn) {
        previewBtn.addEventListener('click', e => {
          e.stopPropagation();
          const html = decodeURIComponent(previewBtn.dataset.preview);
          const blob = new Blob([html], { type: 'text/html' });
          window.open(URL.createObjectURL(blob), '_blank');
        });
      }
    });
  }

  function buildCard(m) {
    const date = new Date(m.date || m.created_at).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
    const pClass = { Urgent: 'p-urgent', High: 'p-high', Normal: 'p-normal', Low: 'p-low' }[m.priority] || 'p-low';
    const actions   = (m.action_items  || []).map(a => `<li>${esc(a)}</li>`).join('');
    const decisions = (m.key_decisions || []).map(d => `<li>${esc(d)}</li>`).join('');
    return `
    <div class="meeting-card" data-id="${m.id}">
      <div class="mc-top">
        <div class="mc-title">${esc(m.title || 'Untitled Meeting')}</div>
        <button class="mc-delete" title="Delete">×</button>
      </div>
      <div class="mc-meta">
        <span class="mc-badge ${pClass}">${m.priority || 'Normal'}</span>
        <span class="mc-badge t-badge">${m.topic || 'General'}</span>
        <span class="mc-badge s-badge">💬 ${m.sentiment || '—'}</span>
        <span class="mc-date">${date}</span>
      </div>
      <div class="mc-expanded">
        ${m.summary   ? `<div class="exp-label">Summary</div><div class="exp-text">${esc(m.summary)}</div>` : ''}
        ${actions     ? `<div class="exp-label">✅ Action Items</div><ul class="exp-list">${actions}</ul>` : ''}
        ${decisions   ? `<div class="exp-label">🔑 Key Decisions</div><ul class="exp-list">${decisions}</ul>` : ''}
        ${m.priority_reason ? `<div class="exp-label">⚡ Priority</div><div class="exp-text" style="color:#d97706">${esc(m.priority_reason)}</div>` : ''}
        ${m.preview_html ? `
        <div class="exp-label" style="margin-top:10px">🚀 App Preview</div>
        <div style="position:relative;width:100%;border-radius:10px;overflow:hidden;border:1px solid #e2e8f0;margin-top:6px;">
          <iframe srcdoc="${esc(m.preview_html)}"
            style="width:300%;height:340px;border:none;transform:scale(0.333);transform-origin:top left;pointer-events:none;"
            sandbox="allow-scripts allow-same-origin"></iframe>
          <div style="position:absolute;inset:0;cursor:pointer;border-radius:10px;" class="preview-overlay" data-id="${m.id}"></div>
        </div>
        <button class="btn-view-app" style="display:inline-block;margin-top:8px;" data-preview="${encodeURIComponent(m.preview_html)}">🚀 Open Full Preview</button>
        ` : ''}
      </div>
    </div>`;
  }

  // Chips
  document.querySelectorAll('#priority-chips .chip').forEach(c => {
    c.addEventListener('click', () => {
      document.querySelectorAll('#priority-chips .chip').forEach(x => x.classList.remove('active'));
      c.classList.add('active');
      activeFilter = c.dataset.filter;
      renderDashboard();
    });
  });

  document.querySelectorAll('#topic-chips .chip').forEach(c => {
    c.addEventListener('click', () => {
      document.querySelectorAll('#topic-chips .chip').forEach(x => x.classList.remove('active'));
      c.classList.add('active');
      activeTopic = c.dataset.topic;
      renderDashboard();
    });
  });

  document.getElementById('search-box').addEventListener('input', e => {
    searchQuery = e.target.value;
    renderDashboard();
  });

  document.getElementById('clear-all-btn').addEventListener('click', async () => {
    if (!confirm('Delete all saved meetings?')) return;
    await fetch(`${API}/meetings`, { method: 'DELETE', headers: apiHeaders() });
    loadDashboard();
  });

  // ── Helpers ──────────────────────────────────────────────────────────────────
  function esc(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function showMsg(el, text, type) {
    el.textContent = text;
    el.className = 'status-msg ' + type;
    setTimeout(() => { el.textContent = ''; el.className = 'status-msg'; }, 3000);
  }

  // ── Service Worker ───────────────────────────────────────────────────────────
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
  }
</script>
</body>
</html>
