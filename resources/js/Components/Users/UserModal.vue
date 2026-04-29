<script setup lang="ts">
import type { Role, User } from '@/types/models/User'
import { useForm } from '@inertiajs/vue3'
import { computed, watch } from 'vue'

interface Props {
  open: boolean
  user?: User | null
  mode: 'create' | 'edit'
  roles: Role[]
  userTypeOptions: Array<{ value: string; label: string }>
  statusOptions: Array<{ value: string; label: string }>
  languageOptions: Array<{ value: string; label: string }>
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
  password: '',
  user_type: '',
  contact_no: '',
  address: '',
  language: '',
  status: '',
  role_id: '',
})

const roleOptions = computed(() => [
  { value: '', label: 'No Role' },
  ...props.roles.map(r => ({ value: r.id.toString(), label: r.display_name || r.name })),
])

watch(() => props.user, (newUser) => {
  if (newUser && props.mode === 'edit') {
    form.name = newUser.name ?? ''
    form.username = newUser.username
    form.email = newUser.email
    form.password = ''
    form.user_type = newUser.user_type || ''
    form.contact_no = newUser.contact_no || ''
    form.address = newUser.address || ''
    form.language = newUser.language || ''
    form.status = newUser.status ? '1' : '0'
    form.role_id = newUser.roles?.[0]?.id ? String(newUser.roles[0].id) : ''
  } else {
    form.reset()
  }
}, { immediate: true })

watch(() => props.open, (isOpen) => {
  if (isOpen) {
    if (props.mode === 'edit' && props.user) {
      form.name = props.user.name
      form.username = props.user.username
      form.email = props.user.email
      form.password = ''
      form.user_type = props.user.user_type || ''
      form.contact_no = props.user.contact_no || ''
      form.address = props.user.address || ''
      form.language = props.user.language || ''
      form.status = props.user.status ? '1' : '0'
      form.role_id = props.user.roles?.[0]?.id ? String(props.user.roles[0].id) : ''
    } else {
      form.reset()
    }
  } else {
    form.reset()
    form.clearErrors()
  }
})

const modalTitle = computed(() => props.mode === 'create' ? 'Create User' : 'Edit User')

function handleSubmit() {
  const submitData = {
    ...form.data(),
    status: form.status === '1',
    role_id: form.role_id ? parseInt(form.role_id) : null,
    language: form.language || 'en',
  }
  if (props.mode === 'create') {
    form.transform(() => submitData).post('/users', {
      preserveScroll: true,
      onSuccess: () => { emit('success'); emit('close') },
    })
  } else if (props.user) {
    form.transform(() => submitData).put(`/users/${props.user.id}`, {
      preserveScroll: true,
      onSuccess: () => { emit('success'); emit('close') },
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
          <div v-if="open" class="mc" style="max-width:580px" @click.stop>
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
                  <input class="fi" v-model="form.name" placeholder="John Doe" :disabled="form.processing" required/>
                  <span v-if="form.errors.name" class="fe">{{ form.errors.name }}</span>
                </div>
                <div class="fg">
                  <label class="fl">Username *</label>
                  <input class="fi" v-model="form.username" placeholder="johndoe" :disabled="form.processing" required/>
                  <span v-if="form.errors.username" class="fe">{{ form.errors.username }}</span>
                </div>
              </div>

              <div class="frow">
                <div class="fg">
                  <label class="fl">Email *</label>
                  <input class="fi" type="email" v-model="form.email" placeholder="john@example.com" :disabled="form.processing" required/>
                  <span v-if="form.errors.email" class="fe">{{ form.errors.email }}</span>
                </div>
                <div class="fg">
                  <label class="fl">Password{{ mode === 'edit' ? '' : ' *' }}</label>
                  <input class="fi" type="password" v-model="form.password" :placeholder="mode === 'edit' ? 'Leave blank to keep' : 'Enter password'" :disabled="form.processing" :required="mode === 'create'"/>
                  <span v-if="form.errors.password" class="fe">{{ form.errors.password }}</span>
                </div>
              </div>

              <div class="frow">
                <div class="fg">
                  <label class="fl">User Type</label>
                  <select class="fs" v-model="form.user_type" :disabled="form.processing">
                    <option value="">Select type</option>
                    <option v-for="opt in userTypeOptions.filter(o => o.value)" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                  </select>
                  <span v-if="form.errors.user_type" class="fe">{{ form.errors.user_type }}</span>
                </div>
                <div class="fg">
                  <label class="fl">Role</label>
                  <select class="fs" v-model="form.role_id" :disabled="form.processing">
                    <option v-for="opt in roleOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                  </select>
                  <span v-if="form.errors.role_id" class="fe">{{ form.errors.role_id }}</span>
                </div>
              </div>

              <div class="frow">
                <div class="fg">
                  <label class="fl">Contact Number</label>
                  <input class="fi" v-model="form.contact_no" placeholder="+1 234 567 8900" :disabled="form.processing"/>
                  <span v-if="form.errors.contact_no" class="fe">{{ form.errors.contact_no }}</span>
                </div>
                <div class="fg">
                  <label class="fl">Language</label>
                  <select class="fs" v-model="form.language" :disabled="form.processing">
                    <option value="">Select language</option>
                    <option v-for="opt in languageOptions.filter(o => o.value)" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                  </select>
                  <span v-if="form.errors.language" class="fe">{{ form.errors.language }}</span>
                </div>
              </div>

              <div class="frow" style="grid-template-columns:1fr">
                <div class="fg">
                  <label class="fl">Status</label>
                  <select class="fs" v-model="form.status" :disabled="form.processing">
                    <option value="">Select status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                  <span v-if="form.errors.status" class="fe">{{ form.errors.status }}</span>
                </div>
              </div>

              <div class="fg" style="margin-bottom:16px">
                <label class="fl">Address</label>
                <textarea class="fta" v-model="form.address" placeholder="Enter full address…" :disabled="form.processing" rows="2"></textarea>
                <span v-if="form.errors.address" class="fe">{{ form.errors.address }}</span>
              </div>

              <!-- Footer -->
              <div class="mb2">
                <button type="button" class="btn bg2" @click="handleClose" :disabled="form.processing">Cancel</button>
                <button type="submit" class="btn bp" :disabled="form.processing">
                  {{ form.processing ? 'Saving…' : (mode === 'create' ? 'Create User' : 'Save Changes') }}
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
.fi{padding:8px 11px;border:1.5px solid #e0d8cc;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.82rem;color:#0e0b07;background:#fff;outline:none;transition:border-color .16s;width:100%;box-sizing:border-box}
.fi:focus{border-color:#e8b96a}
.fs{padding:8px 11px;border:1.5px solid #e0d8cc;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.82rem;color:#0e0b07;background:#fff;outline:none;cursor:pointer;width:100%;box-sizing:border-box}
.fs:focus{border-color:#e8b96a}
.fta{padding:8px 11px;border:1.5px solid #e0d8cc;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.82rem;color:#0e0b07;background:#fff;outline:none;resize:vertical;min-height:70px;width:100%;box-sizing:border-box}
.fta:focus{border-color:#e8b96a}
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
