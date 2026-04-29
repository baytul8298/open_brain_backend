<script setup lang="ts">
import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <TeacherLayout>
    <div class="td">

      <!-- TOPBAR -->
      <header class="td-topbar">
        <div class="td-left">
          <div class="td-date">Sunday, March 29, 2026</div>
          <div class="td-greeting">Good morning, <em>Mr. Rahman</em> 👋</div>
        </div>
        <Link href="/teacher/courses/create" class="td-btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          New Course
        </Link>
        <div class="td-icon td-notif">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div class="td-av">AR</div>
      </header>

      <!-- KPI ROW -->
      <div class="td-kpi-row">
        <div class="td-kpi td-kpi-gold">
          <div class="td-kpi-top">
            <div class="td-kpi-icon td-ki-gold">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="td-trend td-trend-up">↑ 12%</span>
          </div>
          <div class="td-kpi-val">324</div>
          <div class="td-kpi-label">Total Students</div>
          <div class="td-kpi-sub">+12 enrolled this week</div>
        </div>
        <div class="td-kpi td-kpi-blue">
          <div class="td-kpi-top">
            <div class="td-kpi-icon td-ki-blue">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
            <span class="td-trend td-trend-up">↑ 2</span>
          </div>
          <div class="td-kpi-val">6</div>
          <div class="td-kpi-label">Active Courses</div>
          <div class="td-kpi-sub">2 new this month</div>
        </div>
        <div class="td-kpi td-kpi-green">
          <div class="td-kpi-top">
            <div class="td-kpi-icon td-ki-green">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            </div>
            <span class="td-trend td-trend-down">8 pending</span>
          </div>
          <div class="td-kpi-val">38</div>
          <div class="td-kpi-label">Assignments</div>
          <div class="td-kpi-sub">8 need grading</div>
        </div>
        <div class="td-kpi td-kpi-purple">
          <div class="td-kpi-top">
            <div class="td-kpi-icon td-ki-purple">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <span class="td-trend td-trend-up">↑ 18%</span>
          </div>
          <div class="td-kpi-val">৳8,400</div>
          <div class="td-kpi-label">This Month</div>
          <div class="td-kpi-sub">৳1,280 more than Feb</div>
        </div>
      </div>

      <!-- BODY -->
      <div class="td-body">

        <!-- Today's Classes - full width -->
        <div class="td-card td-full">
          <div class="td-card-head">
            <div class="td-card-title">
              <div class="td-ci td-ci-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
              Today's Classes
            </div>
            <button class="td-btn-ghost">Full Schedule →</button>
          </div>
          <div class="td-card-body">
            <div class="td-sched">
              <div class="td-sched-time"><div class="td-st-main">4:00</div><div class="td-st-ampm">PM</div></div>
              <div class="td-sched-bar" style="background:var(--blue)"></div>
              <div class="td-sched-info">
                <div class="td-sched-title">Complete Math Mastery — Class 9 <span class="td-live">● LIVE NOW</span></div>
                <div class="td-sched-meta">22 students · Chapter 5: Algebraic Identities · 45 min</div>
              </div>
              <button class="td-btn-primary" style="flex-shrink:0">Join Class</button>
            </div>
            <div class="td-sched">
              <div class="td-sched-time"><div class="td-st-main">6:00</div><div class="td-st-ampm">PM</div></div>
              <div class="td-sched-bar" style="background:var(--gold)"></div>
              <div class="td-sched-info">
                <div class="td-sched-title">Geometry &amp; Trigonometry — Class 10</div>
                <div class="td-sched-meta">18 students · Chapter 8: Sine Rule · 50 min</div>
              </div>
              <button class="td-btn-ghost" style="flex-shrink:0">Prepare</button>
            </div>
            <div class="td-sched">
              <div class="td-sched-time"><div class="td-st-main">8:00</div><div class="td-st-ampm">PM</div></div>
              <div class="td-sched-bar" style="background:var(--purple)"></div>
              <div class="td-sched-info">
                <div class="td-sched-title">Higher Mathematics — HSC</div>
                <div class="td-sched-meta">14 students · Calculus: Differentiation Review · 60 min</div>
              </div>
              <button class="td-btn-ghost" style="flex-shrink:0">Prepare</button>
            </div>
          </div>
        </div>

        <!-- Needs Grading -->
        <div class="td-card">
          <div class="td-card-head">
            <div class="td-card-title"><div class="td-ci td-ci-orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>Needs Grading</div>
            <Link href="/teacher/assignments" class="td-link-sm">View all →</Link>
          </div>
          <div class="td-card-body">
            <div class="td-asgn"><div class="td-asgn-icon" style="background:rgba(58,107,158,.1)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/></svg></div><div class="td-asgn-info"><div class="td-asgn-title">Algebra Practice Set — Chapter 3</div><div class="td-asgn-meta">Class 9 · 18 submitted</div></div><span class="td-asgn-badge">18 pending</span></div>
            <div class="td-asgn"><div class="td-asgn-icon" style="background:rgba(201,137,60,.1)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg></div><div class="td-asgn-info"><div class="td-asgn-title">Trigonometry Worksheet — Q1–10</div><div class="td-asgn-meta">Class 10 · 14 submitted</div></div><span class="td-asgn-badge">14 pending</span></div>
            <div class="td-asgn"><div class="td-asgn-icon" style="background:rgba(122,92,191,.1)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--purple)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/></svg></div><div class="td-asgn-info"><div class="td-asgn-title">Calculus: Limits — Short Problems</div><div class="td-asgn-meta">HSC · 6 submitted</div></div><span class="td-asgn-badge">6 pending</span></div>
            <div style="margin-top:12px"><Link href="/teacher/assignments" class="td-btn-primary" style="width:100%;justify-content:center;display:flex">Start Grading →</Link></div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="td-card">
          <div class="td-card-head">
            <div class="td-card-title"><div class="td-ci td-ci-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>Recent Activity</div>
            <Link href="/teacher/students" class="td-link-sm">All Students →</Link>
          </div>
          <div class="td-card-body">
            <div class="td-student"><div class="td-sr-av" style="background:linear-gradient(135deg,#1a3a5c,#2d5a87)">RU</div><div class="td-sr-info"><div class="td-sr-name">Rahim Uddin</div><div class="td-sr-meta">Class 9 Math · Submitted assignment 2h ago</div></div><span class="td-badge td-badge-top">⭐ Top</span></div>
            <div class="td-student"><div class="td-sr-av" style="background:linear-gradient(135deg,#1a3a1a,#2a5a2a)">SJ</div><div class="td-sr-info"><div class="td-sr-name">Sadia Jannat</div><div class="td-sr-meta">Class 10 Geo · Enrolled 1 day ago</div></div><span class="td-badge td-badge-new">New</span></div>
            <div class="td-student"><div class="td-sr-av" style="background:linear-gradient(135deg,#2a1040,#5a2a80)">KA</div><div class="td-sr-info"><div class="td-sr-name">Karim Ahmed</div><div class="td-sr-meta">HSC Higher Math · Missed 2 classes</div></div><span class="td-badge td-badge-risk">At Risk</span></div>
            <div class="td-student"><div class="td-sr-av" style="background:linear-gradient(135deg,#2a1a0a,#5a3a1a)">NA</div><div class="td-sr-info"><div class="td-sr-name">Nadia Ahmed</div><div class="td-sr-meta">Class 9 Math · Sent a message</div></div><span class="td-badge td-badge-new">New</span></div>
            <div class="td-student"><div class="td-sr-av" style="background:linear-gradient(135deg,#0a2a1a,#1a5a3a)">TI</div><div class="td-sr-info"><div class="td-sr-name">Tarek Islam</div><div class="td-sr-meta">Class 10 · Completed all modules</div></div><span class="td-badge td-badge-top">⭐ Top</span></div>
          </div>
        </div>

        <!-- Course Completion -->
        <div class="td-card">
          <div class="td-card-head">
            <div class="td-card-title"><div class="td-ci td-ci-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>Course Completion</div>
            <Link href="/teacher/analytics" class="td-link-sm">Analytics →</Link>
          </div>
          <div class="td-card-body">
            <div style="display:flex;gap:16px;align-items:center;margin-bottom:14px">
              <div class="td-donut-wrap">
                <svg width="80" height="80" viewBox="0 0 80 80"><circle fill="none" stroke="var(--warm2)" stroke-width="10" cx="40" cy="40" r="30"/><circle fill="none" stroke="var(--green)" stroke-width="10" stroke-linecap="butt" stroke-dasharray="168" stroke-dashoffset="34" cx="40" cy="40" r="30" style="transform:rotate(-90deg);transform-origin:center"/></svg>
                <div class="td-donut-center"><div class="td-donut-val">94%</div><div class="td-donut-sub">avg</div></div>
              </div>
              <div style="flex:1">
                <div style="font-size:.78rem;color:var(--muted);margin-bottom:8px">By course</div>
                <div style="display:flex;flex-direction:column;gap:6px">
                  <div><div style="display:flex;justify-content:space-between;font-size:.74rem;margin-bottom:3px"><span>Math Class 9</span><strong style="color:var(--green)">96%</strong></div><div style="height:5px;background:var(--warm2);border-radius:20px;overflow:hidden"><div style="width:96%;height:100%;background:var(--green);border-radius:20px"></div></div></div>
                  <div><div style="display:flex;justify-content:space-between;font-size:.74rem;margin-bottom:3px"><span>Geometry Cl. 10</span><strong style="color:var(--gold)">91%</strong></div><div style="height:5px;background:var(--warm2);border-radius:20px;overflow:hidden"><div style="width:91%;height:100%;background:var(--gold);border-radius:20px"></div></div></div>
                  <div><div style="display:flex;justify-content:space-between;font-size:.74rem;margin-bottom:3px"><span>HSC Higher Math</span><strong style="color:var(--blue)">88%</strong></div><div style="height:5px;background:var(--warm2);border-radius:20px;overflow:hidden"><div style="width:88%;height:100%;background:var(--blue);border-radius:20px"></div></div></div>
                </div>
              </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px">
              <div class="td-mini-stat"><div class="td-ms-val" style="color:var(--green)">87%</div><div class="td-ms-label">A/A+ Rate</div></div>
              <div class="td-mini-stat"><div class="td-ms-val" style="color:var(--gold)">4.9★</div><div class="td-ms-label">Avg Rating</div></div>
              <div class="td-mini-stat"><div class="td-ms-val" style="color:var(--blue)">2.1h</div><div class="td-ms-label">Avg Watch</div></div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="td-card">
          <div class="td-card-head"><div class="td-card-title"><div class="td-ci td-ci-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>Quick Actions</div></div>
          <div class="td-card-body">
            <div class="td-qa-grid">
              <Link href="/teacher/courses/create" class="td-qa-btn"><div class="td-qa-icon" style="background:rgba(201,137,60,.12)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="12" y1="8" x2="12" y2="16"/></svg></div><div class="td-qa-label">New Course</div></Link>
              <Link href="/teacher/assignments" class="td-qa-btn"><div class="td-qa-icon" style="background:rgba(217,119,6,.12)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div><div class="td-qa-label">Grade Work</div></Link>
              <div class="td-qa-btn"><div class="td-qa-icon" style="background:rgba(58,107,158,.12)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div><div class="td-qa-label">Message</div></div>
              <div class="td-qa-btn"><div class="td-qa-icon" style="background:rgba(58,158,111,.12)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/></svg></div><div class="td-qa-label">Add Lesson</div></div>
              <div class="td-qa-btn"><div class="td-qa-icon" style="background:rgba(122,92,191,.12)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--purple)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div><div class="td-qa-label">Schedule</div></div>
              <Link href="/teacher/analytics" class="td-qa-btn"><div class="td-qa-icon" style="background:rgba(42,138,138,.12)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div><div class="td-qa-label">Analytics</div></Link>
            </div>
          </div>
        </div>

        <!-- Notifications -->
        <div class="td-card">
          <div class="td-card-head"><div class="td-card-title"><div class="td-ci td-ci-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></div>Notifications</div><span class="td-link-sm" style="cursor:pointer">Mark all read</span></div>
          <div class="td-card-body">
            <div class="td-notif"><div class="td-notif-dot" style="background:var(--orange)"></div><div class="td-notif-body"><div class="td-notif-text"><strong>Rahim Uddin</strong> submitted "Algebra Practice Set" — needs your review</div><div class="td-notif-time">2 hours ago</div></div></div>
            <div class="td-notif"><div class="td-notif-dot" style="background:var(--green)"></div><div class="td-notif-body"><div class="td-notif-text"><strong>Sadia Jannat</strong> enrolled in Geometry &amp; Trigonometry Class 10</div><div class="td-notif-time">5 hours ago</div></div></div>
            <div class="td-notif"><div class="td-notif-dot" style="background:var(--blue)"></div><div class="td-notif-body"><div class="td-notif-text"><strong>Karim Ahmed</strong> sent you a message about Chapter 8 Q5</div><div class="td-notif-time">Yesterday</div></div></div>
            <div class="td-notif"><div class="td-notif-dot" style="background:var(--gold)"></div><div class="td-notif-body"><div class="td-notif-text">New review posted: <strong>4.9★</strong> on "Complete Math Mastery Class 9"</div><div class="td-notif-time">Yesterday</div></div></div>
          </div>
        </div>

        <!-- Earnings -->
        <div class="td-card">
          <div class="td-card-head"><div class="td-card-title"><div class="td-ci td-ci-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>Earnings — March</div><Link href="/teacher/analytics" class="td-link-sm">Details →</Link></div>
          <div class="td-card-body">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:14px">
              <div><div style="font-family:'Playfair Display',serif;font-size:2rem;color:var(--ink)">৳8,400</div><div style="font-size:.76rem;color:var(--green)">↑ ৳1,280 from February</div></div>
              <div style="text-align:right"><div style="font-size:.76rem;color:var(--muted)">Target</div><div style="font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--muted)">৳10,000</div></div>
            </div>
            <div style="height:8px;background:var(--warm2);border-radius:20px;overflow:hidden;margin-bottom:6px"><div style="width:84%;height:100%;background:linear-gradient(90deg,var(--gold),var(--gold-light));border-radius:20px"></div></div>
            <div style="font-size:.72rem;color:var(--muted)">84% of monthly target · ৳1,600 remaining</div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-top:12px">
              <div class="td-mini-stat"><div class="td-ms-val">৳4,200</div><div class="td-ms-label">Class 9</div></div>
              <div class="td-mini-stat"><div class="td-ms-val">৳2,800</div><div class="td-ms-label">Class 10</div></div>
              <div class="td-mini-stat"><div class="td-ms-val">৳1,400</div><div class="td-ms-label">HSC</div></div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </TeacherLayout>
</template>

<style>
.td { display: flex; flex-direction: column; min-height: 100vh; }

/* Topbar */
.td-topbar { position: sticky; top: 0; z-index: 50; background: rgba(250,246,239,.95); backdrop-filter: blur(16px); border-bottom: 1px solid var(--border); padding: 0 32px; height: 64px; display: flex; align-items: center; gap: 12px; }
.td-left { flex: 1; }
.td-date { font-size: .78rem; color: var(--muted); }
.td-greeting { font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--ink); }
.td-greeting em { font-style: italic; color: var(--gold); }
.td-icon { width: 38px; height: 38px; border-radius: 10px; background: var(--warm); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s; position: relative; flex-shrink: 0; }
.td-icon svg { width: 16px; height: 16px; color: var(--muted); }
.td-notif::after { content: '5'; position: absolute; top: -4px; right: -4px; background: var(--rust); color: #fff; font-size: .58rem; font-weight: 700; border-radius: 20px; padding: 1px 5px; border: 2px solid var(--cream); }
.td-av { width: 38px; height: 38px; border-radius: 9px; background: linear-gradient(135deg,#1a3a5c,#2d5a87); display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: .95rem; color: #fff; cursor: pointer; border: 2px solid var(--border); flex-shrink: 0; }

/* Buttons */
.td-btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: 8px 15px; font-family: 'DM Sans', sans-serif; font-size: .8rem; font-weight: 600; color: #fff; background: linear-gradient(135deg,var(--gold),var(--rust)); border: none; border-radius: 9px; cursor: pointer; text-decoration: none; transition: all .18s; }
.td-btn-primary:hover { box-shadow: 0 4px 12px rgba(184,75,47,.3); transform: translateY(-1px); }
.td-btn-primary svg { width: 14px; height: 14px; }
.td-btn-ghost { display: inline-flex; align-items: center; gap: 5px; padding: 6px 13px; font-family: 'DM Sans', sans-serif; font-size: .76rem; font-weight: 600; color: var(--muted); background: var(--card); border: 1.5px solid var(--border); border-radius: 7px; cursor: pointer; transition: all .18s; }
.td-btn-ghost:hover { border-color: var(--gold-light); color: var(--gold); }
.td-link-sm { font-size: .78rem; color: var(--gold); text-decoration: none; font-weight: 600; }

/* KPI */
.td-kpi-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; padding: 24px 32px 0; }
.td-kpi { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 18px 20px; animation: fadeUp .4s ease both; transition: all .2s; position: relative; overflow: hidden; }
.td-kpi:hover { box-shadow: 0 6px 20px rgba(14,11,7,.08); border-color: rgba(201,137,60,.2); }
.td-kpi::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 3px 3px 0 0; }
.td-kpi-gold::after { background: linear-gradient(90deg,var(--gold),var(--gold-light)); }
.td-kpi-blue::after { background: linear-gradient(90deg,var(--blue),#5a8bbf); }
.td-kpi-green::after { background: linear-gradient(90deg,var(--green),#5abf8a); }
.td-kpi-purple::after { background: linear-gradient(90deg,var(--purple),#a07ad0); }
.td-kpi-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 10px; }
.td-kpi-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.td-kpi-icon svg { width: 16px; height: 16px; }
.td-ki-gold { background: rgba(201,137,60,.12); color: var(--gold); }
.td-ki-blue { background: rgba(58,107,158,.12); color: var(--blue); }
.td-ki-green { background: rgba(58,158,111,.12); color: var(--green); }
.td-ki-purple { background: rgba(122,92,191,.12); color: var(--purple); }
.td-trend { font-size: .7rem; font-weight: 700; border-radius: 20px; padding: 2px 8px; }
.td-trend-up { background: rgba(58,158,111,.1); color: var(--green); }
.td-trend-down { background: rgba(197,48,48,.1); color: var(--red); }
.td-kpi-val { font-family: 'Playfair Display', serif; font-size: 2rem; color: var(--ink); line-height: 1; margin-bottom: 4px; }
.td-kpi-label { font-size: .74rem; color: var(--muted); font-weight: 500; }
.td-kpi-sub { font-size: .7rem; color: var(--muted); margin-top: 3px; opacity: .7; }

/* Body grid */
.td-body { padding: 22px 32px; display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.td-full { grid-column: 1 / -1; }

/* Card */
.td-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; animation: fadeUp .4s ease both; }
.td-card-head { padding: 14px 20px 12px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.td-card-title { display: flex; align-items: center; gap: 8px; font-family: 'Playfair Display', serif; font-size: .95rem; color: var(--ink); }
.td-ci { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
.td-ci svg { width: 13px; height: 13px; }
.td-ci-gold { background: rgba(201,137,60,.12); color: var(--gold); }
.td-ci-blue { background: rgba(58,107,158,.12); color: var(--blue); }
.td-ci-green { background: rgba(58,158,111,.12); color: var(--green); }
.td-ci-red { background: rgba(197,48,48,.12); color: var(--red); }
.td-ci-orange { background: rgba(217,119,6,.12); color: var(--orange); }
.td-ci-purple { background: rgba(122,92,191,.12); color: var(--purple); }
.td-card-body { padding: 16px 20px; }

/* Schedule */
.td-sched { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--border); }
.td-sched:last-child { border-bottom: none; }
.td-sched-time { width: 48px; flex-shrink: 0; text-align: center; }
.td-st-main { font-family: 'Playfair Display', serif; font-size: .9rem; color: var(--ink); font-weight: 600; }
.td-st-ampm { font-size: .64rem; color: var(--muted); text-transform: uppercase; }
.td-sched-bar { width: 4px; height: 36px; border-radius: 4px; flex-shrink: 0; }
.td-sched-info { flex: 1; }
.td-sched-title { font-size: .82rem; font-weight: 600; color: var(--ink); margin-bottom: 2px; }
.td-sched-meta { font-size: .7rem; color: var(--muted); }
.td-live { font-size: .65rem; font-weight: 700; background: rgba(197,48,48,.1); border: 1px solid rgba(197,48,48,.25); color: var(--red); border-radius: 20px; padding: 2px 8px; animation: pulse 2s infinite; margin-left: 6px; }
@keyframes pulse { 0%,100%{opacity:1}50%{opacity:.5} }

/* Assignments grading */
.td-asgn { display: flex; align-items: center; gap: 10px; padding: 9px 6px; border-bottom: 1px solid var(--border); border-radius: 8px; }
.td-asgn:last-of-type { border-bottom: none; }
.td-asgn-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.td-asgn-icon svg { width: 15px; height: 15px; }
.td-asgn-info { flex: 1; min-width: 0; }
.td-asgn-title { font-size: .82rem; font-weight: 600; color: var(--ink); }
.td-asgn-meta { font-size: .7rem; color: var(--muted); }
.td-asgn-badge { font-size: .68rem; font-weight: 700; border-radius: 20px; padding: 3px 9px; border: 1px solid rgba(217,119,6,.3); background: rgba(217,119,6,.1); color: var(--orange); white-space: nowrap; }

/* Students */
.td-student { display: flex; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--border); }
.td-student:last-child { border-bottom: none; }
.td-sr-av { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: .74rem; color: #fff; flex-shrink: 0; }
.td-sr-info { flex: 1; min-width: 0; }
.td-sr-name { font-size: .82rem; font-weight: 700; color: var(--ink); }
.td-sr-meta { font-size: .7rem; color: var(--muted); }
.td-badge { font-size: .65rem; font-weight: 700; border-radius: 20px; padding: 2px 8px; border: 1px solid; }
.td-badge-new { background: rgba(58,158,111,.1); border-color: rgba(58,158,111,.25); color: var(--green); }
.td-badge-risk { background: rgba(197,48,48,.1); border-color: rgba(197,48,48,.25); color: var(--red); }
.td-badge-top { background: rgba(201,137,60,.1); border-color: rgba(201,137,60,.25); color: var(--gold); }

/* Donut */
.td-donut-wrap { position: relative; width: 80px; height: 80px; flex-shrink: 0; }
.td-donut-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; }
.td-donut-val { font-family: 'Playfair Display', serif; font-size: 1rem; color: var(--ink); line-height: 1; }
.td-donut-sub { font-size: .58rem; color: var(--muted); text-transform: uppercase; }

/* Mini stats */
.td-mini-stat { padding: 10px; background: var(--warm); border-radius: 9px; text-align: center; border: 1px solid var(--border); }
.td-ms-val { font-family: 'Playfair Display', serif; font-size: 1.2rem; color: var(--ink); }
.td-ms-label { font-size: .66rem; color: var(--muted); }

/* Quick actions */
.td-qa-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; }
.td-qa-btn { display: flex; flex-direction: column; align-items: center; gap: 7px; padding: 14px 10px; background: var(--warm); border: 1.5px solid var(--border); border-radius: 12px; cursor: pointer; transition: all .2s; text-align: center; text-decoration: none; }
.td-qa-btn:hover { border-color: var(--gold-light); background: var(--warm2); transform: translateY(-2px); }
.td-qa-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
.td-qa-icon svg { width: 16px; height: 16px; }
.td-qa-label { font-size: .76rem; font-weight: 600; color: var(--ink); }

/* Notifications */
.td-notif { display: flex; align-items: flex-start; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--border); }
.td-notif:last-child { border-bottom: none; }
.td-notif-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
.td-notif-body { flex: 1; }
.td-notif-text { font-size: .8rem; color: var(--ink); line-height: 1.5; }
.td-notif-time { font-size: .7rem; color: var(--muted); margin-top: 2px; }
</style>
