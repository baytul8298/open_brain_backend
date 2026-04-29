<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

const reports = [
  { id: 'RPT-1241', reporter: 'Arif Hossain', target: 'Review by Karim Uddin', type: 'Inappropriate Content', detail: 'The review contains offensive language and personal attacks against the teacher.', submitted: '2 hrs ago', priority: 'High', status: 'Open', color: '#2c5f9e' },
  { id: 'RPT-1240', reporter: 'Tania Begum', target: 'Comment in HSC Physics Q&A', type: 'Spam', detail: 'User posting repeated promotional links to external websites in the discussion thread.', submitted: '4 hrs ago', priority: 'Medium', status: 'Open', color: '#c9893c' },
  { id: 'RPT-1239', reporter: 'Ms. Khanam (Teacher)', target: 'Student Nadia Akter', type: 'Harassment', detail: 'Student sent threatening messages via the platform messaging system.', submitted: '6 hrs ago', priority: 'High', status: 'Under Review', color: '#b83232' },
  { id: 'RPT-1238', reporter: 'Rahim Miah', target: 'Course: ICT Basics', type: 'Misleading Content', detail: 'Course description promises live sessions but none have been scheduled.', submitted: '1 day ago', priority: 'Low', status: 'Resolved', color: '#2d8a5e' },
  { id: 'RPT-1237', reporter: 'Sana Islam', target: 'Review by anonymous', type: 'Fake Review', detail: 'Suspected coordinated fake 5-star reviews with identical wording on multiple courses.', submitted: '1 day ago', priority: 'Medium', status: 'Under Review', color: '#6b4aaf' },
  { id: 'RPT-1236', reporter: 'Fahim Chowdhury', target: 'Lesson video #14', type: 'Copyright', detail: 'Video contains copyrighted textbook pages photographed without permission.', submitted: '2 days ago', priority: 'High', status: 'Open', color: '#c47a0a' },
]
</script>

<template>
  <Head title="Moderation" />
  <AppLayout>
    <!-- Hero -->
    <div style="background:#100d09;position:relative;overflow:hidden;padding:28px 26px 26px;flex-shrink:0;">
      <div style="position:absolute;inset:0;background:radial-gradient(ellipse 55% 130% at 100% 50%,rgba(201,137,60,.15),transparent 62%),radial-gradient(ellipse 38% 85% at 0% 0%,rgba(184,75,47,.11),transparent 52%);pointer-events:none;"></div>
      <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.022) 1px,transparent 1px);background-size:22px 22px;pointer-events:none;"></div>
      <div style="position:relative;z-index:1;display:flex;align-items:flex-start;justify-content:space-between;gap:18px;flex-wrap:wrap;">
        <div>
          <div class="hero-ey">Trust & Safety</div>
          <h1 class="hero-h">Content <em>Moderation</em></h1>
          <p class="hero-p">Review user-submitted reports, investigate violations, and enforce platform policies.</p>
        </div>
        <div class="kpis">
          <div class="kpi">
            <div class="kpi-v">6</div>
            <div class="kpi-l">Open</div>
            <div class="kpi-d kd">+3</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">3</div>
            <div class="kpi-l">High Priority</div>
            <div class="kpi-d kd">urgent</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">2</div>
            <div class="kpi-l">In Review</div>
            <div class="kpi-d kw">active</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">184</div>
            <div class="kpi-l">Resolved</div>
            <div class="kpi-d ku">this month</div>
          </div>
        </div>
      </div>
    </div>

    <div class="pb">
      <div class="card">
        <div class="ch">
          <span class="ct">Moderation <em>Reports</em></span>
          <div class="tb">
            <div class="ts">
              <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <input placeholder="Search reports…" />
            </div>
            <select class="tsel">
              <option>All Status</option>
              <option>Open</option>
              <option>Under Review</option>
              <option>Resolved</option>
            </select>
            <select class="tsel">
              <option>All Priority</option>
              <option>High</option>
              <option>Medium</option>
              <option>Low</option>
            </select>
            <select class="tsel">
              <option>All Types</option>
              <option>Inappropriate Content</option>
              <option>Spam</option>
              <option>Harassment</option>
              <option>Copyright</option>
              <option>Misleading Content</option>
              <option>Fake Review</option>
            </select>
          </div>
        </div>
        <div class="cb" style="padding:0;">
          <div class="tw">
            <table class="dt">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Reporter</th>
                  <th>Target</th>
                  <th>Type</th>
                  <th>Details</th>
                  <th>Submitted</th>
                  <th>Priority</th>
                  <th>Status</th>
                  <th style="text-align:right;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="r in reports" :key="r.id">
                  <td style="font-family:'DM Mono',monospace;font-size:.72rem;color:#8a7f72;">{{ r.id }}</td>
                  <td>
                    <div class="uc">
                      <div class="uav" :style="{ background: r.color }">{{ r.reporter.charAt(0) }}</div>
                      <div class="unm">{{ r.reporter }}</div>
                    </div>
                  </td>
                  <td style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:600;">{{ r.target }}</td>
                  <td>
                    <span class="badge"
                      :class="r.type === 'Harassment' ? 'bg-r' : r.type === 'Spam' ? 'bg-o' : r.type === 'Copyright' ? 'bg-p' : 'bg-b'">
                      {{ r.type }}
                    </span>
                  </td>
                  <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#8a7f72;font-size:.74rem;">{{ r.detail }}</td>
                  <td style="color:#8a7f72;white-space:nowrap;">{{ r.submitted }}</td>
                  <td>
                    <span class="badge"
                      :class="r.priority === 'High' ? 'bg-r' : r.priority === 'Medium' ? 'bg-o' : 'bg-m'">
                      {{ r.priority }}
                    </span>
                  </td>
                  <td>
                    <span class="badge"
                      :class="r.status === 'Open' ? 'bg-r' : r.status === 'Under Review' ? 'bg-o' : 'bg-g'">
                      {{ r.status }}
                    </span>
                  </td>
                  <td>
                    <div class="ar">
                      <button class="btn bbl bsm">Review</button>
                      <button class="btn bok bxs">Resolve</button>
                    </div>
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
