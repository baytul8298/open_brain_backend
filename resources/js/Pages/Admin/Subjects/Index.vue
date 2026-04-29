<script setup lang="ts">
import { ref, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SubjectModal from '@/Components/Subjects/SubjectModal.vue'

interface Subject {
  id: number
  name: string
  slug: string
  name_bn: string | null
  color: string | null
  icon: string | null
  parent_id: number | null
  sort_order: number
  is_active: boolean
  courses_count: number
  teachers_count: number
  students_count: number
}

interface Props {
  subjects: {
    data: Subject[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
  }
  all_subjects: { id: number; name: string; color: string | null }[]
  stats: { total: number; active: number; inactive: number; courses: number }
  filters: { search: string | null; status: string | null; perPage: number }
}

const props = defineProps<Props>()

const showModal       = ref(false)
const modalMode       = ref<'create' | 'edit'>('create')
const selectedSubject = ref<Subject | null>(null)
const showDeleteConfirm = ref(false)
const subjectToDelete   = ref<Subject | null>(null)
const searchQuery = ref(props.filters.search || '')
const statusFilter = ref(props.filters.status || '')

let searchTimeout: ReturnType<typeof setTimeout>

function applyFilters() {
  const params: Record<string, string> = {}
  if (searchQuery.value) params.search = searchQuery.value
  if (statusFilter.value) params.status = statusFilter.value
  router.get('/subjects', params, { preserveState: true, preserveScroll: true })
}

watch(searchQuery, () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(applyFilters, 400)
})

watch(statusFilter, applyFilters)

function openCreate() {
  modalMode.value = 'create'
  selectedSubject.value = null
  showModal.value = true
}

function openEdit(s: Subject) {
  modalMode.value = 'edit'
  selectedSubject.value = s
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  selectedSubject.value = null
}

function askDelete(s: Subject) {
  subjectToDelete.value = s
  showDeleteConfirm.value = true
}

function doDelete() {
  if (!subjectToDelete.value) return
  router.delete(`/subjects/${subjectToDelete.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteConfirm.value = false
      subjectToDelete.value = null
    },
  })
}

function goPage(page: number) {
  const params: Record<string, string> = { page: String(page) }
  if (searchQuery.value) params.search = searchQuery.value
  if (statusFilter.value) params.status = statusFilter.value
  router.get('/subjects', params, { preserveState: true, preserveScroll: true })
}
</script>

<template>
  <Head title="Subjects" />
  <AppLayout>
    <!-- Hero -->
    <div style="background:#100d09;position:relative;overflow:hidden;padding:28px 26px 26px;flex-shrink:0;">
      <div style="position:absolute;inset:0;background:radial-gradient(ellipse 55% 130% at 100% 50%,rgba(201,137,60,.15),transparent 62%),radial-gradient(ellipse 38% 85% at 0% 0%,rgba(184,75,47,.11),transparent 52%);pointer-events:none;"></div>
      <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.022) 1px,transparent 1px);background-size:22px 22px;pointer-events:none;"></div>
      <div style="position:relative;z-index:1;display:flex;align-items:flex-start;justify-content:space-between;gap:18px;flex-wrap:wrap;">
        <div>
          <div class="hero-ey">Taxonomy</div>
          <h1 class="hero-h">Subject <em>Categories</em></h1>
          <p class="hero-p">Organise the curriculum by subject, assign colours, and track enrolment depth per category.</p>
        </div>
        <div class="kpis">
          <div class="kpi">
            <div class="kpi-v">{{ stats.total }}</div>
            <div class="kpi-l">Subjects</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">{{ stats.courses }}</div>
            <div class="kpi-l">Courses</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">{{ stats.active }}</div>
            <div class="kpi-l">Active</div>
            <div class="kpi-d ku">live</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">{{ stats.inactive }}</div>
            <div class="kpi-l">Inactive</div>
          </div>
        </div>
      </div>
    </div>

    <div class="pb">
      <div class="card">
        <div class="ch">
          <span class="ct">All <em>Subjects</em></span>
          <div class="tb">
            <div class="ts">
              <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <input v-model="searchQuery" placeholder="Search subjects…" />
            </div>
            <select class="tsel" v-model="statusFilter">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
            <button class="btn bp bsm" @click="openCreate">
              <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Add Subject
            </button>
          </div>
        </div>

        <div class="cb" style="padding:0;">
          <!-- Empty state -->
          <div v-if="subjects.data.length === 0" style="padding:40px;text-align:center;color:#8a7f72;font-size:.82rem;">
            No subjects found.
          </div>

          <div v-else class="tw">
            <table class="dt">
              <thead>
                <tr>
                  <th>Subject</th>
                  <th>Slug</th>
                  <th>Colour</th>
                  <th>Courses</th>
                  <th>Teachers</th>
                  <th>Students</th>
                  <th>Status</th>
                  <th style="text-align:right;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="s in subjects.data" :key="s.id">
                  <td>
                    <div style="display:flex;align-items:center;gap:9px;">
                      <div :style="{ width:'28px', height:'28px', borderRadius:'7px', background: s.color ?? '#c9893c', flexShrink:0 }"></div>
                      <div>
                        <div style="font-weight:600;">{{ s.name }}</div>
                        <div v-if="s.name_bn" style="font-size:.68rem;color:#8a7f72;">{{ s.name_bn }}</div>
                      </div>
                    </div>
                  </td>
                  <td style="font-family:'DM Mono',monospace;font-size:.72rem;color:#8a7f72;">{{ s.slug }}</td>
                  <td>
                    <div style="display:flex;align-items:center;gap:6px;">
                      <div :style="{ width:'14px', height:'14px', borderRadius:'4px', background: s.color ?? '#ccc', border:'1px solid rgba(0,0,0,.1)' }"></div>
                      <span style="font-family:'DM Mono',monospace;font-size:.72rem;color:#8a7f72;">{{ s.color ?? '—' }}</span>
                    </div>
                  </td>
                  <td>{{ s.courses_count }}</td>
                  <td>{{ s.teachers_count }}</td>
                  <td>{{ Number(s.students_count).toLocaleString() }}</td>
                  <td>
                    <span class="badge" :class="s.is_active ? 'bg-g' : 'bg-m'">
                      {{ s.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td>
                    <div class="ar">
                      <button class="btn bg2 bxs" @click="openEdit(s)">Edit</button>
                      <button class="btn bd bxs" @click="askDelete(s)">Delete</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="subjects.last_page > 1" style="display:flex;align-items:center;justify-content:space-between;padding:11px 17px;border-top:1px solid #e0d8cc;font-size:.75rem;color:#8a7f72;">
          <span>Showing {{ subjects.from }}–{{ subjects.to }} of {{ subjects.total }}</span>
          <div style="display:flex;gap:4px;">
            <button v-for="p in subjects.last_page" :key="p"
              @click="goPage(p)"
              class="btn bxs"
              :class="p === subjects.current_page ? 'bp' : 'bg2'"
              style="min-width:28px;justify-content:center;">
              {{ p }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Subject Modal (create / edit) -->
    <SubjectModal
      :open="showModal"
      :mode="modalMode"
      :subject="selectedSubject"
      :all-subjects="all_subjects"
      @close="closeModal"
      @success="closeModal"
    />

    <!-- Delete Confirmation -->
    <Teleport to="body">
      <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="showDeleteConfirm = false">
          <div style="background:#faf6ef;border:1.5px solid #e0d8cc;border-radius:17px;width:min(480px,93vw);padding:24px;" @click.stop>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.08rem;color:#0e0b07;margin-bottom:8px;">Delete Subject</h3>
            <p style="font-size:.8rem;color:#8a7f72;margin-bottom:20px;line-height:1.6;">
              Are you sure you want to delete <strong>{{ subjectToDelete?.name }}</strong>?
              This cannot be undone and may affect linked courses.
            </p>
            <div style="display:flex;gap:8px;justify-content:flex-end;">
              <button @click="showDeleteConfirm = false" style="padding:6px 14px;font-family:'DM Sans',sans-serif;font-size:.77rem;font-weight:600;border-radius:9px;border:1.5px solid #e0d8cc;background:#f0e8d6;color:#8a7f72;cursor:pointer;">Cancel</button>
              <button @click="doDelete" style="padding:6px 14px;font-family:'DM Sans',sans-serif;font-size:.77rem;font-weight:600;border-radius:9px;border:1.5px solid rgba(184,50,50,.22);background:rgba(184,50,50,.07);color:#b83232;cursor:pointer;">Delete</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
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
.ku{color:#5abf8a}
.pb{padding:20px 26px;display:flex;flex-direction:column;gap:16px}
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
.bg-m{color:#8a7f72;border-color:#e0d8cc;background:#f0e8d6}
</style>
