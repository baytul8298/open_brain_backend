<script setup lang="ts">
import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <TeacherLayout>
    <div class="tpr">

      <!-- TOPBAR -->
      <header class="tpr-topbar">
        <div class="tpr-left">
          <div class="tpr-breadcrumb">
            <Link href="/teacher/dashboard" class="tpr-bc-link">Dashboard</Link>
            <span class="tpr-bc-sep">›</span>
            <span class="tpr-bc-cur">My Profile</span>
          </div>
          <div class="tpr-title">My Profile</div>
        </div>
        <button class="tpr-btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit Profile
        </button>
        <div class="tpr-icon tpr-notif">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div class="tpr-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="tpr-av">AR</div>
      </header>

      <!-- HERO -->
      <div class="tpr-hero">
        <div class="tpr-hero-cover"></div>
        <div class="tpr-hero-content">
          <div class="tpr-hero-row">
            <div class="tpr-hero-av-wrap">
              <div class="tpr-hero-av">AR</div>
              <div class="tpr-hero-online"></div>
            </div>
            <div class="tpr-hero-info">
              <div class="tpr-hero-name">Abdul Rahman</div>
              <div class="tpr-hero-type">
                <span class="tpr-type-badge">Online Teacher</span>
              </div>
              <div class="tpr-hero-headline">Mathematics Specialist · Classes 9–10 &amp; HSC</div>
              <div class="tpr-hero-tags">
                <span class="tpr-tag">📐 Mathematics</span>
                <span class="tpr-tag">📅 8 yrs exp</span>
                <span class="tpr-tag">📍 Dhaka</span>
                <span class="tpr-tag">🗣️ Bengali &amp; English</span>
              </div>
            </div>
            <div class="tpr-hero-actions">
              <button class="tpr-btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Profile
              </button>
              <button class="tpr-btn-ghost">Share Profile</button>
            </div>
          </div>
          <!-- Stats strip -->
          <div class="tpr-hero-stats">
            <div class="tpr-hstat">
              <div class="tpr-hstat-val">324</div>
              <div class="tpr-hstat-label">Students</div>
            </div>
            <div class="tpr-hstat-div"></div>
            <div class="tpr-hstat">
              <div class="tpr-hstat-val">6</div>
              <div class="tpr-hstat-label">Courses</div>
            </div>
            <div class="tpr-hstat-div"></div>
            <div class="tpr-hstat">
              <div class="tpr-hstat-val">4.9★</div>
              <div class="tpr-hstat-label">Rating</div>
            </div>
            <div class="tpr-hstat-div"></div>
            <div class="tpr-hstat">
              <div class="tpr-hstat-val">8 yrs</div>
              <div class="tpr-hstat-label">Experience</div>
            </div>
          </div>
        </div>
      </div>

      <!-- BODY -->
      <div class="tpr-body">

        <!-- LEFT COLUMN -->
        <div class="tpr-left-col">

          <!-- About -->
          <div class="tpr-card">
            <div class="tpr-card-head">
              <div class="tpr-card-title">
                <div class="tpr-ci tpr-ci-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                About Me
              </div>
              <button class="tpr-btn-ghost-sm">Edit</button>
            </div>
            <div class="tpr-card-body">
              <p class="tpr-about-text">I am a passionate mathematics teacher with 8 years of experience specialising in Classes 9–10 and HSC level mathematics. My teaching approach focuses on building strong conceptual foundations while ensuring students develop problem-solving skills that serve them beyond the classroom.</p>
              <p class="tpr-about-text" style="margin-top:10px">I have helped over 300 students achieve A and A+ grades in their board examinations. My interactive online sessions combine visual explanations, step-by-step problem solving, and regular practice assignments.</p>
            </div>
          </div>

          <!-- Teaching Type Banner -->
          <div class="tpr-card">
            <div class="tpr-card-head">
              <div class="tpr-card-title">
                <div class="tpr-ci tpr-ci-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18M15 3v18M3 9h18M3 15h18"/></svg></div>
                Teaching Mode
              </div>
            </div>
            <div class="tpr-card-body">
              <div class="tpr-mode-grid">
                <div class="tpr-mode tpr-mode-active">
                  <div class="tpr-mode-icon" style="background:rgba(58,107,158,.12)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                  </div>
                  <div class="tpr-mode-label">Online</div>
                  <div class="tpr-mode-check">✓ Active</div>
                </div>
                <div class="tpr-mode">
                  <div class="tpr-mode-icon" style="background:rgba(201,137,60,.12)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                  </div>
                  <div class="tpr-mode-label">Home Visit</div>
                  <div class="tpr-mode-check tpr-mode-inactive">Available</div>
                </div>
                <div class="tpr-mode">
                  <div class="tpr-mode-icon" style="background:rgba(58,158,111,.12)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                  </div>
                  <div class="tpr-mode-label">In Person</div>
                  <div class="tpr-mode-check tpr-mode-inactive">Available</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Education -->
          <div class="tpr-card">
            <div class="tpr-card-head">
              <div class="tpr-card-title">
                <div class="tpr-ci tpr-ci-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></div>
                Education
              </div>
              <button class="tpr-btn-ghost-sm">Add</button>
            </div>
            <div class="tpr-card-body">
              <div class="tpr-edu-timeline">
                <div class="tpr-edu-item">
                  <div class="tpr-edu-dot" style="background:var(--purple)"></div>
                  <div class="tpr-edu-line"></div>
                  <div class="tpr-edu-content">
                    <div class="tpr-edu-degree">M.Sc. in Pure Mathematics</div>
                    <div class="tpr-edu-school">University of Dhaka</div>
                    <div class="tpr-edu-year">2014 – 2016</div>
                  </div>
                </div>
                <div class="tpr-edu-item">
                  <div class="tpr-edu-dot" style="background:var(--blue)"></div>
                  <div class="tpr-edu-line"></div>
                  <div class="tpr-edu-content">
                    <div class="tpr-edu-degree">B.Sc. in Mathematics (Honours)</div>
                    <div class="tpr-edu-school">Jahangirnagar University</div>
                    <div class="tpr-edu-year">2010 – 2014</div>
                  </div>
                </div>
                <div class="tpr-edu-item">
                  <div class="tpr-edu-dot" style="background:var(--gold)"></div>
                  <div class="tpr-edu-content">
                    <div class="tpr-edu-degree">B.Ed. in Secondary Education</div>
                    <div class="tpr-edu-school">Bangladesh Open University</div>
                    <div class="tpr-edu-year">2017 – 2018</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Subjects -->
          <div class="tpr-card">
            <div class="tpr-card-head">
              <div class="tpr-card-title">
                <div class="tpr-ci tpr-ci-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/></svg></div>
                Subjects &amp; Topics
              </div>
            </div>
            <div class="tpr-card-body">
              <div class="tpr-subj-grid">
                <div class="tpr-subj"><span class="tpr-subj-icon" style="background:rgba(58,107,158,.1);color:var(--blue)">∑</span><span>Algebra</span></div>
                <div class="tpr-subj"><span class="tpr-subj-icon" style="background:rgba(201,137,60,.1);color:var(--gold)">△</span><span>Geometry</span></div>
                <div class="tpr-subj"><span class="tpr-subj-icon" style="background:rgba(122,92,191,.1);color:var(--purple)">∫</span><span>Calculus</span></div>
                <div class="tpr-subj"><span class="tpr-subj-icon" style="background:rgba(42,138,138,.1);color:var(--teal)">sin</span><span>Trigonometry</span></div>
                <div class="tpr-subj"><span class="tpr-subj-icon" style="background:rgba(58,158,111,.1);color:var(--green)">π</span><span>Higher Math</span></div>
                <div class="tpr-subj"><span class="tpr-subj-icon" style="background:rgba(197,48,48,.1);color:var(--red)">x²</span><span>Statistics</span></div>
              </div>
            </div>
          </div>

          <!-- Teaching Style -->
          <div class="tpr-card">
            <div class="tpr-card-head">
              <div class="tpr-card-title">
                <div class="tpr-ci tpr-ci-orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
                Teaching Style
              </div>
            </div>
            <div class="tpr-card-body">
              <div class="tpr-style-grid">
                <div class="tpr-style-item">
                  <div class="tpr-style-icon" style="background:rgba(58,107,158,.1)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
                  <div class="tpr-style-label">Interactive Sessions</div>
                </div>
                <div class="tpr-style-item">
                  <div class="tpr-style-icon" style="background:rgba(58,158,111,.1)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
                  <div class="tpr-style-label">Regular Assignments</div>
                </div>
                <div class="tpr-style-item">
                  <div class="tpr-style-icon" style="background:rgba(201,137,60,.1)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
                  <div class="tpr-style-label">Structured Curriculum</div>
                </div>
                <div class="tpr-style-item">
                  <div class="tpr-style-icon" style="background:rgba(122,92,191,.1)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--purple)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
                  <div class="tpr-style-label">Progress Tracking</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Availability -->
          <div class="tpr-card">
            <div class="tpr-card-head">
              <div class="tpr-card-title">
                <div class="tpr-ci tpr-ci-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
                Availability
              </div>
              <button class="tpr-btn-ghost-sm">Edit</button>
            </div>
            <div class="tpr-card-body">
              <div class="tpr-avail-grid">
                <div class="tpr-avail-day tpr-avail-off"><div class="tpr-avail-d">Sun</div><div class="tpr-avail-status">Off</div></div>
                <div class="tpr-avail-day tpr-avail-on"><div class="tpr-avail-d">Mon</div><div class="tpr-avail-status">4–9 PM</div></div>
                <div class="tpr-avail-day tpr-avail-on"><div class="tpr-avail-d">Tue</div><div class="tpr-avail-status">4–9 PM</div></div>
                <div class="tpr-avail-day tpr-avail-on"><div class="tpr-avail-d">Wed</div><div class="tpr-avail-status">4–9 PM</div></div>
                <div class="tpr-avail-day tpr-avail-on"><div class="tpr-avail-d">Thu</div><div class="tpr-avail-status">4–9 PM</div></div>
                <div class="tpr-avail-day tpr-avail-off"><div class="tpr-avail-d">Fri</div><div class="tpr-avail-status">Off</div></div>
                <div class="tpr-avail-day tpr-avail-on"><div class="tpr-avail-d">Sat</div><div class="tpr-avail-status">10AM–8PM</div></div>
              </div>
            </div>
          </div>

          <!-- Reviews -->
          <div class="tpr-card">
            <div class="tpr-card-head">
              <div class="tpr-card-title">
                <div class="tpr-ci tpr-ci-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
                Student Reviews
              </div>
              <div style="display:flex;align-items:center;gap:6px"><span style="font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--ink)">4.9</span><span style="font-size:.78rem;color:var(--gold)">★★★★★</span><span style="font-size:.72rem;color:var(--muted)">(147 reviews)</span></div>
            </div>
            <div class="tpr-card-body">
              <div class="tpr-review">
                <div class="tpr-rv-top">
                  <div class="tpr-rv-av" style="background:linear-gradient(135deg,#1a3a5c,#2d5a87)">RU</div>
                  <div class="tpr-rv-info">
                    <div class="tpr-rv-name">Rahim Uddin</div>
                    <div class="tpr-rv-stars">★★★★★</div>
                  </div>
                  <div class="tpr-rv-date">March 2026</div>
                </div>
                <div class="tpr-rv-text">"Sir Abdul Rahman is an exceptional teacher. His way of explaining algebraic identities made everything crystal clear. I went from failing to scoring A+ in just 3 months."</div>
              </div>
              <div class="tpr-review">
                <div class="tpr-rv-top">
                  <div class="tpr-rv-av" style="background:linear-gradient(135deg,#1a3a1a,#2a5a2a)">SJ</div>
                  <div class="tpr-rv-info">
                    <div class="tpr-rv-name">Sadia Jannat</div>
                    <div class="tpr-rv-stars">★★★★★</div>
                  </div>
                  <div class="tpr-rv-date">February 2026</div>
                </div>
                <div class="tpr-rv-text">"The best online tutor I have had. His classes are well-structured and the assignments really help reinforce learning. Highly recommend for HSC students."</div>
              </div>
              <div class="tpr-review">
                <div class="tpr-rv-top">
                  <div class="tpr-rv-av" style="background:linear-gradient(135deg,#2a1a0a,#5a3a1a)">TI</div>
                  <div class="tpr-rv-info">
                    <div class="tpr-rv-name">Tarek Islam</div>
                    <div class="tpr-rv-stars">★★★★★</div>
                  </div>
                  <div class="tpr-rv-date">January 2026</div>
                </div>
                <div class="tpr-rv-text">"Sir's teaching style is unique — he makes calculus feel easy! The progress tracking feature helped me identify my weak areas and improve dramatically."</div>
              </div>
            </div>
          </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="tpr-right-col">

          <!-- Enroll Card -->
          <div class="tpr-card tpr-enroll-card">
            <div class="tpr-card-body">
              <div class="tpr-price-row">
                <div class="tpr-price">৳1,200<span class="tpr-price-per">/month</span></div>
                <span class="tpr-popular-badge">Popular</span>
              </div>
              <div class="tpr-price-note">Per course · All materials included</div>
              <button class="tpr-btn-primary tpr-btn-full" style="margin-top:14px">Enroll Now</button>
              <button class="tpr-btn-ghost tpr-btn-full" style="margin-top:8px">Free Trial Class</button>
              <div class="tpr-enroll-feats">
                <div class="tpr-efeat"><span class="tpr-efeat-icon" style="color:var(--green)">✓</span> Live sessions 3× per week</div>
                <div class="tpr-efeat"><span class="tpr-efeat-icon" style="color:var(--green)">✓</span> Recorded video access</div>
                <div class="tpr-efeat"><span class="tpr-efeat-icon" style="color:var(--green)">✓</span> Weekly assignments</div>
                <div class="tpr-efeat"><span class="tpr-efeat-icon" style="color:var(--green)">✓</span> Direct messaging</div>
                <div class="tpr-efeat"><span class="tpr-efeat-icon" style="color:var(--green)">✓</span> Progress reports</div>
              </div>
            </div>
          </div>

          <!-- Active Courses -->
          <div class="tpr-card">
            <div class="tpr-card-head">
              <div class="tpr-card-title">
                <div class="tpr-ci tpr-ci-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
                Active Courses
              </div>
              <Link href="/teacher/courses" class="tpr-link-sm">All →</Link>
            </div>
            <div class="tpr-card-body">
              <div class="tpr-course-item">
                <div class="tpr-course-dot" style="background:var(--blue)"></div>
                <div class="tpr-course-info">
                  <div class="tpr-course-name">Complete Math Mastery — Class 9</div>
                  <div class="tpr-course-meta">22 students · ★ 4.9</div>
                </div>
              </div>
              <div class="tpr-course-item">
                <div class="tpr-course-dot" style="background:var(--gold)"></div>
                <div class="tpr-course-info">
                  <div class="tpr-course-name">Geometry &amp; Trigonometry — Class 10</div>
                  <div class="tpr-course-meta">18 students · ★ 4.8</div>
                </div>
              </div>
              <div class="tpr-course-item">
                <div class="tpr-course-dot" style="background:var(--purple)"></div>
                <div class="tpr-course-info">
                  <div class="tpr-course-name">Higher Mathematics — HSC</div>
                  <div class="tpr-course-meta">14 students · ★ 4.9</div>
                </div>
              </div>
              <div class="tpr-course-item">
                <div class="tpr-course-dot" style="background:var(--green)"></div>
                <div class="tpr-course-info">
                  <div class="tpr-course-name">Algebra Fundamentals — Class 9</div>
                  <div class="tpr-course-meta">31 students · ★ 5.0</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Profile Completion -->
          <div class="tpr-card">
            <div class="tpr-card-head">
              <div class="tpr-card-title">
                <div class="tpr-ci tpr-ci-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
                Profile Completion
              </div>
            </div>
            <div class="tpr-card-body">
              <div class="tpr-completion-wrap">
                <div class="tpr-completion-ring">
                  <svg width="90" height="90" viewBox="0 0 90 90">
                    <circle fill="none" stroke="var(--warm2)" stroke-width="10" cx="45" cy="45" r="35"/>
                    <circle fill="none" stroke="var(--green)" stroke-width="10" stroke-linecap="round" stroke-dasharray="220" stroke-dashoffset="33" cx="45" cy="45" r="35" style="transform:rotate(-90deg);transform-origin:center"/>
                  </svg>
                  <div class="tpr-completion-val">85%</div>
                </div>
                <div style="flex:1">
                  <div style="font-size:.8rem;color:var(--muted);margin-bottom:10px">Complete your profile to attract more students</div>
                  <div class="tpr-check-item tpr-check-done"><span>✓</span> Profile photo</div>
                  <div class="tpr-check-item tpr-check-done"><span>✓</span> About section</div>
                  <div class="tpr-check-item tpr-check-done"><span>✓</span> Education added</div>
                  <div class="tpr-check-item tpr-check-done"><span>✓</span> Subjects listed</div>
                  <div class="tpr-check-item"><span>○</span> Intro video</div>
                  <div class="tpr-check-item"><span>○</span> Certificate uploads</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Similar Tutors -->
          <div class="tpr-card">
            <div class="tpr-card-head">
              <div class="tpr-card-title">
                <div class="tpr-ci tpr-ci-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                Similar Tutors
              </div>
            </div>
            <div class="tpr-card-body">
              <div class="tpr-tutor-item">
                <div class="tpr-tutor-av" style="background:linear-gradient(135deg,#1a3a1a,#2a5a2a)">MH</div>
                <div class="tpr-tutor-info">
                  <div class="tpr-tutor-name">Md. Hasan</div>
                  <div class="tpr-tutor-meta">Physics · ★ 4.8 · 215 students</div>
                </div>
                <button class="tpr-btn-ghost-sm">View</button>
              </div>
              <div class="tpr-tutor-item">
                <div class="tpr-tutor-av" style="background:linear-gradient(135deg,#2a1040,#5a2a80)">FK</div>
                <div class="tpr-tutor-info">
                  <div class="tpr-tutor-name">Farhana Khanam</div>
                  <div class="tpr-tutor-meta">Chemistry · ★ 4.9 · 189 students</div>
                </div>
                <button class="tpr-btn-ghost-sm">View</button>
              </div>
              <div class="tpr-tutor-item">
                <div class="tpr-tutor-av" style="background:linear-gradient(135deg,#0a2a2a,#1a5a5a)">RI</div>
                <div class="tpr-tutor-info">
                  <div class="tpr-tutor-name">Rashed Islam</div>
                  <div class="tpr-tutor-meta">Math HSC · ★ 4.7 · 142 students</div>
                </div>
                <button class="tpr-btn-ghost-sm">View</button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </TeacherLayout>
</template>

<style>
.tpr { display: flex; flex-direction: column; min-height: 100vh; }

/* Topbar */
.tpr-topbar { position: sticky; top: 0; z-index: 50; background: rgba(250,246,239,.95); backdrop-filter: blur(16px); border-bottom: 1px solid var(--border); padding: 0 32px; height: 64px; display: flex; align-items: center; gap: 12px; }
.tpr-left { flex: 1; }
.tpr-breadcrumb { font-size: .72rem; color: var(--muted); margin-bottom: 1px; display: flex; align-items: center; gap: 5px; }
.tpr-bc-link { color: var(--gold); text-decoration: none; font-weight: 600; }
.tpr-bc-sep { color: var(--muted); }
.tpr-bc-cur { color: var(--muted); }
.tpr-title { font-family: 'Playfair Display', serif; font-size: 1rem; color: var(--ink); }
.tpr-icon { width: 38px; height: 38px; border-radius: 10px; background: var(--warm); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s; position: relative; flex-shrink: 0; }
.tpr-icon svg { width: 16px; height: 16px; color: var(--muted); }
.tpr-notif::after { content: '5'; position: absolute; top: -4px; right: -4px; background: var(--rust); color: #fff; font-size: .58rem; font-weight: 700; border-radius: 20px; padding: 1px 5px; border: 2px solid var(--cream); }
.tpr-av { width: 38px; height: 38px; border-radius: 9px; background: linear-gradient(135deg,#1a3a5c,#2d5a87); display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: .95rem; color: #fff; cursor: pointer; border: 2px solid var(--border); flex-shrink: 0; }

/* Buttons */
.tpr-btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: 8px 15px; font-family: 'DM Sans', sans-serif; font-size: .8rem; font-weight: 600; color: #fff; background: linear-gradient(135deg,var(--gold),var(--rust)); border: none; border-radius: 9px; cursor: pointer; text-decoration: none; transition: all .18s; }
.tpr-btn-primary:hover { box-shadow: 0 4px 12px rgba(184,75,47,.3); transform: translateY(-1px); }
.tpr-btn-primary svg { width: 14px; height: 14px; }
.tpr-btn-ghost { display: inline-flex; align-items: center; gap: 5px; padding: 6px 13px; font-family: 'DM Sans', sans-serif; font-size: .76rem; font-weight: 600; color: var(--muted); background: var(--card); border: 1.5px solid var(--border); border-radius: 7px; cursor: pointer; transition: all .18s; }
.tpr-btn-ghost:hover { border-color: var(--gold-light); color: var(--gold); }
.tpr-btn-ghost-sm { padding: 4px 10px; font-size: .72rem; font-family: 'DM Sans', sans-serif; font-weight: 600; color: var(--muted); background: var(--warm); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; transition: all .18s; }
.tpr-btn-ghost-sm:hover { border-color: var(--gold-light); color: var(--gold); }
.tpr-btn-full { width: 100%; justify-content: center; }
.tpr-link-sm { font-size: .78rem; color: var(--gold); text-decoration: none; font-weight: 600; }

/* Hero */
.tpr-hero { position: relative; background: var(--ink); overflow: hidden; }
.tpr-hero-cover { position: absolute; inset: 0; background: radial-gradient(ellipse 120% 80% at 60% 0%, rgba(201,137,60,.25), transparent 60%), radial-gradient(ellipse 80% 60% at 10% 100%, rgba(58,107,158,.2), transparent 60%); pointer-events: none; }
.tpr-hero-cover::after { content: ''; position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px); background-size: 40px 40px; }
.tpr-hero-content { position: relative; z-index: 1; padding: 28px 32px 0; }
.tpr-hero-row { display: flex; align-items: flex-start; gap: 20px; }
.tpr-hero-av-wrap { position: relative; flex-shrink: 0; }
.tpr-hero-av { width: 80px; height: 80px; border-radius: 18px; background: linear-gradient(135deg,#1a3a5c,#2d5a87); display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #fff; border: 3px solid rgba(255,255,255,.15); }
.tpr-hero-online { position: absolute; bottom: -2px; right: -2px; width: 14px; height: 14px; background: var(--green); border-radius: 50%; border: 3px solid var(--ink); }
.tpr-hero-info { flex: 1; }
.tpr-hero-name { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: var(--cream); line-height: 1.1; margin-bottom: 6px; }
.tpr-type-badge { background: rgba(58,107,158,.25); border: 1px solid rgba(58,107,158,.4); border-radius: 20px; padding: 3px 12px; font-size: .72rem; color: #7fb3e0; font-weight: 600; display: inline-block; margin-bottom: 6px; }
.tpr-hero-headline { font-size: .88rem; color: rgba(250,246,239,.6); margin-bottom: 10px; }
.tpr-hero-tags { display: flex; flex-wrap: wrap; gap: 7px; }
.tpr-tag { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); border-radius: 20px; padding: 4px 12px; font-size: .74rem; color: rgba(250,246,239,.8); }
.tpr-hero-actions { display: flex; flex-direction: column; gap: 8px; flex-shrink: 0; }
.tpr-hero-stats { display: flex; align-items: center; gap: 0; margin-top: 22px; border-top: 1px solid rgba(255,255,255,.08); }
.tpr-hstat { flex: 1; text-align: center; padding: 16px 0; }
.tpr-hstat-val { font-family: 'Playfair Display', serif; font-size: 1.4rem; color: var(--cream); }
.tpr-hstat-label { font-size: .7rem; color: rgba(250,246,239,.45); text-transform: uppercase; letter-spacing: .08em; margin-top: 2px; }
.tpr-hstat-div { width: 1px; background: rgba(255,255,255,.1); height: 40px; flex-shrink: 0; }

/* Body */
.tpr-body { display: grid; grid-template-columns: 1fr 300px; gap: 20px; padding: 24px 32px; align-items: start; }
.tpr-left-col { display: flex; flex-direction: column; gap: 16px; }
.tpr-right-col { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 80px; }

/* Card */
.tpr-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
.tpr-card-head { padding: 14px 20px 12px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.tpr-card-title { display: flex; align-items: center; gap: 8px; font-family: 'Playfair Display', serif; font-size: .95rem; color: var(--ink); }
.tpr-card-body { padding: 16px 20px; }
.tpr-ci { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
.tpr-ci svg { width: 13px; height: 13px; }
.tpr-ci-gold { background: rgba(201,137,60,.12); color: var(--gold); }
.tpr-ci-blue { background: rgba(58,107,158,.12); color: var(--blue); }
.tpr-ci-green { background: rgba(58,158,111,.12); color: var(--green); }
.tpr-ci-purple { background: rgba(122,92,191,.12); color: var(--purple); }
.tpr-ci-teal { background: rgba(42,138,138,.12); color: var(--teal); }
.tpr-ci-orange { background: rgba(217,119,6,.12); color: var(--orange); }

/* About */
.tpr-about-text { font-size: .84rem; color: var(--ink); line-height: 1.7; }

/* Mode grid */
.tpr-mode-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; }
.tpr-mode { border: 1.5px solid var(--border); border-radius: 12px; padding: 14px 10px; text-align: center; }
.tpr-mode-active { border-color: var(--blue); background: rgba(58,107,158,.05); }
.tpr-mode-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px; }
.tpr-mode-icon svg { width: 18px; height: 18px; }
.tpr-mode-label { font-size: .82rem; font-weight: 600; color: var(--ink); margin-bottom: 4px; }
.tpr-mode-check { font-size: .7rem; font-weight: 700; color: var(--blue); }
.tpr-mode-inactive { color: var(--muted); }

/* Education */
.tpr-edu-timeline { display: flex; flex-direction: column; gap: 0; }
.tpr-edu-item { display: flex; gap: 14px; position: relative; padding-bottom: 18px; }
.tpr-edu-item:last-child { padding-bottom: 0; }
.tpr-edu-dot { width: 12px; height: 12px; border-radius: 50%; margin-top: 4px; flex-shrink: 0; }
.tpr-edu-line { position: absolute; left: 5px; top: 16px; bottom: 0; width: 2px; background: var(--border); }
.tpr-edu-item:last-child .tpr-edu-line { display: none; }
.tpr-edu-content { flex: 1; }
.tpr-edu-degree { font-size: .84rem; font-weight: 700; color: var(--ink); }
.tpr-edu-school { font-size: .78rem; color: var(--muted); margin-top: 2px; }
.tpr-edu-year { font-size: .72rem; color: var(--gold); font-weight: 600; margin-top: 2px; }

/* Subjects */
.tpr-subj-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; }
.tpr-subj { display: flex; align-items: center; gap: 8px; padding: 10px 12px; background: var(--warm); border: 1px solid var(--border); border-radius: 10px; font-size: .8rem; font-weight: 500; color: var(--ink); }
.tpr-subj-icon { width: 28px; height: 28px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: .78rem; font-weight: 700; flex-shrink: 0; }

/* Teaching style */
.tpr-style-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.tpr-style-item { display: flex; align-items: center; gap: 10px; padding: 12px; background: var(--warm); border: 1px solid var(--border); border-radius: 10px; }
.tpr-style-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.tpr-style-icon svg { width: 15px; height: 15px; }
.tpr-style-label { font-size: .78rem; font-weight: 600; color: var(--ink); }

/* Availability */
.tpr-avail-grid { display: grid; grid-template-columns: repeat(7,1fr); gap: 6px; }
.tpr-avail-day { text-align: center; padding: 10px 4px; border-radius: 10px; border: 1px solid var(--border); }
.tpr-avail-on { background: rgba(58,158,111,.08); border-color: rgba(58,158,111,.2); }
.tpr-avail-off { background: var(--warm); opacity: .6; }
.tpr-avail-d { font-size: .7rem; font-weight: 700; color: var(--ink); }
.tpr-avail-status { font-size: .58rem; color: var(--muted); margin-top: 3px; }
.tpr-avail-on .tpr-avail-status { color: var(--green); }

/* Reviews */
.tpr-review { padding: 14px 0; border-bottom: 1px solid var(--border); }
.tpr-review:last-child { border-bottom: none; padding-bottom: 0; }
.tpr-rv-top { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.tpr-rv-av { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: .74rem; color: #fff; flex-shrink: 0; }
.tpr-rv-info { flex: 1; }
.tpr-rv-name { font-size: .82rem; font-weight: 700; color: var(--ink); }
.tpr-rv-stars { font-size: .72rem; color: var(--gold); }
.tpr-rv-date { font-size: .7rem; color: var(--muted); }
.tpr-rv-text { font-size: .78rem; color: var(--muted); line-height: 1.6; font-style: italic; }

/* Enroll card */
.tpr-enroll-card { border: 2px solid rgba(201,137,60,.3); background: linear-gradient(135deg, rgba(201,137,60,.04), rgba(184,75,47,.04)); }
.tpr-price-row { display: flex; align-items: center; justify-content: space-between; }
.tpr-price { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: var(--ink); }
.tpr-price-per { font-family: 'DM Sans', sans-serif; font-size: .78rem; color: var(--muted); font-weight: 400; }
.tpr-popular-badge { background: linear-gradient(135deg,var(--gold),var(--rust)); color: #fff; font-size: .64rem; font-weight: 700; border-radius: 20px; padding: 3px 10px; }
.tpr-price-note { font-size: .74rem; color: var(--muted); margin-top: 4px; }
.tpr-enroll-feats { margin-top: 14px; display: flex; flex-direction: column; gap: 7px; }
.tpr-efeat { font-size: .78rem; color: var(--ink); display: flex; align-items: center; gap: 7px; }
.tpr-efeat-icon { font-weight: 700; font-size: .9rem; }

/* Course list */
.tpr-course-item { display: flex; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--border); }
.tpr-course-item:last-child { border-bottom: none; }
.tpr-course-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.tpr-course-info { flex: 1; }
.tpr-course-name { font-size: .8rem; font-weight: 600; color: var(--ink); }
.tpr-course-meta { font-size: .7rem; color: var(--muted); }

/* Completion ring */
.tpr-completion-wrap { display: flex; gap: 16px; align-items: center; }
.tpr-completion-ring { position: relative; width: 90px; height: 90px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.tpr-completion-val { position: absolute; font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--ink); }
.tpr-check-item { font-size: .76rem; color: var(--muted); display: flex; align-items: center; gap: 7px; padding: 2px 0; }
.tpr-check-done { color: var(--green); }

/* Similar tutors */
.tpr-tutor-item { display: flex; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--border); }
.tpr-tutor-item:last-child { border-bottom: none; }
.tpr-tutor-av { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: .74rem; color: #fff; flex-shrink: 0; }
.tpr-tutor-info { flex: 1; min-width: 0; }
.tpr-tutor-name { font-size: .8rem; font-weight: 600; color: var(--ink); }
.tpr-tutor-meta { font-size: .7rem; color: var(--muted); }
</style>
