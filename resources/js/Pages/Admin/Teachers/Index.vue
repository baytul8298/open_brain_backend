<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import TeacherModal, { type TeacherItem, type TeacherSubject, type TeacherTypeItem } from '@/Components/Teachers/TeacherModal.vue'
import { Head, router } from '@inertiajs/vue3'
import { formatDistanceToNow } from 'date-fns'
import { ref } from 'vue'

interface Paginated<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

interface Props {
  verificationQueue: Paginated<TeacherItem>
  teachers: Paginated<TeacherItem>
  subjects: TeacherSubject[]
  teacherTypes: TeacherTypeItem[]
  stats: {
    total: number
    active: number
    pending: number
    avg_rating: string
  }
  filters: {
    queue_search: string | null
    queue_subject_id: string | null
    queue_teacher_type_id: string | null
    search: string | null
    subject_id: string | null
    teacher_type_id: string | null
    status: string | null
  }
}

const props = defineProps<Props>()

// Verification Queue filters
const queueSearch = ref(props.filters.queue_search || '')
const queueSubjectId = ref(props.filters.queue_subject_id || '')
const queueTeacherTypeId = ref(props.filters.queue_teacher_type_id || '')

// All Teachers filters
const searchQuery = ref(props.filters.search || '')
const subjectId = ref(props.filters.subject_id || '')
const teacherTypeId = ref(props.filters.teacher_type_id || '')
const statusFilter = ref(props.filters.status || '')

// Modals
const showTeacherModal = ref(false)
const modalMode = ref<'create' | 'edit'>('create')
const selectedTeacher = ref<TeacherItem | null>(null)

const showDocsModal = ref(false)
const docsTeacher = ref<TeacherItem | null>(null)

const showDeleteConfirm = ref(false)
const teacherToDelete = ref<TeacherItem | null>(null)

let queueSearchTimeout: ReturnType<typeof setTimeout>
let allSearchTimeout: ReturnType<typeof setTimeout>

function buildParams(overrides: Record<string, string | number | undefined> = {}) {
  return {
    queue_search: queueSearch.value || undefined,
    queue_subject_id: queueSubjectId.value || undefined,
    queue_teacher_type_id: queueTeacherTypeId.value || undefined,
    search: searchQuery.value || undefined,
    subject_id: subjectId.value || undefined,
    teacher_type_id: teacherTypeId.value || undefined,
    status: statusFilter.value || undefined,
    queue_page: props.verificationQueue.current_page > 1 ? props.verificationQueue.current_page : undefined,
    page: props.teachers.current_page > 1 ? props.teachers.current_page : undefined,
    ...overrides,
  }
}

function fetchTeachers(overrides: Record<string, string | number | undefined> = {}) {
  router.get('/teachers', buildParams(overrides), {
    preserveState: true,
    preserveScroll: true,
  })
}

function onQueueSearchInput() {
  clearTimeout(queueSearchTimeout)
  queueSearchTimeout = setTimeout(() => {
    fetchTeachers({ queue_page: 1 })
  }, 300)
}

function applyQueueFilters() {
  clearTimeout(queueSearchTimeout)
  fetchTeachers({ queue_page: 1 })
}

function resetQueueFilters() {
  clearTimeout(queueSearchTimeout)
  queueSearch.value = ''
  queueSubjectId.value = ''
  queueTeacherTypeId.value = ''
  fetchTeachers({
    queue_search: undefined,
    queue_subject_id: undefined,
    queue_teacher_type_id: undefined,
    queue_page: 1,
  })
}

function onAllSearchInput() {
  clearTimeout(allSearchTimeout)
  allSearchTimeout = setTimeout(() => {
    fetchTeachers({ page: 1 })
  }, 300)
}

function applyAllFilters() {
  clearTimeout(allSearchTimeout)
  fetchTeachers({ page: 1 })
}

function resetAllFilters() {
  clearTimeout(allSearchTimeout)
  searchQuery.value = ''
  subjectId.value = ''
  teacherTypeId.value = ''
  statusFilter.value = ''
  fetchTeachers({
    search: undefined,
    subject_id: undefined,
    teacher_type_id: undefined,
    status: undefined,
    page: 1,
  })
}

function goToQueuePage(page: number) {
  if (page < 1 || page > props.verificationQueue.last_page) return
  fetchTeachers({ queue_page: page })
}

function goToPage(page: number) {
  if (page < 1 || page > props.teachers.last_page) return
  fetchTeachers({ page })
}

function pages(current: number, last: number): number[] {
  const p: number[] = []
  for (let i = 1; i <= last; i++) {
    if (Math.abs(i - current) <= 2 || i === 1 || i === last) p.push(i)
  }
  return p
}

function openCreateModal() {
  modalMode.value = 'create'
  selectedTeacher.value = null
  showTeacherModal.value = true
}

function openEditModal(teacher: TeacherItem) {
  modalMode.value = 'edit'
  selectedTeacher.value = teacher
  showTeacherModal.value = true
}

function closeTeacherModal() {
  showTeacherModal.value = false
  selectedTeacher.value = null
}

function openDocsModal(teacher: TeacherItem) {
  docsTeacher.value = teacher
  showDocsModal.value = true
}

function approveTeacher(teacher: TeacherItem) {
  router.post(`/teachers/${teacher.id}/approve`, {}, { preserveScroll: true })
}

function rejectTeacher(teacher: TeacherItem) {
  router.post(`/teachers/${teacher.id}/reject`, {}, { preserveScroll: true })
}

function toggleTeacherStatus(teacher: TeacherItem) {
  router.post(`/teachers/${teacher.id}/toggle-status`, {}, { preserveScroll: true })
}

function askDelete(teacher: TeacherItem) {
  teacherToDelete.value = teacher
  showDeleteConfirm.value = true
}

function confirmDelete() {
  if (!teacherToDelete.value) return
  router.delete(`/teachers/${teacherToDelete.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteConfirm.value = false
      teacherToDelete.value = null
    },
  })
}

function formatSubmitted(dateStr: string | null) {
  if (!dateStr) return '—'
  try {
    return formatDistanceToNow(new Date(dateStr), { addSuffix: true })
  } catch {
    return dateStr
  }
}

const colors = ['#2d8a5e', '#c9893c', '#2c5f9e', '#6b4aaf', '#b84b2f', '#1a5c3a', '#4a3a1a']
function getAvatarColor(name: string | null | undefined) {
  if (!name) return colors[0]
  let sum = 0
  for (const c of name) sum += c.charCodeAt(0)
  return colors[sum % colors.length]
}

function getStatusBadgeClass(status: string) {
  if (status === 'active') return 'bg-g'
  if (status === 'pending_verification') return 'bg-o'
  if (status === 'suspended') return 'bg-r'
  return 'bg-m'
}

function getStatusLabel(status: string) {
  if (status === 'active') return 'Active'
  if (status === 'pending_verification') return 'Pending'
  if (status === 'suspended') return 'Suspended'
  if (status === 'inactive') return 'Inactive'
  return status
}
</script>

<template>
  <Head title="Teachers" />
  <AppLayout>
    <!-- Hero -->
    <div style="background:#100d09;position:relative;overflow:hidden;padding:28px 26px 26px;flex-shrink:0;">
      <div style="position:absolute;inset:0;background:radial-gradient(ellipse 55% 130% at 100% 50%,rgba(201,137,60,.15),transparent 62%),radial-gradient(ellipse 38% 85% at 0% 0%,rgba(184,75,47,.11),transparent 52%);pointer-events:none;"></div>
      <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.022) 1px,transparent 1px);background-size:22px 22px;pointer-events:none;"></div>
      <div style="position:relative;z-index:1;display:flex;align-items:flex-start;justify-content:space-between;gap:18px;flex-wrap:wrap;">
        <div>
          <div class="hero-ey">Educator Management</div>
          <h1 class="hero-h">Teacher <em>Directory</em></h1>
          <p class="hero-p">Manage verified educators, review applications, and monitor teaching performance.</p>
        </div>
        <div class="kpis">
          <div class="kpi">
            <div class="kpi-v">{{ stats.total }}</div>
            <div class="kpi-l">Total</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">{{ stats.active }}</div>
            <div class="kpi-l">Active</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">{{ stats.pending }}</div>
            <div class="kpi-l">Pending</div>
            <div class="kpi-d kw">review</div>
          </div>
          <div class="kpi">
            <div class="kpi-v">{{ stats.avg_rating }}★</div>
            <div class="kpi-l">Avg Rating</div>
          </div>
        </div>
      </div>
    </div>

    <div class="pb">
      <!-- Verification Queue -->
      <div class="card">
        <div class="ch">
          <div style="display:flex;align-items:center;gap:9px;">
            <span class="ct">Verification <em>Queue</em></span>
            <span class="badge bg-o">{{ verificationQueue.total }} Pending</span>
          </div>
          <div class="tb">
            <div class="ts">
              <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <input v-model="queueSearch" placeholder="Search pending…" @input="onQueueSearchInput" />
            </div>
            <select class="tsel" v-model="queueSubjectId" @change="applyQueueFilters">
              <option value="">All Subjects</option>
              <option v-for="s in subjects" :key="s.id" :value="String(s.id)">{{ s.name }}</option>
            </select>
            <select class="tsel" v-model="queueTeacherTypeId" @change="applyQueueFilters">
              <option value="">All Types</option>
              <option v-for="tt in teacherTypes" :key="tt.id" :value="String(tt.id)">{{ tt.name }}</option>
            </select>
            <button class="btn bg2 bsm" @click="resetQueueFilters">Reset</button>
          </div>
        </div>
        <div class="cb" style="padding:0;">
          <div class="tw">
            <table class="dt">
              <thead>
                <tr>
                  <th>Applicant</th>
                  <th>Subject</th>
                  <th>Type</th>
                  <th>Submitted</th>
                  <th>Documents</th>
                  <th style="text-align:right;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="t in verificationQueue.data" :key="t.id">
                  <td>
                    <div class="uc">
                      <div class="uav" :style="{ background: getAvatarColor(t.name) }">{{ t.name.charAt(0).toUpperCase() }}</div>
                      <div>
                        <div class="unm">{{ t.name }}</div>
                        <div class="uem">{{ t.email }}<span v-if="t.phone"> · {{ t.phone }}</span></div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div v-if="t.subjects.length" style="display:flex;flex-wrap:wrap;gap:4px;">
                      <span v-for="s in t.subjects" :key="s.id" class="badge bg-b">{{ s.name }}</span>
                    </div>
                    <span v-else class="badge bg-m">Not set</span>
                  </td>
                  <td><span class="badge bg-p">{{ t.teacher_type }}</span></td>
                  <td style="color:#8a7f72;">{{ formatSubmitted(t.created_at) }}</td>
                  <td>
                    <button v-if="t.verifications.length" class="btn bbl bsm" @click="openDocsModal(t)">
                      <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                      View Docs ({{ t.verifications.length }})
                    </button>
                    <span v-else style="font-size:.73rem;color:#8a7f72;">No docs</span>
                  </td>
                  <td>
                    <div class="ar">
                      <button class="btn bg2 bsm" @click="openEditModal(t)">Edit</button>
                      <button class="btn bok bsm" @click="approveTeacher(t)">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        Approve
                      </button>
                      <button class="btn bd bsm" @click="rejectTeacher(t)">
                        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Reject
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="!verificationQueue.data.length">
                  <td colspan="6" style="text-align:center;padding:24px;color:#8a7f72;font-size:.8rem;">No pending teachers in verification queue</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-if="verificationQueue.last_page > 1" class="cb" style="padding-top:9px;padding-bottom:9px;border-top:1px solid #e0d8cc;">
          <div class="pagi" style="padding-top:0;">
            <span class="pinfo">{{ (verificationQueue.current_page - 1) * verificationQueue.per_page + 1 }}–{{ Math.min(verificationQueue.current_page * verificationQueue.per_page, verificationQueue.total) }} of {{ verificationQueue.total }}</span>
            <button class="pb2" @click="goToQueuePage(verificationQueue.current_page - 1)">‹</button>
            <button
              v-for="p in pages(verificationQueue.current_page, verificationQueue.last_page)"
              :key="p"
              :class="['pb2', p === verificationQueue.current_page ? 'on' : '']"
              @click="goToQueuePage(p)"
            >{{ p }}</button>
            <button class="pb2" @click="goToQueuePage(verificationQueue.current_page + 1)">›</button>
          </div>
        </div>
      </div>

      <!-- All Teachers -->
      <div class="card">
        <div class="ch">
          <span class="ct">All <em>Teachers</em></span>
          <div class="tb">
            <div class="ts">
              <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <input v-model="searchQuery" placeholder="Search teachers…" @input="onAllSearchInput" />
            </div>
            <select class="tsel" v-model="subjectId" @change="applyAllFilters">
              <option value="">All Subjects</option>
              <option v-for="s in subjects" :key="s.id" :value="String(s.id)">{{ s.name }}</option>
            </select>
            <select class="tsel" v-model="teacherTypeId" @change="applyAllFilters">
              <option value="">All Types</option>
              <option v-for="tt in teacherTypes" :key="tt.id" :value="String(tt.id)">{{ tt.name }}</option>
            </select>
            <select class="tsel" v-model="statusFilter" @change="applyAllFilters">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="suspended">Suspended</option>
            </select>
            <button class="btn bg2 bsm" @click="resetAllFilters">Reset</button>
            <button class="btn bp bsm" @click="openCreateModal">
              <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Add Teacher
            </button>
          </div>
        </div>
        <div class="cb" style="padding:0;">
          <div class="tw">
            <table class="dt">
              <thead>
                <tr>
                  <th>Teacher</th>
                  <th>Subject</th>
                  <th>Type</th>
                  <th>Courses</th>
                  <th>Students</th>
                  <th>Revenue</th>
                  <th>Rating</th>
                  <th>Status</th>
                  <th style="text-align:right;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="t in teachers.data" :key="t.id">
                  <td>
                    <div class="uc">
                      <div class="uav" :style="{ background: getAvatarColor(t.name) }">{{ t.name.charAt(0).toUpperCase() }}</div>
                      <div>
                        <div class="unm">{{ t.name }}</div>
                        <div class="uem">{{ t.email }}<span v-if="t.phone"> · {{ t.phone }}</span></div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div v-if="t.subjects.length" style="display:flex;flex-wrap:wrap;gap:4px;">
                      <span v-for="s in t.subjects" :key="s.id" class="badge bg-b">{{ s.name }}</span>
                    </div>
                    <span v-else class="badge bg-m">Not set</span>
                  </td>
                  <td><span class="badge bg-p">{{ t.teacher_type }}</span></td>
                  <td>{{ t.courses_count }}</td>
                  <td>{{ t.students_count.toLocaleString() }}</td>
                  <td><span style="font-family:'Playfair Display',serif;">৳{{ Number(t.total_revenue).toLocaleString() }}</span></td>
                  <td><span style="color:#c9893c;font-weight:700;">{{ t.rating_avg }}★</span></td>
                  <td><span :class="['badge', getStatusBadgeClass(t.status)]">{{ getStatusLabel(t.status) }}</span></td>
                  <td>
                    <div class="ar">
                      <button class="btn bg2 bxs" @click="openEditModal(t)">Edit</button>
                      <button
                        class="btn bxs"
                        :class="t.status === 'active' ? 'bd' : 'bok'"
                        @click="toggleTeacherStatus(t)"
                      >{{ t.status === 'active' ? 'Suspend' : 'Activate' }}</button>
                      <button class="btn bd bxs" @click="askDelete(t)">Delete</button>
                    </div>
                  </td>
                </tr>
                <tr v-if="!teachers.data.length">
                  <td colspan="9" style="text-align:center;padding:24px;color:#8a7f72;font-size:.8rem;">No teachers found</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-if="teachers.total > 0" class="cb" style="padding-top:9px;padding-bottom:9px;border-top:1px solid #e0d8cc;">
          <div class="pagi" style="padding-top:0;">
            <span class="pinfo">{{ (teachers.current_page - 1) * teachers.per_page + 1 }}–{{ Math.min(teachers.current_page * teachers.per_page, teachers.total) }} of {{ teachers.total }}</span>
            <button class="pb2" @click="goToPage(teachers.current_page - 1)">‹</button>
            <button
              v-for="p in pages(teachers.current_page, teachers.last_page)"
              :key="p"
              :class="['pb2', p === teachers.current_page ? 'on' : '']"
              @click="goToPage(p)"
            >{{ p }}</button>
            <button class="pb2" @click="goToPage(teachers.current_page + 1)">›</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add / Edit Teacher Modal -->
    <TeacherModal
      :open="showTeacherModal"
      :mode="modalMode"
      :teacher="selectedTeacher"
      :subjects="subjects"
      :teacher-types="teacherTypes"
      @close="closeTeacherModal"
      @success="closeTeacherModal"
    />

    <!-- Verification Docs Modal -->
    <Teleport to="body">
      <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showDocsModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="showDocsModal = false">
          <div style="background:#faf6ef;border:1.5px solid #e0d8cc;border-radius:17px;width:min(540px,93vw);padding:24px;" @click.stop>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
              <h3 style="font-family:'Playfair Display',serif;font-size:1.08rem;color:#0e0b07;">Verification Documents · {{ docsTeacher?.name }}</h3>
              <button @click="showDocsModal = false" style="border:none;background:none;cursor:pointer;color:#8a7f72;font-size:1.1rem;">✕</button>
            </div>
            <div v-if="docsTeacher?.verifications.length" style="display:flex;flex-direction:column;gap:8px;margin-bottom:18px;">
              <div v-for="doc in docsTeacher.verifications" :key="doc.id" style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:#fff;border:1.5px solid #e0d8cc;border-radius:10px;">
                <div>
                  <div style="font-size:.8rem;font-weight:600;color:#0e0b07;text-transform:uppercase;">{{ doc.doc_type }}</div>
                  <div style="font-size:.7rem;color:#8a7f72;">Status: {{ doc.status }} · {{ formatSubmitted(doc.submitted_at) }}</div>
                </div>
                <a v-if="doc.doc_url" :href="doc.doc_url" target="_blank" class="btn bbl bsm" style="text-decoration:none;">Open</a>
              </div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;">
              <button @click="showDocsModal = false" class="btn bg2 bsm">Close</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Delete Confirmation Modal -->
    <Teleport to="body">
      <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="showDeleteConfirm = false">
          <div style="background:#faf6ef;border:1.5px solid #e0d8cc;border-radius:17px;width:min(480px,93vw);padding:24px;" @click.stop>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.08rem;color:#0e0b07;margin-bottom:8px;">Delete Teacher</h3>
            <p style="font-size:.8rem;color:#8a7f72;margin-bottom:20px;line-height:1.6;">
              Are you sure you want to delete <strong>{{ teacherToDelete?.name }}</strong>? This action cannot be undone.
            </p>
            <div style="display:flex;gap:8px;justify-content:flex-end;">
              <button @click="showDeleteConfirm = false" class="btn bg2 bsm">Cancel</button>
              <button @click="confirmDelete" class="btn bd bsm">Delete</button>
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
.kw{color:#fbbf24}
.pb{padding:20px 26px;display:flex;flex-direction:column;gap:16px}
.card{background:#fff;border:1.5px solid #e0d8cc;border-radius:13px;overflow:hidden}
.ch{padding:13px 17px 12px;border-bottom:1px solid #e0d8cc;display:flex;align-items:center;justify-content:space-between;gap:9px;flex-wrap:wrap}
.ct{font-family:'Playfair Display',serif;font-size:.95rem;color:#0e0b07}
.ct em{font-style:italic;color:#c9893c}
.cb{padding:15px 17px}
.tb{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.ts{display:flex;align-items:center;gap:6px;background:#f0e8d6;border:1.5px solid #e0d8cc;border-radius:9px;padding:6px 10px;width:190px;transition:border-color .16s}
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
.bg2:hover{border-color:#e8b96a;color:#c9893c}
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
.pagi{display:flex;align-items:center;gap:5px;justify-content:flex-end;padding-top:11px;flex-wrap:wrap}
.pinfo{font-size:.7rem;color:#8a7f72;margin-right:auto}
.pb2{width:29px;height:29px;border-radius:7px;border:1.5px solid #e0d8cc;background:#f0e8d6;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.73rem;font-weight:600;color:#8a7f72;transition:all .15s}
.pb2:hover,.pb2.on{border-color:#c9893c;color:#c9893c;background:rgba(201,137,60,.07)}
</style>
