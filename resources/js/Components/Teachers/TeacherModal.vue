<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { computed, watch } from 'vue'

export interface TeacherSubject {
  id: number
  name: string
  color: string | null
}

export interface TeacherTypeItem {
  id: number
  name: string
}

export interface TeacherItem {
  id: string
  name: string
  username: string
  email: string
  phone: string | null
  address: string | null
  bio: string | null
  status: string
  teacher_type_id: number | null
  teacher_type: string
  headline: string | null
  institution: string | null
  experience_years: number | null
  verified: boolean
  subjects: TeacherSubject[]
  subject_ids: number[]
  courses_count: number
  students_count: number
  total_revenue: number
  rating_avg: string
  verifications: Array<{
    id: number
    doc_type: string
    doc_url: string | null
    status: string
    submitted_at: string | null
  }>
  created_at: string | null
}

interface Props {
  open: boolean
  mode: 'create' | 'edit'
  teacher?: TeacherItem | null
  subjects: TeacherSubject[]
  teacherTypes: TeacherTypeItem[]
}

const props = defineProps<Props>()
const emit = defineEmits<{
  close: []
  success: []
}>()

const form = useForm({
  name: '',
  username: '',
  email: '',
  phone: '',
  password: '',
  status: 'active',
  teacher_type_id: '' as string | number,
  subject_ids: [] as number[],
  headline: '',
  institution: '',
  experience_years: '' as string | number,
  bio: '',
  address: '',
})

const modalTitle = computed(() => (props.mode === 'create' ? 'Add Teacher' : 'Edit Teacher'))

const statusChoices = computed(() => {
  if (props.mode === 'create') {
    return [
      { value: 'active', label: 'Active' },
      { value: 'pending_verification', label: 'Pending' },
    ]
  }
  return [
    { value: 'active', label: 'Active' },
    { value: 'pending_verification', label: 'Pending' },
    { value: 'inactive', label: 'Inactive' },
    { value: 'suspended', label: 'Suspended' },
  ]
})

function populateForm(t: TeacherItem) {
  form.name = t.name ?? ''
  form.username = t.username ?? ''
  form.email = t.email ?? ''
  form.phone = t.phone ?? ''
  form.password = ''
  form.status = t.status || 'active'
  form.teacher_type_id = t.teacher_type_id ?? (props.teacherTypes[0]?.id ?? '')
  form.subject_ids = [...(t.subject_ids ?? [])]
  form.headline = t.headline ?? ''
  form.institution = t.institution ?? ''
  form.experience_years = t.experience_years ?? ''
  form.bio = t.bio ?? ''
  form.address = t.address ?? ''
}

function resetToDefaults() {
  form.reset()
  form.status = 'active'
  form.teacher_type_id = props.teacherTypes[0]?.id ?? ''
  form.subject_ids = []
}

watch(
  () => props.open,
  (isOpen) => {
    if (isOpen) {
      form.clearErrors()
      if (props.mode === 'edit' && props.teacher) {
        populateForm(props.teacher)
      } else {
        resetToDefaults()
      }
    } else {
      resetToDefaults()
      form.clearErrors()
    }
  }
)

function toggleSubject(subjectId: number) {
  const idx = form.subject_ids.indexOf(subjectId)
  if (idx === -1) {
    form.subject_ids.push(subjectId)
  } else {
    form.subject_ids.splice(idx, 1)
  }
}

function handleSubmit() {
  const payload = {
    ...form.data(),
    teacher_type_id: form.teacher_type_id ? Number(form.teacher_type_id) : null,
    experience_years: form.experience_years !== '' && form.experience_years !== null ? Number(form.experience_years) : null,
  }

  if (props.mode === 'create') {
    form.transform(() => payload).post('/teachers', {
      preserveScroll: true,
      onSuccess: () => {
        emit('success')
        emit('close')
      },
    })
  } else if (props.teacher) {
    form.transform(() => payload).put(`/teachers/${props.teacher.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        emit('success')
        emit('close')
      },
    })
  }
}

function handleClose() {
  if (!form.processing) emit('close')
}
</script>

<template>
  <Teleport to="body">
    <Transition enter-active-class="mfade-in" leave-active-class="mfade-out">
      <div v-if="open" class="modal" @click.self="handleClose">
        <Transition enter-active-class="mslide-in" leave-active-class="mslide-out">
          <div v-if="open" class="mc" style="max-width:640px" @click.stop>
            <!-- Header -->
            <div class="mh">
              <span class="mt">{{ modalTitle }}</span>
              <button class="mclose" @click="handleClose" :disabled="form.processing">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>

            <!-- Body -->
            <form @submit.prevent="handleSubmit" class="mf">
              <div class="frow">
                <div class="fg">
                  <label class="fl">Full Name *</label>
                  <input class="fi" v-model="form.name" placeholder="Dr. Rahim Uddin" :disabled="form.processing" required />
                  <span v-if="form.errors.name" class="fe">{{ form.errors.name }}</span>
                </div>
                <div class="fg">
                  <label class="fl">Username *</label>
                  <input class="fi" v-model="form.username" placeholder="rahimuddin" :disabled="form.processing" required />
                  <span v-if="form.errors.username" class="fe">{{ form.errors.username }}</span>
                </div>
              </div>

              <div class="frow">
                <div class="fg">
                  <label class="fl">Email *</label>
                  <input class="fi" type="email" v-model="form.email" placeholder="teacher@example.com" :disabled="form.processing" required />
                  <span v-if="form.errors.email" class="fe">{{ form.errors.email }}</span>
                </div>
                <div class="fg">
                  <label class="fl">Phone</label>
                  <input class="fi" v-model="form.phone" placeholder="+8801700000000" :disabled="form.processing" />
                  <span v-if="form.errors.phone" class="fe">{{ form.errors.phone }}</span>
                </div>
              </div>

              <div class="frow">
                <div class="fg">
                  <label class="fl">Password{{ mode === 'edit' ? '' : ' *' }}</label>
                  <input
                    class="fi"
                    type="password"
                    v-model="form.password"
                    :placeholder="mode === 'edit' ? 'Leave blank to keep current' : 'Minimum 6 characters'"
                    :disabled="form.processing"
                    :required="mode === 'create'"
                  />
                  <span v-if="form.errors.password" class="fe">{{ form.errors.password }}</span>
                </div>
                <div class="fg">
                  <label class="fl">Status *</label>
                  <select class="fs" v-model="form.status" :disabled="form.processing" required>
                    <option v-for="opt in statusChoices" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                  </select>
                  <span v-if="form.errors.status" class="fe">{{ form.errors.status }}</span>
                </div>
              </div>

              <div class="frow">
                <div class="fg">
                  <label class="fl">Teacher Type</label>
                  <select class="fs" v-model="form.teacher_type_id" :disabled="form.processing">
                    <option value="">Select type</option>
                    <option v-for="tt in teacherTypes" :key="tt.id" :value="tt.id">{{ tt.name }}</option>
                  </select>
                  <span v-if="form.errors.teacher_type_id" class="fe">{{ form.errors.teacher_type_id }}</span>
                </div>
                <div class="fg">
                  <label class="fl">Experience (Years)</label>
                  <input class="fi" type="number" min="0" max="80" v-model="form.experience_years" placeholder="e.g. 5" :disabled="form.processing" />
                  <span v-if="form.errors.experience_years" class="fe">{{ form.errors.experience_years }}</span>
                </div>
              </div>

              <div class="frow">
                <div class="fg">
                  <label class="fl">Headline</label>
                  <input class="fi" v-model="form.headline" placeholder="Senior Physics Instructor" :disabled="form.processing" />
                  <span v-if="form.errors.headline" class="fe">{{ form.errors.headline }}</span>
                </div>
                <div class="fg">
                  <label class="fl">Institution</label>
                  <input class="fi" v-model="form.institution" placeholder="Dhaka University" :disabled="form.processing" />
                  <span v-if="form.errors.institution" class="fe">{{ form.errors.institution }}</span>
                </div>
              </div>

              <div class="fg" style="margin-bottom:13px">
                <label class="fl">Subjects</label>
                <div class="subj-wrap">
                  <button
                    v-for="subj in subjects"
                    :key="subj.id"
                    type="button"
                    class="subj-chip"
                    :class="{ active: form.subject_ids.includes(subj.id) }"
                    @click="toggleSubject(subj.id)"
                  >
                    <span class="subj-dot" :style="{ background: subj.color || '#c9893c' }"></span>
                    {{ subj.name }}
                  </button>
                  <span v-if="!subjects.length" style="font-size:.75rem;color:#8a7f72;">No active subjects available</span>
                </div>
                <span v-if="form.errors.subject_ids" class="fe">{{ form.errors.subject_ids }}</span>
              </div>

              <div class="fg" style="margin-bottom:13px">
                <label class="fl">Bio</label>
                <textarea class="fta" v-model="form.bio" placeholder="Short teaching biography…" :disabled="form.processing" rows="2"></textarea>
                <span v-if="form.errors.bio" class="fe">{{ form.errors.bio }}</span>
              </div>

              <div class="fg" style="margin-bottom:16px">
                <label class="fl">Address</label>
                <input class="fi" v-model="form.address" placeholder="City, Country" :disabled="form.processing" />
                <span v-if="form.errors.address" class="fe">{{ form.errors.address }}</span>
              </div>

              <!-- Footer -->
              <div class="mb2">
                <button type="button" class="btn bg2" @click="handleClose" :disabled="form.processing">Cancel</button>
                <button type="submit" class="btn bp" :disabled="form.processing">
                  {{ form.processing ? 'Saving…' : (mode === 'create' ? 'Create Teacher' : 'Save Changes') }}
                </button>
              </div>
            </form>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.modal{position:fixed;inset:0;background:rgba(14,11,7,.55);z-index:1000;display:flex;align-items:center;justify-content:center;padding:16px}
.mc{background:#faf6ef;border:1.5px solid #e0d8cc;border-radius:16px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 24px 48px rgba(14,11,7,.18)}
.mh{display:flex;align-items:center;justify-content:space-between;padding:18px 22px 14px;border-bottom:1px solid #e0d8cc;position:sticky;top:0;background:#faf6ef;z-index:2}
.mt{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:600;color:#0e0b07}
.mclose{width:28px;height:28px;border-radius:7px;border:1.5px solid #e0d8cc;background:#f0e8d6;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#8a7f72;transition:all .15s;flex-shrink:0}
.mclose:hover{border-color:#e8b96a;color:#c9893c}
.mclose svg{stroke:#8a7f72}
.mf{padding:18px 22px 4px}
.frow{display:grid;grid-template-columns:1fr 1fr;gap:13px;margin-bottom:13px}
.fg{display:flex;flex-direction:column;gap:5px}
.fl{font-size:.72rem;font-weight:600;color:#8a7f72;letter-spacing:.05em;text-transform:uppercase}
.fi,.fs,.fta{padding:8px 11px;border:1.5px solid #e0d8cc;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.82rem;color:#0e0b07;background:#fff;outline:none;transition:border-color .16s;width:100%;box-sizing:border-box}
.fi:focus,.fs:focus,.fta:focus{border-color:#e8b96a}
.fs{cursor:pointer}
.fta{resize:vertical;min-height:66px}
.subj-wrap{display:flex;flex-wrap:wrap;gap:6px;padding:8px 10px;background:#fff;border:1.5px solid #e0d8cc;border-radius:9px}
.subj-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;border:1.5px solid #e0d8cc;background:#f0e8d6;font-family:'DM Sans',sans-serif;font-size:.74rem;font-weight:600;color:#5c5349;cursor:pointer;transition:all .15s}
.subj-chip.active{border-color:#c9893c;background:rgba(201,137,60,.14);color:#8f5616}
.subj-dot{width:7px;height:7px;border-radius:50%;flex-shrink:0}
.mb2{display:flex;align-items:center;justify-content:flex-end;gap:9px;padding:13px 0 18px;border-top:1px solid #e0d8cc;margin-top:4px}
.fe{font-size:.71rem;color:#b83232;margin-top:2px}
.btn{display:inline-flex;align-items:center;gap:5px;padding:7px 16px;font-family:'DM Sans',sans-serif;font-size:.78rem;font-weight:600;border-radius:9px;border:1.5px solid;cursor:pointer;transition:all .16s;white-space:nowrap;background:none}
.bp{background:linear-gradient(135deg,#c9893c,#b84b2f)!important;border-color:transparent;color:#fff;box-shadow:0 2px 10px rgba(201,137,60,.22)}
.bg2{background:#f0e8d6!important;border-color:#e0d8cc;color:#8a7f72}
.bg2:hover{border-color:#e8b96a;color:#c9893c}
.mfade-in{animation:fadeIn .18s ease}
.mfade-out{animation:fadeOut .15s ease}
.mslide-in{animation:slideIn .2s ease}
.mslide-out{animation:slideOut .15s ease}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes fadeOut{from{opacity:1}to{opacity:0}}
@keyframes slideIn{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
@keyframes slideOut{from{opacity:1;transform:scale(1)}to{opacity:0;transform:scale(.96)}}
</style>
