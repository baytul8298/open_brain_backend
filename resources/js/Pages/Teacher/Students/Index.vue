<script setup lang="ts">
import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const search = ref('')

const students = [
  { initials: 'RU', bg: 'linear-gradient(135deg,#1a3a5c,#2d5a87)', name: 'Rahim Uddin', course: 'Math Class 9', progress: 92, last: '2h ago', status: 'active' },
  { initials: 'SJ', bg: 'linear-gradient(135deg,#1a3a1a,#2a5a2a)', name: 'Sadia Jannat', course: 'Geometry Cl. 10', progress: 78, last: '1 day ago', status: 'active' },
  { initials: 'KA', bg: 'linear-gradient(135deg,#2a1040,#5a2a80)', name: 'Karim Ahmed', course: 'HSC Higher Math', progress: 45, last: '3 days ago', status: 'risk' },
  { initials: 'NA', bg: 'linear-gradient(135deg,#2a1a0a,#5a3a1a)', name: 'Nadia Ahmed', course: 'Math Class 9', progress: 88, last: '5h ago', status: 'active' },
  { initials: 'TI', bg: 'linear-gradient(135deg,#0a2a1a,#1a5a3a)', name: 'Tarek Islam', course: 'Geometry Cl. 10', progress: 100, last: '1h ago', status: 'top' },
  { initials: 'MR', bg: 'linear-gradient(135deg,#1a1a3a,#2a2a5a)', name: 'Musa Rahman', course: 'Math Class 9', progress: 67, last: '2 days ago', status: 'active' },
  { initials: 'FA', bg: 'linear-gradient(135deg,#3a1a0a,#6a3a1a)', name: 'Fatema Akter', course: 'HSC Higher Math', progress: 55, last: '4 days ago', status: 'risk' },
  { initials: 'HU', bg: 'linear-gradient(135deg,#0a1a3a,#1a2a5a)', name: 'Habib Uddin', course: 'Geometry Cl. 10', progress: 82, last: '6h ago', status: 'active' },
  { initials: 'RI', bg: 'linear-gradient(135deg,#1a3a2a,#2a5a3a)', name: 'Roksana Islam', course: 'Math Class 9', progress: 95, last: 'Just now', status: 'top' },
  { initials: 'JA', bg: 'linear-gradient(135deg,#2a0a0a,#5a1a1a)', name: 'Jamal Ahmed', course: 'HSC Higher Math', progress: 38, last: '5 days ago', status: 'risk' },
]
</script>

<template>
  <TeacherLayout>
    <div class="tst">

      <!-- TOPBAR -->
      <header class="tst-topbar">
        <div class="tst-left">
          <div class="tst-breadcrumb">
            <Link href="/teacher/dashboard" class="tst-bc-link">Dashboard</Link>
            <span class="tst-bc-sep">›</span>
            <span class="tst-bc-cur">My Students</span>
          </div>
          <div class="tst-title">My Students</div>
        </div>
        <div class="tst-search-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input v-model="search" class="tst-search" type="text" placeholder="Search students…" />
        </div>
        <button class="tst-btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Add Student
        </button>
        <div class="tst-icon tst-notif">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div class="tst-av">AR</div>
      </header>

      <!-- KPI ROW -->
      <div class="tst-kpi-row">
        <div class="tst-kpi tst-kpi-gold">
          <div class="tst-kpi-top">
            <div class="tst-kpi-icon tst-ki-gold">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="tst-trend tst-trend-up">↑ 12%</span>
          </div>
          <div class="tst-kpi-val">324</div>
          <div class="tst-kpi-label">Total Students</div>
          <div class="tst-kpi-sub">Across all courses</div>
        </div>
        <div class="tst-kpi tst-kpi-blue">
          <div class="tst-kpi-top">
            <div class="tst-kpi-icon tst-ki-blue">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <span class="tst-trend tst-trend-up">↑ 5%</span>
          </div>
          <div class="tst-kpi-val">287</div>
          <div class="tst-kpi-label">Active</div>
          <div class="tst-kpi-sub">Last 7 days</div>
        </div>
        <div class="tst-kpi tst-kpi-red">
          <div class="tst-kpi-top">
            <div class="tst-kpi-icon tst-ki-red">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <span class="tst-trend tst-trend-down">Needs attention</span>
          </div>
          <div class="tst-kpi-val">12</div>
          <div class="tst-kpi-label">At Risk</div>
          <div class="tst-kpi-sub">Missed 2+ classes</div>
        </div>
        <div class="tst-kpi tst-kpi-green">
          <div class="tst-kpi-top">
            <div class="tst-kpi-icon tst-ki-green">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <span class="tst-trend tst-trend-up">↑ 8%</span>
          </div>
          <div class="tst-kpi-val">48</div>
          <div class="tst-kpi-label">Top Performers</div>
          <div class="tst-kpi-sub">A/A+ grade</div>
        </div>
      </div>

      <!-- BODY -->
      <div class="tst-body">

        <!-- Students Table -->
        <div class="tst-table-card">
          <div class="tst-card-head">
            <div class="tst-card-title">
              <div class="tst-ci tst-ci-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
              All Students
            </div>
            <div style="display:flex;gap:8px">
              <button class="tst-btn-ghost">Export</button>
              <button class="tst-btn-ghost">Filter</button>
            </div>
          </div>
          <div class="tst-table-wrap">
            <table class="tst-table">
              <thead>
                <tr>
                  <th>Student</th>
                  <th>Course</th>
                  <th>Progress</th>
                  <th>Last Active</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="s in students" :key="s.name">
                  <td>
                    <div class="tst-student-cell">
                      <div class="tst-s-av" :style="{ background: s.bg }">{{ s.initials }}</div>
                      <div>
                        <div class="tst-s-name">{{ s.name }}</div>
                        <div class="tst-s-id">ID: #STU{{ Math.floor(Math.random() * 9000 + 1000) }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="tst-course-cell">{{ s.course }}</td>
                  <td>
                    <div class="tst-prog-wrap">
                      <div class="tst-prog-bar">
                        <div class="tst-prog-fill" :style="{ width: s.progress + '%', background: s.progress >= 90 ? 'var(--green)' : s.progress >= 60 ? 'var(--gold)' : 'var(--red)' }"></div>
                      </div>
                      <span class="tst-prog-val">{{ s.progress }}%</span>
                    </div>
                  </td>
                  <td class="tst-last-cell">{{ s.last }}</td>
                  <td>
                    <span :class="['tst-status', `tst-status-${s.status}`]">
                      {{ s.status === 'active' ? 'Active' : s.status === 'top' ? '⭐ Top' : 'At Risk' }}
                    </span>
                  </td>
                  <td>
                    <div style="display:flex;gap:5px">
                      <button class="tst-action-btn" title="View">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                      </button>
                      <button class="tst-action-btn" title="Message">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="tst-table-footer">
            <span class="tst-table-info">Showing 10 of 324 students</span>
            <div style="display:flex;gap:6px">
              <button class="tst-btn-ghost">← Prev</button>
              <button class="tst-btn-primary">Next →</button>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="tst-sidebar">

          <!-- Filter Card -->
          <div class="tst-card">
            <div class="tst-card-head">
              <div class="tst-card-title">
                <div class="tst-ci tst-ci-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg></div>
                Filters
              </div>
              <button class="tst-btn-ghost-sm">Reset</button>
            </div>
            <div class="tst-card-body">
              <div class="tst-filter-group">
                <div class="tst-filter-label">Course</div>
                <select class="tst-select">
                  <option>All Courses</option>
                  <option>Math Class 9</option>
                  <option>Geometry Cl. 10</option>
                  <option>HSC Higher Math</option>
                </select>
              </div>
              <div class="tst-filter-group">
                <div class="tst-filter-label">Status</div>
                <div class="tst-filter-checks">
                  <label class="tst-check"><input type="checkbox" checked /> All</label>
                  <label class="tst-check"><input type="checkbox" /> Active</label>
                  <label class="tst-check"><input type="checkbox" /> At Risk</label>
                  <label class="tst-check"><input type="checkbox" /> Top</label>
                </div>
              </div>
              <div class="tst-filter-group">
                <div class="tst-filter-label">Progress Range</div>
                <select class="tst-select">
                  <option>All</option>
                  <option>90–100%</option>
                  <option>70–90%</option>
                  <option>Below 70%</option>
                </select>
              </div>
              <button class="tst-btn-primary" style="width:100%;justify-content:center;margin-top:4px">Apply Filters</button>
            </div>
          </div>

          <!-- Activity Chart -->
          <div class="tst-card">
            <div class="tst-card-head">
              <div class="tst-card-title">
                <div class="tst-ci tst-ci-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
                Weekly Activity
              </div>
            </div>
            <div class="tst-card-body">
              <div style="font-size:.76rem;color:var(--muted);margin-bottom:12px">Students active per day</div>
              <div class="tst-chart">
                <div class="tst-bar-group" v-for="(bar, i) in [
                  {day:'Mon', val:180, pct:72},
                  {day:'Tue', val:210, pct:84},
                  {day:'Wed', val:195, pct:78},
                  {day:'Thu', val:225, pct:90},
                  {day:'Fri', val:160, pct:64},
                  {day:'Sat', val:240, pct:96},
                  {day:'Sun', val:80, pct:32},
                ]" :key="i">
                  <div class="tst-bar-val">{{ bar.val }}</div>
                  <div class="tst-bar-outer">
                    <div class="tst-bar-inner" :style="{ height: bar.pct + '%', background: bar.pct > 80 ? 'var(--green)' : 'var(--gold)' }"></div>
                  </div>
                  <div class="tst-bar-day">{{ bar.day }}</div>
                </div>
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:14px">
                <div class="tst-mini-stat"><div class="tst-ms-val" style="color:var(--green)">89%</div><div class="tst-ms-label">Attendance</div></div>
                <div class="tst-mini-stat"><div class="tst-ms-val" style="color:var(--gold)">2.4h</div><div class="tst-ms-label">Avg Time</div></div>
              </div>
            </div>
          </div>

          <!-- Quick Stats -->
          <div class="tst-card">
            <div class="tst-card-head">
              <div class="tst-card-title">
                <div class="tst-ci tst-ci-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg></div>
                Quick Stats
              </div>
            </div>
            <div class="tst-card-body">
              <div class="tst-qstat"><div class="tst-qstat-label">New this week</div><div class="tst-qstat-val" style="color:var(--green)">+12</div></div>
              <div class="tst-qstat"><div class="tst-qstat-label">Completed course</div><div class="tst-qstat-val" style="color:var(--blue)">28</div></div>
              <div class="tst-qstat"><div class="tst-qstat-label">Pending assignments</div><div class="tst-qstat-val" style="color:var(--orange)">38</div></div>
              <div class="tst-qstat"><div class="tst-qstat-label">Avg. score</div><div class="tst-qstat-val" style="color:var(--gold)">81%</div></div>
              <div class="tst-qstat"><div class="tst-qstat-label">Messages unread</div><div class="tst-qstat-val" style="color:var(--purple)">7</div></div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </TeacherLayout>
</template>

<style>
.tst { display: flex; flex-direction: column; min-height: 100vh; }

/* Topbar */
.tst-topbar { position: sticky; top: 0; z-index: 50; background: rgba(250,246,239,.95); backdrop-filter: blur(16px); border-bottom: 1px solid var(--border); padding: 0 32px; height: 64px; display: flex; align-items: center; gap: 12px; }
.tst-left { flex: 1; }
.tst-breadcrumb { font-size: .72rem; color: var(--muted); margin-bottom: 1px; display: flex; align-items: center; gap: 5px; }
.tst-bc-link { color: var(--gold); text-decoration: none; font-weight: 600; }
.tst-bc-sep { color: var(--muted); }
.tst-bc-cur { color: var(--muted); }
.tst-title { font-family: 'Playfair Display', serif; font-size: 1rem; color: var(--ink); }
.tst-search-wrap { display: flex; align-items: center; gap: 8px; background: var(--warm); border: 1px solid var(--border); border-radius: 9px; padding: 6px 12px; }
.tst-search-wrap svg { width: 14px; height: 14px; color: var(--muted); flex-shrink: 0; }
.tst-search { border: none; background: transparent; font-family: 'DM Sans', sans-serif; font-size: .8rem; color: var(--ink); outline: none; width: 180px; }
.tst-search::placeholder { color: var(--muted); }
.tst-icon { width: 38px; height: 38px; border-radius: 10px; background: var(--warm); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s; position: relative; flex-shrink: 0; }
.tst-icon svg { width: 16px; height: 16px; color: var(--muted); }
.tst-notif::after { content: '5'; position: absolute; top: -4px; right: -4px; background: var(--rust); color: #fff; font-size: .58rem; font-weight: 700; border-radius: 20px; padding: 1px 5px; border: 2px solid var(--cream); }
.tst-av { width: 38px; height: 38px; border-radius: 9px; background: linear-gradient(135deg,#1a3a5c,#2d5a87); display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: .95rem; color: #fff; cursor: pointer; border: 2px solid var(--border); flex-shrink: 0; }

/* Buttons */
.tst-btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: 8px 15px; font-family: 'DM Sans', sans-serif; font-size: .8rem; font-weight: 600; color: #fff; background: linear-gradient(135deg,var(--gold),var(--rust)); border: none; border-radius: 9px; cursor: pointer; text-decoration: none; transition: all .18s; }
.tst-btn-primary:hover { box-shadow: 0 4px 12px rgba(184,75,47,.3); transform: translateY(-1px); }
.tst-btn-primary svg { width: 14px; height: 14px; }
.tst-btn-ghost { display: inline-flex; align-items: center; gap: 5px; padding: 6px 13px; font-family: 'DM Sans', sans-serif; font-size: .76rem; font-weight: 600; color: var(--muted); background: var(--card); border: 1.5px solid var(--border); border-radius: 7px; cursor: pointer; transition: all .18s; }
.tst-btn-ghost:hover { border-color: var(--gold-light); color: var(--gold); }
.tst-btn-ghost-sm { padding: 4px 10px; font-size: .72rem; font-family: 'DM Sans', sans-serif; font-weight: 600; color: var(--muted); background: var(--warm); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; transition: all .18s; }

/* KPI */
.tst-kpi-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; padding: 24px 32px 0; }
.tst-kpi { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 18px 20px; animation: fadeUp .4s ease both; transition: all .2s; position: relative; overflow: hidden; }
.tst-kpi:hover { box-shadow: 0 6px 20px rgba(14,11,7,.08); }
.tst-kpi::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 3px 3px 0 0; }
.tst-kpi-gold::after { background: linear-gradient(90deg,var(--gold),var(--gold-light)); }
.tst-kpi-blue::after { background: linear-gradient(90deg,var(--blue),#5a8bbf); }
.tst-kpi-red::after { background: linear-gradient(90deg,var(--red),#e05a5a); }
.tst-kpi-green::after { background: linear-gradient(90deg,var(--green),#5abf8a); }
.tst-kpi-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 10px; }
.tst-kpi-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.tst-kpi-icon svg { width: 16px; height: 16px; }
.tst-ki-gold { background: rgba(201,137,60,.12); color: var(--gold); }
.tst-ki-blue { background: rgba(58,107,158,.12); color: var(--blue); }
.tst-ki-red { background: rgba(197,48,48,.12); color: var(--red); }
.tst-ki-green { background: rgba(58,158,111,.12); color: var(--green); }
.tst-trend { font-size: .7rem; font-weight: 700; border-radius: 20px; padding: 2px 8px; }
.tst-trend-up { background: rgba(58,158,111,.1); color: var(--green); }
.tst-trend-down { background: rgba(197,48,48,.1); color: var(--red); }
.tst-kpi-val { font-family: 'Playfair Display', serif; font-size: 2rem; color: var(--ink); line-height: 1; margin-bottom: 4px; }
.tst-kpi-label { font-size: .74rem; color: var(--muted); font-weight: 500; }
.tst-kpi-sub { font-size: .7rem; color: var(--muted); margin-top: 3px; opacity: .7; }

/* Body */
.tst-body { display: grid; grid-template-columns: 1fr 280px; gap: 18px; padding: 22px 32px; align-items: start; }

/* Table card */
.tst-table-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
.tst-card-head { padding: 14px 20px 12px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.tst-card-title { display: flex; align-items: center; gap: 8px; font-family: 'Playfair Display', serif; font-size: .95rem; color: var(--ink); }
.tst-ci { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
.tst-ci svg { width: 13px; height: 13px; }
.tst-ci-gold { background: rgba(201,137,60,.12); color: var(--gold); }
.tst-ci-blue { background: rgba(58,107,158,.12); color: var(--blue); }
.tst-ci-green { background: rgba(58,158,111,.12); color: var(--green); }
.tst-ci-purple { background: rgba(122,92,191,.12); color: var(--purple); }

.tst-table-wrap { overflow-x: auto; }
.tst-table { width: 100%; border-collapse: collapse; }
.tst-table th { padding: 10px 16px; text-align: left; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); background: var(--warm); border-bottom: 1px solid var(--border); }
.tst-table td { padding: 12px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
.tst-table tbody tr:last-child td { border-bottom: none; }
.tst-table tbody tr:hover { background: rgba(240,232,214,.3); }

.tst-student-cell { display: flex; align-items: center; gap: 10px; }
.tst-s-av { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: .7rem; color: #fff; flex-shrink: 0; }
.tst-s-name { font-size: .82rem; font-weight: 600; color: var(--ink); }
.tst-s-id { font-size: .68rem; color: var(--muted); }
.tst-course-cell { font-size: .78rem; color: var(--muted); white-space: nowrap; }
.tst-last-cell { font-size: .76rem; color: var(--muted); white-space: nowrap; }

.tst-prog-wrap { display: flex; align-items: center; gap: 8px; min-width: 100px; }
.tst-prog-bar { flex: 1; height: 6px; background: var(--warm2); border-radius: 20px; overflow: hidden; }
.tst-prog-fill { height: 100%; border-radius: 20px; transition: width .3s; }
.tst-prog-val { font-size: .72rem; font-weight: 700; color: var(--muted); width: 30px; text-align: right; }

.tst-status { font-size: .68rem; font-weight: 700; border-radius: 20px; padding: 3px 9px; border: 1px solid; white-space: nowrap; }
.tst-status-active { background: rgba(58,158,111,.1); border-color: rgba(58,158,111,.25); color: var(--green); }
.tst-status-top { background: rgba(201,137,60,.1); border-color: rgba(201,137,60,.25); color: var(--gold); }
.tst-status-risk { background: rgba(197,48,48,.1); border-color: rgba(197,48,48,.25); color: var(--red); }

.tst-action-btn { width: 28px; height: 28px; border-radius: 7px; background: var(--warm); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .18s; }
.tst-action-btn svg { width: 13px; height: 13px; color: var(--muted); }
.tst-action-btn:hover { border-color: var(--gold-light); background: rgba(201,137,60,.08); }
.tst-action-btn:hover svg { color: var(--gold); }

.tst-table-footer { padding: 12px 20px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.tst-table-info { font-size: .76rem; color: var(--muted); }

/* Sidebar */
.tst-sidebar { display: flex; flex-direction: column; gap: 16px; }
.tst-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
.tst-card-body { padding: 16px 20px; }

.tst-filter-group { margin-bottom: 14px; }
.tst-filter-group:last-of-type { margin-bottom: 0; }
.tst-filter-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); margin-bottom: 7px; }
.tst-select { width: 100%; padding: 7px 10px; border: 1.5px solid var(--border); border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: .78rem; color: var(--ink); background: var(--warm); outline: none; }
.tst-filter-checks { display: flex; flex-direction: column; gap: 6px; }
.tst-check { display: flex; align-items: center; gap: 7px; font-size: .78rem; color: var(--ink); cursor: pointer; }

/* Chart */
.tst-chart { display: flex; align-items: flex-end; justify-content: space-between; gap: 4px; height: 90px; }
.tst-bar-group { display: flex; flex-direction: column; align-items: center; gap: 3px; flex: 1; height: 100%; }
.tst-bar-val { font-size: .58rem; color: var(--muted); }
.tst-bar-outer { flex: 1; width: 100%; background: var(--warm2); border-radius: 4px 4px 0 0; position: relative; display: flex; align-items: flex-end; overflow: hidden; }
.tst-bar-inner { width: 100%; border-radius: 4px 4px 0 0; transition: height .3s; }
.tst-bar-day { font-size: .62rem; color: var(--muted); font-weight: 600; }

/* Mini stat */
.tst-mini-stat { padding: 10px; background: var(--warm); border-radius: 9px; text-align: center; border: 1px solid var(--border); }
.tst-ms-val { font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--ink); }
.tst-ms-label { font-size: .66rem; color: var(--muted); }

/* Quick stats */
.tst-qstat { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border); }
.tst-qstat:last-child { border-bottom: none; }
.tst-qstat-label { font-size: .78rem; color: var(--muted); }
.tst-qstat-val { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 600; }
</style>
