<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

// Mini sparkline bar heights for each stat card
const spark1 = [30, 45, 35, 60, 50, 75, 65, 88]
const spark2 = [55, 62, 48, 70, 65, 80, 72, 90]
const spark3 = [40, 35, 50, 45, 60, 55, 68, 72]
const spark4 = [80, 70, 85, 60, 75, 65, 78, 68]

// Heatmap: 26 weeks × 7 days
function heatLevel() {
  return Math.floor(Math.random() * 5) // 0-4
}
const heatColors = ['#e8ddc8', 'rgba(201,137,60,.25)', 'rgba(201,137,60,.45)', 'rgba(201,137,60,.7)', '#c9893c']
const heatmap: number[][] = Array.from({ length: 26 }, () => Array.from({ length: 7 }, () => heatLevel()))

// Donut ring segments
const donutSegments = [
  { label: 'Students', count: 960, pct: 74.8, color: '#c9893c' },
  { label: 'Teachers', count: 312, pct: 24.3, color: '#2d8a5e' },
  { label: 'Admins', count: 12, pct: 0.9, color: '#2c5f9e' },
]

// Recent activity feed
const feed = [
  { icon: '👤', text: 'New user <strong>Fatima Rahman</strong> registered', time: '2m ago', bg: 'rgba(201,137,60,.1)' },
  { icon: '📚', text: 'Course <strong>Advanced Calculus</strong> submitted for review', time: '14m ago', bg: 'rgba(44,95,158,.1)' },
  { icon: '💳', text: 'Payment of <strong>৳2,400</strong> completed via bKash', time: '31m ago', bg: 'rgba(45,138,94,.1)' },
  { icon: '⭐', text: 'New 5★ review on <strong>Physics for HSC</strong>', time: '1h ago', bg: 'rgba(201,137,60,.1)' },
  { icon: '🎓', text: '<strong>87 students</strong> enrolled this week', time: '2h ago', bg: 'rgba(107,74,175,.1)' },
]

// Top revenue courses
const topRevenue = [
  { initials: 'AC', color: '#c9893c', name: 'Advanced Calculus', revenue: '৳18.4k' },
  { initials: 'PH', color: '#2d8a5e', name: 'Physics for HSC', revenue: '৳14.1k' },
  { initials: 'EN', color: '#2c5f9e', name: 'English Grammar Pro', revenue: '৳11.7k' },
  { initials: 'CH', color: '#6b4aaf', name: 'Chemistry A-Level', revenue: '৳9.2k' },
  { initials: 'BM', color: '#b83232', name: 'Biology Masterclass', revenue: '৳7.8k' },
]

// Revenue trend bars (normalized heights)
const revBars = [62, 71, 58, 80, 75, 88, 82, 100]
const revMonths = ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar']
</script>

<template>
  <Head title="Dashboard" />
  <AppLayout>
    <!-- Hero -->
    <div class="hero">
      <div class="hero-in">
        <div>
          <div class="hero-ey">OpenBrain · Command Center · March 2026</div>
          <h1 class="hero-h">Platform <em>Overview</em></h1>
          <p class="hero-p">Real-time metrics, pending actions, and critical alerts</p>
        </div>
        <div class="kpis">
          <div class="kpi">
            <div class="kpi-v">$142k</div>
            <div class="kpi-l">MRR</div>
            <div class="kpi-d ku">↑23%</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">1,284</div>
            <div class="kpi-l">Users</div>
            <div class="kpi-d ku">+87</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">68</div>
            <div class="kpi-l">Courses</div>
            <div class="kpi-d ku">+4</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">26</div>
            <div class="kpi-l">Alerts</div>
            <div class="kpi-d kd">Action</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Page Body -->
    <div class="pb">
      <!-- Stat Cards -->
      <div class="g4">
        <!-- Revenue -->
        <div class="sc fu d1">
          <div class="sc-bar" style="background:linear-gradient(90deg,#c9893c,#e8b96a)"></div>
          <div class="sc-row">
            <div class="sc-ico" style="background:rgba(201,137,60,.1);color:#c9893c">
              <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <span class="sc-d sdp">+23%</span>
          </div>
          <div class="sc-val">$142k</div>
          <div class="sc-lbl">Monthly Revenue</div>
          <div class="sc-mini">
            <div v-for="h in spark1" :key="h" class="mb" :style="`height:${h}%;background:rgba(201,137,60,.25)`"></div>
          </div>
        </div>
        <!-- Users -->
        <div class="sc fu d2">
          <div class="sc-bar" style="background:linear-gradient(90deg,#2d8a5e,#3aab76)"></div>
          <div class="sc-row">
            <div class="sc-ico" style="background:rgba(45,138,94,.1);color:#2d8a5e">
              <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="sc-d sdp">+87</span>
          </div>
          <div class="sc-val">1,284</div>
          <div class="sc-lbl">Total Users</div>
          <div class="sc-mini">
            <div v-for="h in spark2" :key="h" class="mb" :style="`height:${h}%;background:rgba(45,138,94,.25)`"></div>
          </div>
        </div>
        <!-- Courses -->
        <div class="sc fu d3">
          <div class="sc-bar" style="background:linear-gradient(90deg,#2c5f9e,#4a80c0)"></div>
          <div class="sc-row">
            <div class="sc-ico" style="background:rgba(44,95,158,.1);color:#2c5f9e">
              <svg viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
            <span class="sc-d sdp">+4</span>
          </div>
          <div class="sc-val">68</div>
          <div class="sc-lbl">Live Courses</div>
          <div class="sc-mini">
            <div v-for="h in spark3" :key="h" class="mb" :style="`height:${h}%;background:rgba(44,95,158,.25)`"></div>
          </div>
        </div>
        <!-- Alerts -->
        <div class="sc fu d4">
          <div class="sc-bar" style="background:linear-gradient(90deg,#b83232,#d45050)"></div>
          <div class="sc-row">
            <div class="sc-ico" style="background:rgba(184,50,50,.1);color:#b83232">
              <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <span class="sc-d sdw">Review</span>
          </div>
          <div class="sc-val">26</div>
          <div class="sc-lbl">Pending Actions</div>
          <div class="sc-mini">
            <div v-for="h in spark4" :key="h" class="mb" :style="`height:${h}%;background:rgba(184,50,50,.22)`"></div>
          </div>
        </div>
      </div>

      <!-- Row 2: Revenue Trend + Action Required -->
      <div class="g3">
        <!-- Revenue Trend (spans 2 cols) -->
        <div class="card fu d1" style="grid-column:span 2">
          <div class="ch">
            <div class="ct">Revenue <em>Trend</em></div>
            <select class="tsel" style="font-size:.72rem;padding:5px 8px">
              <option>8 Months</option>
              <option>12 Months</option>
            </select>
          </div>
          <div class="cb">
            <!-- Bar chart -->
            <div style="display:flex;align-items:flex-end;gap:6px;height:185px;padding:0 4px">
              <div v-for="(h, i) in revBars" :key="i" style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px">
                <div
                  :style="`flex:1;width:100%;border-radius:4px 4px 0 0;background:${h===100 ? 'linear-gradient(180deg,#c9893c,#e8b96a)' : 'rgba(201,137,60,.25)'};height:${h}%;align-self:flex-end`"
                  style="min-height:4px;transition:all .3s"
                ></div>
                <div style="font-size:.6rem;color:#8a7f72;white-space:nowrap">{{ revMonths[i] }}</div>
              </div>
            </div>
            <!-- Summary stats -->
            <div style="display:flex;gap:18px;margin-top:12px;padding-top:11px;border-top:1px solid #e0d8cc;flex-wrap:wrap">
              <div>
                <div style="font-family:'Playfair Display',serif;font-size:1.05rem;color:#0e0b07">$142k</div>
                <div style="font-size:.66rem;color:#8a7f72">Gross This Month</div>
              </div>
              <div>
                <div style="font-family:'Playfair Display',serif;font-size:1.05rem;color:#b83232">$21.3k</div>
                <div style="font-size:.66rem;color:#8a7f72">Platform Fees (15%)</div>
              </div>
              <div>
                <div style="font-family:'Playfair Display',serif;font-size:1.05rem;color:#2d8a5e">$120.7k</div>
                <div style="font-size:.66rem;color:#8a7f72">Teacher Payouts</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Required -->
        <div class="card fu d2">
          <div class="ch">
            <div class="ct">⚠️ Action <em>Required</em></div>
          </div>
          <div class="cb" style="display:flex;flex-direction:column;gap:7px">
            <div class="alert ad">
              <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              <div><strong>7 teachers</strong> awaiting ID verification</div>
            </div>
            <div class="alert aw">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              <div><strong>4 courses</strong> pending approval</div>
            </div>
            <div class="alert aw">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              <div><strong>3 payouts</strong> pending processing</div>
            </div>
            <div class="alert ad">
              <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              <div><strong>12 reports</strong> in moderation queue</div>
            </div>
            <div class="alert ai">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
              <div><strong>2 disputes</strong> on payment transactions</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Row 3: User Breakdown + Recent Activity + Top Revenue -->
      <div class="g3">
        <!-- User Breakdown -->
        <div class="card fu d1">
          <div class="ch"><div class="ct">User <em>Breakdown</em></div></div>
          <div class="cb">
            <!-- SVG donut ring -->
            <div style="display:flex;justify-content:center;margin-bottom:12px">
              <svg width="116" height="116" viewBox="0 0 116 116">
                <!-- Background ring -->
                <circle cx="58" cy="58" r="42" fill="none" stroke="#f0e8d6" stroke-width="16"/>
                <!-- Students 74.8% -->
                <circle cx="58" cy="58" r="42" fill="none" stroke="#c9893c" stroke-width="16"
                  stroke-dasharray="263.9 89.1"
                  stroke-dashoffset="66"
                  transform="rotate(-90 58 58)"/>
                <!-- Teachers 24.3% -->
                <circle cx="58" cy="58" r="42" fill="none" stroke="#2d8a5e" stroke-width="16"
                  stroke-dasharray="85.8 267.2"
                  stroke-dashoffset="-197.9"
                  transform="rotate(-90 58 58)"/>
                <!-- Admins 0.9% -->
                <circle cx="58" cy="58" r="42" fill="none" stroke="#2c5f9e" stroke-width="16"
                  stroke-dasharray="3.2 349.8"
                  stroke-dashoffset="-283.7"
                  transform="rotate(-90 58 58)"/>
                <text x="58" y="55" text-anchor="middle" font-family="Playfair Display,serif" font-size="15" fill="#0e0b07">1,284</text>
                <text x="58" y="68" text-anchor="middle" font-family="DM Sans,sans-serif" font-size="7.5" fill="#8a7f72" font-weight="600" letter-spacing="0.08em">TOTAL USERS</text>
              </svg>
            </div>
            <!-- Legend -->
            <div>
              <div v-for="seg in donutSegments" :key="seg.label" class="rl">
                <div class="rd" :style="`background:${seg.color}`"></div>
                <div style="flex:1;font-size:.76rem;color:#0e0b07">{{ seg.label }}</div>
                <div style="font-family:'Playfair Display',serif;font-size:.82rem;color:#0e0b07">{{ seg.count.toLocaleString() }}</div>
                <div style="font-size:.68rem;color:#8a7f72;margin-left:5px;width:34px;text-align:right">{{ seg.pct }}%</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="card fu d2">
          <div class="ch"><div class="ct">Recent <em>Activity</em></div></div>
          <div class="cb" style="padding:9px 15px">
            <div v-for="item in feed" :key="item.time" class="fi2">
              <div class="fic" :style="`background:${item.bg}`">{{ item.icon }}</div>
              <div class="ftx" v-html="item.text"></div>
              <div class="ftm">{{ item.time }}</div>
            </div>
          </div>
        </div>

        <!-- Top Revenue -->
        <div class="card fu d3">
          <div class="ch"><div class="ct">Top <em>Revenue</em></div></div>
          <div class="cb" style="padding:7px 13px">
            <div v-for="(c, i) in topRevenue" :key="c.name" class="sl-row">
              <div class="sl-rank">{{ i + 1 }}</div>
              <div class="sl-av" :style="`background:${c.color}`">{{ c.initials }}</div>
              <div class="sl-nm">{{ c.name }}</div>
              <div class="sl-v">{{ c.revenue }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Heatmap -->
      <div class="card fu d1">
        <div class="ch">
          <div class="ct">Engagement <em>Heatmap</em> — Last 26 Weeks</div>
          <div style="display:flex;gap:4px;align-items:center;font-size:.67rem;color:#8a7f72">
            Less
            <div style="width:9px;height:9px;border-radius:2px;background:#e8ddc8;margin:0 2px"></div>
            <div style="width:9px;height:9px;border-radius:2px;background:rgba(201,137,60,.3)"></div>
            <div style="width:9px;height:9px;border-radius:2px;background:rgba(201,137,60,.65)"></div>
            <div style="width:9px;height:9px;border-radius:2px;background:#c9893c"></div>
            More
          </div>
        </div>
        <div class="cb">
          <div class="hm-w">
            <div class="hm">
              <div v-for="(week, wi) in heatmap" :key="wi" class="hm-col">
                <div
                  v-for="(level, di) in week"
                  :key="di"
                  class="hm-cell"
                  :style="`background:${heatColors[level]}`"
                  :title="`Week ${wi + 1}, Day ${di + 1}`"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Hero */
.hero{background:#100d09;position:relative;overflow:hidden;padding:28px 26px 26px;flex-shrink:0}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 55% 130% at 100% 50%,rgba(201,137,60,.15),transparent 62%),radial-gradient(ellipse 38% 85% at 0% 0%,rgba(184,75,47,.11),transparent 52%);pointer-events:none}
.hero::after{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.022) 1px,transparent 1px);background-size:22px 22px;pointer-events:none}
.hero-in{position:relative;z-index:1;display:flex;align-items:flex-start;justify-content:space-between;gap:18px;flex-wrap:wrap}
.hero-ey{font-size:.58rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:rgba(250,246,239,.28);margin-bottom:7px}
.hero-h{font-family:'Playfair Display',serif;font-size:1.75rem;color:#faf6ef;line-height:1.1;margin-bottom:5px}
.hero-h em{font-style:italic;color:#e8b96a}
.hero-p{font-size:.78rem;color:rgba(250,246,239,.36);line-height:1.6}
.kpis{display:flex;gap:0;border:1px solid rgba(255,255,255,.08);border-radius:12px;overflow:hidden;align-self:flex-start;flex-shrink:0}
.kpi{padding:11px 17px;border-right:1px solid rgba(255,255,255,.08);text-align:center;min-width:76px}
.kpi:last-child{border-right:none}
.kpi-v{font-family:'Playfair Display',serif;font-size:1.35rem;color:#faf6ef;line-height:1;margin-bottom:2px}
.kpi-l{font-size:.57rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(250,246,239,.26)}
.kpi-d{font-size:.63rem;font-weight:600;margin-top:2px}
.ku{color:#5abf8a}.kd{color:#f87171}.kw{color:#fbbf24}
/* Page body */
.pb{padding:20px 26px;flex:1;display:flex;flex-direction:column;gap:16px}
/* Grids */
.g4{display:grid;grid-template-columns:repeat(4,1fr);gap:13px}
.g3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px}
/* Stat cards */
.sc{background:#fff;border:1.5px solid #e0d8cc;border-radius:12px;padding:15px 17px;position:relative;overflow:hidden}
.sc-bar{position:absolute;top:0;left:0;right:0;height:3px;border-radius:12px 12px 0 0}
.sc-row{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:9px}
.sc-ico{width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center}
.sc-ico svg{width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.sc-d{font-size:.66rem;font-weight:700;padding:2px 8px;border-radius:20px}
.sdp{background:rgba(45,138,94,.1);color:#2d8a5e}
.sdw{background:rgba(196,122,10,.1);color:#c47a0a}
.sc-val{font-family:'Playfair Display',serif;font-size:1.85rem;color:#0e0b07;line-height:1;margin-bottom:3px}
.sc-lbl{font-size:.71rem;color:#8a7f72;font-weight:500}
.sc-mini{display:flex;align-items:flex-end;gap:2px;height:26px;margin-top:9px}
.mb{flex:1;border-radius:2px 2px 0 0;min-width:0}
/* Cards */
.card{background:#fff;border:1.5px solid #e0d8cc;border-radius:13px;overflow:hidden}
.ch{padding:13px 17px 12px;border-bottom:1px solid #e0d8cc;display:flex;align-items:center;justify-content:space-between;gap:9px;flex-wrap:wrap}
.ct{font-family:'Playfair Display',serif;font-size:.95rem;color:#0e0b07}
.ct em{font-style:italic;color:#c9893c}
.cb{padding:15px 17px}
/* Toolbar select */
select.tsel{padding:6px 9px;font-family:'DM Sans',sans-serif;font-size:.77rem;color:#0e0b07;background:#f0e8d6;border:1.5px solid #e0d8cc;border-radius:9px;outline:none;cursor:pointer}
/* Alerts */
.alert{padding:10px 13px;border-radius:9px;font-size:.77rem;display:flex;align-items:flex-start;gap:8px;border:1px solid;line-height:1.5}
.alert svg{width:13px;height:13px;flex-shrink:0;margin-top:1px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.aw{background:rgba(196,122,10,.07);border-color:rgba(196,122,10,.24);color:#7c4a00}
.ad{background:rgba(184,50,50,.07);border-color:rgba(184,50,50,.24);color:#6d1515}
.ai{background:rgba(44,95,158,.07);border-color:rgba(44,95,158,.24);color:#1a3a6a}
/* Ring legend */
.rl{display:flex;align-items:center;gap:7px;padding:5px 0;border-bottom:1px solid #e0d8cc}
.rl:last-child{border-bottom:none}
.rd{width:8px;height:8px;border-radius:50%;flex-shrink:0}
/* Feed */
.fi2{display:flex;gap:9px;padding:9px 0;border-bottom:1px solid #e0d8cc;align-items:flex-start}
.fi2:last-child{border-bottom:none}
.fic{width:27px;height:27px;border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.78rem}
.ftx{font-size:.77rem;color:#0e0b07;line-height:1.5;flex:1}
.ftm{font-size:.64rem;color:#8a7f72;white-space:nowrap;padding-top:1px}
/* Stat list */
.sl-row{display:flex;align-items:center;gap:8px;padding:8px 0;border-bottom:1px solid #e0d8cc}
.sl-row:last-child{border-bottom:none}
.sl-rank{font-family:'Playfair Display',serif;font-size:.82rem;color:#8a7f72;width:16px}
.sl-av{width:25px;height:25px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:.62rem;color:#fff;flex-shrink:0}
.sl-nm{flex:1;font-size:.78rem;font-weight:600;color:#0e0b07;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sl-v{font-family:'Playfair Display',serif;font-size:.88rem;color:#0e0b07}
/* Heatmap */
.hm-w{overflow-x:auto;padding-bottom:3px}
.hm{display:flex;gap:3px}
.hm-col{display:flex;flex-direction:column;gap:3px}
.hm-cell{width:12px;height:12px;border-radius:3px;cursor:pointer;transition:opacity .15s;flex-shrink:0}
.hm-cell:hover{opacity:.65;outline:2px solid #c9893c;outline-offset:1px}
/* Animations */
@keyframes fu{from{opacity:0;transform:translateY(9px)}to{opacity:1;transform:translateY(0)}}
.fu{animation:fu .32s ease both}
.d1{animation-delay:.04s}.d2{animation-delay:.08s}.d3{animation-delay:.13s}.d4{animation-delay:.18s}
/* Responsive */
@media(max-width:1150px){.g4{grid-template-columns:1fr 1fr}.g3{grid-template-columns:1fr 1fr}}
@media(max-width:780px){.g4,.g3{grid-template-columns:1fr}}
</style>
