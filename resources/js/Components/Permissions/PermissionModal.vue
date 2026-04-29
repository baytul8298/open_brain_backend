<script setup lang="ts">
import { watch, computed, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import type { Permission } from '@/types/models/User'
import type { Module } from '@/types/models/Module'
import type { SubModule } from '@/types/models/SubModule'

interface Props {
  open: boolean
  permission?: Permission | null
  mode: 'create' | 'edit'
  modules: Module[]
}

const props = defineProps<Props>()
const emit = defineEmits<{
  close: []
  success: []
}>()

const subModules = ref<SubModule[]>([])

const form = useForm({
  name: '',
  display_name: '',
  description: '',
  module_id: '' as number | '',
  sub_module_id: '' as number | '',
})

const moduleOptions = computed(() =>
  props.modules.map(m => ({ value: m.id, label: m.module_name }))
)

const subModuleOptions = computed(() =>
  subModules.value.map(s => ({ value: s.id, label: s.name }))
)

async function fetchSubModules(moduleId: number | '') {
  if (!moduleId) {
    subModules.value = []
    return
  }
  try {
    const res = await fetch(`/sub-modules/all?module_id=${moduleId}`)
    const data = await res.json()
    subModules.value = data.data
  } catch {
    subModules.value = []
  }
}

watch(() => form.module_id, (newModuleId) => {
  form.sub_module_id = ''
  fetchSubModules(newModuleId)
})

watch(() => props.permission, (p) => {
  if (p && props.mode === 'edit') {
    form.name = p.name
    form.display_name = p.display_name || ''
    form.description = p.description || ''
    form.module_id = p.module_id
    form.sub_module_id = p.sub_module_id ?? ''
    fetchSubModules(p.module_id)
  } else {
    form.reset()
    subModules.value = []
  }
}, { immediate: true })

watch(() => props.open, (isOpen) => {
  if (!isOpen) { form.reset(); form.clearErrors(); subModules.value = [] }
})

const modalTitle = computed(() => props.mode === 'create' ? 'Create Permission' : 'Edit Permission')

function handleSubmit() {
  if (props.mode === 'create') {
    form.post('/permissions', { preserveScroll: true, onSuccess: () => { emit('success'); emit('close') } })
  } else if (props.permission) {
    form.put(`/permissions/${props.permission.id}`, { preserveScroll: true, onSuccess: () => { emit('success'); emit('close') } })
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
          <div v-if="open" class="mc" @click.stop>
            <div class="mh">
              <span class="mt">{{ modalTitle }}</span>
              <button class="mclose" @click="handleClose" :disabled="form.processing">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>
            <form @submit.prevent="handleSubmit" class="mf">
              <div class="fg" style="margin-bottom:13px">
                <label class="fl">Permission Name *</label>
                <input class="fi" v-model="form.name" placeholder="e.g., users.create" :disabled="form.processing" required/>
                <span v-if="form.errors.name" class="fe">{{ form.errors.name }}</span>
                <span class="fhint">Use lowercase letters, numbers, hyphens, and dots only</span>
              </div>

              <div class="fg" style="margin-bottom:13px">
                <label class="fl">Display Name *</label>
                <input class="fi" v-model="form.display_name" placeholder="e.g., Create Users" :disabled="form.processing" required/>
                <span v-if="form.errors.display_name" class="fe">{{ form.errors.display_name }}</span>
              </div>

              <div class="fg" style="margin-bottom:13px">
                <label class="fl">Module *</label>
                <select class="fs" v-model="form.module_id" :disabled="form.processing" required>
                  <option value="">Select module…</option>
                  <option v-for="opt in moduleOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                </select>
                <span v-if="form.errors.module_id" class="fe">{{ form.errors.module_id }}</span>
              </div>

              <div class="fg" style="margin-bottom:13px">
                <label class="fl">Sub-Module</label>
                <select class="fs" v-model="form.sub_module_id" :disabled="form.processing || !form.module_id">
                  <option value="">{{ form.module_id ? 'No sub-module' : 'Select a module first…' }}</option>
                  <option v-for="opt in subModuleOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                </select>
                <span v-if="form.errors.sub_module_id" class="fe">{{ form.errors.sub_module_id }}</span>
              </div>

              <div class="fg" style="margin-bottom:16px">
                <label class="fl">Description</label>
                <textarea class="fta" v-model="form.description" placeholder="Brief description of what this permission allows…" :disabled="form.processing" rows="3"></textarea>
                <span v-if="form.errors.description" class="fe">{{ form.errors.description }}</span>
              </div>

              <div class="mb2">
                <button type="button" class="btn bg2" @click="handleClose" :disabled="form.processing">Cancel</button>
                <button type="submit" class="btn bp" :disabled="form.processing">
                  {{ form.processing ? 'Saving…' : (mode === 'create' ? 'Create Permission' : 'Save Changes') }}
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
.mc{background:#faf6ef;border:1.5px solid #e0d8cc;border-radius:16px;width:100%;max-width:480px;box-shadow:0 24px 48px rgba(14,11,7,.18)}
.mh{display:flex;align-items:center;justify-content:space-between;padding:18px 22px 14px;border-bottom:1px solid #e0d8cc}
.mt{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:600;color:#0e0b07}
.mclose{width:28px;height:28px;border-radius:7px;border:1.5px solid #e0d8cc;background:#f0e8d6;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#8a7f72;transition:all .15s;flex-shrink:0}
.mclose:hover{border-color:#e8b96a}
.mf{padding:18px 22px 4px}
.fg{display:flex;flex-direction:column;gap:5px}
.fl{font-size:.72rem;font-weight:600;color:#8a7f72;letter-spacing:.05em;text-transform:uppercase}
.fi{padding:8px 11px;border:1.5px solid #e0d8cc;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.82rem;color:#0e0b07;background:#fff;outline:none;transition:border-color .16s;width:100%;box-sizing:border-box}
.fi:focus{border-color:#e8b96a}
.fs{padding:8px 11px;border:1.5px solid #e0d8cc;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.82rem;color:#0e0b07;background:#fff;outline:none;cursor:pointer;width:100%;box-sizing:border-box}
.fs:focus{border-color:#e8b96a}
.fta{padding:8px 11px;border:1.5px solid #e0d8cc;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.82rem;color:#0e0b07;background:#fff;outline:none;resize:vertical;min-height:70px;width:100%;box-sizing:border-box}
.fta:focus{border-color:#e8b96a}
.fhint{font-size:.71rem;color:#8a7f72}
.mb2{display:flex;align-items:center;justify-content:flex-end;gap:9px;padding:13px 0 18px;border-top:1px solid #e0d8cc;margin-top:4px}
.fe{font-size:.71rem;color:#b83232}
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
