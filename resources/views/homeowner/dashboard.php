<?php  
?>

<style>
/* Hide footer on dashboard */
footer { display: none !important; }

/* ── Dashboard Shell ─────────────────────────────────────── */
.dash-shell {
    display: grid;
    grid-template-columns: 240px 1fr 272px;
    min-height: calc(100vh - 64px);
    background: #f8f7ff;
    font-family: 'Inter', sans-serif;
}
html[lang="ar"], html[lang="ar"] body, html[lang="ar"] * { font-family: 'Cairo', sans-serif !important; }

/* ── Left Sidebar ────────────────────────────────────────── */
.dash-sidebar {
    background: #ffffff;
    border-inline-end: 1px solid #ede9fe;
    display: flex;
    flex-direction: column;
    padding: 1.5rem 0.75rem;
    position: sticky;
    top: 64px;
    height: calc(100vh - 64px);
    overflow-y: auto;
}

.dash-nav {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.dash-nav-item {
    display: flex; align-items: center; gap: 0.65rem;
    padding: 0.6rem 1rem; border-radius: 0.6rem;
    font-size: 0.875rem; font-weight: 700; color: #6b7280;
    cursor: pointer; transition: all 0.15s ease;
    border: none; border-inline-start: 3px solid transparent;
    background: none; width: 100%; text-align: start;
}
.dash-nav-item:hover  { background: #f5f3ff; color: #4f46e5; transform: translateX(2px); }
html[dir="rtl"] .dash-nav-item:hover { transform: translateX(-2px); }
.dash-nav-item.active { background: #eef2ff; color: #4f46e5; border-inline-start-color: #4f46e5; }
.dash-nav-item svg    { width: 17px; height: 17px; flex-shrink: 0; }

.dash-nav-badge {
    margin-inline-start: auto; color: white;
    font-size: 0.62rem; font-weight: 900;
    padding: 0.1rem 0.42rem; border-radius: 99px; line-height: 1.5;
}
.dash-nav-badge.indigo { background: #4f46e5; }
.dash-nav-badge.red    { background: #ef4444; }
.dash-nav-badge.amber  { background: #f59e0b; }
.dash-nav-badge.pink   { background: #ec4899; }
.dash-nav-badge.blue   { background: #3b82f6; }

.dash-sidebar-bottom {
    padding-top: 1rem; border-top: 1px solid #f3f0ff;
    display: flex; flex-direction: column; gap: 0.45rem; margin-top: 0.5rem;
}
.dash-quick-btn {
    display: flex; align-items: center; gap: 0.55rem;
    padding: 0.55rem 1rem; border-radius: 0.6rem;
    font-size: 0.8rem; font-weight: 800; text-decoration: none; transition: all 0.15s;
}
.dash-quick-btn svg { width: 14px; height: 14px; flex-shrink: 0; }
.dash-quick-btn.primary   { background: #4f46e5; color: white; }
.dash-quick-btn.primary:hover { background: #4338ca; box-shadow: 0 4px 14px rgba(79,70,229,0.3); transform: translateY(-1px); }
.dash-quick-btn.secondary { background: #f5f3ff; color: #4f46e5; border: 1px solid #e0e7ff; }
.dash-quick-btn.secondary:hover { background: #ede9fe; }

/* ── Main Content ─────────────────────────────────────────── */
.dash-main { padding: 2rem 1.75rem; overflow-y: auto; min-width: 0; }

.dash-welcome h1 {
    font-size: 1.7rem; font-weight: 900; color: #1e1b4b;
    letter-spacing: -0.03em; line-height: 1.1; margin-bottom: 0.3rem;
}
.dash-welcome p { font-size: 0.875rem; color: #9ca3af; font-weight: 600; margin-bottom: 1.5rem; }

.dash-tab { display: none; }
.dash-tab.active { display: block; }

/* ── Flash messages ───────────────────────────────────────── */
.flash {
    display: flex; align-items: center; gap: 0.75rem;
    border-radius: 1rem; padding: 0.875rem 1.125rem; margin-bottom: 1.25rem;
    font-size: 0.875rem; font-weight: 700;
}
.flash svg { width: 18px; height: 18px; flex-shrink: 0; }
.flash.success { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border: 1px solid #bbf7d0; color: #15803d; }
.flash.success svg { color: #16a34a; }
.flash.error   { background: linear-gradient(135deg,#fef2f2,#fee2e2); border: 1px solid #fecaca; color: #991b1b; }
.flash.error svg   { color: #ef4444; }

/* ── Attention Banners ────────────────────────────────────── */
.attention-banner {
    background: linear-gradient(135deg,#eef2ff,#f5f3ff);
    border: 1px solid #c7d2fe; border-radius: 1rem;
    padding: 1rem 1.25rem; margin-bottom: 0.75rem;
    display: flex; align-items: flex-start; gap: 0.875rem;
}
.attention-icon {
    width: 36px; height: 36px; background: #4f46e5;
    border-radius: 0.6rem; display: flex; align-items: center;
    justify-content: center; flex-shrink: 0;
}
.attention-icon svg { width: 17px; height: 17px; color: white; }
.attention-banner h3 { font-size: 0.875rem; font-weight: 800; color: #312e81; margin-bottom: 0.15rem; }
.attention-banner p  { font-size: 0.78rem; color: #6366f1; font-weight: 600; }
.attention-banner a  {
    display: inline-flex; align-items: center; gap: 0.3rem;
    margin-top: 0.5rem; font-size: 0.75rem; font-weight: 800; color: #4f46e5;
    text-decoration: none; background: white; border: 1px solid #c7d2fe;
    padding: 0.28rem 0.7rem; border-radius: 0.4rem; transition: all 0.15s;
}
.attention-banner a:hover { background: #4f46e5; color: white; border-color: #4f46e5; }
.attention-banner a svg { width: 11px; height: 11px; }

.attention-banner.yellow { background: linear-gradient(135deg,#fffbeb,#fef9c3); border-color: #fde68a; }
.attention-banner.yellow h3 { color: #854d0e; }
.attention-banner.yellow p  { color: #b45309; }
.attention-banner.yellow .attention-icon { background: #f59e0b; }
.attention-banner.yellow a  { color: #d97706; border-color: #fde68a; }
.attention-banner.yellow a:hover { background: #f59e0b; color: white; border-color: #f59e0b; }

.attention-banner.purple { background: linear-gradient(135deg,#faf5ff,#f3e8ff); border-color: #e9d5ff; }
.attention-banner.purple h3 { color: #6b21a8; }
.attention-banner.purple p  { color: #9333ea; }
.attention-banner.purple .attention-icon { background: #a855f7; }
.attention-banner.purple a  { color: #9333ea; border-color: #e9d5ff; }
.attention-banner.purple a:hover { background: #a855f7; color: white; border-color: #a855f7; }

.all-good-banner {
    background: linear-gradient(135deg,#f0fdf4,#dcfce7);
    border: 1px solid #bbf7d0; border-radius: 1rem;
    padding: 0.875rem 1.25rem; margin-bottom: 0.75rem;
    display: flex; align-items: center; gap: 0.75rem;
}
.all-good-banner svg { width: 20px; height: 20px; color: #16a34a; flex-shrink: 0; }
.all-good-banner p   { font-size: 0.85rem; font-weight: 700; color: #15803d; }

/* ── Section label ────────────────────────────────────────── */
.section-label {
    font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: 0.08em; color: #9ca3af;
    margin-bottom: 0.7rem; padding-inline-start: 0.2rem;
}

/* ── Activity feed ────────────────────────────────────────── */
.activity-list { background: white; border: 1px solid #ede9fe; border-radius: 1rem; overflow: hidden; }
.activity-item {
    display: flex; align-items: center; gap: 0.875rem;
    padding: 0.825rem 1.125rem; border-bottom: 1px solid #f9f8ff;
    cursor: pointer; transition: background 0.12s;
}
.activity-item:last-child { border-bottom: none; }
.activity-item:hover { background: #f9f8ff; }
.act-dot {
    width: 34px; height: 34px; border-radius: 0.55rem;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.act-dot svg   { width: 15px; height: 15px; }
.act-dot.blue  { background: #eff6ff; color: #3b82f6; }
.act-dot.green { background: #f0fdf4; color: #22c55e; }
.act-dot.amber { background: #fffbeb; color: #f59e0b; }
.act-dot.indigo{ background: #eef2ff; color: #6366f1; }
.act-dot.orange{ background: #fff7ed; color: #ea580c; }
.act-dot.purple{ background: #faf5ff; color: #a855f7; }
.activity-text { flex: 1; min-width: 0; }
.activity-text p    { font-size: 0.82rem; font-weight: 700; color: #374151; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.activity-text span { font-size: 0.7rem; color: #9ca3af; font-weight: 600; }
.activity-chevron { width: 13px; height: 13px; color: #d1d5db; flex-shrink: 0; }
.activity-empty { padding: 2.5rem 1.5rem; text-align: center; }
.activity-empty svg { width: 38px; height: 38px; color: #e0e7ff; margin: 0 auto 0.625rem; }
.activity-empty p   { font-size: 0.82rem; color: #9ca3af; font-weight: 600; }
.activity-empty a   { display:inline-block; margin-top:0.625rem; font-size:0.78rem; font-weight:800; color:#4f46e5; text-decoration:none; }

/* ── Group headers ────────────────────────────────────────── */
.group-header {
    display: flex; align-items: center; gap: 0.45rem;
    margin: 1.125rem 0 0.5rem; padding-inline-start: 0.2rem;
}
.group-header span.label { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.07em; color: #6b7280; }
.group-header span.count { background: #f3f4f6; color: #6b7280; font-size: 0.62rem; font-weight: 800; padding: 0.1rem 0.42rem; border-radius: 99px; }

/* ── Status badges ────────────────────────────────────────── */
.sbadge {
    display: inline-flex; align-items: center;
    padding: 0.18rem 0.6rem; border-radius: 99px;
    font-size: 0.68rem; font-weight: 800; letter-spacing: 0.02em; white-space: nowrap; flex-shrink: 0;
}
.sbadge.yellow { background: #fef9c3; color: #854d0e; }
.sbadge.green  { background: #dcfce7; color: #166534; }
.sbadge.red    { background: #fee2e2; color: #991b1b; }
.sbadge.blue   { background: #dbeafe; color: #1e40af; }
.sbadge.purple { background: #f3e8ff; color: #6b21a8; }
.sbadge.orange { background: #ffedd5; color: #9a3412; }
.sbadge.gray   { background: #f3f4f6; color: #4b5563; }

/* ── Job cards ────────────────────────────────────────────── */
.job-card {
    background: white; border: 1px solid #ede9fe;
    border-radius: 0.875rem; padding: 1rem 1.125rem;
    margin-bottom: 0.5rem; display: block; text-decoration: none;
    transition: all 0.15s ease;
}
.job-card:hover { border-color: #c7d2fe; box-shadow: 0 4px 16px rgba(79,70,229,0.08); transform: translateY(-1px); }
.job-card-top  { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; }
.job-title     { font-size: 0.9rem; font-weight: 800; color: #1e1b4b; margin-bottom: 0.25rem; }
.job-chips     { display: flex; align-items: center; flex-wrap: wrap; gap: 0.45rem; margin-top: 0.5rem; }
.job-chip      { display: inline-flex; align-items: center; gap: 0.28rem; font-size: 0.71rem; font-weight: 600; color: #6b7280; }
.job-chip svg  { width: 11px; height: 11px; color: #9ca3af; flex-shrink: 0; }
.job-chip.cat  { background: #eef2ff; color: #4338ca; padding: 0.15rem 0.5rem; border-radius: 0.35rem; font-weight: 700; }
.job-chip.budget { color: #059669; font-weight: 800; }

/* ── Quote cards ──────────────────────────────────────────── */
.quote-card {
    background: white; border: 1px solid #ede9fe;
    border-radius: 0.875rem; padding: 1rem 1.125rem;
    margin-bottom: 0.5rem; transition: all 0.15s;
}
.quote-card:hover { border-color: #c7d2fe; box-shadow: 0 3px 14px rgba(79,70,229,0.07); }
.quote-card-top  { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; }
.quote-job-link  { font-size: 0.78rem; font-weight: 800; color: #4f46e5; text-decoration: none; }
.quote-job-link:hover { text-decoration: underline; }
.quote-craftsman { font-size: 0.9rem; font-weight: 800; color: #1e1b4b; display: flex; align-items: center; gap: 0.3rem; margin: 0.2rem 0; }
.quote-price     { font-size: 1.05rem; font-weight: 900; color: #059669; background: #f0fdf4; padding: 0.15rem 0.5rem; border-radius: 0.4rem; }
.quote-msg {
    margin-top: 0.55rem; font-size: 0.78rem; color: #6b7280; font-style: italic;
    background: #f9f8ff; border-inline-start: 3px solid #c7d2fe;
    padding: 0.35rem 0.55rem; border-radius: 0 0.35rem 0.35rem 0;
}
.quote-card-actions {
    padding-top: 0.65rem; margin-top: 0.65rem;
    border-top: 1px solid #f3f0ff;
    display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;
}

/* ── Booking cards ────────────────────────────────────────── */
.booking-card {
    background: white; border: 1px solid #ede9fe;
    border-radius: 0.875rem; margin-bottom: 0.5rem; overflow: hidden; transition: all 0.15s;
}
.booking-card:hover { border-color: #c7d2fe; box-shadow: 0 3px 14px rgba(79,70,229,0.07); }
.booking-card-body  { padding: 1rem 1.125rem; }
.booking-card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.4rem; }
.booking-name { font-size: 0.9rem; font-weight: 800; color: #1e1b4b; display: flex; align-items: center; gap: 0.3rem; }
.booking-desc { font-size: 0.79rem; color: #6b7280; font-weight: 600; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.booking-chips { display: flex; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.55rem; }
.bchip     { display: inline-flex; align-items: center; gap: 0.28rem; font-size: 0.71rem; font-weight: 600; color: #6b7280; }
.bchip svg { width: 11px; height: 11px; color: #9ca3af; flex-shrink: 0; }
.bchip.price { color: #059669; font-weight: 800; }
.booking-card-actions {
    padding: 0.65rem 1.125rem; background: #f9f8ff;
    border-top: 1px solid #f3f0ff;
    display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;
    border-radius: 0 0 0.875rem 0.875rem;
}
.booking-footer-banner {
    padding: 0.55rem 1.125rem; display: flex; align-items: center;
    gap: 0.45rem; font-size: 0.775rem; font-weight: 700;
    border-radius: 0 0 0.875rem 0.875rem;
}
.booking-footer-banner svg { width: 14px; height: 14px; flex-shrink: 0; }
.booking-footer-banner.orange { background: #fff7ed; border-top: 1px solid #fed7aa; color: #9a3412; }
.booking-footer-banner.blue   { background: #eff6ff; border-top: 1px solid #bfdbfe; color: #1e40af; }
.booking-footer-banner.green  { background: #f0fdf4; border-top: 1px solid #bbf7d0; color: #166534; }

/* ── Saved (favorites) cards ──────────────────────────────── */
.saved-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; }
.saved-card {
    background: white; border: 1px solid #ede9fe;
    border-radius: 0.875rem; padding: 1rem;
    transition: all 0.15s; position: relative; overflow: hidden;
}
.saved-card::after { display: none; }
.saved-card:hover { border-color: #fbcfe8; box-shadow: 0 4px 16px rgba(236,72,153,0.08); transform: translateY(-1px); }
.saved-top    { display: flex; align-items: flex-start; gap: 0.7rem; margin-bottom: 0.75rem; }
.saved-avatar { width: 40px; height: 40px; border-radius: 0.55rem; object-fit: cover; border: 2px solid #fce7f3; }
.saved-name   { font-size: 0.85rem; font-weight: 800; color: #1e1b4b; line-height: 1.2; }
.saved-cat    { font-size: 0.68rem; font-weight: 700; color: #6366f1; margin-top: 0.15rem; }
.saved-wilaya { font-size: 0.68rem; color: #9ca3af; font-weight: 600; margin-top: 0.1rem; }
.saved-bottom { display: flex; align-items: center; justify-content: space-between; padding-top: 0.65rem; border-top: 1px solid #fdf2f8; }
.saved-rate   { font-size: 0.8rem; font-weight: 900; color: #1e1b4b; }
.saved-rate span { font-size: 0.68rem; font-weight: 600; color: #9ca3af; }
.saved-actions { display: flex; align-items: center; gap: 0.35rem; }

/* ── Buttons ──────────────────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.42rem 0.825rem; border-radius: 0.5rem;
    font-size: 0.775rem; font-weight: 800; border: none; cursor: pointer;
    transition: all 0.15s; text-decoration: none; white-space: nowrap;
}
.btn svg { width: 12px; height: 12px; }
.btn-green  { background: #dcfce7; color: #166534; }
.btn-green:hover  { background: #16a34a; color: white; }
.btn-red    { background: #fee2e2; color: #991b1b; }
.btn-red:hover    { background: #dc2626; color: white; }
.btn-indigo { background: #eef2ff; color: #4338ca; }
.btn-indigo:hover { background: #4f46e5; color: white; }
.btn-yellow { background: #fef9c3; color: #854d0e; }
.btn-yellow:hover { background: #f59e0b; color: white; }
.btn-gray   { background: #f3f4f6; color: #374151; }
.btn-gray:hover   { background: #e5e7eb; }
.btn-pink   { background: #fce7f3; color: #db2777; }
.btn-pink:hover   { background: #ec4899; color: white; }

/* ── Empty states ─────────────────────────────────────────── */
.empty-state {
    background: white; border: 2px dashed #e0e7ff;
    border-radius: 1rem; padding: 2.75rem 1.5rem; text-align: center;
}
.empty-state svg { width: 42px; height: 42px; color: #c7d2fe; margin: 0 auto 0.75rem; }
.empty-state h3  { font-size: 0.9rem; font-weight: 800; color: #374151; margin-bottom: 0.3rem; }
.empty-state p   { font-size: 0.78rem; color: #9ca3af; font-weight: 600; margin-bottom: 0.875rem; }

/* ── Right Metrics Panel ──────────────────────────────────── */
.dash-metrics {
    background: white; border-inline-start: 1px solid #ede9fe;
    padding: 1.5rem 1rem;
    position: sticky; top: 64px;
    height: calc(100vh - 64px);
    overflow-y: auto;
    display: flex; flex-direction: column; gap: 0.55rem;
}
.metrics-heading {
    font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: 0.09em; color: #9ca3af;
    margin-bottom: 0.2rem; padding-inline-start: 0.2rem;
}
.metric-card {
    border-radius: 0.875rem; padding: 0.875rem 1rem;
    position: relative; overflow: hidden;
}
.metric-card::after {
    content: ''; position: absolute; top: -18px; right: -18px;
    width: 64px; height: 64px; border-radius: 50%; opacity: 0.12;
}
html[dir="rtl"] .metric-card::after { right: auto; left: -18px; }
.metric-card.green  { background: #f0fdf4; border: 1px solid #bbf7d0; }
.metric-card.green::after  { background: #22c55e; }
.metric-card.indigo { background: #eef2ff; border: 1px solid #c7d2fe; }
.metric-card.indigo::after { background: #6366f1; }
.metric-card.amber  { background: #fffbeb; border: 1px solid #fde68a; }
.metric-card.amber::after  { background: #f59e0b; }
.metric-card.pink   { background: #fdf2f8; border: 1px solid #fbcfe8; }
.metric-card.pink::after   { background: #ec4899; }
.metric-card.blue   { background: #eff6ff; border: 1px solid #bfdbfe; }
.metric-card.blue::after   { background: #3b82f6; }

.metric-badge {
    display: inline-flex; font-size: 0.58rem; font-weight: 900;
    letter-spacing: 0.08em; text-transform: uppercase;
    padding: 0.12rem 0.45rem; border-radius: 99px; margin-bottom: 0.55rem;
}
.metric-badge.green  { background: #dcfce7; color: #166534; }
.metric-badge.indigo { background: #e0e7ff; color: #3730a3; }
.metric-badge.amber  { background: #fef9c3; color: #854d0e; }
.metric-badge.pink   { background: #fce7f3; color: #9d174d; }
.metric-badge.blue   { background: #dbeafe; color: #1e40af; }

.metric-icon {
    width: 28px; height: 28px; border-radius: 0.45rem;
    display: flex; align-items: center; justify-content: center; margin-bottom: 0.4rem;
}
.metric-icon svg { width: 14px; height: 14px; }
.metric-icon.green  { background: #dcfce7; color: #16a34a; }
.metric-icon.indigo { background: #e0e7ff; color: #4f46e5; }
.metric-icon.amber  { background: #fef9c3; color: #d97706; }
.metric-icon.pink   { background: #fce7f3; color: #db2777; }
.metric-icon.blue   { background: #dbeafe; color: #2563eb; }

.metric-label { font-size: 0.7rem; font-weight: 700; color: #6b7280; margin-bottom: 0.15rem; }
.metric-value { font-size: 1.35rem; font-weight: 900; letter-spacing: -0.03em; line-height: 1; }
.metric-value.green  { color: #15803d; }
.metric-value.indigo { color: #3730a3; }
.metric-value.amber  { color: #b45309; }
.metric-value.pink   { color: #9d174d; }
.metric-value.blue   { color: #1d4ed8; }
.metric-sub { font-size: 0.68rem; font-weight: 600; margin-top: 0.15rem; }
.metric-sub.green  { color: #4ade80; }
.metric-sub.indigo { color: #818cf8; }
.metric-sub.amber  { color: #fbbf24; }
.metric-sub.pink   { color: #f9a8d4; }
.metric-sub.blue   { color: #93c5fd; }

/* ── Responsive ───────────────────────────────────────────── */
@media (max-width: 1200px) { .dash-shell { grid-template-columns: 220px 1fr 240px; } }
@media (max-width: 1024px) {
    .dash-shell { grid-template-columns: 200px 1fr; }
    .dash-metrics { display: none; }
}
@media (max-width: 768px) {
    .dash-shell { grid-template-columns: 1fr; }
    .dash-sidebar { display: none; }
    .dash-main { padding: 1.25rem 1rem 5rem; }
    .saved-grid { grid-template-columns: 1fr; }
}

/* ── Mobile FAB ───────────────────────────────────────────── */
.mob-dash-fab {
    position: fixed !important;
    bottom: 1.5rem !important; inset-inline-end: 1.5rem !important;
    z-index: 9999 !important;
    width: 52px !important; height: 52px !important;
    background: #4f46e5 !important;
    border-radius: 50% !important;
    border: none !important; cursor: pointer !important;
    box-shadow: 0 4px 24px rgba(79,70,229,0.5) !important;
    display: none;
    align-items: center; justify-content: center;
    transition: transform 0.2s, box-shadow 0.2s;
}
.mob-dash-fab:hover { transform: scale(1.08); }
.mob-dash-fab svg { width: 22px; height: 22px; color: white; }
@media (max-width: 768px) { .mob-dash-fab { display: flex !important; } }

/* ── Mobile drawer overlay ────────────────────────────────── */
.mob-dash-overlay {
    display: none; position: fixed; inset: 0; z-index: 10000;
    background: rgba(0,0,0,0.45); opacity: 0; pointer-events: none;
    transition: opacity 0.25s ease;
}
.mob-dash-overlay.open { opacity: 1; pointer-events: auto; }
@media (max-width: 768px) { .mob-dash-overlay { display: block; } }

/* ── Mobile drawer panel ──────────────────────────────────── */
.mob-dash-drawer {
    position: fixed; bottom: 0; left: 0; right: 0; z-index: 10001;
    background: white; border-radius: 1.25rem 1.25rem 0 0;
    padding: 0 0.75rem 2rem;
    transform: translateY(100%);
    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
    max-height: 80vh; overflow-y: auto;
    box-shadow: 0 -4px 30px rgba(0,0,0,0.12);
}
.mob-dash-drawer.open { transform: translateY(0); }
.mob-dash-handle {
    width: 36px; height: 4px; background: #e0e7ff;
    border-radius: 99px; margin: 0.75rem auto 1rem;
}
.mob-dash-drawer-title {
    font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: 0.08em; color: #9ca3af; padding: 0 0.5rem 0.5rem;
}
.mob-dash-nav-item {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.75rem 0.875rem; border-radius: 0.65rem;
    font-size: 0.9rem; font-weight: 600; color: #374151;
    width: 100%; text-align: start; border-inline-start: 3px solid transparent;
    transition: all 0.15s;
}
.mob-dash-nav-item:hover  { background: #f5f3ff; color: #4f46e5; }
.mob-dash-nav-item.active { background: #eef2ff; color: #4f46e5; border-inline-start-color: #4f46e5; font-weight: 700; }
.mob-dash-nav-item svg    { width: 18px; height: 18px; flex-shrink: 0; }
.mob-dash-badge {
    margin-inline-start: auto; font-size: 0.62rem; font-weight: 900;
    padding: 0.1rem 0.45rem; border-radius: 99px; color: white; line-height: 1.5;
}
.mob-dash-badge.indigo { background: #4f46e5; }
.mob-dash-badge.red    { background: #ef4444; }
.mob-dash-badge.amber  { background: #f59e0b; }
.mob-dash-badge.pink   { background: #ec4899; }
.mob-dash-divider { height: 1px; background: #f3f0ff; margin: 0.5rem 0; }
.mob-dash-quick {
    display: flex; align-items: center; gap: 0.6rem;
    padding: 0.65rem 0.875rem; border-radius: 0.65rem;
    font-size: 0.875rem; font-weight: 700; text-decoration: none; transition: all 0.15s;
}
.mob-dash-quick svg { width: 16px; height: 16px; flex-shrink: 0; }
.mob-dash-quick.primary   { background: #4f46e5; color: white; }
.mob-dash-quick.primary:hover { background: #4338ca; }
.mob-dash-quick.secondary { background: #f5f3ff; color: #4f46e5; }
.mob-dash-quick.secondary:hover { background: #ede9fe; }

/* ── Filter Dropdown ──────────────────────────────────────── */
.dash-tab-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.25rem;
}
.dash-tab-header h2 {
    font-size: 1.15rem; font-weight: 800; color: #1f2937; letter-spacing: -0.01em;
}
.filter-dropdown { position: relative; display: inline-block; }
.filter-btn {
    display: flex; align-items: center; gap: 0.45rem;
    padding: 0.45rem 0.85rem; background: white; border: 1px solid #e5e7eb;
    border-radius: 99px; font-size: 0.78rem; font-weight: 700; color: #4b5563;
    cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.15s;
}
.filter-btn:hover { background: #f9fafb; border-color: #d1d5db; color: #111827; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
.filter-btn svg { width: 14px; height: 14px; color: #9ca3af; }
.filter-menu {
    position: absolute; top: calc(100% + 0.5rem); inset-inline-end: 0;
    width: 230px; background: white; border: 1px solid #e5e7eb;
    border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
    z-index: 50; padding: 0.5rem; display: none;
    transform-origin: top right;
}
.filter-menu.open { display: block; animation: filterFadeIn 0.15s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
@keyframes filterFadeIn { from { opacity: 0; transform: scale(0.95) translateY(-5px); } to { opacity: 1; transform: scale(1) translateY(0); } }
.filter-opt {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.5rem 0.75rem; border-radius: 0.5rem;
    font-size: 0.78rem; font-weight: 600; color: #4b5563;
    cursor: pointer; transition: background 0.15s;
    background: none; border: none; width: 100%; text-align: left;
}
.filter-opt:hover { background: #f3f4f6; color: #111827; }
.filter-opt-left { display: flex; align-items: center; gap: 0.55rem; }
.filter-opt-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.filter-opt-count { background: #f3f4f6; color: #6b7280; font-size: 0.65rem; font-weight: 800; padding: 0.1rem 0.4rem; border-radius: 99px; }

/* ── DARK MODE OVERRIDES ──────────────────────────────────── */
html.dark .dash-shell { background: #111827; }
html.dark .dash-sidebar, html.dark .dash-metrics { background: #1f2937; border-color: #374151; }
html.dark .dash-nav-item { color: #9ca3af; }
html.dark .dash-nav-item:hover, html.dark .dash-nav-item.active { background: #374151; color: #a5b4fc; }
html.dark .dash-nav-item.active { border-inline-start-color: #818cf8; color: #818cf8; }
html.dark .dash-sidebar-bottom { border-color: #374151; }
html.dark .dash-sidebar-greeting { border-color: #374151; }
html.dark .dash-sidebar-greeting h2 { color: #f3f4f6; }
html.dark .dash-quick-btn.primary { background: #4f46e5; color: white; }
html.dark .dash-quick-btn.secondary { background: #374151; border-color: #4b5563; color: #d1d5db; }
html.dark .dash-quick-btn.secondary:hover { background: #4b5563; }
html.dark .dash-welcome h1 { color: #f3f4f6; }
html.dark .activity-list { background: #1f2937; border-color: #374151; }
html.dark .activity-item { border-color: #374151; color: #d1d5db; }
html.dark .activity-item:hover { background: #374151; }
html.dark .activity-text p { color: #f3f4f6; }
html.dark .job-card, html.dark .quote-card, html.dark .booking-card, html.dark .review-card { background: #1f2937; border-color: #374151; }
html.dark .saved-card { background: #1f2937; border-color: #374151; }
html.dark .saved-card::after { background: radial-gradient(circle, rgba(129,140,248,0.15) 0%, transparent 70%); }
html.dark .saved-avatar { border-color: #4b5563; }
html.dark .saved-cat { color: #a5b4fc; }
html.dark .saved-wilaya { color: #9ca3af; }
html.dark .job-card:hover, html.dark .quote-card:hover, html.dark .booking-card:hover, html.dark .saved-card:hover, html.dark .review-card:hover { border-color: #818cf8; }
html.dark .job-title, html.dark .quote-craftsman, html.dark .quote-title, html.dark .booking-name, html.dark .saved-name, html.dark .review-name, html.dark .dash-tab-header h2 { color: #f3f4f6; }
html.dark .quote-card-actions, html.dark .booking-card-actions { background: #111827; border-color: #374151; }
html.dark .saved-bottom { background: transparent; border-color: #374151; }
html.dark .quote-msg { background: #374151; border-inline-start-color: #4f46e5; color: #d1d5db; }
html.dark .quote-price, html.dark .bchip.price { color: #34d399; }
html.dark .quote-date, html.dark .booking-desc, html.dark .bchip, html.dark .review-date { color: #9ca3af; }
html.dark .review-comment { color: #d1d5db; }
html.dark .saved-rate { color: #f3f4f6; }
html.dark .saved-rate span { color: #9ca3af; }
html.dark .group-header span.label { color: #9ca3af; }
html.dark .group-header span.count, html.dark .filter-opt-count { background: #374151; color: #d1d5db; }
html.dark .filter-menu { background: #1f2937; border-color: #374151; }
html.dark .filter-opt { color: #d1d5db; }
html.dark .filter-opt:hover { background: #374151; color: #f3f4f6; }
html.dark .filter-btn { background: #1f2937; border-color: #374151; color: #d1d5db; }
html.dark .filter-btn:hover { background: #374151; color: #f3f4f6; }
html.dark .empty-state { background: #1f2937; border-color: #374151; }
html.dark .empty-state h3 { color: #f3f4f6; }
html.dark .job-chip.cat { background: #374151; color: #a5b4fc; }

html.dark .metric-card { background: #1f2937; border-color: #374151; }
html.dark .metric-card.green { background: rgba(21,128,61,0.2); border-color: #16a34a; }
html.dark .metric-card.indigo { background: rgba(79,70,229,0.2); border-color: #6366f1; }
html.dark .metric-card.amber { background: rgba(217,119,6,0.2); border-color: #d97706; }
html.dark .metric-card.purple { background: rgba(147,51,234,0.2); border-color: #9333ea; }
html.dark .metric-card.blue { background: rgba(59,130,246,0.2); border-color: #3b82f6; }
html.dark .metric-card.pink { background: rgba(236,72,153,0.2); border-color: #ec4899; }
html.dark .metric-label { color: #d1d5db; }
html.dark .metrics-heading { color: #9ca3af; }
html.dark .metric-value.indigo { color: #a5b4fc; }
html.dark .metric-value.green { color: #4ade80; }
html.dark .metric-value.amber { color: #fcd34d; }
html.dark .metric-value.purple { color: #d8b4fe; }
html.dark .metric-value.blue { color: #93c5fd; }
html.dark .metric-value.pink { color: #f9a8d4; }
html.dark .metric-badge.green { background: #15803d; color: #dcfce7; }
html.dark .metric-badge.indigo { background: #4338ca; color: #e0e7ff; }
html.dark .metric-badge.amber { background: #b45309; color: #fef3c7; }
html.dark .metric-badge.purple { background: #7e22ce; color: #f3e8ff; }

html.dark .mob-dash-drawer { background: #1f2937; border-top: 1px solid #374151; }
html.dark .mob-dash-nav-item { color: #d1d5db; }
html.dark .mob-dash-nav-item:hover, html.dark .mob-dash-nav-item.active { background: #374151; color: #a5b4fc; }
html.dark .mob-dash-nav-item.active { border-color: #818cf8; color: #818cf8; font-weight: 700; }
html.dark .mob-dash-handle { background: #4b5563; }
html.dark .mob-dash-divider { background: #374151; }
html.dark .mob-dash-quick.secondary { background: #374151; color: #a5b4fc; }
html.dark .mob-dash-quick.secondary:hover { background: #4b5563; }

html.dark .attention-banner { background: #1f2937; border-color: #374151; }
html.dark .attention-banner h3 { color: #f3f4f6; }
html.dark .attention-banner a { background: #374151; border-color: #4b5563; color: #a5b4fc; }
html.dark .attention-banner.orange { background: #451a03; border-color: #78350f; }
html.dark .attention-banner.orange h3 { color: #fcd34d; }
html.dark .attention-banner.orange p { color: #fde68a; }
html.dark .attention-banner.orange a { background: #78350f; border-color: #92400e; color: #fef3c7; }

html.dark .all-good-banner { background: #064e3b; border-color: #065f46; color: #6ee7b7; }
html.dark .all-good-banner p { color: #6ee7b7; }

html.dark .booking-footer-banner.orange { background: #451a03; border-top-color: #78350f; color: #fde68a; }
html.dark .booking-footer-banner.purple { background: #2e1065; border-top-color: #4c1d95; color: #d8b4fe; }
html.dark .booking-footer-banner.green { background: #064e3b; border-top-color: #065f46; color: #86efac; }

html.dark .sbadge.yellow { background: #a16207; color: #fef9c3; }
html.dark .sbadge.green { background: #15803d; color: #dcfce7; }
html.dark .sbadge.red { background: #b91c1c; color: #fee2e2; }
html.dark .sbadge.blue { background: #1d4ed8; color: #dbeafe; }
html.dark .sbadge.purple { background: #7e22ce; color: #f3e8ff; }
html.dark .sbadge.orange { background: #c2410c; color: #ffedd5; }
html.dark .sbadge.gray { background: #4b5563; color: #e5e7eb; }

html.dark .btn-green { background: #047857; color: white; }
html.dark .btn-green:hover { background: #059669; }
html.dark .btn-red { background: #b91c1c; color: white; }
html.dark .btn-red:hover { background: #dc2626; }
html.dark .btn-orange { background: #b45309; color: white; }
html.dark .btn-orange:hover { background: #d97706; }
html.dark .btn-blue { background: #1d4ed8; color: white; }
html.dark .btn-blue:hover { background: #2563eb; }
html.dark .btn-indigo { background: #4338ca; color: white; }
html.dark .btn-indigo:hover { background: #4f46e5; }
html.dark .btn-yellow { background: #b45309; color: white; }
html.dark .btn-yellow:hover { background: #d97706; }
html.dark .btn-gray { background: #6b7280; color: white; }
html.dark .btn-gray:hover { background: #9ca3af; }
html.dark .btn-pink { background: #9d174d; color: white; }
html.dark .btn-pink:hover { background: #db2777; }

/* Saved card meta text */
.saved-meta { display: inline-flex; align-items: center; gap: 0.2rem; font-size: 0.68rem; font-weight: 600; color: #9ca3af; }
html.dark .saved-meta { color: #9ca3af; }

/* Sidebar nav badges dark mode */
html.dark .dash-nav-badge.red { background: #ef4444; }
html.dark .dash-nav-badge.amber { background: #f59e0b; }
html.dark .dash-nav-badge.pink { background: #ec4899; }

/* Modal icon circle backgrounds */
html.dark #confirm-modal .bg-green-100 { background: #065f46 !important; }
html.dark #confirm-modal .bg-red-100 { background: #991b1b !important; }

/* Activity dot icon backgrounds */
html.dark .act-dot.blue { background: #1e3a8a; color: #60a5fa; }
html.dark .act-dot.green { background: #064e3b; color: #34d399; }
html.dark .act-dot.amber { background: #78350f; color: #fbbf24; }
html.dark .act-dot.indigo { background: #312e81; color: #818cf8; }
html.dark .act-dot.orange { background: #7c2d12; color: #fb923c; }
html.dark .act-dot.purple { background: #4c1d95; color: #c084fc; }

/* Metric icon backgrounds */
html.dark .metric-icon.green { background: #064e3b; color: #34d399; }
html.dark .metric-icon.indigo { background: #312e81; color: #818cf8; }
html.dark .metric-icon.amber { background: #78350f; color: #fbbf24; }
html.dark .metric-icon.pink { background: #831843; color: #f9a8d4; }
html.dark .metric-icon.blue { background: #1e3a8a; color: #60a5fa; }

/* Metric sub text */
html.dark .metric-sub.green { color: #6ee7b7; }
html.dark .metric-sub.indigo { color: #a5b4fc; }
html.dark .metric-sub.amber { color: #fde68a; }
html.dark .metric-sub.pink { color: #f9a8d4; }
html.dark .metric-sub.blue { color: #93c5fd; }

/* Metric badge extra */
html.dark .metric-badge.pink { background: #831843; color: #f9a8d4; }
html.dark .metric-badge.blue { background: #1e3a8a; color: #93c5fd; }

/* Section label */
html.dark .section-label { color: #9ca3af; }

/* Quote price bg */
html.dark .quote-price { background: #064e3b; color: #34d399; }

/* Booking footer banner blue (job in progress) */
html.dark .booking-footer-banner.blue { background: #1e3a8a; border-top-color: #1d4ed8; color: #93c5fd; }

/* Attention banner yellow */
html.dark .attention-banner.yellow { background: #451a03; border-color: #78350f; }
html.dark .attention-banner.yellow h3 { color: #fde68a; }
html.dark .attention-banner.yellow p { color: #fcd34d; }
html.dark .attention-banner.yellow .attention-icon { background: #b45309; }
html.dark .attention-banner.yellow a { background: #78350f; border-color: #92400e; color: #fef3c7; }

/* Attention banner purple */
html.dark .attention-banner.purple { background: #2e1065; border-color: #4c1d95; }
html.dark .attention-banner.purple h3 { color: #d8b4fe; }
html.dark .attention-banner.purple p { color: #c084fc; }
html.dark .attention-banner.purple .attention-icon { background: #7e22ce; }
html.dark .attention-banner.purple a { background: #4c1d95; border-color: #6b21a8; color: #e9d5ff; }

/* Inline counter-offer text in booking cards */
html.dark .booking-card-actions p { color: #d1d5db !important; }

/* Modals */
html.dark #confirm-modal .bg-white { background: #1f2937 !important; }
html.dark #confirm-modal .text-gray-900,
html.dark #confirm-modal .text-base { color: #f3f4f6 !important; }
html.dark #confirm-modal .text-gray-500 { color: #d1d5db !important; }
html.dark #confirm-modal .bg-green-100 { background: #15803d !important; }
html.dark #confirm-modal .bg-red-100 { background: #b91c1c !important; }
html.dark #confirm-modal .text-green-600 { color: #ffffff !important; }
html.dark #confirm-modal .text-red-600 { color: #ffffff !important; }

/* Flash messages */
html.dark .flash.success { background: #064e3b; border-color: #065f46; color: #6ee7b7; }
html.dark .flash.error { background: #7f1d1d; border-color: #991b1b; color: #fca5a5; }

</style>


<div class="dash-shell">

    <!-- ════════════════════════════════
         LEFT SIDEBAR
    ════════════════════════════════ -->
    <aside class="dash-sidebar">
        <nav class="dash-nav">

            <button onclick="switchTab('overview')" data-tab="overview" class="dash-nav-item active">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <?= __('dashboard.shared.overview') ?>
            </button>

            <button onclick="switchTab('jobs')" data-tab="jobs" class="dash-nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <?= __('dashboard.homeowner.my_jobs') ?>
                <?php $openJobsCount = count(array_filter($jobs??[], fn($j)=>$j['status']==='open')); ?>
                <span id="tab-badge-open-jobs" class="dash-nav-badge indigo" style="<?= $openJobsCount > 0 ? '' : 'display:none;' ?>"><?= $openJobsCount ?></span>
            </button>

            <button onclick="switchTab('quotes')" data-tab="quotes" class="dash-nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <?= __('dashboard.homeowner.incoming_quotes') ?>
                <span id="tab-badge-pending-quotes" class="dash-nav-badge red" style="<?= $pendingQuotesCount > 0 ? '' : 'display:none;' ?>"><?= $pendingQuotesCount ?></span>
            </button>

            <button onclick="switchTab('bookings')" data-tab="bookings" class="dash-nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <?= __('dashboard.shared.bookings') ?>
                <?php $activeBookingsCount = count(array_filter($bookings??[], fn($b)=>in_array($b['status'], ['requested', 'in_progress', 'counter_offered']))); ?>
                <span id="tab-badge-active-bookings" class="dash-nav-badge blue" style="<?= $activeBookingsCount > 0 ? '' : 'display:none;' ?>"><?= $activeBookingsCount ?></span>
            </button>

            <button onclick="switchTab('favorites')" data-tab="favorites" class="dash-nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <?= __('dashboard.homeowner.saved_craftsmen') ?>
                <?php $favsCount = count($favorites??[]); ?>
                <span id="tab-badge-saved" class="dash-nav-badge pink" style="<?= $favsCount > 0 ? '' : 'display:none;' ?>"><?= $favsCount ?></span>
            </button>

        </nav>

        <div class="dash-sidebar-bottom">
            <a href="<?= APP_URL ?>/jobs/create" class="dash-quick-btn primary">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <?= __('dashboard.homeowner.post_new_job') ?>
            </a>
            <a href="<?= APP_URL ?>/search" class="dash-quick-btn secondary">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <?= __('dashboard.homeowner.find_craftsmen') ?>
            </a>
        </div>
    </aside>


    <!-- ════════════════════════════════
         MAIN CONTENT
    ════════════════════════════════ -->
    <main class="dash-main">

        <div class="dash-welcome">
            <h1><?= __('dashboard.homeowner.welcome') ?>, <?= htmlspecialchars($_SESSION['name'] ?? 'Homeowner') ?>!</h1>
            <p><?= __('dashboard.homeowner.welcome_desc') ?></p>
        </div>

        <!-- Flash messages -->
        <?php if (isset($_GET['success'])): ?>
        <div class="flash success">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?php switch($_GET['success']) {
                case 'job_posted':        echo __('dashboard.homeowner.job_posted_success'); break;
                case 'counter_accepted':  echo __('dashboard.homeowner.counter_accepted_success'); break;
                case 'counter_cancelled': echo __('dashboard.shared.cancelled'); break;
                case 'job_completed':     echo __('dashboard.homeowner.job_completed_success'); break;
                case 'booking_requested': echo __('dashboard.shared.requested'); break;
                case 'quote_accepted':    echo __('dashboard.homeowner.quote_accepted_success'); break;
                case 'quote_rejected':    echo __('dashboard.homeowner.quote_rejected_success'); break;
                default: echo __('dashboard.shared.completed');
            } ?>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        <div class="flash error">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?php switch($_GET['error']) {
                case 'accept_failed': echo 'Failed to accept the quote. Please try again.'; break;
                case 'reject_failed': echo 'Failed to decline the quote. Please try again.'; break;
                default: echo 'An error occurred. Please try again.';
            } ?>
        </div>
        <?php endif; ?>


        <!-- ── OVERVIEW ─────────────────────────────── -->
        <div id="tab-overview" class="dash-tab active">

            <?php
            $activeBookingsCount = count(array_filter($bookings, fn($b) => in_array($b['status'], ['requested','in_progress','counter_offered'])));

            $attentionItems = [];
            if ($pendingQuotesCount > 0)
                $attentionItems[] = ['type' => 'quotes', 'count' => $pendingQuotesCount];
            foreach ($bookings as $b) {
                if ($b['status'] === 'pending_completion')
                    $attentionItems[] = ['type' => 'pending_completion', 'data' => $b];
                if ($b['status'] === 'counter_offered')
                    $attentionItems[] = ['type' => 'counter_offered', 'data' => $b];
            }
            ?>

            <?php if (!empty($attentionItems)): ?>
                <?php foreach ($attentionItems as $item): ?>
                    <?php if ($item['type'] === 'quotes'): ?>
                    <div class="attention-banner yellow">
                        <div class="attention-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div>
                            <h3><?= $item['count'] ?> <?= __('dashboard.homeowner.incoming_quotes') ?></h3>
                            <p><?= __('dashboard.homeowner.new_quote_rcv_desc') ?></p>
                            <a href="#" onclick="switchTab('quotes');return false;">
                                <?= __('dashboard.homeowner.review_quote') ?>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    <?php elseif ($item['type'] === 'pending_completion'): $b = $item['data']; ?>
                    <div class="attention-banner purple">
                        <div class="attention-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3><?= __('dashboard.homeowner.job_status_update') ?></h3>
                            <p><?= htmlspecialchars($b['first_name'] . ' ' . $b['last_name']) ?> <?= __('dashboard.homeowner.job_status_desc') ?></p>
                            <a href="#" onclick="switchTab('bookings');return false;">
                                <?= __('dashboard.homeowner.confirm_completion') ?>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    <?php elseif ($item['type'] === 'counter_offered'): $b = $item['data']; ?>
                    <div class="attention-banner">
                        <div class="attention-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3><?= __('dashboard.homeowner.counter_rcv') ?></h3>
                            <p><?= htmlspecialchars($b['first_name'] . ' ' . $b['last_name']) ?> <?= __('dashboard.homeowner.counter_rcv_desc') ?></p>
                            <a href="#" onclick="switchTab('bookings');return false;">
                                <?= __('dashboard.homeowner.review_quote') ?>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="all-good-banner">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p><?= __('dashboard.craftsman.all_caught_up') ?></p>
                </div>
            <?php endif; ?>

            <!-- Recent Activity -->
            <p class="section-label" style="margin-top:1.25rem"><?= __('dashboard.shared.recent_activity') ?></p>
            <div class="activity-list">
                <?php
                $acts = [];
                foreach (array_slice($bookings, 0, 3) as $b) {
                    $name = htmlspecialchars($b['first_name'] ?? 'Craftsman');
                    $map = [
                        'requested'          => ['txt' => __('dashboard.shared.requested').' '.$name,               'dot' => 'blue',   'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'tab' => 'bookings'],
                        'in_progress'        => ['txt' => __('dashboard.craftsman.job_in_progress').$name,           'dot' => 'indigo', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'tab' => 'bookings'],
                        'counter_offered'    => ['txt' => $name.' '.__('dashboard.homeowner.counter_rcv'),           'dot' => 'amber',  'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'tab' => 'bookings'],
                        'pending_completion' => ['txt' => $name.' '.__('dashboard.homeowner.job_status_update'),         'dot' => 'purple', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'tab' => 'bookings'],
                        'completed'          => ['txt' => __('dashboard.craftsman.job_completed').$name,             'dot' => 'green',  'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'tab' => 'bookings'],
                        'cancelled'          => ['txt' => __('dashboard.shared.cancelled'),                     'dot' => 'orange', 'icon' => 'M6 18L18 6M6 6l12 12', 'tab' => 'bookings'],
                    ];
                    $info = $map[$b['status']] ?? ['txt'=>'Booking updated','dot'=>'indigo','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','tab'=>'bookings'];
                    $acts[] = ['info' => $info, 'time' => $b['created_at'] ?? ''];
                }
                foreach (array_slice($jobs, 0, 2) as $j) {
                    $acts[] = ['info' => ['txt' => __('dashboard.homeowner.post_job').': '.htmlspecialchars($j['title']), 'dot' => 'indigo', 'icon' => 'M12 4v16m8-8H4', 'tab' => 'jobs'], 'time' => $j['created_at'] ?? ''];
                }
                usort($acts, fn($a,$b) => strtotime($b['time']) - strtotime($a['time']));
                $acts = array_slice($acts, 0, 6);
                ?>
                <?php if (empty($acts)): ?>
                <div class="activity-empty">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p><?= __('dashboard.shared.no_activity_yet') ?></p>
                    <a href="<?= APP_URL ?>/jobs/create"><?= __('dashboard.homeowner.post_job') ?> &rarr;</a>
                </div>
                <?php else: ?>
                <?php foreach ($acts as $act): ?>
                <div class="activity-item" onclick="switchTab('<?= $act['info']['tab'] ?>')">
                    <div class="act-dot <?= $act['info']['dot'] ?>">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $act['info']['icon'] ?>"/></svg>
                    </div>
                    <div class="activity-text">
                        <p><?= $act['info']['txt'] ?></p>
                        <span><?php
                            $diff = time() - strtotime($act['time']);
                            if ($diff < 3600) echo floor($diff/60).__('dashboard.shared.ago_m').__('dashboard.shared.ago_suffix');
                            elseif ($diff < 86400) echo floor($diff/3600).__('dashboard.shared.ago_h').__('dashboard.shared.ago_suffix');
                            elseif ($diff < 604800) echo floor($diff/86400).__('dashboard.shared.ago_d').__('dashboard.shared.ago_suffix');
                            else echo (__('lang')==='ar' ? date('d/m/Y', strtotime($act['time'])) : date('M d', strtotime($act['time'])));
                        ?></span>
                    </div>
                    <svg class="activity-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div><!-- /overview -->


        <!-- ── MY JOBS ───────────────────────────────── -->
        <div id="tab-jobs" class="dash-tab">
            <?php if (!empty($jobs)): ?>
            <?php
            $jobGroups = [
                ['label' => __('dashboard.homeowner.open'),      'color' => '#22c55e', 'key' => 'open',      'items' => array_filter($jobs, fn($j)=>$j['status']==='open')],
                ['label' => __('dashboard.homeowner.assigned'),  'color' => '#f59e0b', 'key' => 'assigned',  'items' => array_filter($jobs, fn($j)=>$j['status']==='assigned')],
                ['label' => __('dashboard.shared.completed'), 'color' => '#6366f1', 'key' => 'completed', 'items' => array_filter($jobs, fn($j)=>$j['status']==='completed')],
                ['label' => __('dashboard.shared.cancelled'), 'color' => '#9ca3af', 'key' => 'cancelled', 'items' => array_filter($jobs, fn($j)=>$j['status']==='cancelled')],
            ];
            $badgeMapJ = ['open'=>'green','assigned'=>'amber','completed'=>'blue','cancelled'=>'gray'];
            ?>
            
            <div class="dash-tab-header">
                <h2><?= __('dashboard.homeowner.my_jobs') ?></h2>
                <div class="filter-dropdown">
                    <button class="filter-btn" type="button" onclick="toggleFilter('filter-jobs')">
                        <div class="filter-opt-dot" style="display:none"></div>
                        <span class="lbl"><?= __('dashboard.shared.filter_all') ?></span>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="filter-jobs" class="filter-menu">
                        <button type="button" class="filter-opt" onclick="applyDashFilter('tab-jobs', 'all', '', '<?= __('dashboard.homeowner.all_jobs') ?>')">
                            <div class="filter-opt-left">
                                <span style="font-weight:700"><?= __('dashboard.homeowner.all_jobs') ?></span>
                            </div>
                            <span class="filter-opt-count"><?= count($jobs) ?></span>
                        </button>
                        <?php foreach($jobGroups as $g): if(empty($g['items'])) continue; ?>
                        <button type="button" class="filter-opt" onclick="applyDashFilter('tab-jobs', '<?= $g['key'] ?>', '<?= $g['color'] ?>', '<?= $g['label'] ?>')">
                            <div class="filter-opt-left">
                                <div class="filter-opt-dot" style="background:<?= $g['color'] ?>"></div>
                                <span><?= $g['label'] ?></span>
                            </div>
                            <span class="filter-opt-count"><?= count($g['items']) ?></span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php foreach ($jobGroups as $grp): if (empty($grp['items'])) continue; ?>
            <div class="group-header" data-status="<?= $grp['key'] ?>">
                <svg viewBox="0 0 8 8" style="width:8px;height:8px;flex-shrink:0"><circle cx="4" cy="4" r="4" fill="<?= $grp['color'] ?>"/></svg>
                <span class="label"><?= $grp['label'] ?></span>
                <span class="count"><?= count($grp['items']) ?></span>
            </div>
            <?php foreach ($grp['items'] as $job): ?>
            <div class="job-card relative cursor-pointer group" onclick="window.location.href='<?= APP_URL ?>/jobs/<?= $job['id'] ?>'">
                <div class="job-card-top">
                    <div style="flex:1;min-width:0;padding-inline-end:1rem;">
                        <a href="<?= APP_URL ?>/jobs/<?= $job['id'] ?>" class="job-title hover:text-indigo-600 transition-colors block"><?= htmlspecialchars($job['title']) ?></a>
                        <div class="job-chips">
                            <span class="job-chip cat"><?= htmlspecialchars(__('categories.'.($job['service_category']??'')) !== 'categories.'.($job['service_category']??'') ? __('categories.'.($job['service_category']??'')) : ($job['service_category']??'')) ?></span>
                            <?php if (!empty($job['address'])): ?>
                            <?php 
                                $rawAddr = $job['address'];
                                $addrParts = explode(',', $rawAddr, 2);
                                $wilayaKey = trim($addrParts[0]);
                                $restAddr  = isset($addrParts[1]) ? '، ' . trim($addrParts[1]) : '';
                                $mappedW   = __('wilayas.'.$wilayaKey) !== 'wilayas.'.$wilayaKey ? preg_replace('/^\d{2}\s-\s/', '', __('wilayas.'.$wilayaKey)) : preg_replace('/^\d{2}\s-\s/', '', $wilayaKey);
                            ?>
                            <span class="job-chip hidden sm:inline-flex">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <?= htmlspecialchars($mappedW . $restAddr) ?>
                            </span>
                            <?php endif; ?>
                            <span class="job-chip">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <?= date('d', strtotime($job['created_at'])) ?> <?= __('months_short.'.date('M', strtotime($job['created_at']))) ?> <?= date('Y', strtotime($job['created_at'])) ?>
                            </span>
                            <?php if (!empty($job['budget_range'])): ?>
                            <span class="job-chip budget"><?= htmlspecialchars($job['budget_range']) ?> <?= __('lang')==='ar' ? 'د.ج' : 'DZD' ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="sbadge <?= $badgeMapJ[$job['status']] ?? 'gray' ?>"><?= ucfirst($job['status']) ?></span>
                        
                        <?php if ($job['status'] === 'open'): ?>
                        <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                            <!-- Edit Button -->
                            <a href="<?= APP_URL ?>/jobs/edit/<?= $job['id'] ?>?source=dashboard"
                               class="flex items-center justify-center w-7 h-7 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:scale-105 border border-amber-100 transition-all duration-200"
                               title="Edit Job">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                            
                            <!-- Delete Button -->
                            <form id="delete-job-<?= $job['id'] ?>" action="<?= APP_URL ?>/jobs/delete" method="POST" style="display:none;">
                                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                            </form>
                            <button type="button" 
                            onclick="showConfirmModal('delete-job-<?= $job['id'] ?>', '<?= __('dashboard.homeowner.cancel_job') ?>', '<?= __('dashboard.homeowner.cancel_job_desc') ?>', 'decline')"
                                    class="flex items-center justify-center w-7 h-7 rounded-md bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-700 hover:scale-105 border border-red-100 transition-all duration-200"
                                    title="<?= __('dashboard.homeowner.cancel_job') ?>">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endforeach; ?>

            <?php else: ?>
            <div class="empty-state">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <h3><?= __('dashboard.homeowner.no_jobs') ?></h3>
                <p><?= __('dashboard.homeowner.no_jobs_desc') ?></p>
                <a href="<?= APP_URL ?>/jobs/create" class="btn btn-indigo"><?= __('dashboard.homeowner.post_first_job') ?></a>
            </div>
            <?php endif; ?>
        </div><!-- /jobs -->


        <!-- ── INCOMING QUOTES ───────────────────────── -->
        <div id="tab-quotes" class="dash-tab">
            <?php if (!empty($allQuotes)): ?>
            <?php
            $qGroups = [
                ['label'=>__('dashboard.homeowner.awaiting_review'), 'color'=>'#f59e0b', 'key'=>'pending',  'items'=>array_filter($allQuotes, fn($q)=>$q['quote_status']==='pending')],
                ['label'=>__('dashboard.shared.accepted'),        'color'=>'#22c55e', 'key'=>'accepted', 'items'=>array_filter($allQuotes, fn($q)=>$q['quote_status']==='accepted')],
                ['label'=>__('dashboard.homeowner.declined'),        'color'=>'#9ca3af', 'key'=>'rejected', 'items'=>array_filter($allQuotes, fn($q)=>$q['quote_status']==='rejected')],
            ];
            ?>
            <div class="dash-tab-header">
                <h2><?= __('dashboard.homeowner.incoming_quotes') ?></h2>
                <div class="filter-dropdown">
                    <button class="filter-btn" type="button" onclick="toggleFilter('filter-quotes')">
                        <div class="filter-opt-dot" style="display:none"></div>
                        <span class="lbl"><?= __('dashboard.shared.filter_all') ?></span>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="filter-quotes" class="filter-menu">
                        <button type="button" class="filter-opt" onclick="applyDashFilter('tab-quotes', 'all', '', '<?= __('dashboard.homeowner.all_quotes') ?>')">
                            <div class="filter-opt-left">
                                <span style="font-weight:700"><?= __('dashboard.homeowner.all_quotes') ?></span>
                            </div>
                            <span class="filter-opt-count"><?= count($allQuotes) ?></span>
                        </button>
                        <?php foreach($qGroups as $g): if(empty($g['items'])) continue; ?>
                        <button type="button" class="filter-opt" onclick="applyDashFilter('tab-quotes', '<?= $g['key'] ?>', '<?= $g['color'] ?>', '<?= $g['label'] ?>')">
                            <div class="filter-opt-left">
                                <div class="filter-opt-dot" style="background:<?= $g['color'] ?>"></div>
                                <span><?= $g['label'] ?></span>
                            </div>
                            <span class="filter-opt-count"><?= count($g['items']) ?></span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php foreach ($qGroups as $grp): if (empty($grp['items'])) continue; ?>
            <div class="group-header" data-status="<?= $grp['key'] ?>">
                <svg viewBox="0 0 8 8" style="width:8px;height:8px;flex-shrink:0"><circle cx="4" cy="4" r="4" fill="<?= $grp['color'] ?>"/></svg>
                <span class="label"><?= $grp['label'] ?></span>
                <span class="count"><?= count($grp['items']) ?></span>
            </div>
            <?php foreach ($grp['items'] as $q): ?>
            <div class="quote-card" <?php if($grp['key']==='rejected') echo 'style="opacity:0.6;background:#fafafa"'; ?>>
                <div class="quote-card-top">
                    <div style="flex:1;min-width:0">
                        <a href="<?= APP_URL ?>/jobs/<?= $q['job_posting_id'] ?>" class="quote-job-link"><?= htmlspecialchars($q['job_title']) ?></a>
                        <div class="quote-craftsman">
                            <?= htmlspecialchars($q['craftsman_first_name'].' '.$q['craftsman_last_name']) ?>
                            <?php if (!empty($q['craftsman_is_verified'])): ?>
                            <svg viewBox="0 0 20 20" fill="#3b82f6" style="width:14px;height:14px;display:inline;vertical-align:middle;margin-inline-start:2px"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <?php endif; ?>
                            <?php if($grp['key']==='pending'): ?><span style="font-size:0.75rem;color:#9ca3af;font-weight:500;margin-inline-start:0.25rem">· <?= date('d', strtotime($q['quote_created_at'])) ?> <?= __('months_short.'.date('M', strtotime($q['quote_created_at']))) ?> <?= date('Y', strtotime($q['quote_created_at'])) ?></span><?php endif; ?>
                        </div>
                        <span class="quote-price"><?= number_format($q['quoted_price'],2) ?> <?= __('lang')==='ar' ? 'د.ج' : 'DZD' ?></span>
                    </div>
                    <?php if($grp['key']==='pending'): ?><span class="sbadge yellow"><?= __('dashboard.craftsman.pending') ?></span>
                    <?php elseif($grp['key']==='accepted'): ?><span class="sbadge green"><?= __('dashboard.shared.accepted') ?> ✓</span>
                    <?php else: ?><span class="sbadge gray"><?= __('dashboard.homeowner.declined') ?></span><?php endif; ?>
                </div>
                <?php if ($grp['key']==='pending' && !empty($q['cover_message'])): ?>
                <div class="quote-msg">"<?= htmlspecialchars($q['cover_message']) ?>"</div>
                <?php endif; ?>
                <?php if ($grp['key']==='pending'): ?>
                <div class="quote-card-actions">
                    <form id="accept-quote-<?= $q['quote_id'] ?>" action="<?= APP_URL ?>/jobs/accept-quote" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']??'') ?>">
                        <input type="hidden" name="quote_id" value="<?= $q['quote_id'] ?>">
                        <button type="button" onclick="showConfirmModal('accept-quote-<?= $q['quote_id'] ?>','<?= __('dashboard.homeowner.accept_quote_q') ?>','<?= __('dashboard.homeowner.accept_quote_desc') ?>','accept')" class="btn btn-green">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <?= __('dashboard.homeowner.accept_quote') ?>
                        </button>
                    </form>
                    <form id="decline-quote-<?= $q['quote_id'] ?>" action="<?= APP_URL ?>/jobs/reject-quote" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']??'') ?>">
                        <input type="hidden" name="quote_id" value="<?= $q['quote_id'] ?>">
                        <button type="button" onclick="showConfirmModal('decline-quote-<?= $q['quote_id'] ?>','<?= __('dashboard.homeowner.decline_quote_q') ?>','<?= __('dashboard.homeowner.decline_quote_desc') ?>','decline')" class="btn btn-red"><?= __('dashboard.shared.decline') ?></button>
                    </form>
                    <a href="<?= APP_URL ?>/profile/<?= $q['craftsman_username'] ?>" class="btn btn-indigo" style="margin-inline-start:auto"><?= __('dashboard.shared.view_profile') ?></a>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php endforeach; ?>

            <?php else: ?>
            <div class="empty-state">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <h3><?= __('dashboard.homeowner.no_quotes') ?></h3>
                <p><?= __('dashboard.homeowner.no_quotes_desc') ?></p>
            </div>
            <?php endif; ?>
        </div><!-- /quotes -->


        <!-- ── BOOKINGS ──────────────────────────────── -->
        <div id="tab-bookings" class="dash-tab">
            <?php
            $badgeMap = ['requested'=>'yellow','counter_offered'=>'orange','in_progress'=>'blue','pending_completion'=>'purple','completed'=>'green','cancelled'=>'gray'];
            $labelMap = ['requested'=>__('dashboard.craftsman.pending'),'counter_offered'=>__('dashboard.homeowner.counter_rcv'),'in_progress'=>__('dashboard.shared.in_progress'),'pending_completion'=>__('dashboard.craftsman.awaiting_confirm'),'completed'=>__('dashboard.shared.completed'),'cancelled'=>__('dashboard.shared.cancelled')];

            $bGroups = [
                ['label'=>__('dashboard.homeowner.counter_rcv'),     'color'=>'#ea580c', 'key'=>'counter',  'items'=> array_filter($bookings, fn($b)=>$b['status']==='counter_offered')],
                ['label'=>__('dashboard.craftsman.awaiting_confirm'),'color'=>'#a855f7', 'key'=>'pendingc', 'items'=> array_filter($bookings, fn($b)=>$b['status']==='pending_completion')],
                ['label'=>__('dashboard.craftsman.pending'),              'color'=>'#f59e0b', 'key'=>'req',      'items'=> array_filter($bookings, fn($b)=>$b['status']==='requested')],
                ['label'=>__('dashboard.shared.in_progress'),          'color'=>'#6366f1', 'key'=>'prog',     'items'=> array_filter($bookings, fn($b)=>$b['status']==='in_progress')],
                ['label'=>__('dashboard.shared.completed'),            'color'=>'#22c55e', 'key'=>'comp',     'items'=> array_filter($bookings, fn($b)=>$b['status']==='completed')],
                ['label'=>__('dashboard.shared.cancelled'),            'color'=>'#9ca3af', 'key'=>'canc',     'items'=> array_filter($bookings, fn($b)=>$b['status']==='cancelled')],
            ];
            ?>
            <div class="dash-tab-header">
                <h2><?= __('dashboard.shared.bookings') ?></h2>
                <div class="filter-dropdown">
                    <button class="filter-btn" type="button" onclick="toggleFilter('filter-bookings')">
                        <div class="filter-opt-dot" style="display:none"></div>
                        <span class="lbl"><?= __('dashboard.shared.filter_all') ?></span>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="filter-bookings" class="filter-menu">
                        <button type="button" class="filter-opt" onclick="applyDashFilter('tab-bookings', 'all', '', '<?= __('dashboard.homeowner.all_bookings') ?>')">
                            <div class="filter-opt-left">
                                <span style="font-weight:700"><?= __('dashboard.homeowner.all_bookings') ?></span>
                            </div>
                            <span class="filter-opt-count"><?= count($bookings) ?></span>
                        </button>
                        <?php foreach($bGroups as $g): if(empty($g['items'])) continue; ?>
                        <button type="button" class="filter-opt" onclick="applyDashFilter('tab-bookings', '<?= $g['key'] ?>', '<?= $g['color'] ?>', '<?= $g['label'] ?>')">
                            <div class="filter-opt-left">
                                <div class="filter-opt-dot" style="background:<?= $g['color'] ?>"></div>
                                <span><?= $g['label'] ?></span>
                            </div>
                            <span class="filter-opt-count"><?= count($g['items']) ?></span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($bookings)): ?>
            <?php foreach ($bGroups as $grp): if (empty($grp['items'])) continue; ?>
            <div class="group-header" data-status="<?= $grp['key'] ?>">
                <svg viewBox="0 0 8 8" style="width:8px;height:8px;flex-shrink:0"><circle cx="4" cy="4" r="4" fill="<?= $grp['color'] ?>"/></svg>
                <span class="label"><?= $grp['label'] ?></span>
                <span class="count"><?= count($grp['items']) ?></span>
            </div>
            <?php foreach ($grp['items'] as $booking): ?>
            <div class="booking-card">
                <div class="booking-card-body">
                    <div class="booking-card-header">
                        <div class="booking-name">
                            <?= htmlspecialchars($booking['first_name'].' '.$booking['last_name']) ?>
                            <?php if (!empty($booking['is_verified'])): ?>
                            <svg viewBox="0 0 20 20" fill="#3b82f6" style="width:13px;height:13px;flex-shrink:0"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <?php endif; ?>
                        </div>
                        <span class="sbadge <?= $badgeMap[$booking['status']]??'gray' ?>"><?= $labelMap[$booking['status']]??ucfirst($booking['status']) ?></span>
                    </div>
                    <div class="booking-desc"><?= htmlspecialchars($booking['description']) ?></div>
                    <div class="booking-chips">
                        <?php 
                            $rawAddr   = $booking['address']??'';
                            $addrParts = explode(',', $rawAddr, 2);
                            $wilayaKey = trim($addrParts[0]);
                            $restAddr  = isset($addrParts[1]) ? '، ' . trim($addrParts[1]) : '';
                            $mappedW   = __('wilayas.'.$wilayaKey) !== 'wilayas.'.$wilayaKey ? preg_replace('/^\d{2}\s-\s/','',__('wilayas.'.$wilayaKey)) : preg_replace('/^\d{2}\s-\s/','',$wilayaKey);
                        ?>
                        <span class="bchip"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg><?= htmlspecialchars($mappedW . $restAddr) ?></span>
                        <span class="bchip"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><?= date('d', strtotime($booking['scheduled_date'])) ?> <?= __('months_short.'.date('M', strtotime($booking['scheduled_date']))) ?> <?= date('Y', strtotime($booking['scheduled_date'])) ?></span>
                        <?php if (!empty($booking['quoted_price'])): ?><span class="bchip price"><?= number_format($booking['quoted_price'],2) ?> <?= __('lang')==='ar' ? 'د.ج' : 'DZD' ?></span><?php endif; ?>
                    </div>
                </div>

                <?php if ($booking['status'] === 'counter_offered'): ?>
                <div class="booking-card-actions">
                    <div style="flex:1">
                        <p style="font-size:0.75rem;font-weight:800;color:#9a3412;margin-bottom:0.4rem"><?= __('dashboard.craftsman.counter_review_respond') ?></p>
                        <?php if (!empty($booking['counter_description'])): ?>
                        <p style="font-size:0.75rem;color:#6b7280;font-weight:600;margin-bottom:0.5rem"><?= htmlspecialchars($booking['counter_description']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($booking['counter_price'])): ?>
                        <p style="font-size:0.78rem;font-weight:800;color:#059669;margin-bottom:0.5rem"><?= __('dashboard.shared.price') ?>: <?= number_format($booking['counter_price'],2) ?> <?= __('lang')==='ar' ? 'د.ج' : 'DZD' ?></p>
                        <?php endif; ?>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap">
                            <form id="accept-counter-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/accept-counter" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']??'') ?>">
                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                <button type="button" onclick="showConfirmModal('accept-counter-<?= $booking['id'] ?>','<?= __('dashboard.homeowner.accept_counter_q') ?>','<?= __('dashboard.homeowner.accept_counter_desc') ?>','accept')" class="btn btn-green"><?= __('dashboard.craftsman.accept_counter') ?></button>
                            </form>
                            <form id="cancel-counter-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/cancel-counter" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']??'') ?>">
                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                <button type="button" onclick="showConfirmModal('cancel-counter-<?= $booking['id'] ?>','<?= __('dashboard.shared.decline_booking_q') ?>','<?= __('dashboard.shared.decline_booking_desc') ?>','decline')" class="btn btn-gray"><?= __('dashboard.shared.decline') ?></button>
                            </form>
                        </div>
                    </div>
                </div>

                <?php elseif ($booking['status'] === 'pending_completion'): ?>
                <div class="booking-card-actions">
                    <div style="flex:1">
                        <p style="font-size:0.78rem;font-weight:800;color:#6b21a8;margin-bottom:0.5rem"><?= __('dashboard.homeowner.craftsman_marked_complete') ?></p>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap">
                            <form id="confirm-complete-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/confirm-completion" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']??'') ?>">
                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                <button type="button" onclick="showConfirmModal('confirm-complete-<?= $booking['id'] ?>','<?= __('dashboard.homeowner.confirm_complete_q') ?>','<?= __('dashboard.homeowner.confirm_complete_desc') ?>','accept')" class="btn btn-green">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <?= __('dashboard.homeowner.confirm_completion') ?>
                                </button>
                            </form>
                            <a href="<?= APP_URL ?>/profile/<?= $booking['username'] ?>" class="btn btn-indigo"><?= __('dashboard.shared.view_craftsman') ?></a>
                        </div>
                    </div>
                </div>

                <?php elseif ($booking['status'] === 'completed' && empty($booking['has_reviewed'])): ?>
                <div class="booking-card-actions">
                    <a href="<?= APP_URL ?>/reviews/create/<?= $booking['id'] ?>" class="btn btn-yellow">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        <?= __('dashboard.homeowner.write_review') ?>
                    </a>
                    <a href="<?= APP_URL ?>/profile/<?= $booking['username'] ?>" class="btn btn-indigo"><?= __('dashboard.shared.view_craftsman') ?></a>
                </div>

                <?php elseif ($booking['status'] === 'completed' && !empty($booking['has_reviewed'])): ?>
                <div class="booking-footer-banner green">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <?= __('dashboard.homeowner.completed_reviewed') ?>
                </div>

                <?php elseif ($booking['status'] === 'in_progress'): ?>
                <div class="booking-footer-banner blue">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <?= __('dashboard.shared.in_progress') ?>
                </div>

                <?php else: ?>
                <div class="booking-card-actions">
                    <a href="<?= APP_URL ?>/profile/<?= $booking['username'] ?>" class="btn btn-indigo"><?= __('dashboard.shared.view_craftsman') ?></a>
                </div>
                <?php endif; ?>

            </div>
            <?php endforeach; ?>
            <?php endforeach; ?>

            <?php else: ?>
            <div class="empty-state">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <h3><?= __('dashboard.homeowner.no_bookings') ?></h3>
                <p><?= __('dashboard.homeowner.no_bookings_desc') ?></p>
                <a href="<?= APP_URL ?>/search" class="btn btn-indigo"><?= __('dashboard.homeowner.find_craftsmen') ?></a>
            </div>
            <?php endif; ?>
        </div><!-- /bookings -->


        <!-- ── SAVED CRAFTSMEN ───────────────────────── -->
        <div id="tab-favorites" class="dash-tab">
            <?php if (!empty($favorites)): ?>
            <div class="saved-grid">
                <?php foreach ($favorites as $fav): ?>
                <div class="saved-card">
                    <div class="saved-top">
                        <img class="saved-avatar" src="<?= get_profile_picture_url($fav['profile_picture']??'default.png',$fav['first_name'],$fav['last_name']) ?>" alt="<?= e($fav['first_name']) ?>">
                        <div style="flex:1;min-width:0">
                            <div class="saved-name">
                                <?= htmlspecialchars($fav['first_name'].' '.$fav['last_name']) ?>
                                <?php if (!empty($fav['is_verified'])): ?>
                                <svg viewBox="0 0 20 20" fill="#3b82f6" style="width:12px;height:12px;display:inline;vertical-align:middle"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <?php endif; ?>
                            </div>
                            <?php $favCatStyles = get_category_classes($fav['service_category']??'General Handyman'); ?>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[0.62rem] font-bold uppercase tracking-wider ring-1 ring-inset <?= $favCatStyles['badge'] ?>" style="margin-top:0.2rem"><?= htmlspecialchars(__('categories.'.($fav['service_category']??'')) !== 'categories.'.($fav['service_category']??'') ? __('categories.'.($fav['service_category']??'')) : ($fav['service_category']??'')) ?></span>
                            <div style="display:flex;align-items:center;gap:0.55rem;margin-top:0.3rem;flex-wrap:wrap">
                                <?php if (!empty($fav['wilaya'])): ?>
                                <?php $cleanWilaya = preg_replace('/^\d{2}\s-\s/','',$fav['wilaya']); ?>
                                <span class="saved-meta"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:10px;height:10px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><?= htmlspecialchars(__('wilayas.'.$fav['wilaya']) !== 'wilayas.'.$fav['wilaya'] ? preg_replace('/^\d{2}\s-\s/','',__('wilayas.'.$fav['wilaya'])) : $cleanWilaya) ?></span>
                                <?php endif; ?>
                                <span class="saved-meta"><svg viewBox="0 0 20 20" fill="#f59e0b" style="width:10px;height:10px;flex-shrink:0"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><?= number_format((float)($fav['rating_score']??0),1) ?> (<?= (int)($fav['reviews_count']??0) ?>)</span>
                            </div>
                        </div>
                    </div>
                    <div class="saved-bottom">
                        <div class="saved-rate"><?= number_format($fav['hourly_rate']??0,0) ?> <span><?= __('dashboard.shared.dzd_hr') ?></span></div>
                        <div class="saved-actions">
                            <button type="button" onclick="confirmRemoveFavorite(<?= $fav['id'] ?>)" class="btn btn-pink" style="padding:0.32rem 0.55rem" title="<?= __('dashboard.shared.remove') ?>">
                                <svg fill="currentColor" viewBox="0 0 20 20" style="width:12px;height:12px"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                            </button>
                            <a href="<?= APP_URL ?>/profile/<?= $fav['username'] ?>" class="btn btn-indigo"><?= __('dashboard.shared.view_details') ?></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <h3><?= __('dashboard.homeowner.no_saved') ?></h3>
                <p><?= __('dashboard.homeowner.no_saved_desc') ?></p>
                <a href="<?= APP_URL ?>/search" class="btn btn-indigo"><?= __('dashboard.homeowner.find_craftsmen') ?></a>
            </div>
            <?php endif; ?>
        </div><!-- /favorites -->

    </main>


    <!-- ════════════════════════════════
         RIGHT METRICS PANEL
    ════════════════════════════════ -->
    <aside class="dash-metrics" id="metrics-panel">
        <!-- Populated by JS on tab switch -->
    </aside>

</div><!-- /dash-shell -->


<!-- Mobile FAB + Drawer -->
<button class="mob-dash-fab" onclick="openMobDrawer()" aria-label="Open navigation">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

<div class="mob-dash-overlay" id="mob-dash-overlay" onclick="closeMobDrawer()"></div>

<div class="mob-dash-drawer" id="mob-dash-drawer">
    <div class="mob-dash-handle"></div>
    <p class="mob-dash-drawer-title"><?= __('dashboard.shared.overview') ?></p>

    <button onclick="mobSwitchTab('overview')" data-mob-tab="overview" class="mob-dash-nav-item active">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
        <?= __('dashboard.shared.overview') ?>
    </button>
    <button onclick="mobSwitchTab('jobs')" data-mob-tab="jobs" class="mob-dash-nav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <?= __('dashboard.homeowner.my_jobs') ?>
    </button>
    <button onclick="mobSwitchTab('quotes')" data-mob-tab="quotes" class="mob-dash-nav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        <?= __('dashboard.homeowner.incoming_quotes') ?>
        <?php if ($pendingQuotesCount > 0): ?><span class="mob-dash-badge red"><?= $pendingQuotesCount ?></span><?php endif; ?>
    </button>
    <button onclick="mobSwitchTab('bookings')" data-mob-tab="bookings" class="mob-dash-nav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <?= __('dashboard.shared.bookings') ?>
    </button>
    <button onclick="mobSwitchTab('favorites')" data-mob-tab="favorites" class="mob-dash-nav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        <?= __('dashboard.homeowner.saved_craftsmen') ?>
        <?php if (!empty($favorites)): ?><span class="mob-dash-badge pink"><?= count($favorites) ?></span><?php endif; ?>
    </button>

    <div class="mob-dash-divider"></div>
    <a href="<?= APP_URL ?>/jobs/create" class="mob-dash-quick primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        <?= __('dashboard.homeowner.post_new_job') ?>
    </a>
    <div style="height:0.4rem"></div>
    <a href="<?= APP_URL ?>/search" class="mob-dash-quick secondary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <?= __('dashboard.homeowner.find_craftsmen') ?>
    </a>
</div>


<!-- ════════════════════════════════════════════════════════
     CONFIRM MODAL
════════════════════════════════════════════════════════ -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50" onclick="hideConfirmModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div id="modal-icon-accept" class="hidden w-9 h-9 bg-green-100 rounded-full flex items-center justify-center me-3 flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <div id="modal-icon-decline" class="hidden w-9 h-9 bg-red-100 rounded-full flex items-center justify-center me-3 flex-shrink-0">
                        <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3 id="modal-title" class="text-base font-extrabold text-gray-900"></h3>
                </div>
                <p id="modal-message" class="text-sm text-gray-500 font-semibold mb-5"></p>
                <div class="flex justify-end gap-2">
                    <button onclick="hideConfirmModal()" class="btn btn-gray"><?= __('dashboard.shared.cancel') ?></button>
                    <button id="modal-confirm-btn" onclick="confirmAction()" class="btn"><?= __('dashboard.shared.confirm') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ════════════════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════════════════ -->
<script>
/* PHP → JS data bridge */
const D = {
    activeJobs:    <?= (int)$activeJobsCount ?>,
    completedJobs: <?= (int)$completedJobsCount ?>,
    pendingQuotes: <?= (int)$pendingQuotesCount ?>,
    totalQuotes:   <?= count($allQuotes) ?>,
    totalBookings: <?= count($bookings) ?>,
    savedCount:    <?= count($favorites) ?>,
    appUrl:        '<?= APP_URL ?>',
    activeBookings: <?= count(array_filter($bookings, fn($b) => in_array($b['status'], ['requested','in_progress','counter_offered']))) ?>,
    completedBookings: <?= count(array_filter($bookings, fn($b) => $b['status'] === 'completed')) ?>,
    qCount: {
        pending:  <?= count(array_filter($allQuotes, fn($q)=>$q['quote_status']==='pending')) ?>,
        accepted: <?= count(array_filter($allQuotes, fn($q)=>$q['quote_status']==='accepted')) ?>,
        rejected: <?= count(array_filter($allQuotes, fn($q)=>$q['quote_status']==='rejected')) ?>,
    },
    bCount: {
        pending:   <?= count(array_filter($bookings, fn($b)=>$b['status']==='requested')) ?>,
        progress:  <?= count(array_filter($bookings, fn($b)=>$b['status']==='in_progress')) ?>,
        pendComp:  <?= count(array_filter($bookings, fn($b)=>$b['status']==='pending_completion')) ?>,
        completed: <?= count(array_filter($bookings, fn($b)=>$b['status']==='completed')) ?>,
    }
};

/* PHP → JS translation bridge */
const T = {
    overview:        '<?= __('dashboard.shared.overview') ?>',
    my_jobs:         '<?= __('dashboard.homeowner.my_jobs') ?>',
    incoming_quotes: '<?= __('dashboard.homeowner.incoming_quotes') ?>',
    bookings:        '<?= __('dashboard.shared.bookings') ?>',
    saved_craftsmen: '<?= __('dashboard.homeowner.saved_craftsmen') ?>',
    active:          '<?= __('dashboard.homeowner.open') ?>',
    completed:       '<?= __('dashboard.shared.completed') ?>',
    in_progress:     '<?= __('dashboard.shared.in_progress') ?>',
    pending:         '<?= __('dashboard.craftsman.pending') ?>',
    awaiting_review: '<?= __('dashboard.homeowner.awaiting_review') ?>',
    accepted:        '<?= __('dashboard.shared.accepted') ?>',
    declined:        '<?= __('dashboard.homeowner.declined') ?>',
    awaiting_confirm:'<?= __('dashboard.craftsman.awaiting_confirm') ?>',
    all_done:        '<?= __('dashboard.shared.completed') ?>',
    yes_accept:      '<?= __('dashboard.shared.accept') ?>',
    yes_decline:     '<?= __('dashboard.shared.decline') ?>',
    remove:          '<?= __('dashboard.shared.remove') ?>',
    remove_saved:    '<?= __('dashboard.craftsman.confirm_remove_fav') ?>',
    remove_saved_desc:'<?= __('dashboard.craftsman.confirm_remove_fav_desc') ?>',
    saved:           '<?= __('dashboard.shared.saved') ?>',
};

/* Helpers */
const mCard = (c, badge, iconPath, label, value, sub) => `
    <div class="metric-card ${c}">
        <span class="metric-badge ${c}">${badge}</span>
        <div class="metric-icon ${c}"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="${iconPath}"/></svg></div>
        <div class="metric-label">${label}</div>
        <div class="metric-value ${c}">${value}</div>
        ${sub ? `<div class="metric-sub ${c}">${sub}</div>` : ''}
    </div>`;

const IC = {
    jobs:   'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    quote:  'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z',
    book:   'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    heart:  'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
    check:  'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
};

function metricsHTML(tab) {
    switch(tab) {
        case 'overview':
            return `<p class="metrics-heading">${T.overview}</p>
                ${mCard('indigo', T.active,    IC.jobs,  T.my_jobs,       D.activeJobs,    D.completedJobs+' '+T.completed)}
                ${mCard('amber',  T.pending,   IC.quote, T.incoming_quotes,  D.pendingQuotes, D.totalQuotes+' total')}
                ${mCard('blue',   T.in_progress, IC.book,  T.bookings,      D.activeBookings, T.in_progress)}
                ${mCard('pink',   T.saved,     IC.heart, T.saved_craftsmen, D.savedCount,  T.saved)}`;

        case 'jobs':
            return `<p class="metrics-heading">${T.my_jobs}</p>
                ${mCard('indigo', T.active,    IC.jobs,  T.my_jobs,   D.activeJobs,    T.active)}
                ${mCard('green',  T.completed, IC.check, T.completed, D.completedJobs, T.completed)}`;

        case 'quotes':
            return `<p class="metrics-heading">${T.incoming_quotes}</p>
                ${mCard('amber',  T.awaiting_review,    IC.quote, T.awaiting_review, D.qCount.pending,  T.pending)}
                ${mCard('green',  T.accepted,  IC.check, T.accepted,        D.qCount.accepted, T.accepted)}
                ${mCard('indigo', 'TOTAL',     IC.quote, T.incoming_quotes,  D.totalQuotes,     'Total')}`;

        case 'bookings':
            return `<p class="metrics-heading">${T.bookings}</p>
                ${mCard('amber',  T.pending,   IC.book, T.pending,           D.bCount.pending,   T.pending)}
                ${mCard('blue',   T.in_progress, IC.book, T.in_progress,       D.bCount.progress,  T.in_progress)}
                ${mCard('indigo', 'CONFIRM',   IC.book, T.awaiting_confirm,  D.bCount.pendComp,  T.awaiting_confirm)}
                ${mCard('green',  T.completed, IC.check, T.completed,         D.bCount.completed, T.all_done)}`;

        case 'favorites':
            return `<p class="metrics-heading">${T.saved_craftsmen}</p>
                ${mCard('pink',   T.saved,     IC.heart, T.saved_craftsmen, D.savedCount, T.saved)}`;

        default: return '';
    }
}

/* Tab switching */
var pendingFormId = null, pendingFavoriteId = null;

function switchTab(tab) {
    document.querySelectorAll('.dash-tab').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.dash-nav-item').forEach(btn => btn.classList.remove('active'));
    const t = document.getElementById('tab-'+tab);
    if (t) t.classList.add('active');
    const b = document.querySelector(`[data-tab="${tab}"]`);
    if (b) b.classList.add('active');
    document.getElementById('metrics-panel').innerHTML = metricsHTML(tab);
    // sync mob
    document.querySelectorAll('.mob-dash-nav-item').forEach(btn => btn.classList.remove('active'));
    const m = document.querySelector(`[data-mob-tab="${tab}"]`);
    if (m) m.classList.add('active');
    if (history.replaceState) history.replaceState(null, null, '#'+tab);
}

(function() {
    let h = window.location.hash.substring(1).split('?')[0];
    switchTab((h && document.getElementById('tab-'+h)) ? h : 'overview');
})();

window.addEventListener('hashchange', function() {
    let h = window.location.hash.substring(1).split('?')[0];
    if (h && document.getElementById('tab-'+h)) switchTab(h);
});

/* Confirm modal */
function showConfirmModal(formId, title, message, type) {
    pendingFormId = formId; pendingFavoriteId = null;
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-message').textContent = message;
    const btn = document.getElementById('modal-confirm-btn');
    const ia  = document.getElementById('modal-icon-accept');
    const id  = document.getElementById('modal-icon-decline');
    if (type === 'accept') {
        btn.className = 'btn btn-green'; btn.textContent = T.yes_accept;
        ia.classList.remove('hidden'); id.classList.add('hidden');
    } else {
        btn.className = 'btn btn-red'; btn.textContent = T.yes_decline;
        ia.classList.add('hidden'); id.classList.remove('hidden');
    }
    document.getElementById('confirm-modal').classList.remove('hidden');
}
function hideConfirmModal() {
    document.getElementById('confirm-modal').classList.add('hidden');
    pendingFormId = null; pendingFavoriteId = null;
}
function confirmAction() {
    if (pendingFormId) { const id = pendingFormId; hideConfirmModal(); document.getElementById(id).submit(); }
    else if (pendingFavoriteId) { const id = pendingFavoriteId; hideConfirmModal(); removeFavorite(id); }
}

/* Favorites */
function confirmRemoveFavorite(id) {
    pendingFavoriteId = id; pendingFormId = null;
    document.getElementById('modal-title').innerText = T.remove_saved;
    document.getElementById('modal-message').innerText = T.remove_saved_desc;
    document.getElementById('modal-icon-accept').classList.add('hidden');
    document.getElementById('modal-icon-decline').classList.remove('hidden');
    const btn = document.getElementById('modal-confirm-btn');
    btn.innerText = T.remove; btn.className = 'btn btn-red';
    document.getElementById('confirm-modal').classList.remove('hidden');
}
async function removeFavorite(craftsmanId) {
    try {
        const res = await fetch('<?= APP_URL ?>/favorites/toggle', {
            method: 'POST', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ craftsman_id: craftsmanId, csrf_token: '<?= e($_SESSION['csrf_token']??'') ?>' })
        });
        const data = await res.json();
        if (data.success) window.location.reload();
        else alert(data.message || 'Failed to remove.');
    } catch(e) { console.error(e); }
}

/* Mobile drawer */
function openMobDrawer() {
    document.getElementById('mob-dash-drawer').classList.add('open');
    document.getElementById('mob-dash-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeMobDrawer() {
    document.getElementById('mob-dash-drawer').classList.remove('open');
    document.getElementById('mob-dash-overlay').classList.remove('open');
    document.body.style.overflow = '';
}
function mobSwitchTab(tab) {
    closeMobDrawer();
    switchTab(tab);
}
(function() {
    const drawer = document.getElementById('mob-dash-drawer');
    let startY = 0;
    drawer.addEventListener('touchstart', e => { startY = e.touches[0].clientY; }, { passive: true });
    drawer.addEventListener('touchend',   e => { if (e.changedTouches[0].clientY - startY > 60) closeMobDrawer(); }, { passive: true });
})();

/* Dropdown Filters */
function toggleFilter(menuId) {
    const menus = document.querySelectorAll('.filter-menu');
    menus.forEach(m => { if (m.id !== menuId) m.classList.remove('open'); });
    document.getElementById(menuId).classList.toggle('open');
}

document.addEventListener('click', (e) => {
    if (!e.target.closest('.filter-dropdown')) {
        document.querySelectorAll('.filter-menu').forEach(m => m.classList.remove('open'));
    }
});

function applyDashFilter(tabId, statusKey, color, label) {
    document.querySelectorAll('.filter-menu').forEach(m => m.classList.remove('open'));
    
    // Update button visual
    const btnLbl = document.querySelector(`#${tabId} .filter-btn .lbl`);
    const btnDot = document.querySelector(`#${tabId} .filter-btn .filter-opt-dot`);
    if(btnLbl) btnLbl.textContent = 'Filter: ' + label;
    if(btnDot) {
        if(statusKey === 'all') { btnDot.style.display = 'none'; }
        else { btnDot.style.display = 'block'; btnDot.style.background = color; }
    }

    // Toggle items
    const headers = document.querySelectorAll(`#${tabId} .group-header`);
    headers.forEach(h => {
        const itemStatus = h.getAttribute('data-status');
        const show = (statusKey === 'all' || statusKey === itemStatus);
        
        h.style.display = show ? 'flex' : 'none';
        
        // Find sibling cards until next header
        let el = h;
        while (el.nextElementSibling && !el.nextElementSibling.classList.contains('group-header') && !el.nextElementSibling.classList.contains('empty-state')) {
            el = el.nextElementSibling;
            if (el.classList.contains('quote-card') || el.classList.contains('booking-card') || el.classList.contains('job-card')) {
                el.style.display = show ? '' : 'none';
            }
        }
    });
}

</script>