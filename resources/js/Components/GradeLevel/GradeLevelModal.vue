<script setup lang="ts">
import { watch, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

interface GradeLevel {
  id: number
  name: string
  name_bn: string | null
  slug: string | null
  icon: string | null
  color: string | null
  sort_order: number
  is_active: boolean
}

interface Props {
  open: boolean
  mode: 'create' | 'edit'
  gradeLevel?: GradeLevel | null
}

const props = defineProps<Props>()
const emit = defineEmits<{ close: []; success: [] }>()

const form = useForm({
  name: '',
  name_bn: '',
  slug: '',
  icon: '',
  color: '#c9893c',
  sort_order: 0,
  is_active: true,
})

watch(() => props.gradeLevel, (g) => {
  if (g && props.mode === 'edit') {
    form.name       = g.name
    form.name_bn    = g.name_bn ?? ''
    form.slug       = g.slug ?? ''
    form.icon       = g.icon ?? ''
    form.color      = g.color ?? '#c9893c'
    form.sort_order = g.sort_order
    form.is_active  = g.is_active
  } else {
    form.reset()
    form.color      = '#c9893c'
    form.is_active  = true
    form.sort_order = 0
  }
}, { immediate: true })

watch(() => props.open, (isOpen) => {
  if (!isOpen) { form.reset(); form.clearErrors() }
})

watch(() => form.name, (val) => {
  if (props.mode === 'create') {
    form.slug = val.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')
  }
})

const title = computed(() => props.mode === 'create' ? 'Add Grade Level' : 'Edit Grade Level')

function handleSubmit() {
  const payload = {
    ...form.data(),
    name_bn: form.name_bn || null,
    slug:    form.slug    || null,
    icon:    form.icon    || null,
    color:   form.color   || null,
  }

  if (props.mode === 'create') {
    form.transform(() => payload).post('/grade-level', {
      preserveScroll: true,
      onSuccess: () => { emit('success'); emit('close') },
    })
  } else if (props.gradeLevel) {
    form.transform(() => payload).put(`/grade-level/${props.gradeLevel.id}`, {
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
          <div v-if="open" class="mc" @click.stop>

            <div class="mh">
              <span class="mt">{{ title }}</span>
              <button class="mclose" @click="handleClose" :disabled="form.processing">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>

            <form @submit.prevent="handleSubmit" class="mf">

              <div class="frow">
                <div class="fg">
                  <label class="fl">Name *</label>
                  <input class="fi" v-model="form.name" placeholder="e.g. Class 6" :disabled="form.processing" required />
                  <span v-if="form.errors.name" class="fe">{{ form.errors.name }}</span>
                </div>
                <div class="fg">
                  <label class="fl">Bangla Name</label>
                  <input class="fi" v-model="form.name_bn" placeholder="e.g. ষষ্ঠ শ্রেণি" :disabled="form.processing" />
                  <span v-if="form.errors.name_bn" class="fe">{{ form.errors.name_bn }}</span>
                </div>
              </div>

              <div class="fg">
                <label class="fl">Slug</label>
                <input class="fi" v-model="form.slug" placeholder="e.g. class-6" :disabled="form.processing" />
                <span v-if="form.errors.slug" class="fe">{{ form.errors.slug }}</span>
                <span class="fhint">Auto-filled from name</span>
              </div>

              <div class="frow">
                <div class="fg">
                  <label class="fl">Colour</label>
                  <div style="display:flex;align-items:center;gap:8px;">
                    <input type="color" v-model="form.color" :disabled="form.processing"
                      style="width:36px;height:34px;padding:2px;border:1.5px solid #e0d8cc;border-radius:8px;background:#f0e8d6;cursor:pointer;" />
                    <input class="fi" v-model="form.color" placeholder="#c9893c" :disabled="form.processing"
                      style="font-family:'DM Mono',monospace;font-size:.78rem;" maxlength="7" />
                  </div>
                  <span v-if="form.errors.color" class="fe">{{ form.errors.color }}</span>
                </div>
                <div class="fg">
                  <label class="fl">Icon</label>
                  <input class="fi" v-model="form.icon" placeholder="e.g. book" :disabled="form.processing" />
                  <span v-if="form.errors.icon" class="fe">{{ form.errors.icon }}</span>
                </div>
              </div>

              <div class="fg">
                <label class="fl">Sort Order</label>
                <input class="fi" type="number" v-model.number="form.sort_order" min="0" max="9999" :disabled="form.processing" />
                <span v-if="form.errors.sort_order" class="fe">{{ form.errors.sort_order }}</span>
              </div>

              <div class="fg" style="flex-direction:row;align-items:center;gap:10px;margin-top:2px;">
                <label class="tgl">
                  <input type="checkbox" v-model="form.is_active" :disabled="form.processing" />
                  <span class="tgl-s"></span>
                </label>
                <span style="font-size:.8rem;font-weight:600;color:#0e0b07;">Active</span>
                <span style="font-size:.75rem;color:#8a7f72;">Inactive levels are hidden from students</span>
              </div>

              <div class="mb2">
                <button type="button" class="btn bg2" @click="handleClose" :disabled="form.processing">Cancel</button>
                <button type="submit" class="btn bp" :disabled="form.processing">
                  {{ form.processing ? 'Saving…' : (mode === 'create' ? 'Add Grade Level' : 'Save Changes') }}
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
.mc{background:#faf6ef;border:1.5px solid #e0d8cc;border-radius:16px;width:100%;max-width:520px;box-shadow:0 24px 48px rgba(14,11,7,.18);max-height:90vh;overflow-y:auto}
.mh{display:flex;align-items:center;justify-content:space-between;padding:18px 22px 14px;border-bottom:1px solid #e0d8cc;position:sticky;top:0;background:#faf6ef;z-index:1;border-radius:16px 16px 0 0}
.mt{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:600;color:#0e0b07}
.mclose{width:28px;height:28px;border-radius:7px;border:1.5px solid #e0d8cc;background:#f0e8d6;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#8a7f72;transition:all .15s;flex-shrink:0}
.mclose:hover{border-color:#e8b96a}
.mf{padding:18px 22px 4px;display:flex;flex-direction:column;gap:13px}
.frow{display:grid;grid-template-columns:1fr 1fr;gap:11px}
.fg{display:flex;flex-direction:column;gap:5px}
.fl{font-size:.72rem;font-weight:600;color:#8a7f72;letter-spacing:.05em;text-transform:uppercase}
.fi{padding:8px 11px;border:1.5px solid #e0d8cc;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.82rem;color:#0e0b07;background:#fff;outline:none;transition:border-color .16s;width:100%;box-sizing:border-box}
.fi:focus{border-color:#e8b96a}
.fhint{font-size:.71rem;color:#8a7f72}
.fe{font-size:.71rem;color:#b83232}
.mb2{display:flex;align-items:center;justify-content:flex-end;gap:9px;padding:13px 0 18px;border-top:1px solid #e0d8cc;margin-top:4px}
.btn{display:inline-flex;align-items:center;gap:5px;padding:7px 16px;font-family:'DM Sans',sans-serif;font-size:.78rem;font-weight:600;border-radius:9px;border:1.5px solid;cursor:pointer;transition:all .16s;white-space:nowrap;background:none}
.bp{background:linear-gradient(135deg,#c9893c,#b84b2f)!important;border-color:transparent;color:#fff;box-shadow:0 2px 10px rgba(201,137,60,.22)}
.bg2{background:#f0e8d6!important;border-color:#e0d8cc;color:#8a7f72}
.bg2:hover{border-color:#e8b96a;color:#c9893c}
.tgl{position:relative;display:inline-block;width:36px;height:21px;flex-shrink:0}
.tgl input{opacity:0;width:0;height:0;position:absolute}
.tgl-s{position:absolute;inset:0;background:#e8ddc8;border-radius:21px;cursor:pointer;transition:.18s;border:1.5px solid #e0d8cc}
.tgl-s::before{content:'';position:absolute;width:13px;height:13px;border-radius:50%;background:#fff;bottom:2px;left:2px;transition:.18s;box-shadow:0 1px 3px rgba(0,0,0,.18)}
.tgl input:checked+.tgl-s{background:#2d8a5e;border-color:rgba(45,138,94,.38)}
.tgl input:checked+.tgl-s::before{transform:translateX(15px)}
.mfade-in{animation:fadeIn .18s ease}
.mfade-out{animation:fadeOut .15s ease}
.mslide-in{animation:slideIn .2s ease}
.mslide-out{animation:slideOut .15s ease}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes fadeOut{from{opacity:1}to{opacity:0}}
@keyframes slideIn{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
@keyframes slideOut{from{opacity:1;transform:scale(1)}to{opacity:0;transform:scale(.96)}}
</style>
