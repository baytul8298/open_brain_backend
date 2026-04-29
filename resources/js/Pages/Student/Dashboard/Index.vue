<script setup lang="ts">
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { ref } from 'vue'

const activeTab = ref('All')
const tabs = ['All', 'Class 9', 'Class 10', 'Mathematics', 'Science', 'Free']

const following = ref({ na: false, ta: false })
</script>

<template>
  <StudentLayout>
    <div class="sd">

      <!-- ══ TOPBAR ══ -->
      <header class="sd-topbar">
        <div class="sd-greeting">
          Good morning, <span>Rahim</span> 👋
        </div>

        <div class="sd-search-wrap">
          <span class="sd-search-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
          </span>
          <input type="text" placeholder="Search courses, subjects…"/>
        </div>

        <div class="sd-tb-icon sd-tb-notif" title="Notifications">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
          </svg>
        </div>
        <div class="sd-tb-icon" title="Messages">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
          </svg>
        </div>
        <div class="sd-tb-avatar" title="Profile">R</div>
      </header>

      <!-- ══ CONTENT ══ -->
      <div class="sd-content">

        <!-- ── PROMO BANNER ── -->
        <section>
          <div class="sd-promo-banner">
            <div class="sd-promo-left">
              <div class="sd-promo-tag">🔥 Limited Offer — Ends Friday</div>
              <h2 class="sd-promo-title">Unlock <em>all subjects</em><br/>for Class 9 &amp; 10 —<br/>30% off this week.</h2>
              <p class="sd-promo-sub">Get full access to Math, Physics, Chemistry, Biology &amp; English with live tutor sessions included.</p>
              <div class="sd-promo-actions">
                <button class="sd-btn-promo-primary">Claim Offer →</button>
                <button class="sd-btn-promo-ghost">Learn More</button>
              </div>
            </div>
            <div class="sd-promo-right">
              <div class="sd-promo-stat">
                <div class="sd-promo-stat-num">৳ 1,200</div>
                <div class="sd-promo-stat-label">Was ৳1,714 · Save ৳514</div>
              </div>
              <div class="sd-promo-stat">
                <div class="sd-promo-stat-num">5 Courses</div>
                <div class="sd-promo-stat-label">Bundled together</div>
              </div>
            </div>
            <div class="sd-promo-dots">
              <div class="sd-pdot active"></div>
              <div class="sd-pdot"></div>
              <div class="sd-pdot"></div>
            </div>
          </div>
        </section>

        <!-- ── CONTINUE LEARNING ── -->
        <section class="sd-continue-section">
          <div class="sd-sec-header">
            <h2 class="sd-sec-title">Continue Learning</h2>
            <a href="#" class="sd-sec-link">View all →</a>
          </div>
          <div class="sd-continue-grid">

            <div class="sd-continue-card">
              <div class="sd-cc-top" style="padding:0;background:#111">
                <img src="https://images.unsplash.com/photo-1636466497217-26a8cbeaf0aa?q=80&w=2148&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Math blackboard" style="width:100%;height:100%;object-fit:cover;opacity:.85"/>
                <div class="sd-cc-live-badge">● Live Now</div>
              </div>
              <div class="sd-cc-body">
                <div class="sd-cc-subject">Mathematics · Class 9</div>
                <div class="sd-cc-title">Algebra &amp; Quadratic Equations — Chapter 5</div>
                <div class="sd-progress-wrap">
                  <div class="sd-progress-meta"><span>Progress</span><span>68%</span></div>
                  <div class="sd-progress-bar"><div class="sd-progress-fill" style="width:68%"></div></div>
                </div>
                <div class="sd-cc-footer">
                  <div class="sd-cc-tutor">
                    <div class="sd-cc-tutor-dot" style="background:#3a6b9e">MR</div>
                    Mr. Rahman
                  </div>
                  <button class="sd-btn-resume">Join Live →</button>
                </div>
              </div>
            </div>

            <div class="sd-continue-card">
              <div class="sd-cc-top" style="padding:0;background:#111">
                <img src="https://plus.unsplash.com/premium_vector-1722586231105-a190495ce48a?q=80&w=1760&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Physics motion" style="width:100%;height:100%;object-fit:cover;opacity:.85"/>
              </div>
              <div class="sd-cc-body">
                <div class="sd-cc-subject">Physics · Class 9</div>
                <div class="sd-cc-title">Motion &amp; Laws of Force — Unit 3</div>
                <div class="sd-progress-wrap">
                  <div class="sd-progress-meta"><span>Progress</span><span>45%</span></div>
                  <div class="sd-progress-bar"><div class="sd-progress-fill" style="width:45%"></div></div>
                </div>
                <div class="sd-cc-footer">
                  <div class="sd-cc-tutor">
                    <div class="sd-cc-tutor-dot" style="background:#7a3520">SK</div>
                    Sir Karim
                  </div>
                  <button class="sd-btn-resume">Resume →</button>
                </div>
              </div>
            </div>

            <div class="sd-continue-card">
              <div class="sd-cc-top" style="padding:0;background:#111">
                <img src="https://images.unsplash.com/photo-1596495577886-d920f1fb7238?q=80&w=2348&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Atomic structure" style="width:100%;height:100%;object-fit:cover;opacity:.85"/>
              </div>
              <div class="sd-cc-body">
                <div class="sd-cc-subject">Chemistry · Class 9</div>
                <div class="sd-cc-title">Atomic Structure &amp; Periodic Table</div>
                <div class="sd-progress-wrap">
                  <div class="sd-progress-meta"><span>Progress</span><span>30%</span></div>
                  <div class="sd-progress-bar"><div class="sd-progress-fill" style="width:30%"></div></div>
                </div>
                <div class="sd-cc-footer">
                  <div class="sd-cc-tutor">
                    <div class="sd-cc-tutor-dot" style="background:#1a7a35">NA</div>
                    Ms. Nasrin
                  </div>
                  <button class="sd-btn-resume">Resume →</button>
                </div>
              </div>
            </div>

          </div>
        </section>

        <!-- ── CATEGORIES ── -->
        <section class="sd-categories-section">
          <div class="sd-sec-header">
            <h2 class="sd-sec-title">Browse by Subject</h2>
            <a href="#" class="sd-sec-link">All subjects →</a>
          </div>
          <div class="sd-cat-grid">
            <div class="sd-cat-item active">
              <div class="sd-cat-icon" style="background:rgba(58,107,158,.12)">
                <svg viewBox="0 0 24 24" fill="none" stroke="#3a6b9e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20"/></svg>
              </div>
              <div class="sd-cat-name">Math</div>
              <div class="sd-cat-count">24 courses</div>
            </div>
            <div class="sd-cat-item">
              <div class="sd-cat-icon" style="background:rgba(184,75,47,.1)">
                <svg viewBox="0 0 24 24" fill="none" stroke="#b84b2f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83"/></svg>
              </div>
              <div class="sd-cat-name">Physics</div>
              <div class="sd-cat-count">18 courses</div>
            </div>
            <div class="sd-cat-item">
              <div class="sd-cat-icon" style="background:rgba(58,158,111,.1)">
                <svg viewBox="0 0 24 24" fill="none" stroke="#3a9e6f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H7l-4 8 4 9h10l4-9-4-8h-2"/></svg>
              </div>
              <div class="sd-cat-name">Chemistry</div>
              <div class="sd-cat-count">15 courses</div>
            </div>
            <div class="sd-cat-item">
              <div class="sd-cat-icon" style="background:rgba(220,100,150,.1)">
                <svg viewBox="0 0 24 24" fill="none" stroke="#dc6496" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              </div>
              <div class="sd-cat-name">Biology</div>
              <div class="sd-cat-count">14 courses</div>
            </div>
            <div class="sd-cat-item">
              <div class="sd-cat-icon" style="background:rgba(122,92,191,.1)">
                <svg viewBox="0 0 24 24" fill="none" stroke="#7a5cbf" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
              </div>
              <div class="sd-cat-name">English</div>
              <div class="sd-cat-count">20 courses</div>
            </div>
            <div class="sd-cat-item">
              <div class="sd-cat-icon" style="background:rgba(201,137,60,.1)">
                <svg viewBox="0 0 24 24" fill="none" stroke="#c9893c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
              </div>
              <div class="sd-cat-name">History</div>
              <div class="sd-cat-count">11 courses</div>
            </div>
            <div class="sd-cat-item">
              <div class="sd-cat-icon" style="background:rgba(58,158,180,.1)">
                <svg viewBox="0 0 24 24" fill="none" stroke="#3a9eb4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
              </div>
              <div class="sd-cat-name">ICT</div>
              <div class="sd-cat-count">9 courses</div>
            </div>
            <div class="sd-cat-item">
              <div class="sd-cat-icon" style="background:rgba(144,180,58,.1)">
                <svg viewBox="0 0 24 24" fill="none" stroke="#90b43a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10z"/></svg>
              </div>
              <div class="sd-cat-name">Geography</div>
              <div class="sd-cat-count">8 courses</div>
            </div>
          </div>
        </section>

        <!-- ── TOP RANKING COURSES ── -->
        <section class="sd-courses-section">
          <div class="sd-sec-header">
            <h2 class="sd-sec-title">Top Ranking Courses</h2>
            <a href="#" class="sd-sec-link">See all →</a>
          </div>
          <div class="sd-filter-tabs">
            <div
              v-for="tab in tabs"
              :key="tab"
              :class="['sd-ftab', { active: activeTab === tab }]"
              @click="activeTab = tab"
            >{{ tab }}</div>
          </div>
          <div class="sd-courses-grid">

            <div class="sd-course-card">
              <div class="sd-cc2-thumb" style="background:linear-gradient(135deg,#1a3a5c,#2d5a87)">
                <div class="sd-thumb-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20"/></svg></div>
                <div class="sd-cc2-rank">1</div>
                <button class="sd-cc2-bookmark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg></button>
              </div>
              <div class="sd-cc2-body">
                <div class="sd-cc2-subject">Mathematics</div>
                <div class="sd-cc2-title">Complete Math Mastery — Class 9 Full Course</div>
                <div class="sd-cc2-meta">
                  <span class="sd-cc2-tag">Class 9</span>
                  <span class="sd-cc2-tag">42 lessons</span>
                  <div class="sd-cc2-rating"><span class="sd-star">★</span> 4.9 (312)</div>
                </div>
                <div class="sd-cc2-footer">
                  <div class="sd-cc2-price">৳ 850</div>
                  <button class="sd-btn-enroll">Enroll</button>
                </div>
              </div>
            </div>

            <div class="sd-course-card">
              <div class="sd-cc2-thumb" style="background:linear-gradient(135deg,#3a1a0e,#7a3520)">
                <div class="sd-thumb-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4"/></svg></div>
                <div class="sd-cc2-rank">2</div>
                <button class="sd-cc2-bookmark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg></button>
              </div>
              <div class="sd-cc2-body">
                <div class="sd-cc2-subject">Physics</div>
                <div class="sd-cc2-title">Physics Foundations for Class 9 &amp; 10</div>
                <div class="sd-cc2-meta">
                  <span class="sd-cc2-tag">Class 9–10</span>
                  <span class="sd-cc2-tag">36 lessons</span>
                  <div class="sd-cc2-rating"><span class="sd-star">★</span> 4.8 (248)</div>
                </div>
                <div class="sd-cc2-footer">
                  <div class="sd-cc2-price">৳ 750</div>
                  <button class="sd-btn-enroll">Enroll</button>
                </div>
              </div>
            </div>

            <div class="sd-course-card">
              <div class="sd-cc2-thumb" style="background:linear-gradient(135deg,#122b12,#1a6a1a)">
                <div class="sd-thumb-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <div class="sd-cc2-rank">3</div>
                <button class="sd-cc2-bookmark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg></button>
              </div>
              <div class="sd-cc2-body">
                <div class="sd-cc2-subject">Biology</div>
                <div class="sd-cc2-title">Life Sciences — Biology Class 9 Complete</div>
                <div class="sd-cc2-meta">
                  <span class="sd-cc2-tag">Class 9</span>
                  <span class="sd-cc2-tag">28 lessons</span>
                  <div class="sd-cc2-rating"><span class="sd-star">★</span> 4.7 (195)</div>
                </div>
                <div class="sd-cc2-footer">
                  <div class="sd-cc2-price">৳ 680</div>
                  <button class="sd-btn-enroll">Enroll</button>
                </div>
              </div>
            </div>

            <div class="sd-course-card">
              <div class="sd-cc2-thumb" style="background:linear-gradient(135deg,#2a1a40,#5a3a8a)">
                <div class="sd-thumb-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"/></svg></div>
                <div class="sd-cc2-rank">4</div>
                <button class="sd-cc2-bookmark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg></button>
              </div>
              <div class="sd-cc2-body">
                <div class="sd-cc2-subject">English</div>
                <div class="sd-cc2-title">English Grammar &amp; Composition — Free Starter</div>
                <div class="sd-cc2-meta">
                  <span class="sd-cc2-tag">All Classes</span>
                  <span class="sd-cc2-tag">16 lessons</span>
                  <div class="sd-cc2-rating"><span class="sd-star">★</span> 4.6 (430)</div>
                </div>
                <div class="sd-cc2-footer">
                  <div class="sd-cc2-price"><span class="sd-free">Free</span></div>
                  <button class="sd-btn-enroll">Enroll</button>
                </div>
              </div>
            </div>

          </div>
        </section>

        <!-- ── MOST POPULAR THIS WEEK ── -->
        <section class="sd-ranking-section">
          <div class="sd-sec-header">
            <h2 class="sd-sec-title">🏆 Most Popular This Week</h2>
            <a href="#" class="sd-sec-link">Full rankings →</a>
          </div>
          <div class="sd-ranking-grid">

            <div class="sd-rank-card">
              <div class="sd-rank-num sd-gold-num">①</div>
              <div class="sd-rank-icon" style="background:linear-gradient(135deg,#1a3a5c,#2d5a87)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20"/></svg>
              </div>
              <div class="sd-rank-body">
                <div class="sd-rank-subject">Mathematics</div>
                <div class="sd-rank-title">Complete Math Mastery — Class 9</div>
                <div class="sd-rank-meta">
                  <span class="sd-rank-students">👥 312 students</span>
                  <span class="sd-rank-rating">★ 4.9</span>
                </div>
              </div>
              <div class="sd-rank-trend">↑ 14%</div>
            </div>

            <div class="sd-rank-card">
              <div class="sd-rank-num sd-silver-num">②</div>
              <div class="sd-rank-icon" style="background:linear-gradient(135deg,#3a1a0e,#7a3520)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4"/></svg>
              </div>
              <div class="sd-rank-body">
                <div class="sd-rank-subject">Physics</div>
                <div class="sd-rank-title">Physics Foundations Class 9 &amp; 10</div>
                <div class="sd-rank-meta">
                  <span class="sd-rank-students">👥 248 students</span>
                  <span class="sd-rank-rating">★ 4.8</span>
                </div>
              </div>
              <div class="sd-rank-trend">↑ 9%</div>
            </div>

            <div class="sd-rank-card">
              <div class="sd-rank-num sd-bronze-num">③</div>
              <div class="sd-rank-icon" style="background:linear-gradient(135deg,#0e2a36,#1a6a7a)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H7l-4 8 4 9h10l4-9-4-8h-2"/></svg>
              </div>
              <div class="sd-rank-body">
                <div class="sd-rank-subject">Chemistry</div>
                <div class="sd-rank-title">Atomic Structure &amp; Bonding — Class 9</div>
                <div class="sd-rank-meta">
                  <span class="sd-rank-students">👥 210 students</span>
                  <span class="sd-rank-rating">★ 4.7</span>
                </div>
              </div>
              <div class="sd-rank-trend">↑ 6%</div>
            </div>

            <div class="sd-rank-card">
              <div class="sd-rank-num sd-plain-num">④</div>
              <div class="sd-rank-icon" style="background:linear-gradient(135deg,#2a1a40,#5a3a8a)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
              </div>
              <div class="sd-rank-body">
                <div class="sd-rank-subject">English</div>
                <div class="sd-rank-title">Grammar &amp; Composition Starter</div>
                <div class="sd-rank-meta">
                  <span class="sd-rank-students">👥 430 students</span>
                  <span class="sd-rank-rating">★ 4.6</span>
                </div>
              </div>
              <div class="sd-rank-trend" style="color:#8a7f72">— Stable</div>
            </div>

            <div class="sd-rank-card">
              <div class="sd-rank-num sd-plain-num">⑤</div>
              <div class="sd-rank-icon" style="background:linear-gradient(135deg,#122b12,#1a6a1a)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              </div>
              <div class="sd-rank-body">
                <div class="sd-rank-subject">Biology</div>
                <div class="sd-rank-title">Life Sciences Class 9 Complete</div>
                <div class="sd-rank-meta">
                  <span class="sd-rank-students">👥 195 students</span>
                  <span class="sd-rank-rating">★ 4.7</span>
                </div>
              </div>
              <div class="sd-rank-trend">↑ 4%</div>
            </div>

            <div class="sd-rank-card">
              <div class="sd-rank-num sd-plain-num">⑥</div>
              <div class="sd-rank-icon" style="background:linear-gradient(135deg,#1a2a1a,#3a6a3a)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
              </div>
              <div class="sd-rank-body">
                <div class="sd-rank-subject">History</div>
                <div class="sd-rank-title">Bangladesh History &amp; World Affairs</div>
                <div class="sd-rank-meta">
                  <span class="sd-rank-students">👥 142 students</span>
                  <span class="sd-rank-rating">★ 4.5</span>
                </div>
              </div>
              <div class="sd-rank-trend">↑ 11%</div>
            </div>

          </div>
        </section>

        <!-- ── MY PURCHASED COURSES ── -->
        <section class="sd-my-courses-section">
          <div class="sd-sec-header">
            <h2 class="sd-sec-title">My Purchased Courses</h2>
            <a href="#" class="sd-sec-link">Manage all →</a>
          </div>
          <div class="sd-my-courses-list">

            <div class="sd-my-course-row">
              <div class="sd-mcr-icon" style="background:linear-gradient(135deg,#1a3a5c,#2d5a87)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20"/></svg>
              </div>
              <div class="sd-mcr-body">
                <div class="sd-mcr-subject">Mathematics · Class 9</div>
                <div class="sd-mcr-title">Complete Math Mastery — Algebra, Geometry &amp; Calculus Intro</div>
                <div class="sd-mcr-progress">
                  <div class="sd-mcr-bar"><div class="sd-mcr-fill" style="width:68%"></div></div>
                  <span class="sd-mcr-pct">68%</span>
                </div>
              </div>
              <div class="sd-mcr-right">
                <div class="sd-mcr-lessons">29 / 42 lessons</div>
                <span class="sd-mcr-status sd-status-active">Active</span>
              </div>
            </div>

            <div class="sd-my-course-row">
              <div class="sd-mcr-icon" style="background:linear-gradient(135deg,#3a1a0e,#7a3520)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4"/></svg>
              </div>
              <div class="sd-mcr-body">
                <div class="sd-mcr-subject">Physics · Class 9</div>
                <div class="sd-mcr-title">Physics Foundations — Motion, Force, Energy &amp; Waves</div>
                <div class="sd-mcr-progress">
                  <div class="sd-mcr-bar"><div class="sd-mcr-fill" style="width:45%"></div></div>
                  <span class="sd-mcr-pct">45%</span>
                </div>
              </div>
              <div class="sd-mcr-right">
                <div class="sd-mcr-lessons">16 / 36 lessons</div>
                <span class="sd-mcr-status sd-status-active">Active</span>
              </div>
            </div>

            <div class="sd-my-course-row">
              <div class="sd-mcr-icon" style="background:linear-gradient(135deg,#0e2a36,#1a6a7a)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H7l-4 8 4 9h10l4-9-4-8h-2"/></svg>
              </div>
              <div class="sd-mcr-body">
                <div class="sd-mcr-subject">Chemistry · Class 9</div>
                <div class="sd-mcr-title">Atomic Structure, Bonding &amp; Chemical Reactions</div>
                <div class="sd-mcr-progress">
                  <div class="sd-mcr-bar"><div class="sd-mcr-fill" style="width:30%"></div></div>
                  <span class="sd-mcr-pct">30%</span>
                </div>
              </div>
              <div class="sd-mcr-right">
                <div class="sd-mcr-lessons">9 / 30 lessons</div>
                <span class="sd-mcr-status sd-status-paused">Paused</span>
              </div>
            </div>

            <div class="sd-my-course-row">
              <div class="sd-mcr-icon" style="background:linear-gradient(135deg,#2a1a40,#5a3a8a)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
              </div>
              <div class="sd-mcr-body">
                <div class="sd-mcr-subject">English · All Classes</div>
                <div class="sd-mcr-title">English Grammar &amp; Composition — Free Starter Course</div>
                <div class="sd-mcr-progress">
                  <div class="sd-mcr-bar"><div class="sd-mcr-fill" style="width:88%"></div></div>
                  <span class="sd-mcr-pct">88%</span>
                </div>
              </div>
              <div class="sd-mcr-right">
                <div class="sd-mcr-lessons">14 / 16 lessons</div>
                <span class="sd-mcr-status sd-status-active">Almost done!</span>
              </div>
            </div>

          </div>
        </section>

        <!-- ── UPCOMING LIVE CLASSES ── -->
        <section class="sd-upcoming-section">
          <div class="sd-sec-header">
            <h2 class="sd-sec-title">Upcoming Live Classes</h2>
            <a href="#" class="sd-sec-link">Full schedule →</a>
          </div>
          <div class="sd-upcoming-list">

            <div class="sd-upcoming-item sd-live-now">
              <div class="sd-up-date" style="border-color:rgba(184,75,47,.3);background:rgba(184,75,47,.06)">
                <div class="sd-up-day" style="color:#b84b2f">4</div>
                <div class="sd-up-mon">MAR</div>
              </div>
              <div class="sd-up-body">
                <div class="sd-up-title">Algebra — Quadratic Equations (Live Session)</div>
                <div class="sd-up-meta">
                  <span class="sd-up-time">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    4:00 PM – 5:30 PM
                  </span>
                  <span class="sd-up-tutor-tag">Mr. Rahman · Math</span>
                  <span style="font-size:.7rem;font-weight:700;color:#b84b2f">● LIVE NOW</span>
                </div>
              </div>
              <div class="sd-up-actions">
                <button class="sd-btn-join">Join Class →</button>
              </div>
            </div>

            <div class="sd-upcoming-item">
              <div class="sd-up-date">
                <div class="sd-up-day">5</div>
                <div class="sd-up-mon">MAR</div>
              </div>
              <div class="sd-up-body">
                <div class="sd-up-title">Laws of Motion — Newton's 3 Laws Explained</div>
                <div class="sd-up-meta">
                  <span class="sd-up-time">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    3:00 PM – 4:30 PM
                  </span>
                  <span class="sd-up-tutor-tag">Sir Karim · Physics</span>
                </div>
              </div>
              <div class="sd-up-actions">
                <button class="sd-btn-remind">🔔 Remind me</button>
              </div>
            </div>

            <div class="sd-upcoming-item">
              <div class="sd-up-date">
                <div class="sd-up-day">7</div>
                <div class="sd-up-mon">MAR</div>
              </div>
              <div class="sd-up-body">
                <div class="sd-up-title">Chemical Bonding — Ionic vs Covalent Bonds</div>
                <div class="sd-up-meta">
                  <span class="sd-up-time">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    5:00 PM – 6:00 PM
                  </span>
                  <span class="sd-up-tutor-tag">Ms. Nasrin · Chemistry</span>
                </div>
              </div>
              <div class="sd-up-actions">
                <button class="sd-btn-remind">🔔 Remind me</button>
              </div>
            </div>

          </div>
        </section>

        <!-- ── YOUR TUTORS ── -->
        <section class="sd-tutor-section">
          <div class="sd-sec-header">
            <h2 class="sd-sec-title">Your Tutors</h2>
            <a href="#" class="sd-sec-link">Browse all tutors →</a>
          </div>
          <div class="sd-tutor-grid">

            <div class="sd-tutor-card">
              <div class="sd-tutor-avatar" style="background:linear-gradient(135deg,#1a3a5c,#2d5a87)">MR</div>
              <div class="sd-tutor-name">Mr. Rahman</div>
              <div class="sd-tutor-subject">Mathematics · Class 8–10</div>
              <div class="sd-tutor-rating">
                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
              </div>
              <div class="sd-tutor-stats">
                <div class="sd-ts-item"><div class="sd-ts-num">312</div><div class="sd-ts-label">Students</div></div>
                <div class="sd-ts-item"><div class="sd-ts-num">8</div><div class="sd-ts-label">Courses</div></div>
              </div>
              <button class="sd-btn-follow">✓ Following</button>
            </div>

            <div class="sd-tutor-card">
              <div class="sd-tutor-avatar" style="background:linear-gradient(135deg,#3a1a0e,#7a3520)">SK</div>
              <div class="sd-tutor-name">Sir Karim</div>
              <div class="sd-tutor-subject">Physics · Class 9–12</div>
              <div class="sd-tutor-rating">
                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
              </div>
              <div class="sd-tutor-stats">
                <div class="sd-ts-item"><div class="sd-ts-num">248</div><div class="sd-ts-label">Students</div></div>
                <div class="sd-ts-item"><div class="sd-ts-num">5</div><div class="sd-ts-label">Courses</div></div>
              </div>
              <button class="sd-btn-follow">✓ Following</button>
            </div>

            <div class="sd-tutor-card">
              <div class="sd-tutor-avatar" style="background:linear-gradient(135deg,#122b12,#1a6a1a)">NA</div>
              <div class="sd-tutor-name">Ms. Nasrin</div>
              <div class="sd-tutor-subject">Chemistry · Class 9–10</div>
              <div class="sd-tutor-rating">
                <span>★</span><span>★</span><span>★</span><span>★</span><span class="sd-empty">★</span>
              </div>
              <div class="sd-tutor-stats">
                <div class="sd-ts-item"><div class="sd-ts-num">195</div><div class="sd-ts-label">Students</div></div>
                <div class="sd-ts-item"><div class="sd-ts-num">4</div><div class="sd-ts-label">Courses</div></div>
              </div>
              <button class="sd-btn-follow" @click="following.na = !following.na">
                {{ following.na ? '✓ Following' : '+ Follow' }}
              </button>
            </div>

            <div class="sd-tutor-card">
              <div class="sd-tutor-avatar" style="background:linear-gradient(135deg,#2a1a40,#5a3a8a)">TA</div>
              <div class="sd-tutor-name">Ms. Tahmina</div>
              <div class="sd-tutor-subject">English · All Classes</div>
              <div class="sd-tutor-rating">
                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
              </div>
              <div class="sd-tutor-stats">
                <div class="sd-ts-item"><div class="sd-ts-num">430</div><div class="sd-ts-label">Students</div></div>
                <div class="sd-ts-item"><div class="sd-ts-num">6</div><div class="sd-ts-label">Courses</div></div>
              </div>
              <button class="sd-btn-follow" @click="following.ta = !following.ta">
                {{ following.ta ? '✓ Following' : '+ Follow' }}
              </button>
            </div>

          </div>
        </section>

      </div><!-- /sd-content -->
    </div><!-- /sd -->
  </StudentLayout>
</template>

<style>
/* ══ STUDENT DASHBOARD ══ */
.sd {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* ── TOPBAR ── */
.sd-topbar {
  position: sticky;
  top: 0;
  z-index: 50;
  background: rgba(250,246,239,.92);
  backdrop-filter: blur(14px);
  border-bottom: 1px solid #e0d8cc;
  padding: 0 36px;
  height: 64px;
  display: flex;
  align-items: center;
  gap: 16px;
}
.sd-greeting {
  font-family: 'Playfair Display', serif;
  font-size: 1rem;
  color: #0e0b07;
  margin-right: auto;
}
.sd-greeting span { color: #c9893c; font-style: italic; }

.sd-search-wrap { position: relative; }
.sd-search-wrap input {
  width: 240px;
  padding: 9px 14px 9px 38px;
  font-family: 'DM Sans', sans-serif;
  font-size: .84rem;
  background: #f0e8d6;
  border: 1.5px solid transparent;
  border-radius: 10px;
  outline: none;
  color: #0e0b07;
  transition: all .2s;
}
.sd-search-wrap input:focus {
  border-color: #c9893c;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(201,137,60,.1);
  width: 290px;
}
.sd-search-wrap input::placeholder { color: #8a7f72; }
.sd-search-icon {
  position: absolute;
  left: 11px;
  top: 50%;
  transform: translateY(-50%);
  color: #8a7f72;
  pointer-events: none;
}
.sd-search-icon svg { width: 14px; height: 14px; }

.sd-tb-icon {
  width: 38px; height: 38px;
  border-radius: 10px;
  background: #f0e8d6;
  border: 1px solid #e0d8cc;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all .2s;
  position: relative;
}
.sd-tb-icon:hover { background: #e8ddc8; border-color: #e8b96a; }
.sd-tb-icon svg { width: 16px; height: 16px; color: #8a7f72; }
.sd-tb-notif::after {
  content: '3';
  position: absolute;
  top: -4px; right: -4px;
  background: #b84b2f;
  color: #fff;
  font-size: .58rem;
  font-weight: 700;
  border-radius: 20px;
  padding: 1px 5px;
  border: 2px solid #faf6ef;
}
.sd-tb-avatar {
  width: 38px; height: 38px;
  border-radius: 50%;
  background: linear-gradient(135deg, #c9893c, #b84b2f);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: .95rem;
  color: #fff;
  cursor: pointer;
  border: 2px solid #e0d8cc;
  transition: border-color .2s;
}
.sd-tb-avatar:hover { border-color: #c9893c; }

/* ── CONTENT ── */
.sd-content {
  padding: 32px 36px;
  display: flex;
  flex-direction: column;
  gap: 36px;
}

/* ── SECTION HEADER ── */
.sd-sec-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 18px;
}
.sd-sec-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.25rem;
  color: #0e0b07;
  display: flex;
  align-items: center;
  gap: 10px;
}
.sd-sec-title::before {
  content: '';
  width: 4px; height: 22px;
  border-radius: 2px;
  background: linear-gradient(180deg, #c9893c, #b84b2f);
}
.sd-sec-link {
  font-size: .8rem;
  font-weight: 500;
  color: #c9893c;
  text-decoration: none;
  border-bottom: 1px solid #e8b96a;
  transition: color .2s;
}
.sd-sec-link:hover { color: #b84b2f; }

/* ── PROMO BANNER ── */
.sd-promo-banner {
  position: relative;
  background: #0e0b07;
  border-radius: 20px;
  padding: 36px 40px;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  animation: sdFadeUp .6s ease both;
}
.sd-promo-banner::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 70% 80% at 100% 50%, rgba(201,137,60,.35) 0%, transparent 60%),
    radial-gradient(ellipse 50% 60% at 0% 0%, rgba(184,75,47,.25) 0%, transparent 60%);
  pointer-events: none;
}
.sd-promo-banner::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
  background-size: 48px 48px;
  pointer-events: none;
}
.sd-promo-left { position: relative; z-index: 1; }
.sd-promo-tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: .68rem;
  font-weight: 600;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: #e8b96a;
  background: rgba(201,137,60,.18);
  border: 1px solid rgba(201,137,60,.35);
  border-radius: 20px;
  padding: 4px 12px;
  margin-bottom: 14px;
}
.sd-promo-tag::before {
  content: '';
  width: 5px; height: 5px;
  border-radius: 50%;
  background: #e8b96a;
}
.sd-promo-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.5rem, 2.2vw, 2.1rem);
  color: #faf6ef;
  line-height: 1.2;
  margin-bottom: 10px;
}
.sd-promo-title em { font-style: italic; color: #e8b96a; }
.sd-promo-sub {
  font-size: .88rem;
  font-weight: 300;
  color: rgba(250,246,239,.6);
  max-width: 380px;
  margin-bottom: 22px;
}
.sd-promo-actions { display: flex; gap: 12px; align-items: center; }
.sd-btn-promo-primary {
  padding: 11px 22px;
  font-family: 'DM Sans', sans-serif;
  font-size: .88rem;
  font-weight: 600;
  color: #fff;
  background: linear-gradient(135deg, #c9893c, #b84b2f);
  border: none;
  border-radius: 10px;
  cursor: pointer;
  transition: transform .18s, box-shadow .18s;
  position: relative;
  overflow: hidden;
}
.sd-btn-promo-primary::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,.18), transparent 60%);
  pointer-events: none;
}
.sd-btn-promo-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(184,75,47,.35); }
.sd-btn-promo-ghost {
  padding: 11px 20px;
  font-family: 'DM Sans', sans-serif;
  font-size: .88rem;
  font-weight: 500;
  color: rgba(250,246,239,.75);
  background: transparent;
  border: 1.5px solid rgba(255,255,255,.2);
  border-radius: 10px;
  cursor: pointer;
  transition: all .2s;
}
.sd-btn-promo-ghost:hover { border-color: rgba(255,255,255,.5); color: #faf6ef; }
.sd-promo-right {
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  gap: 10px;
  flex-shrink: 0;
}
.sd-promo-stat {
  background: rgba(255,255,255,.07);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 14px;
  padding: 14px 20px;
  min-width: 140px;
  backdrop-filter: blur(8px);
}
.sd-promo-stat-num {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem;
  color: #faf6ef;
  line-height: 1;
  margin-bottom: 3px;
}
.sd-promo-stat-label { font-size: .72rem; color: rgba(250,246,239,.5); }
.sd-promo-dots {
  position: absolute;
  bottom: 18px; left: 40px;
  z-index: 1;
  display: flex;
  gap: 6px;
}
.sd-pdot {
  width: 20px; height: 4px;
  border-radius: 2px;
  cursor: pointer;
  background: rgba(255,255,255,.25);
  transition: all .3s;
}
.sd-pdot.active { background: #c9893c; width: 32px; }

/* ── CONTINUE LEARNING ── */
.sd-continue-section { animation: sdFadeUp .6s .16s ease both; }
.sd-continue-grid {
  display: grid;
  grid-template-columns: repeat(3,1fr);
  gap: 16px;
}
.sd-continue-card {
  background: #fff;
  border: 1px solid #e0d8cc;
  border-radius: 16px;
  overflow: hidden;
  transition: transform .2s, box-shadow .2s;
  cursor: pointer;
}
.sd-continue-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(14,11,7,.1); }
.sd-cc-top {
  height: 130px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}
.sd-cc-live-badge {
  position: absolute;
  top: 10px; right: 10px;
  background: #b84b2f;
  color: #fff;
  font-size: .62rem;
  font-weight: 700;
  letter-spacing: .06em;
  text-transform: uppercase;
  border-radius: 20px;
  padding: 3px 9px;
  display: flex;
  align-items: center;
  gap: 5px;
}
.sd-cc-body { padding: 16px; }
.sd-cc-subject {
  font-size: .68rem;
  font-weight: 600;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: #8a7f72;
  margin-bottom: 5px;
}
.sd-cc-title {
  font-family: 'Playfair Display', serif;
  font-size: .98rem;
  color: #0e0b07;
  line-height: 1.3;
  margin-bottom: 10px;
}
.sd-progress-wrap { margin-bottom: 10px; }
.sd-progress-meta {
  display: flex;
  justify-content: space-between;
  font-size: .7rem;
  color: #8a7f72;
  margin-bottom: 5px;
}
.sd-progress-bar {
  height: 5px;
  background: #f0e8d6;
  border-radius: 3px;
  overflow: hidden;
}
.sd-progress-fill {
  height: 100%;
  border-radius: 3px;
  background: linear-gradient(90deg, #c9893c, #e8b96a);
  transition: width .6s ease;
}
.sd-cc-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: 10px;
  border-top: 1px solid #e0d8cc;
}
.sd-cc-tutor {
  font-size: .74rem;
  color: #8a7f72;
  display: flex;
  align-items: center;
  gap: 5px;
}
.sd-cc-tutor-dot {
  width: 18px; height: 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .58rem;
  font-weight: 700;
  color: #fff;
  flex-shrink: 0;
}
.sd-btn-resume {
  padding: 6px 14px;
  font-family: 'DM Sans', sans-serif;
  font-size: .76rem;
  font-weight: 600;
  color: #fff;
  background: #c9893c;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background .2s;
}
.sd-btn-resume:hover { background: #b84b2f; }

/* ── CATEGORIES ── */
.sd-categories-section { animation: sdFadeUp .6s .24s ease both; }
.sd-cat-grid {
  display: grid;
  grid-template-columns: repeat(8,1fr);
  gap: 12px;
}
.sd-cat-item {
  background: #fff;
  border: 1.5px solid #e0d8cc;
  border-radius: 14px;
  padding: 16px 10px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  transition: all .2s;
  text-align: center;
}
.sd-cat-item:hover { border-color: #c9893c; background: rgba(201,137,60,.10); transform: translateY(-2px); }
.sd-cat-item.active { border-color: #c9893c; background: rgba(201,137,60,.10); }
.sd-cat-icon {
  width: 40px; height: 40px;
  border-radius: 11px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.sd-cat-icon svg { width: 20px; height: 20px; }
.sd-cat-name { font-size: .72rem; font-weight: 500; color: #0e0b07; }
.sd-cat-count { font-size: .62rem; color: #8a7f72; }

/* ── TOP RANKING COURSES ── */
.sd-courses-section { animation: sdFadeUp .6s .32s ease both; }
.sd-filter-tabs { display: flex; gap: 6px; margin-bottom: 20px; }
.sd-ftab {
  padding: 7px 16px;
  font-family: 'DM Sans', sans-serif;
  font-size: .78rem;
  font-weight: 500;
  border: 1.5px solid #e0d8cc;
  border-radius: 20px;
  cursor: pointer;
  color: #8a7f72;
  background: #f0e8d6;
  transition: all .2s;
}
.sd-ftab:hover { border-color: #e8b96a; color: #c9893c; }
.sd-ftab.active { background: #0e0b07; border-color: #0e0b07; color: #faf6ef; }
.sd-courses-grid {
  display: grid;
  grid-template-columns: repeat(4,1fr);
  gap: 18px;
}
.sd-course-card {
  background: #fff;
  border: 1px solid #e0d8cc;
  border-radius: 16px;
  overflow: hidden;
  cursor: pointer;
  transition: transform .2s, box-shadow .2s;
}
.sd-course-card:hover { transform: translateY(-5px); box-shadow: 0 14px 36px rgba(14,11,7,.1); }
.sd-cc2-thumb {
  height: 130px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}
.sd-thumb-icon {
  width: 56px; height: 56px;
  border-radius: 16px;
  background: rgba(255,255,255,.15);
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid rgba(255,255,255,.25);
}
.sd-thumb-icon svg { width: 28px; height: 28px; color: #fff; }
.sd-cc2-rank {
  position: absolute;
  top: 10px; left: 10px;
  width: 26px; height: 26px;
  border-radius: 50%;
  background: rgba(0,0,0,.4);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .68rem;
  font-weight: 700;
  color: #fff;
  border: 1px solid rgba(255,255,255,.2);
}
.sd-cc2-bookmark {
  position: absolute;
  top: 10px; right: 10px;
  width: 28px; height: 28px;
  border-radius: 8px;
  background: rgba(0,0,0,.3);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border: 1px solid rgba(255,255,255,.15);
  transition: background .2s;
}
.sd-cc2-bookmark:hover { background: rgba(201,137,60,.5); }
.sd-cc2-bookmark svg { width: 13px; height: 13px; color: #fff; }
.sd-cc2-body { padding: 14px 16px; }
.sd-cc2-subject {
  font-size: .65rem;
  font-weight: 600;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: #8a7f72;
  margin-bottom: 4px;
}
.sd-cc2-title {
  font-family: 'Playfair Display', serif;
  font-size: .92rem;
  color: #0e0b07;
  line-height: 1.3;
  margin-bottom: 8px;
}
.sd-cc2-meta { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; flex-wrap: wrap; }
.sd-cc2-tag {
  font-size: .64rem;
  font-weight: 500;
  border-radius: 20px;
  padding: 2px 8px;
  background: #f0e8d6;
  color: #8a7f72;
  border: 1px solid #e0d8cc;
}
.sd-cc2-rating { font-size: .74rem; color: #8a7f72; display: flex; align-items: center; gap: 3px; }
.sd-star { color: #c9893c; }
.sd-cc2-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: 10px;
  border-top: 1px solid #e0d8cc;
}
.sd-cc2-price {
  font-family: 'Playfair Display', serif;
  font-size: .95rem;
  color: #0e0b07;
}
.sd-free { color: #3a9e6f; font-size: .88rem; }
.sd-btn-enroll {
  padding: 6px 12px;
  font-size: .74rem;
  font-weight: 600;
  color: #c9893c;
  background: rgba(201,137,60,.10);
  border: 1px solid rgba(201,137,60,.3);
  border-radius: 8px;
  cursor: pointer;
  transition: all .2s;
  font-family: 'DM Sans', sans-serif;
}
.sd-btn-enroll:hover { background: #c9893c; color: #fff; }

/* ── RANKING ── */
.sd-ranking-section { animation: sdFadeUp .6s .4s ease both; }
.sd-ranking-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}
.sd-rank-card {
  background: #fff;
  border: 1px solid #e0d8cc;
  border-radius: 14px;
  padding: 16px 18px;
  display: flex;
  align-items: center;
  gap: 14px;
  cursor: pointer;
  transition: all .2s;
}
.sd-rank-card:hover { border-color: #e8b96a; transform: translateX(3px); }
.sd-rank-num {
  font-family: 'Playfair Display', serif;
  font-size: 1.3rem;
  min-width: 32px;
  text-align: center;
}
.sd-gold-num { color: #c9893c; }
.sd-silver-num { color: #9ba4b0; }
.sd-bronze-num { color: #b8896a; }
.sd-plain-num { color: #8a7f72; }
.sd-rank-icon {
  width: 40px; height: 40px;
  border-radius: 10px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}
.sd-rank-icon svg { width: 20px; height: 20px; color: #fff; }
.sd-rank-body { flex: 1; min-width: 0; }
.sd-rank-subject { font-size: .66rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: #8a7f72; margin-bottom: 3px; }
.sd-rank-title { font-size: .88rem; font-weight: 500; color: #0e0b07; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px; }
.sd-rank-meta { display: flex; align-items: center; gap: 10px; font-size: .7rem; color: #8a7f72; }
.sd-rank-students { display: flex; align-items: center; gap: 4px; }
.sd-rank-rating { color: #c9893c; }
.sd-rank-trend { flex-shrink: 0; font-size: .72rem; font-weight: 600; color: #3a9e6f; display: flex; align-items: center; gap: 2px; }

/* ── MY PURCHASED COURSES ── */
.sd-my-courses-section { animation: sdFadeUp .6s .48s ease both; }
.sd-my-courses-list { display: flex; flex-direction: column; gap: 12px; }
.sd-my-course-row {
  background: #fff;
  border: 1px solid #e0d8cc;
  border-radius: 14px;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  cursor: pointer;
  transition: all .2s;
}
.sd-my-course-row:hover { border-color: #e8b96a; box-shadow: 0 4px 16px rgba(14,11,7,.06); }
.sd-mcr-icon {
  width: 46px; height: 46px;
  border-radius: 12px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}
.sd-mcr-icon svg { width: 22px; height: 22px; color: #fff; }
.sd-mcr-body { flex: 1; min-width: 0; }
.sd-mcr-subject { font-size: .66rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: #8a7f72; margin-bottom: 3px; }
.sd-mcr-title { font-size: .9rem; font-weight: 500; color: #0e0b07; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sd-mcr-progress { display: flex; align-items: center; gap: 10px; }
.sd-mcr-bar { flex: 1; height: 5px; background: #f0e8d6; border-radius: 3px; overflow: hidden; }
.sd-mcr-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, #c9893c, #e8b96a); }
.sd-mcr-pct { font-size: .72rem; font-weight: 600; color: #c9893c; white-space: nowrap; }
.sd-mcr-right { flex-shrink: 0; text-align: right; }
.sd-mcr-lessons { font-size: .75rem; color: #8a7f72; margin-bottom: 4px; }
.sd-mcr-status { font-size: .7rem; font-weight: 600; border-radius: 20px; padding: 3px 10px; }
.sd-status-active { background: rgba(58,158,111,.1); color: #3a9e6f; }
.sd-status-paused { background: rgba(201,137,60,.1); color: #c9893c; }

/* ── UPCOMING CLASSES ── */
.sd-upcoming-section { animation: sdFadeUp .6s .54s ease both; }
.sd-upcoming-list { display: flex; flex-direction: column; gap: 12px; }
.sd-upcoming-item {
  background: #fff;
  border: 1px solid #e0d8cc;
  border-radius: 14px;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  transition: all .2s;
}
.sd-upcoming-item:hover { border-color: #e8b96a; }
.sd-upcoming-item.sd-live-now {
  border-color: rgba(184,75,47,.4);
  background: rgba(184,75,47,.03);
}
.sd-up-date {
  text-align: center;
  min-width: 44px;
  background: #f0e8d6;
  border-radius: 10px;
  padding: 8px 6px;
  border: 1px solid #e0d8cc;
}
.sd-up-day { font-family: 'Playfair Display', serif; font-size: 1.2rem; color: #0e0b07; line-height: 1; }
.sd-up-mon { font-size: .62rem; color: #8a7f72; text-transform: uppercase; letter-spacing: .06em; }
.sd-up-body { flex: 1; }
.sd-up-title { font-size: .9rem; font-weight: 500; color: #0e0b07; margin-bottom: 3px; }
.sd-up-meta { font-size: .74rem; color: #8a7f72; display: flex; align-items: center; gap: 10px; }
.sd-up-time { display: flex; align-items: center; gap: 4px; }
.sd-up-time svg { width: 12px; height: 12px; }
.sd-up-tutor-tag {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: #f0e8d6;
  border-radius: 20px;
  padding: 2px 8px;
  font-size: .7rem;
  border: 1px solid #e0d8cc;
}
.sd-up-actions { display: flex; gap: 8px; }
.sd-btn-join {
  padding: 7px 16px;
  font-size: .78rem;
  font-weight: 600;
  color: #fff;
  background: linear-gradient(135deg, #c9893c, #b84b2f);
  border: none;
  border-radius: 9px;
  cursor: pointer;
  transition: transform .15s, box-shadow .15s;
}
.sd-btn-join:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(184,75,47,.3); }
.sd-btn-remind {
  padding: 7px 14px;
  font-size: .78rem;
  font-weight: 500;
  color: #8a7f72;
  background: #f0e8d6;
  border: 1px solid #e0d8cc;
  border-radius: 9px;
  cursor: pointer;
  transition: all .2s;
}
.sd-btn-remind:hover { border-color: #e8b96a; color: #c9893c; }

/* ── YOUR TUTORS ── */
.sd-tutor-section { animation: sdFadeUp .6s .6s ease both; }
.sd-tutor-grid {
  display: grid;
  grid-template-columns: repeat(4,1fr);
  gap: 16px;
}
.sd-tutor-card {
  background: #fff;
  border: 1px solid #e0d8cc;
  border-radius: 16px;
  padding: 20px;
  text-align: center;
  transition: all .2s;
  cursor: pointer;
}
.sd-tutor-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(14,11,7,.09); border-color: #e8b96a; }
.sd-tutor-avatar {
  width: 56px; height: 56px;
  border-radius: 50%;
  margin: 0 auto 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: 1.2rem;
  color: #fff;
  border: 3px solid #e0d8cc;
}
.sd-tutor-name { font-size: .88rem; font-weight: 600; color: #0e0b07; margin-bottom: 2px; }
.sd-tutor-subject { font-size: .72rem; color: #8a7f72; margin-bottom: 8px; }
.sd-tutor-stats { display: flex; justify-content: center; gap: 16px; margin-bottom: 12px; }
.sd-ts-item { text-align: center; }
.sd-ts-num { font-family: 'Playfair Display', serif; font-size: .95rem; color: #0e0b07; }
.sd-ts-label { font-size: .62rem; color: #8a7f72; }
.sd-tutor-rating { display: flex; justify-content: center; gap: 2px; margin-bottom: 12px; }
.sd-tutor-rating span { color: #c9893c; font-size: .8rem; }
.sd-tutor-rating span.sd-empty { color: #e0d8cc; }
.sd-btn-follow {
  width: 100%;
  padding: 8px;
  font-size: .78rem;
  font-weight: 500;
  color: #c9893c;
  background: rgba(201,137,60,.10);
  border: 1px solid rgba(201,137,60,.3);
  border-radius: 9px;
  cursor: pointer;
  transition: all .2s;
  font-family: 'DM Sans', sans-serif;
}
.sd-btn-follow:hover { background: #c9893c; color: #fff; }

/* ── ANIMATION ── */
@keyframes sdFadeUp {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── RESPONSIVE ── */
@media (max-width: 1280px) {
  .sd-courses-grid { grid-template-columns: repeat(3,1fr); }
  .sd-cat-grid { grid-template-columns: repeat(4,1fr); }
  .sd-tutor-grid { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 768px) {
  .sd-courses-grid { grid-template-columns: 1fr 1fr; }
  .sd-ranking-grid { grid-template-columns: 1fr; }
  .sd-topbar { padding: 0 20px; }
  .sd-content { padding: 20px; }
  .sd-continue-grid { grid-template-columns: 1fr; }
}
</style>
