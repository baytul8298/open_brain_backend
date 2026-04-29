<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

const subjects = [
  { name: 'Mathematics', revenue: '৳42k', pct: 82 },
  { name: 'Physics', revenue: '৳31k', pct: 61 },
  { name: 'Chemistry', revenue: '৳27k', pct: 53 },
  { name: 'Biology', revenue: '৳22k', pct: 43 },
  { name: 'English', revenue: '৳14k', pct: 27 },
  { name: 'ICT', revenue: '৳6k', pct: 12 },
]

const topCourses = [
  { name: 'HSC Physics Full Course', teacher: 'Dr. Rahim', students: 1240, revenue: '৳18,600', rating: '4.9', status: 'Active' },
  { name: 'SSC Math Master Class', teacher: 'Ms. Khanam', students: 980, revenue: '৳14,700', rating: '4.8', status: 'Active' },
  { name: 'English Grammar Pro', teacher: 'Mr. Hasan', students: 760, revenue: '৳9,120', rating: '4.7', status: 'Active' },
  { name: 'Chemistry Crash Course', teacher: 'Dr. Sadia', students: 640, revenue: '৳7,680', rating: '4.6', status: 'Active' },
  { name: 'Biology for HSC', teacher: 'Ms. Parvin', students: 520, revenue: '৳6,240', rating: '4.5', status: 'Active' },
  { name: 'ICT Basics', teacher: 'Mr. Kabir', students: 310, revenue: '৳3,100', rating: '4.2', status: 'Paused' },
]

const growthBars = [
  { month: 'Oct', pct: 45 },
  { month: 'Nov', pct: 58 },
  { month: 'Dec', pct: 52 },
  { month: 'Jan', pct: 67 },
  { month: 'Feb', pct: 74 },
  { month: 'Mar', pct: 91 },
]
</script>

<template>
  <Head title="Analytics" />
  <AppLayout>
    <!-- Hero -->
    <div style="background:#100d09;position:relative;overflow:hidden;padding:28px 26px 26px;flex-shrink:0;">
      <div style="position:absolute;inset:0;background:radial-gradient(ellipse 55% 130% at 100% 50%,rgba(201,137,60,.15),transparent 62%),radial-gradient(ellipse 38% 85% at 0% 0%,rgba(184,75,47,.11),transparent 52%);pointer-events:none;"></div>
      <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.022) 1px,transparent 1px);background-size:22px 22px;pointer-events:none;"></div>
      <div style="position:relative;z-index:1;display:flex;align-items:flex-start;justify-content:space-between;gap:18px;flex-wrap:wrap;">
        <div>
          <div class="hero-ey">Platform Intelligence</div>
          <h1 class="hero-h">Platform <em>Analytics</em></h1>
          <p class="hero-p">Comprehensive metrics, revenue insights, and growth tracking across the platform.</p>
        </div>
        <div class="kpis">
          <div class="kpi">
            <div class="kpi-v">৳142k</div>
            <div class="kpi-l">MRR</div>
            <div class="kpi-d ku">+18%</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">89%</div>
            <div class="kpi-l">Retention</div>
            <div class="kpi-d ku">+3%</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">4.8★</div>
            <div class="kpi-l">Avg Rating</div>
            <div class="kpi-d kw">stable</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">12.4k</div>
            <div class="kpi-l">Active Users</div>
            <div class="kpi-d ku">+9%</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Body -->
    <div class="pb">
      <div class="g2">
        <!-- User Growth Card -->
        <div class="card">
          <div class="ch">
            <span class="ct">User <em>Growth</em></span>
            <span class="badge bg-g">Last 6 Months</span>
          </div>
          <div class="cb">
            <div style="display:flex;align-items:flex-end;gap:10px;height:160px;padding-bottom:8px;">
              <div v-for="bar in growthBars" :key="bar.month" style="flex:1;display:flex;flex-direction:column;align-items:center;gap:5px;height:100%;justify-content:flex-end;">
                <div style="font-size:.6rem;color:#8a7f72;">{{ Math.round(bar.pct * 1.36) }}%</div>
                <div :style="{ height: bar.pct + '%', background: 'linear-gradient(180deg,#c9893c,#b84b2f)', borderRadius: '5px 5px 0 0', width: '100%', minHeight: '8px' }"></div>
                <div style="font-size:.62rem;color:#8a7f72;white-space:nowrap;">{{ bar.month }}</div>
              </div>
            </div>
            <div style="margin-top:12px;padding-top:10px;border-top:1px solid #e0d8cc;display:flex;gap:16px;">
              <div>
                <div style="font-family:'Playfair Display',serif;font-size:1.1rem;color:#0e0b07;">12,430</div>
                <div style="font-size:.63rem;color:#8a7f72;text-transform:uppercase;letter-spacing:.08em;">Total Users</div>
              </div>
              <div>
                <div style="font-family:'Playfair Display',serif;font-size:1.1rem;color:#2d8a5e;">+1,842</div>
                <div style="font-size:.63rem;color:#8a7f72;text-transform:uppercase;letter-spacing:.08em;">This Month</div>
              </div>
              <div>
                <div style="font-family:'Playfair Display',serif;font-size:1.1rem;color:#c9893c;">91%</div>
                <div style="font-size:.63rem;color:#8a7f72;text-transform:uppercase;letter-spacing:.08em;">Activation</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Revenue by Subject Card -->
        <div class="card">
          <div class="ch">
            <span class="ct">Revenue by <em>Subject</em></span>
            <span class="badge bg-o">March 2026</span>
          </div>
          <div class="cb">
            <div v-for="item in subjects" :key="item.name" style="display:flex;align-items:center;gap:9px;padding:7px 0;border-bottom:1px solid #e0d8cc;">
              <div style="font-size:.74rem;color:#8a7f72;width:90px;white-space:nowrap;">{{ item.name }}</div>
              <div style="flex:1;height:8px;background:#e8ddc8;border-radius:20px;overflow:hidden;">
                <div :style="{ height: '100%', borderRadius: '20px', background: '#c9893c', width: item.pct + '%' }"></div>
              </div>
              <div style="font-family:'Playfair Display',serif;font-size:.82rem;color:#0e0b07;width:40px;text-align:right;">{{ item.revenue }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Performing Courses -->
      <div class="card">
        <div class="ch">
          <span class="ct">Top Performing <em>Courses</em></span>
          <div class="tb">
            <div class="ts">
              <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <input placeholder="Search courses…" />
            </div>
            <select class="tsel">
              <option>All Subjects</option>
              <option>Mathematics</option>
              <option>Physics</option>
              <option>Chemistry</option>
            </select>
          </div>
        </div>
        <div class="cb" style="padding:0;">
          <div class="tw">
            <table class="dt">
              <thead>
                <tr>
                  <th>Course</th>
                  <th>Teacher</th>
                  <th>Students</th>
                  <th>Revenue/mo</th>
                  <th>Rating</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="c in topCourses" :key="c.name">
                  <td><span style="font-weight:600;">{{ c.name }}</span></td>
                  <td>{{ c.teacher }}</td>
                  <td>{{ c.students.toLocaleString() }}</td>
                  <td><span style="font-family:'Playfair Display',serif;">{{ c.revenue }}</span></td>
                  <td><span style="color:#c9893c;font-weight:700;">{{ c.rating }}★</span></td>
                  <td>
                    <span class="badge" :class="c.status === 'Active' ? 'bg-g' : 'bg-o'">{{ c.status }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.hero-ey{font-size:.58rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:rgba(250,246,239,.28);margin-bottom:7px}
.hero-h{font-family:'Playfair Display',serif;font-size:1.75rem;color:#faf6ef;line-height:1.1;margin-bottom:5px}
.hero-h em{font-style:italic;color:#e8b96a}
.hero-p{font-size:.78rem;color:rgba(250,246,239,.36);line-height:1.6}
.kpis{display:flex;gap:0;border:1px solid rgba(255,255,255,.08);border-radius:12px;overflow:hidden;align-self:flex-start}
.kpi{padding:11px 17px;border-right:1px solid rgba(255,255,255,.08);text-align:center;min-width:76px}
.kpi:last-child{border-right:none}
.kpi-v{font-family:'Playfair Display',serif;font-size:1.35rem;color:#faf6ef;line-height:1;margin-bottom:2px}
.kpi-l{font-size:.57rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(250,246,239,.26)}
.kpi-d{font-size:.63rem;font-weight:600;margin-top:2px}
.ku{color:#5abf8a}.kd{color:#f87171}.kw{color:#fbbf24}
.pb{padding:20px 26px;display:flex;flex-direction:column;gap:16px}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:15px}
.g3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px}
.g4{display:grid;grid-template-columns:repeat(4,1fr);gap:13px}
.card{background:#fff;border:1.5px solid #e0d8cc;border-radius:13px;overflow:hidden}
.ch{padding:13px 17px 12px;border-bottom:1px solid #e0d8cc;display:flex;align-items:center;justify-content:space-between;gap:9px;flex-wrap:wrap}
.ct{font-family:'Playfair Display',serif;font-size:.95rem;color:#0e0b07}
.ct em{font-style:italic;color:#c9893c}
.cb{padding:15px 17px}
.tb{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.ts{display:flex;align-items:center;gap:6px;background:#f0e8d6;border:1.5px solid #e0d8cc;border-radius:9px;padding:6px 10px;flex:1;min-width:170px;transition:border-color .16s}
.ts:focus-within{border-color:#e8b96a}
.ts svg{width:12px;height:12px;stroke:#8a7f72;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0}
.ts input{border:none;background:none;font-family:'DM Sans',sans-serif;font-size:.78rem;color:#0e0b07;outline:none;flex:1;min-width:0}
.ts input::placeholder{color:#8a7f72}
select.tsel{padding:6px 9px;font-family:'DM Sans',sans-serif;font-size:.77rem;color:#0e0b07;background:#f0e8d6;border:1.5px solid #e0d8cc;border-radius:9px;outline:none;cursor:pointer}
select.tsel:focus{border-color:#e8b96a}
.btn{display:inline-flex;align-items:center;gap:5px;padding:6px 14px;font-family:'DM Sans',sans-serif;font-size:.77rem;font-weight:600;border-radius:9px;border:1.5px solid;cursor:pointer;transition:all .16s;white-space:nowrap;background:none}
.btn svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.bp{background:linear-gradient(135deg,#c9893c,#b84b2f)!important;border-color:transparent;color:#fff}
.bg2{background:#f0e8d6!important;border-color:#e0d8cc;color:#8a7f72}
.bd{background:rgba(184,50,50,.07)!important;border-color:rgba(184,50,50,.22);color:#b83232}
.bok{background:rgba(45,138,94,.07)!important;border-color:rgba(45,138,94,.22);color:#2d8a5e}
.bbl{background:rgba(44,95,158,.07)!important;border-color:rgba(44,95,158,.22);color:#2c5f9e}
.bsm{padding:4px 10px;font-size:.71rem}
.bxs{padding:3px 8px;font-size:.67rem}
.tw{overflow-x:auto}
.dt{width:100%;border-collapse:collapse}
.dt th{font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#8a7f72;padding:9px 13px;text-align:left;border-bottom:2px solid #e0d8cc;background:#f0e8d6;white-space:nowrap}
.dt td{padding:10px 13px;border-bottom:1px solid #e0d8cc;vertical-align:middle;font-size:.79rem;color:#0e0b07}
.dt tr:last-child td{border-bottom:none}
.dt tbody tr:hover td{background:rgba(240,232,214,.32)}
.ar{display:flex;gap:5px;align-items:center;justify-content:flex-end}
.badge{display:inline-flex;align-items:center;gap:4px;font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:20px;border:1px solid;white-space:nowrap}
.badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.6}
.bg-g{color:#2d8a5e;border-color:rgba(45,138,94,.28);background:rgba(45,138,94,.07)}
.bg-r{color:#b83232;border-color:rgba(184,50,50,.28);background:rgba(184,50,50,.07)}
.bg-o{color:#c47a0a;border-color:rgba(196,122,10,.28);background:rgba(196,122,10,.07)}
.bg-b{color:#2c5f9e;border-color:rgba(44,95,158,.28);background:rgba(44,95,158,.07)}
.bg-p{color:#6b4aaf;border-color:rgba(107,74,175,.28);background:rgba(107,74,175,.07)}
.bg-m{color:#8a7f72;border-color:#e0d8cc;background:#f0e8d6}
.uc{display:flex;align-items:center;gap:8px}
.uav{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:.67rem;color:#fff;flex-shrink:0}
.unm{font-weight:600;font-size:.79rem;color:#0e0b07}
.uem{font-size:.68rem;color:#8a7f72;margin-top:1px}
.prog{height:5px;background:#e8ddc8;border-radius:20px;overflow:hidden;flex:1;min-width:50px}
.pf{height:100%;border-radius:20px;background:#2d8a5e}
.sr{display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid #e0d8cc;gap:11px}
.sr:last-child{border-bottom:none}
.sr-l{flex:1;min-width:0}
.sr-lbl{font-size:.81rem;font-weight:600;color:#0e0b07}
.sr-desc{font-size:.7rem;color:#8a7f72;margin-top:2px;line-height:1.5}
.sr-r{display:flex;align-items:center;gap:7px;flex-shrink:0}
.fg{display:flex;flex-direction:column;gap:4px}
.fl{font-size:.72rem;font-weight:600;color:#0e0b07}
.fi,.fs,.fta{width:100%;padding:8px 11px;font-family:'DM Sans',sans-serif;font-size:.8rem;color:#0e0b07;background:#f0e8d6;border:1.5px solid #e0d8cc;border-radius:9px;outline:none;transition:border-color .16s}
.fi:focus,.fs:focus,.fta:focus{border-color:#e8b96a;background:#faf6ef}
.fta{resize:vertical;min-height:78px}
.frow{display:grid;grid-template-columns:1fr 1fr;gap:11px}
.tgl{position:relative;display:inline-block;width:36px;height:21px;flex-shrink:0}
.tgl input{opacity:0;width:0;height:0;position:absolute}
.tgl-s{position:absolute;inset:0;background:#e8ddc8;border-radius:21px;cursor:pointer;transition:.18s;border:1.5px solid #e0d8cc}
.tgl-s::before{content:'';position:absolute;width:13px;height:13px;border-radius:50%;background:#fff;bottom:2px;left:2px;transition:.18s;box-shadow:0 1px 3px rgba(0,0,0,.18)}
.tgl input:checked+.tgl-s{background:#2d8a5e;border-color:rgba(45,138,94,.38)}
.tgl input:checked+.tgl-s::before{transform:translateX(15px)}
.fi-sm{width:55px!important;text-align:center}
@media(max-width:1150px){.g4{grid-template-columns:1fr 1fr}.g3{grid-template-columns:1fr 1fr}}
@media(max-width:780px){.g2,.g3,.g4{grid-template-columns:1fr}}
</style>
