<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

const logs = [
  { id: 'AUD-28441', actor: 'Super Admin', actorEmail: 'admin@openbrain.io', action: 'TEACHER_APPROVED', target: 'Dr. Nusrat Jahan', targetType: 'Teacher', ip: '103.31.4.22', timestamp: 'Mar 28, 2026 — 09:42 AM', severity: 'Info', color: '#2d8a5e' },
  { id: 'AUD-28440', actor: 'Super Admin', actorEmail: 'admin@openbrain.io', action: 'PAYOUT_APPROVED', target: 'PO-3421 (৳12,480)', targetType: 'Payout', ip: '103.31.4.22', timestamp: 'Mar 28, 2026 — 09:38 AM', severity: 'Info', color: '#2d8a5e' },
  { id: 'AUD-28439', actor: 'Super Admin', actorEmail: 'admin@openbrain.io', action: 'COURSE_PUBLISHED', target: 'HSC Physics Full Course', targetType: 'Course', ip: '103.31.4.22', timestamp: 'Mar 27, 2026 — 04:12 PM', severity: 'Info', color: '#2d8a5e' },
  { id: 'AUD-28438', actor: 'Super Admin', actorEmail: 'admin@openbrain.io', action: 'USER_SUSPENDED', target: 'Mr. Jahangir Kabir', targetType: 'User', ip: '103.31.4.22', timestamp: 'Mar 27, 2026 — 02:55 PM', severity: 'Warning', color: '#c47a0a' },
  { id: 'AUD-28437', actor: 'Super Admin', actorEmail: 'admin@openbrain.io', action: 'COUPON_CREATED', target: 'SUMMER25', targetType: 'Coupon', ip: '103.31.4.22', timestamp: 'Mar 27, 2026 — 11:30 AM', severity: 'Info', color: '#2d8a5e' },
  { id: 'AUD-28436', actor: 'Super Admin', actorEmail: 'admin@openbrain.io', action: 'SETTING_CHANGED', target: 'teacher_commission: 70→72', targetType: 'Setting', ip: '103.31.4.22', timestamp: 'Mar 26, 2026 — 05:18 PM', severity: 'Warning', color: '#c47a0a' },
  { id: 'AUD-28435', actor: 'Super Admin', actorEmail: 'admin@openbrain.io', action: 'REVIEW_HIDDEN', target: 'REV-4817 (Rahim Miah)', targetType: 'Review', ip: '103.31.4.22', timestamp: 'Mar 26, 2026 — 03:02 PM', severity: 'Info', color: '#2d8a5e' },
  { id: 'AUD-28434', actor: 'Super Admin', actorEmail: 'admin@openbrain.io', action: 'FEATURE_FLAG_TOGGLED', target: 'dark_mode: disabled→enabled', targetType: 'Feature Flag', ip: '103.31.4.22', timestamp: 'Mar 25, 2026 — 10:44 AM', severity: 'Info', color: '#2d8a5e' },
  { id: 'AUD-28433', actor: 'System', actorEmail: 'system@openbrain.io', action: 'PAYMENT_REFUNDED', target: 'PAY-88417 (৳1,000)', targetType: 'Payment', ip: '—', timestamp: 'Mar 23, 2026 — 08:11 PM', severity: 'Warning', color: '#b83232' },
  { id: 'AUD-28432', actor: 'Super Admin', actorEmail: 'admin@openbrain.io', action: 'BROADCAST_SENT', target: 'NTF-6421 (12,430 users)', targetType: 'Notification', ip: '103.31.4.22', timestamp: 'Mar 25, 2026 — 06:00 PM', severity: 'Info', color: '#2d8a5e' },
  { id: 'AUD-28431', actor: 'System', actorEmail: 'system@openbrain.io', action: 'MAINTENANCE_MODE_ON', target: 'Platform', targetType: 'System', ip: '—', timestamp: 'Mar 22, 2026 — 12:00 AM', severity: 'Critical', color: '#b83232' },
  { id: 'AUD-28430', actor: 'System', actorEmail: 'system@openbrain.io', action: 'MAINTENANCE_MODE_OFF', target: 'Platform', targetType: 'System', ip: '—', timestamp: 'Mar 22, 2026 — 02:00 AM', severity: 'Critical', color: '#b83232' },
]

function severityClass(s: string) {
  if (s === 'Critical') return 'bg-r'
  if (s === 'Warning') return 'bg-o'
  return 'bg-g'
}

function actionColor(action: string) {
  if (action.includes('SUSPEND') || action.includes('HIDDEN') || action.includes('REFUND') || action.includes('MAINTENANCE_MODE_ON')) return '#b83232'
  if (action.includes('SETTING') || action.includes('TOGGLED')) return '#c47a0a'
  return '#2d8a5e'
}
</script>

<template>
  <Head title="Audit Log" />
  <AppLayout>
    <!-- Hero -->
    <div style="background:#100d09;position:relative;overflow:hidden;padding:28px 26px 26px;flex-shrink:0;">
      <div style="position:absolute;inset:0;background:radial-gradient(ellipse 55% 130% at 100% 50%,rgba(201,137,60,.15),transparent 62%),radial-gradient(ellipse 38% 85% at 0% 0%,rgba(184,75,47,.11),transparent 52%);pointer-events:none;"></div>
      <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.022) 1px,transparent 1px);background-size:22px 22px;pointer-events:none;"></div>
      <div style="position:relative;z-index:1;display:flex;align-items:flex-start;justify-content:space-between;gap:18px;flex-wrap:wrap;">
        <div>
          <div class="hero-ey">Compliance & Traceability</div>
          <h1 class="hero-h">Audit <em>Log</em></h1>
          <p class="hero-p">Immutable record of all admin actions, system events, and security-critical operations.</p>
        </div>
        <div class="kpis">
          <div class="kpi">
            <div class="kpi-v">28.4k</div>
            <div class="kpi-l">Total Events</div>
            <div class="kpi-d ku">this month</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">12</div>
            <div class="kpi-l">Today</div>
            <div class="kpi-d kw">events</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">2</div>
            <div class="kpi-l">Critical</div>
            <div class="kpi-d kd">warnings</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">100%</div>
            <div class="kpi-l">Retention</div>
            <div class="kpi-d kw">90 days</div>
          </div>
        </div>
      </div>
    </div>

    <div class="pb">
      <div class="card">
        <div class="ch">
          <span class="ct">Event <em>Log</em></span>
          <div class="tb">
            <div class="ts">
              <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <input placeholder="Search events, actors, targets…" />
            </div>
            <select class="tsel">
              <option>All Severity</option>
              <option>Critical</option>
              <option>Warning</option>
              <option>Info</option>
            </select>
            <select class="tsel">
              <option>All Types</option>
              <option>User</option>
              <option>Course</option>
              <option>Payment</option>
              <option>System</option>
              <option>Setting</option>
            </select>
            <select class="tsel">
              <option>All Time</option>
              <option>Today</option>
              <option>Last 7 days</option>
              <option>Last 30 days</option>
            </select>
            <button class="btn bg2 bsm">
              <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Export
            </button>
          </div>
        </div>
        <div class="cb" style="padding:0;">
          <div class="tw">
            <table class="dt">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Actor</th>
                  <th>Action</th>
                  <th>Target</th>
                  <th>Type</th>
                  <th>IP Address</th>
                  <th>Timestamp</th>
                  <th>Severity</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="log in logs" :key="log.id">
                  <td style="font-family:'DM Mono',monospace;font-size:.7rem;color:#8a7f72;">{{ log.id }}</td>
                  <td>
                    <div class="uc">
                      <div class="uav" :style="{ background: log.color }">{{ log.actor.charAt(0) }}</div>
                      <div>
                        <div class="unm">{{ log.actor }}</div>
                        <div class="uem">{{ log.actorEmail }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span :style="{ fontFamily: '\'DM Mono\',monospace', fontSize: '.72rem', fontWeight: '700', color: actionColor(log.action) }">{{ log.action }}</span>
                  </td>
                  <td style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:600;">{{ log.target }}</td>
                  <td><span class="badge bg-b">{{ log.targetType }}</span></td>
                  <td style="font-family:'DM Mono',monospace;font-size:.7rem;color:#8a7f72;">{{ log.ip }}</td>
                  <td style="color:#8a7f72;white-space:nowrap;font-size:.73rem;">{{ log.timestamp }}</td>
                  <td>
                    <span class="badge" :class="severityClass(log.severity)">{{ log.severity }}</span>
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
