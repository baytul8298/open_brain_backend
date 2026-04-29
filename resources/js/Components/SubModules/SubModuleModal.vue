<script setup lang="ts">
import { watch, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import type { SubModule } from '@/types/models/SubModule'
import type { Module } from '@/types/models/Module'

interface Props {
  open: boolean
  subModule?: SubModule | null
  mode: 'create' | 'edit'
  modules: Module[]
}

const props = defineProps<Props>()
const emit = defineEmits<{
  close: []
  success: []
}>()

const form = useForm({ module_id: '' as string | number, name: '' })

watch(() => props.subModule, (s) => {
  if (s && props.mode === 'edit') {
    form.module_id = s.module_id
    form.name = s.name
  } else {
    form.reset()
  }
}, { immediate: true })

watch(() => props.open, (isOpen) => {
  if (!isOpen) { form.reset(); form.clearErrors() }
})

const modalTitle = computed(() => props.mode === 'create' ? 'Create Sub-Module' : 'Edit Sub-Module')

function handleSubmit() {
  if (props.mode === 'create') {
    form.post('/sub-modules', { preserveScroll: true, onSuccess: () => { emit('success'); emit('close') } })
  } else if (props.subModule) {
    form.put(`/sub-modules/${props.subModule.id}`, { preserveScroll: true, onSuccess: () => { emit('success'); emit('close') } })
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
              <div class="fg" style="margin-bottom:16px">
                <label class="fl">Module *</label>
                <select class="fi" v-model="form.module_id" :disabled="form.processing" required>
                  <option value="" disabled>Select a module…</option>
                  <option v-for="mod in modules" :key="mod.id" :value="mod.id">{{ mod.module_name }}</option>
                </select>
                <span v-if="form.errors.module_id" class="fe">{{ form.errors.module_id }}</span>
                <span class="fhint">Select the parent module for this sub-module</span>
              </div>
              <div class="fg" style="margin-bottom:16px">
                <label class="fl">Sub-Module Name *</label>
                <input class="fi" v-model="form.name" placeholder="e.g., User Profiles" :disabled="form.processing" required/>
                <span v-if="form.errors.name" class="fe">{{ form.errors.name }}</span>
                <span class="fhint">Enter a unique name for this sub-module</span>
              </div>
              <div class="mb2">
                <button type="button" class="btn bg2" @click="handleClose" :disabled="form.processing">Cancel</button>
                <button type="submit" class="btn bp" :disabled="form.processing">
                  {{ form.processing ? 'Saving…' : (mode === 'create' ? 'Create Sub-Module' : 'Save Changes') }}
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
.mc{background:#faf6ef;border:1.5px solid #e0d8cc;border-radius:16px;width:100%;max-width:440px;box-shadow:0 24px 48px rgba(14,11,7,.18)}
.mh{display:flex;align-items:center;justify-content:space-between;padding:18px 22px 14px;border-bottom:1px solid #e0d8cc}
.mt{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:600;color:#0e0b07}
.mclose{width:28px;height:28px;border-radius:7px;border:1.5px solid #e0d8cc;background:#f0e8d6;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#8a7f72;transition:all .15s;flex-shrink:0}
.mclose:hover{border-color:#e8b96a}
.mf{padding:18px 22px 4px}
.fg{display:flex;flex-direction:column;gap:5px}
.fl{font-size:.72rem;font-weight:600;color:#8a7f72;letter-spacing:.05em;text-transform:uppercase}
.fi{padding:8px 11px;border:1.5px solid #e0d8cc;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.82rem;color:#0e0b07;background:#fff;outline:none;transition:border-color .16s;width:100%;box-sizing:border-box}
.fi:focus{border-color:#e8b96a}
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
