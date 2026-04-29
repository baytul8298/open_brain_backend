<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import StudentLayout from '@/Layouts/StudentLayout.vue'

const props = defineProps<{
  profileData: {
    bio: string
    first_name: string
    last_name: string
    display_name: string
    date_of_birth: string
    contact_no: string
    email: string
    city: string
    country: string
    avatar_url: string
    cover_url: string
    gender: string
  }
  studentData: {
    school_name: string
    board: string
    roll_number: string
    grade_level: string
    grade_level_bn: string
  }
  boardOptions: string[]
}>()

const editMode = ref(false)
const aboutFormOpen = ref(false)
const infoEditMode = ref(false)

// About Me form
const aboutForm = useForm({
  bio: props.profileData.bio,
})

// Personal Information form
const infoForm = useForm({
  first_name:    props.profileData.first_name,
  last_name:     props.profileData.last_name,
  date_of_birth: props.profileData.date_of_birth,
  contact_no:    props.profileData.contact_no,
  city:          props.profileData.city,
  country:       props.profileData.country,
  school_name:   props.studentData.school_name,
  board:         props.studentData.board,
})

// Computed display values
const fullName = computed(() => {
  return [props.profileData.first_name, props.profileData.last_name].filter(Boolean).join(' ') || props.profileData.display_name || ''
})

const locationDisplay = computed(() => {
  return [props.profileData.city, props.profileData.country].filter(Boolean).join(', ')
})

const boardDisplay = computed(() => {
  if (!props.studentData.board) return ''
  return props.studentData.board.replace(/_/g, ' ').replace(/\b\w/g, (c: string) => c.toUpperCase())
})

// ── Profile Completion Steps ──
const completionSteps = computed(() => [
  {
    label: 'Basic info added',
    done: !!(props.profileData.first_name && props.profileData.last_name),
  },
  {
    label: 'About me written',
    done: !!props.profileData.bio,
  },
  {
    label: 'School info added',
    done: !!props.studentData.school_name,
  },
  {
    label: 'Upload profile photo',
    done: !!props.profileData.avatar_url,
    action: !props.profileData.avatar_url ? 'Add →' : '',
  },
  {
    label: 'Contact info added',
    done: !!props.profileData.contact_no,
    action: !props.profileData.contact_no ? 'Add →' : '',
  },
  {
    label: 'Location added',
    done: !!(props.profileData.city && props.profileData.country),
    action: !(props.profileData.city && props.profileData.country) ? 'Add →' : '',
  },
])

const completedCount = computed(() => completionSteps.value.filter(s => s.done).length)
const totalSteps = computed(() => completionSteps.value.length)
const completionPct = computed(() => Math.round((completedCount.value / totalSteps.value) * 100))
const itemsLeft = computed(() => totalSteps.value - completedCount.value)

// SVG ring: circumference = 2π * 18 ≈ 113
const ringOffset = computed(() => 113 * (1 - completionPct.value / 100))

function toggleEditMode() {
  editMode.value = !editMode.value
}

function toggleAboutEdit() {
  if (!aboutFormOpen.value) {
    aboutForm.bio = props.profileData.bio
    aboutFormOpen.value = true
  } else {
    aboutFormOpen.value = false
  }
}

function saveAbout() {
  aboutForm.put('/student/profile/about', {
    preserveScroll: true,
    onSuccess: () => {
      aboutFormOpen.value = false
    },
  })
}

function toggleInfoEdit() {
  if (!infoEditMode.value) {
    infoForm.first_name    = props.profileData.first_name
    infoForm.last_name     = props.profileData.last_name
    infoForm.date_of_birth = props.profileData.date_of_birth
    infoForm.contact_no    = props.profileData.contact_no
    infoForm.city          = props.profileData.city
    infoForm.country       = props.profileData.country
    infoForm.school_name   = props.studentData.school_name
    infoForm.board         = props.studentData.board
    infoEditMode.value = true
  } else {
    infoEditMode.value = false
  }
}

function savePersonalInfo() {
  infoForm.put('/student/profile/personal-info', {
    preserveScroll: true,
    onSuccess: () => {
      infoEditMode.value = false
    },
  })
}
</script>

<template>
  <Head title="My Profile — Student" />

  <StudentLayout>

    <!-- ══ TOPBAR ══ -->
    <header class="sp-topbar">
      <div class="sp-topbar-breadcrumb">
        <a href="/student/dashboard">Dashboard</a>
        <span class="sp-sep">›</span>
        <span class="sp-current">My Profile</span>
      </div>

      <div class="sp-tb-icon sp-tb-notif">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
      </div>
      <div class="sp-tb-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
      </div>
      <button class="sp-btn-edit-profile" @click="toggleEditMode">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
        <span>{{ editMode ? 'Done Editing' : 'Edit Profile' }}</span>
      </button>
      <div class="sp-tb-avatar">R</div>
    </header>

    <!-- ══ HERO ══ -->
    <div class="sp-profile-hero">

      <!-- Cover photo -->
      <div class="sp-hero-cover">
        <img
          class="sp-hero-cover-img"
          src="https://images.unsplash.com/photo-1509228468518-180dd4864904?q=80&w=1400&auto=format&fit=crop"
          alt="Cover"
        />
        <div class="sp-hero-cover-overlay"></div>
        <button v-show="editMode" class="sp-btn-cover-edit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
          </svg>
          Change Cover
        </button>
      </div>

      <!-- Avatar + name row -->
      <div class="sp-hero-body">
        <div class="sp-hero-inner">
          <div class="sp-hero-avatar-row">

            <!-- Avatar -->
            <div class="sp-hero-avatar-wrap">
              <div class="sp-hero-avatar">R</div>
              <div class="sp-hero-online-dot"></div>
              <div v-show="editMode" class="sp-hero-avatar-edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </div>
            </div>

            <!-- Name block -->
            <div class="sp-hero-name-block">
              <div class="sp-hero-name">
                Rahim Uddin
                <span class="sp-verified">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                  </svg>
                  Verified
                </span>
              </div>
              <div class="sp-hero-subtitle">
                Class 9 Student · <em>Dhaka Board</em> · Enrolled since January 2026
              </div>
              <div class="sp-hero-tags">
                <span class="sp-hero-tag gold">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                  </svg>
                  Mathematics
                </span>
                <span class="sp-hero-tag">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                  </svg>
                  38h Learned
                </span>
                <span class="sp-hero-tag green">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                  </svg>
                  Top 10% This Month
                </span>
                <span class="sp-hero-tag blue">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                  </svg>
                  3 Tutors Following
                </span>
              </div>
            </div>

            <!-- Hero actions -->
            <div class="sp-hero-actions-row">
              <button class="sp-btn-hero sp-btn-hero-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polygon points="5 3 19 12 5 21 5 3"/>
                </svg>
                Continue Learning
              </button>
              <button class="sp-btn-hero sp-btn-hero-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2"/>
                  <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                Schedule
              </button>
            </div>

          </div>
        </div>
      </div>

      <!-- Stat strip -->
      <div class="sp-stat-strip">
        <div class="sp-stat-inner">
          <div class="sp-stat-item">
            <div class="sp-stat-num">4</div>
            <div class="sp-stat-label">Courses</div>
            <div class="sp-stat-sub">2 active · 2 done</div>
          </div>
          <div class="sp-stat-item">
            <div class="sp-stat-num">38h</div>
            <div class="sp-stat-label">Watch Time</div>
            <div class="sp-stat-sub">↑ 6h this week</div>
          </div>
          <div class="sp-stat-item">
            <div class="sp-stat-num">29</div>
            <div class="sp-stat-label">Lessons Done</div>
            <div class="sp-stat-sub">of 84 total</div>
          </div>
          <div class="sp-stat-item">
            <div class="sp-stat-num">7</div>
            <div class="sp-stat-label">Quizzes</div>
            <div class="sp-stat-sub">Avg. 81%</div>
          </div>
          <div class="sp-stat-item">
            <div class="sp-stat-num">14</div>
            <div class="sp-stat-label">Day Streak 🔥</div>
            <div class="sp-stat-sub">Personal best!</div>
          </div>
          <div class="sp-stat-item">
            <div class="sp-stat-num">5</div>
            <div class="sp-stat-label">Badges</div>
            <div class="sp-stat-sub">3 more unlockable</div>
          </div>
        </div>
      </div>

    </div><!-- /profile-hero -->

    <!-- ══ PAGE BODY ══ -->
    <div class="sp-page-body">

      <!-- ── LEFT COLUMN ── -->
      <div class="sp-left-col">

        <!-- About -->
        <div class="sp-card" style="animation-delay:.04s">
          <div class="sp-card-header">
            <div class="sp-card-header-left">
              <div class="sp-card-icon" style="background:linear-gradient(135deg,var(--sp-gold),var(--sp-rust))">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
              </div>
              <div class="sp-card-title">About Me</div>
            </div>
            <button class="sp-card-action" @click="toggleAboutEdit">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
              Edit
            </button>
          </div>
          <div class="sp-card-body">
            <div v-if="!aboutFormOpen" class="sp-about-text">
              <p v-if="profileData.bio" style="white-space:pre-line">{{ profileData.bio }}</p>
              <p v-else class="sp-about-empty">No bio added yet. Click Edit to add one.</p>
            </div>
            <div v-else class="sp-about-form">
              <textarea v-model="aboutForm.bio"></textarea>
              <div v-if="aboutForm.errors.bio" class="sp-form-error">{{ aboutForm.errors.bio }}</div>
              <div class="sp-about-form-btns">
                <button class="sp-btn-cancel" @click="toggleAboutEdit" :disabled="aboutForm.processing">Cancel</button>
                <button class="sp-btn-save" @click="saveAbout" :disabled="aboutForm.processing">
                  {{ aboutForm.processing ? 'Saving...' : 'Save Changes' }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Personal Info -->
        <div class="sp-card" style="animation-delay:.08s">
          <div class="sp-card-header">
            <div class="sp-card-header-left">
              <div class="sp-card-icon" style="background:linear-gradient(135deg,var(--sp-blue),#5a8cbf)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="2" y="3" width="20" height="14" rx="2"/>
                  <line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                </svg>
              </div>
              <div class="sp-card-title">Personal Information</div>
            </div>
            <button class="sp-card-action" @click="toggleInfoEdit">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
              {{ infoEditMode ? 'Cancel' : 'Edit' }}
            </button>
          </div>
          <div class="sp-card-body">
            <!-- Display mode -->
            <div v-if="!infoEditMode" class="sp-info-grid">
              <div class="sp-info-item">
                <div class="sp-info-item-icon" style="background:linear-gradient(135deg,var(--sp-gold),#e0a84a)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                  </svg>
                </div>
                <div>
                  <div class="sp-info-label">Full Name</div>
                  <div class="sp-info-value">{{ fullName || '—' }}</div>
                </div>
              </div>
              <div class="sp-info-item">
                <div class="sp-info-item-icon" style="background:linear-gradient(135deg,var(--sp-blue),#5a8cbf)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                  </svg>
                </div>
                <div>
                  <div class="sp-info-label">Date of Birth</div>
                  <div class="sp-info-value">{{ profileData.date_of_birth || '—' }}</div>
                </div>
              </div>
              <div class="sp-info-item">
                <div class="sp-info-item-icon" style="background:linear-gradient(135deg,var(--sp-green),#5dbf90)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.5a16 16 0 0 0 6 6l.75-.75a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                  </svg>
                </div>
                <div>
                  <div class="sp-info-label">Phone</div>
                  <div class="sp-info-value">{{ profileData.contact_no || '—' }}</div>
                </div>
              </div>
              <div class="sp-info-item">
                <div class="sp-info-item-icon" style="background:linear-gradient(135deg,var(--sp-rust),#d4724e)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                  </svg>
                </div>
                <div>
                  <div class="sp-info-label">Email</div>
                  <div class="sp-info-value">{{ profileData.email || '—' }}</div>
                </div>
              </div>
              <div class="sp-info-item">
                <div class="sp-info-item-icon" style="background:linear-gradient(135deg,var(--sp-purple),#9a7cd4)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                  </svg>
                </div>
                <div>
                  <div class="sp-info-label">School / Institution</div>
                  <div class="sp-info-value">{{ studentData.school_name || '—' }}</div>
                </div>
              </div>
              <div class="sp-info-item">
                <div class="sp-info-item-icon" style="background:linear-gradient(135deg,#8a7f72,#a89f92)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                  </svg>
                </div>
                <div>
                  <div class="sp-info-label">Location</div>
                  <div class="sp-info-value">{{ locationDisplay || '—' }}</div>
                </div>
              </div>
              <div class="sp-info-item">
                <div class="sp-info-item-icon" style="background:linear-gradient(135deg,var(--sp-gold),var(--sp-gold-light))">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                  </svg>
                </div>
                <div>
                  <div class="sp-info-label">Current Class</div>
                  <div class="sp-info-value">{{ studentData.grade_level || '—' }}</div>
                </div>
              </div>
              <div class="sp-info-item">
                <div class="sp-info-item-icon" style="background:linear-gradient(135deg,var(--sp-blue),var(--sp-purple))">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                  </svg>
                </div>
                <div>
                  <div class="sp-info-label">Board</div>
                  <div class="sp-info-value">{{ boardDisplay || '—' }}</div>
                </div>
              </div>
            </div>

            <!-- Edit mode -->
            <div v-else class="sp-info-edit-form">
              <div class="sp-info-edit-grid">
                <div class="sp-info-edit-field">
                  <label class="sp-info-edit-label">First Name</label>
                  <input v-model="infoForm.first_name" type="text" class="sp-info-edit-input" />
                  <div v-if="infoForm.errors.first_name" class="sp-form-error">{{ infoForm.errors.first_name }}</div>
                </div>
                <div class="sp-info-edit-field">
                  <label class="sp-info-edit-label">Last Name</label>
                  <input v-model="infoForm.last_name" type="text" class="sp-info-edit-input" />
                  <div v-if="infoForm.errors.last_name" class="sp-form-error">{{ infoForm.errors.last_name }}</div>
                </div>
                <div class="sp-info-edit-field">
                  <label class="sp-info-edit-label">Date of Birth</label>
                  <input v-model="infoForm.date_of_birth" type="date" class="sp-info-edit-input" />
                  <div v-if="infoForm.errors.date_of_birth" class="sp-form-error">{{ infoForm.errors.date_of_birth }}</div>
                </div>
                <div class="sp-info-edit-field">
                  <label class="sp-info-edit-label">Phone</label>
                  <input v-model="infoForm.contact_no" type="text" class="sp-info-edit-input" />
                  <div v-if="infoForm.errors.contact_no" class="sp-form-error">{{ infoForm.errors.contact_no }}</div>
                </div>
                <div class="sp-info-edit-field">
                  <label class="sp-info-edit-label">School / Institution</label>
                  <input v-model="infoForm.school_name" type="text" class="sp-info-edit-input" />
                  <div v-if="infoForm.errors.school_name" class="sp-form-error">{{ infoForm.errors.school_name }}</div>
                </div>
                <div class="sp-info-edit-field">
                  <label class="sp-info-edit-label">City</label>
                  <input v-model="infoForm.city" type="text" class="sp-info-edit-input" />
                </div>
                <div class="sp-info-edit-field">
                  <label class="sp-info-edit-label">Country</label>
                  <input v-model="infoForm.country" type="text" class="sp-info-edit-input" />
                </div>
                <div class="sp-info-edit-field">
                  <label class="sp-info-edit-label">Board</label>
                  <select v-model="infoForm.board" class="sp-info-edit-input">
                    <option value="">— Select Board —</option>
                    <option v-for="b in boardOptions" :key="b" :value="b">{{ b.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) }}</option>
                  </select>
                  <div v-if="infoForm.errors.board" class="sp-form-error">{{ infoForm.errors.board }}</div>
                </div>
              </div>
              <div class="sp-about-form-btns" style="margin-top:1rem">
                <button class="sp-btn-cancel" @click="toggleInfoEdit" :disabled="infoForm.processing">Cancel</button>
                <button class="sp-btn-save" @click="savePersonalInfo" :disabled="infoForm.processing">
                  {{ infoForm.processing ? 'Saving...' : 'Save Changes' }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Enrolled Courses -->
        <div class="sp-card" style="animation-delay:.12s">
          <div class="sp-card-header">
            <div class="sp-card-header-left">
              <div class="sp-card-icon" style="background:linear-gradient(135deg,var(--sp-rust),#d4724e)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>
                </svg>
              </div>
              <div class="sp-card-title">Enrolled Courses</div>
            </div>
            <button class="sp-card-action">
              View All
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"/>
              </svg>
            </button>
          </div>
          <div class="sp-card-body">
            <div class="sp-course-list">

              <div class="sp-course-row">
                <div class="sp-course-thumb">
                  <img src="https://images.unsplash.com/photo-1636466497217-26a8cbeaf0aa?q=80&w=100&auto=format&fit=crop" alt=""/>
                </div>
                <div class="sp-course-info">
                  <div class="sp-course-name">Complete Math Mastery — Class 9</div>
                  <div class="sp-course-meta">
                    <span class="sp-course-tutor">Mr. Abdul Rahman</span>
                    <span>·</span><span>42 lessons</span>
                  </div>
                  <div class="sp-course-progress-wrap">
                    <div class="sp-course-progress-bar"><div class="sp-course-progress-fill" style="width:68%"></div></div>
                    <div class="sp-course-pct">68% complete · 29 of 42 lessons</div>
                  </div>
                </div>
                <div class="sp-course-status-badge sp-status-active">Active</div>
              </div>

              <div class="sp-course-row">
                <div class="sp-course-thumb">
                  <img src="https://images.unsplash.com/photo-1518133910546-b6c2fb7d79e3?q=80&w=100&auto=format&fit=crop" alt=""/>
                </div>
                <div class="sp-course-info">
                  <div class="sp-course-name">English Grammar — Class 9 &amp; 10</div>
                  <div class="sp-course-meta">
                    <span class="sp-course-tutor">Ms. Farhana Akter</span>
                    <span>·</span><span>30 lessons</span>
                  </div>
                  <div class="sp-course-progress-wrap">
                    <div class="sp-course-progress-bar"><div class="sp-course-progress-fill" style="width:35%"></div></div>
                    <div class="sp-course-pct">35% complete · 11 of 30 lessons</div>
                  </div>
                </div>
                <div class="sp-course-status-badge sp-status-active">Active</div>
              </div>

              <div class="sp-course-row">
                <div class="sp-course-thumb">
                  <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=100&auto=format&fit=crop" alt=""/>
                </div>
                <div class="sp-course-info">
                  <div class="sp-course-name">General Science — Class 8 Full Course</div>
                  <div class="sp-course-meta">
                    <span class="sp-course-tutor">Mr. Karim Hossain</span>
                    <span>·</span><span>36 lessons</span>
                  </div>
                  <div class="sp-course-progress-wrap">
                    <div class="sp-course-progress-bar"><div class="sp-course-progress-fill" style="width:100%"></div></div>
                    <div class="sp-course-pct">100% complete · Finished Feb 2026</div>
                  </div>
                </div>
                <div class="sp-course-status-badge sp-status-completed">Completed</div>
              </div>

              <div class="sp-course-row">
                <div class="sp-course-thumb">
                  <img src="https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?q=80&w=100&auto=format&fit=crop" alt=""/>
                </div>
                <div class="sp-course-info">
                  <div class="sp-course-name">Bangladesh History &amp; Culture</div>
                  <div class="sp-course-meta">
                    <span class="sp-course-tutor">Ms. Nasrin Begum</span>
                    <span>·</span><span>24 lessons</span>
                  </div>
                  <div class="sp-course-progress-wrap">
                    <div class="sp-course-progress-bar"><div class="sp-course-progress-fill" style="width:12%"></div></div>
                    <div class="sp-course-pct">12% complete · 3 of 24 lessons</div>
                  </div>
                </div>
                <div class="sp-course-status-badge sp-status-paused">Paused</div>
              </div>

            </div>
          </div>
        </div>

        <!-- Learning Goals -->
        <div class="sp-card" style="animation-delay:.16s">
          <div class="sp-card-header">
            <div class="sp-card-header-left">
              <div class="sp-card-icon" style="background:linear-gradient(135deg,var(--sp-green),#5dbf90)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/>
                </svg>
              </div>
              <div class="sp-card-title">Learning Goals</div>
            </div>
            <button class="sp-card-action">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
              Add Goal
            </button>
          </div>
          <div class="sp-card-body">
            <div class="sp-goal-list">
              <div class="sp-goal-item">
                <div class="sp-goal-top">
                  <div class="sp-goal-name">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Score A+ in SSC Mathematics
                  </div>
                  <div class="sp-goal-pct">68%</div>
                </div>
                <div class="sp-goal-bar"><div class="sp-goal-fill" style="width:68%;background:linear-gradient(90deg,var(--sp-gold),var(--sp-gold-light))"></div></div>
                <div class="sp-goal-meta">Target: SSC 2027 · 29 of 42 lessons completed · On track ✓</div>
              </div>
              <div class="sp-goal-item">
                <div class="sp-goal-top">
                  <div class="sp-goal-name">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Complete English Course
                  </div>
                  <div class="sp-goal-pct" style="color:var(--sp-blue)">35%</div>
                </div>
                <div class="sp-goal-bar"><div class="sp-goal-fill" style="width:35%;background:linear-gradient(90deg,var(--sp-blue),#5a8cbf)"></div></div>
                <div class="sp-goal-meta">Target: June 2026 · 11 of 30 lessons · Needs more focus</div>
              </div>
              <div class="sp-goal-item">
                <div class="sp-goal-top">
                  <div class="sp-goal-name">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Study 1 hour every day
                  </div>
                  <div class="sp-goal-pct" style="color:var(--sp-green)">93%</div>
                </div>
                <div class="sp-goal-bar"><div class="sp-goal-fill" style="width:93%;background:linear-gradient(90deg,var(--sp-green),#5dbf90)"></div></div>
                <div class="sp-goal-meta">14-day streak · 93 of 100 days · Almost there! 🔥</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Achievements -->
        <div class="sp-card" style="animation-delay:.20s">
          <div class="sp-card-header">
            <div class="sp-card-header-left">
              <div class="sp-card-icon" style="background:linear-gradient(135deg,#c9893c,#e8b96a)">
                <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                  <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
              </div>
              <div class="sp-card-title">Badges &amp; Achievements</div>
            </div>
            <div style="font-size:.76rem;color:var(--sp-muted)">5 earned · 3 remaining</div>
          </div>
          <div class="sp-card-body">
            <div class="sp-achievements-grid">
              <div class="sp-ach-card earned">
                <span class="sp-ach-icon">🎯</span>
                <div class="sp-ach-name">First Lesson</div>
                <div class="sp-ach-desc">Completed your very first lesson</div>
                <div class="sp-ach-date">Jan 10, 2026</div>
              </div>
              <div class="sp-ach-card earned">
                <span class="sp-ach-icon">🔥</span>
                <div class="sp-ach-name">7-Day Streak</div>
                <div class="sp-ach-desc">Studied 7 days in a row</div>
                <div class="sp-ach-date">Jan 18, 2026</div>
              </div>
              <div class="sp-ach-card earned">
                <span class="sp-ach-icon">📚</span>
                <div class="sp-ach-name">Course Complete</div>
                <div class="sp-ach-desc">Finished your first full course</div>
                <div class="sp-ach-date">Feb 14, 2026</div>
              </div>
              <div class="sp-ach-card earned">
                <span class="sp-ach-icon">⭐</span>
                <div class="sp-ach-name">Quiz Star</div>
                <div class="sp-ach-desc">Scored 90%+ on a quiz</div>
                <div class="sp-ach-date">Feb 22, 2026</div>
              </div>
              <div class="sp-ach-card earned">
                <span class="sp-ach-icon">💬</span>
                <div class="sp-ach-name">Curious Learner</div>
                <div class="sp-ach-desc">Asked 5 questions in Q&amp;A</div>
                <div class="sp-ach-date">Mar 2, 2026</div>
              </div>
              <div class="sp-ach-card locked">
                <span class="sp-ach-icon">🏆</span>
                <div class="sp-ach-name">Top Student</div>
                <div class="sp-ach-desc">Rank #1 in class this month</div>
                <div class="sp-ach-date">Locked</div>
              </div>
              <div class="sp-ach-card locked">
                <span class="sp-ach-icon">🌙</span>
                <div class="sp-ach-name">30-Day Streak</div>
                <div class="sp-ach-desc">Study for 30 consecutive days</div>
                <div class="sp-ach-date">16 days left</div>
              </div>
              <div class="sp-ach-card locked">
                <span class="sp-ach-icon">🎓</span>
                <div class="sp-ach-name">Scholar</div>
                <div class="sp-ach-desc">Complete 3 courses with 90%+</div>
                <div class="sp-ach-date">2 more to go</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="sp-card" style="animation-delay:.24s">
          <div class="sp-card-header">
            <div class="sp-card-header-left">
              <div class="sp-card-icon" style="background:linear-gradient(135deg,var(--sp-purple),#9a7cd4)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
              </div>
              <div class="sp-card-title">Recent Activity</div>
            </div>
            <div style="font-size:.72rem;color:var(--sp-muted)">Last 7 days</div>
          </div>
          <div class="sp-card-body">
            <div class="sp-timeline">
              <div class="sp-tl-item">
                <div class="sp-tl-dot dot-gold">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>
                  </svg>
                </div>
                <div class="sp-tl-body">
                  <div class="sp-tl-title">Watched Lesson 29 — Coordinate Geometry</div>
                  <div class="sp-tl-sub">Complete Math Mastery · Mr. Abdul Rahman</div>
                  <div class="sp-tl-time">Today, 4:32 PM · 52 min</div>
                </div>
              </div>
              <div class="sp-tl-item">
                <div class="sp-tl-dot dot-green">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 11l3 3L22 4"/>
                  </svg>
                </div>
                <div class="sp-tl-body">
                  <div class="sp-tl-title">Completed Quiz 5 — Algebra · Scored 88%</div>
                  <div class="sp-tl-sub">8/10 correct answers · Personal best!</div>
                  <div class="sp-tl-time">Yesterday, 6:15 PM</div>
                </div>
              </div>
              <div class="sp-tl-item">
                <div class="sp-tl-dot dot-blue">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                  </svg>
                </div>
                <div class="sp-tl-body">
                  <div class="sp-tl-title">Asked a question in Q&amp;A</div>
                  <div class="sp-tl-sub">"Why does factorisation work for quadratics?" — Mr. Rahman replied</div>
                  <div class="sp-tl-time">Mar 5, 2026 · 8:20 PM</div>
                </div>
              </div>
              <div class="sp-tl-item">
                <div class="sp-tl-dot dot-rust">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16h12V8z"/>
                  </svg>
                </div>
                <div class="sp-tl-body">
                  <div class="sp-tl-title">Downloaded Chapter 3 Notes PDF</div>
                  <div class="sp-tl-sub">Factorisation Methods · Complete Math Mastery</div>
                  <div class="sp-tl-time">Mar 5, 2026 · 4:00 PM</div>
                </div>
              </div>
              <div class="sp-tl-item">
                <div class="sp-tl-dot dot-gold">
                  <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                  </svg>
                </div>
                <div class="sp-tl-body">
                  <div class="sp-tl-title">Earned Badge — "Curious Learner" 💬</div>
                  <div class="sp-tl-sub">Unlocked for asking 5 questions in Q&amp;A</div>
                  <div class="sp-tl-time">Mar 2, 2026</div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /left-col -->

      <!-- ── RIGHT COLUMN ── -->
      <div class="sp-right-col">

        <!-- Profile Completion -->
        <div class="sp-completion-card">
          <svg width="0" height="0" style="position:absolute">
            <defs>
              <linearGradient id="ringGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#c9893c"/>
                <stop offset="100%" stop-color="#e8b96a"/>
              </linearGradient>
            </defs>
          </svg>
          <div class="sp-cc-body">
            <div class="sp-cc-top">
              <div class="sp-cc-ring">
                <svg viewBox="0 0 44 44">
                  <circle class="sp-cc-ring-bg" cx="22" cy="22" r="18"/>
                  <circle class="sp-cc-ring-fill" cx="22" cy="22" r="18" :style="{ strokeDashoffset: ringOffset }"/>
                </svg>
                <div class="sp-cc-pct">{{ completionPct }}%</div>
              </div>
              <div>
                <div class="sp-cc-label">Profile Complete</div>
                <div class="sp-cc-sub">
                  <template v-if="itemsLeft > 0">{{ itemsLeft }} item{{ itemsLeft > 1 ? 's' : '' }} left to finish</template>
                  <template v-else>All done!</template>
                </div>
              </div>
            </div>
            <div class="sp-cc-steps">
              <div
                v-for="(step, i) in completionSteps"
                :key="i"
                class="sp-cc-step"
                :class="{ done: step.done }"
              >
                <div class="sp-cc-step-icon">
                  <svg v-if="step.done" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                </div>
                <div class="sp-cc-step-text">{{ step.label }}</div>
                <div v-if="step.action" class="sp-cc-step-action">{{ step.action }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="sp-quick-stats">
          <div class="sp-card-header">
            <div class="sp-card-header-left">
              <div class="sp-card-icon" style="background:linear-gradient(135deg,var(--sp-blue),#5a8cbf)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="20" x2="18" y2="10"/>
                  <line x1="12" y1="20" x2="12" y2="4"/>
                  <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
              </div>
              <div class="sp-card-title">This Month</div>
            </div>
          </div>
          <div class="sp-qs-grid">
            <div class="sp-qs-item">
              <div class="sp-qs-num">18h</div>
              <div class="sp-qs-label">Study Time</div>
              <div class="sp-qs-trend trend-up">↑ 3h vs last</div>
            </div>
            <div class="sp-qs-item">
              <div class="sp-qs-num">12</div>
              <div class="sp-qs-label">Lessons</div>
              <div class="sp-qs-trend trend-up">↑ 4 vs last</div>
            </div>
            <div class="sp-qs-item">
              <div class="sp-qs-num">3</div>
              <div class="sp-qs-label">Quizzes</div>
              <div class="sp-qs-trend trend-up">Avg 84%</div>
            </div>
            <div class="sp-qs-item">
              <div class="sp-qs-num">14</div>
              <div class="sp-qs-label">Day Streak</div>
              <div class="sp-qs-trend" style="color:var(--sp-gold)">🔥 Best!</div>
            </div>
          </div>
        </div>

        <!-- Following Tutors -->
        <div class="sp-following-card">
          <div class="sp-card-header">
            <div class="sp-card-header-left">
              <div class="sp-card-icon" style="background:linear-gradient(135deg,var(--sp-gold),var(--sp-rust))">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
              </div>
              <div class="sp-card-title">Following</div>
            </div>
            <div style="font-size:.72rem;color:var(--sp-muted)">3 tutors</div>
          </div>
          <div class="sp-ft-item">
            <div class="sp-ft-avatar" style="background:linear-gradient(135deg,#1a3a5c,#2d5a87)">
              MR<div class="sp-ft-online"></div>
            </div>
            <div class="sp-ft-body">
              <div class="sp-ft-name">Mr. Abdul Rahman</div>
              <div class="sp-ft-sub">Mathematics · ★ 4.9</div>
            </div>
            <div class="sp-ft-btn">View →</div>
          </div>
          <div class="sp-ft-item">
            <div class="sp-ft-avatar" style="background:linear-gradient(135deg,#1a2a40,#2a4a6a)">FA</div>
            <div class="sp-ft-body">
              <div class="sp-ft-name">Ms. Farhana Akter</div>
              <div class="sp-ft-sub">English · ★ 4.7</div>
            </div>
            <div class="sp-ft-btn">View →</div>
          </div>
          <div class="sp-ft-item">
            <div class="sp-ft-avatar" style="background:linear-gradient(135deg,#122b12,#1a6a1a)">KH</div>
            <div class="sp-ft-body">
              <div class="sp-ft-name">Mr. Karim Hossain</div>
              <div class="sp-ft-sub">Science · ★ 4.8</div>
            </div>
            <div class="sp-ft-btn">View →</div>
          </div>
        </div>

        <!-- Upcoming Sessions -->
        <div class="sp-upcoming-card">
          <div class="sp-card-header">
            <div class="sp-card-header-left">
              <div class="sp-card-icon" style="background:linear-gradient(135deg,var(--sp-purple),#9a7cd4)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2"/>
                  <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
              </div>
              <div class="sp-card-title">Upcoming Sessions</div>
            </div>
            <button class="sp-card-action">
              All
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"/>
              </svg>
            </button>
          </div>
          <div class="sp-session-item">
            <div class="sp-session-date-box">
              <div class="sp-session-day">11</div>
              <div class="sp-session-month">Mar</div>
            </div>
            <div class="sp-session-body">
              <div class="sp-session-name">Algebra — Number System Q&amp;A</div>
              <div class="sp-session-meta">Mr. Abdul Rahman · Math</div>
            </div>
            <div class="sp-session-time-badge">4:00 PM</div>
          </div>
          <div class="sp-session-item">
            <div class="sp-session-date-box">
              <div class="sp-session-day">14</div>
              <div class="sp-session-month">Mar</div>
            </div>
            <div class="sp-session-body">
              <div class="sp-session-name">English — Grammar Practice Live</div>
              <div class="sp-session-meta">Ms. Farhana Akter · English</div>
            </div>
            <div class="sp-session-time-badge">5:30 PM</div>
          </div>
          <div class="sp-session-item" style="border-bottom:none">
            <div class="sp-session-date-box">
              <div class="sp-session-day">25</div>
              <div class="sp-session-month">Mar</div>
            </div>
            <div class="sp-session-body">
              <div class="sp-session-name">Polynomial &amp; Factorisation</div>
              <div class="sp-session-meta">Mr. Abdul Rahman · Math</div>
            </div>
            <div class="sp-session-time-badge">4:00 PM</div>
          </div>
        </div>

      </div><!-- /right-col -->

    </div><!-- /page-body -->

  </StudentLayout>
</template>

<style>
/* ══ TOPBAR ══ */
.sp-topbar {
  position: sticky;
  top: 0;
  z-index: 50;
  background: rgba(250,246,239,.95);
  backdrop-filter: blur(14px);
  border-bottom: 1px solid var(--sp-border);
  padding: 0 36px;
  height: 64px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.sp-topbar-breadcrumb {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: .8rem;
  color: var(--sp-muted);
  margin-right: auto;
}
.sp-topbar-breadcrumb a { color: var(--sp-muted); text-decoration: none; transition: color .2s; }
.sp-topbar-breadcrumb a:hover { color: var(--sp-gold); }
.sp-sep { opacity: .4; }
.sp-current { color: var(--sp-ink); font-weight: 600; }
.sp-tb-icon {
  width: 38px; height: 38px;
  border-radius: 10px;
  background: var(--sp-warm);
  border: 1px solid var(--sp-border);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all .2s;
  position: relative;
  flex-shrink: 0;
}
.sp-tb-icon:hover { background: var(--sp-warm2); border-color: var(--sp-gold-light); }
.sp-tb-icon svg { width: 16px; height: 16px; color: var(--sp-muted); }
.sp-tb-notif::after {
  content: '3';
  position: absolute;
  top: -4px; right: -4px;
  background: var(--sp-rust);
  color: #fff;
  font-size: .58rem;
  font-weight: 700;
  border-radius: 20px;
  padding: 1px 5px;
  border: 2px solid var(--sp-cream);
}
.sp-btn-edit-profile {
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 8px 16px;
  font-family: 'DM Sans', sans-serif;
  font-size: .8rem;
  font-weight: 600;
  color: #fff;
  background: linear-gradient(135deg, var(--sp-gold), var(--sp-rust));
  border: none;
  border-radius: 9px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: transform .18s, box-shadow .18s;
  flex-shrink: 0;
}
.sp-btn-edit-profile::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,.18), transparent 55%);
  pointer-events: none;
}
.sp-btn-edit-profile:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(184,75,47,.35); }
.sp-btn-edit-profile svg { width: 13px; height: 13px; }
.sp-tb-avatar {
  width: 38px; height: 38px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--sp-gold), var(--sp-rust));
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: .95rem;
  color: #fff;
  cursor: pointer;
  border: 2px solid var(--sp-border);
  flex-shrink: 0;
}

/* ══ HERO ══ */
.sp-profile-hero { position: relative; background: var(--sp-ink); overflow: hidden; }
.sp-hero-cover { height: 180px; position: relative; overflow: hidden; }
.sp-hero-cover-img { width: 100%; height: 100%; object-fit: cover; opacity: .35; }
.sp-hero-cover-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(14,11,7,.1) 0%, rgba(14,11,7,.9) 100%);
}
.sp-hero-cover-overlay::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: linear-gradient(rgba(255,255,255,.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.02) 1px, transparent 1px);
  background-size: 36px 36px;
}
.sp-btn-cover-edit {
  position: absolute;
  top: 14px; right: 16px;
  z-index: 2;
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 13px;
  font-family: 'DM Sans', sans-serif;
  font-size: .72rem;
  font-weight: 600;
  color: rgba(250,246,239,.7);
  background: rgba(0,0,0,.35);
  border: 1px solid rgba(255,255,255,.15);
  border-radius: 8px;
  cursor: pointer;
  transition: all .2s;
  backdrop-filter: blur(8px);
}
.sp-btn-cover-edit:hover { background: rgba(0,0,0,.55); color: var(--sp-cream); }
.sp-btn-cover-edit svg { width: 12px; height: 12px; }

.sp-hero-body { padding: 0 0 28px; }
.sp-hero-inner {
  padding: 0 36px;
}
.sp-hero-avatar-row {
  display: flex;
  align-items: flex-end;
  gap: 20px;
  margin-top: -48px;
  margin-bottom: 16px;
  position: relative;
  z-index: 2;
}
.sp-hero-avatar-wrap { position: relative; flex-shrink: 0; }
.sp-hero-avatar {
  width: 96px; height: 96px;
  border-radius: 22px;
  background: linear-gradient(135deg, var(--sp-gold), var(--sp-rust));
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: 2.2rem;
  color: #fff;
  border: 4px solid var(--sp-ink);
  box-shadow: 0 8px 24px rgba(0,0,0,.4);
}
.sp-hero-avatar-edit {
  position: absolute;
  bottom: -4px; right: -4px;
  width: 26px; height: 26px;
  background: var(--sp-gold);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border: 3px solid var(--sp-ink);
  transition: transform .18s;
}
.sp-hero-avatar-edit:hover { transform: scale(1.12); }
.sp-hero-avatar-edit svg { width: 11px; height: 11px; color: #fff; }
.sp-hero-online-dot {
  position: absolute;
  top: 6px; right: 6px;
  width: 14px; height: 14px;
  background: var(--sp-green);
  border-radius: 50%;
  border: 3px solid var(--sp-ink);
}

.sp-hero-name-block { flex: 1; padding-bottom: 6px; }
.sp-hero-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.7rem;
  color: var(--sp-cream);
  line-height: 1.15;
  margin-bottom: 4px;
}
.sp-verified {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  background: rgba(58,158,111,.15);
  border: 1px solid rgba(58,158,111,.3);
  border-radius: 20px;
  padding: 2px 9px;
  font-family: 'DM Sans', sans-serif;
  font-size: .65rem;
  font-weight: 700;
  color: #5dbf90;
  margin-left: 8px;
  vertical-align: middle;
}
.sp-verified svg { width: 10px; height: 10px; }
.sp-hero-subtitle { font-size: .86rem; color: rgba(250,246,239,.55); margin-bottom: 10px; font-weight: 300; }
.sp-hero-subtitle em { font-style: italic; color: var(--sp-gold-light); }
.sp-hero-tags { display: flex; flex-wrap: wrap; gap: 7px; }
.sp-hero-tag {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: .72rem;
  font-weight: 500;
  background: rgba(255,255,255,.07);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 20px;
  padding: 4px 11px;
  color: rgba(250,246,239,.65);
}
.sp-hero-tag svg { width: 11px; height: 11px; }
.sp-hero-tag.gold { background: rgba(201,137,60,.18); border-color: rgba(201,137,60,.32); color: var(--sp-gold-light); }
.sp-hero-tag.green { background: rgba(58,158,111,.18); border-color: rgba(58,158,111,.32); color: #7dd4ad; }
.sp-hero-tag.blue { background: rgba(58,107,158,.18); border-color: rgba(58,107,158,.32); color: #8eb4e0; }

.sp-hero-actions-row { display: flex; align-items: center; gap: 10px; flex-shrink: 0; padding-bottom: 6px; }
.sp-btn-hero {
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 9px 18px;
  font-family: 'DM Sans', sans-serif;
  font-size: .82rem;
  font-weight: 600;
  border-radius: 10px;
  cursor: pointer;
  transition: all .2s;
  white-space: nowrap;
}
.sp-btn-hero svg { width: 14px; height: 14px; }
.sp-btn-hero-primary {
  color: #fff;
  background: linear-gradient(135deg, var(--sp-gold), var(--sp-rust));
  border: none;
  position: relative;
  overflow: hidden;
}
.sp-btn-hero-primary::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,.18), transparent 55%);
  pointer-events: none;
}
.sp-btn-hero-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(184,75,47,.38); }
.sp-btn-hero-secondary {
  color: rgba(250,246,239,.7);
  background: rgba(255,255,255,.07);
  border: 1.5px solid rgba(255,255,255,.14);
}
.sp-btn-hero-secondary:hover { background: rgba(255,255,255,.13); color: var(--sp-cream); }

/* ══ STAT STRIP ══ */
.sp-stat-strip {
  background: rgba(255,255,255,.04);
  border-top: 1px solid rgba(255,255,255,.07);
  border-bottom: 1px solid rgba(255,255,255,.07);
}
.sp-stat-inner {
  padding: 0 36px;
  display: flex;
  gap: 0;
}
.sp-stat-item {
  flex: 1;
  padding: 14px 0;
  text-align: center;
  border-right: 1px solid rgba(255,255,255,.07);
}
.sp-stat-item:last-child { border-right: none; }
.sp-stat-num { font-family: 'Playfair Display', serif; font-size: 1.4rem; color: var(--sp-cream); line-height: 1; }
.sp-stat-label { font-size: .65rem; text-transform: uppercase; letter-spacing: .09em; color: rgba(250,246,239,.4); margin-top: 3px; }
.sp-stat-sub { font-size: .68rem; color: rgba(250,246,239,.3); margin-top: 1px; }

/* ══ PAGE BODY ══ */
.sp-page-body {
  display: grid;
  grid-template-columns: 1fr 310px;
  gap: 24px;
  padding: 28px 36px 60px;
}

/* ══ COLUMNS ══ */
.sp-left-col { display: flex; flex-direction: column; gap: 20px; }
.sp-right-col {
  display: flex;
  flex-direction: column;
  gap: 18px;
  align-self: start;
  position: sticky;
  top: 80px;
}

/* ══ CARDS ══ */
.sp-card {
  background: var(--sp-card);
  border: 1px solid var(--sp-border);
  border-radius: 18px;
  overflow: hidden;
  animation: spFadeUp .45s ease both;
}
.sp-card-header {
  padding: 15px 22px;
  background: var(--sp-warm);
  border-bottom: 1px solid var(--sp-border);
  display: flex;
  align-items: center;
  gap: 10px;
  justify-content: space-between;
}
.sp-card-header-left { display: flex; align-items: center; gap: 10px; }
.sp-card-icon {
  width: 30px; height: 30px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.sp-card-icon svg { width: 14px; height: 14px; color: #fff; }
.sp-card-title { font-family: 'Playfair Display', serif; font-size: .94rem; color: var(--sp-ink); }
.sp-card-action {
  font-size: .75rem;
  font-weight: 600;
  color: var(--sp-gold);
  cursor: pointer;
  border: none;
  background: none;
  font-family: 'DM Sans', sans-serif;
  transition: color .2s;
  display: flex;
  align-items: center;
  gap: 4px;
}
.sp-card-action:hover { color: var(--sp-rust); }
.sp-card-action svg { width: 12px; height: 12px; }
.sp-card-body { padding: 20px 22px; }

/* About */
.sp-about-text { font-size: .87rem; line-height: 1.85; color: #3a3530; margin-bottom: 0; }
.sp-about-text strong { color: var(--sp-ink); }
.sp-about-form { display: flex; flex-direction: column; gap: 10px; }
.sp-about-form textarea {
  width: 100%;
  min-height: 100px;
  font-family: 'DM Sans', sans-serif;
  font-size: .85rem;
  padding: 11px 14px;
  border: 1.5px solid var(--sp-border);
  border-radius: 10px;
  resize: vertical;
  outline: none;
  background: var(--sp-warm);
  color: var(--sp-ink);
  transition: border-color .2s;
}
.sp-about-form textarea:focus { border-color: var(--sp-gold); }
.sp-about-form-btns { display: flex; gap: 8px; justify-content: flex-end; }
.sp-btn-save {
  padding: 7px 18px;
  font-family: 'DM Sans', sans-serif;
  font-size: .8rem;
  font-weight: 600;
  color: #fff;
  background: var(--sp-gold);
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background .2s;
}
.sp-btn-save:hover { background: var(--sp-rust); }
.sp-btn-cancel {
  padding: 7px 14px;
  font-family: 'DM Sans', sans-serif;
  font-size: .8rem;
  font-weight: 500;
  color: var(--sp-muted);
  background: var(--sp-warm);
  border: 1px solid var(--sp-border);
  border-radius: 8px;
  cursor: pointer;
  transition: all .2s;
}
.sp-btn-cancel:hover { border-color: var(--sp-muted); }

/* Info edit form */
.sp-info-edit-form { padding: 4px 0; }
.sp-info-edit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.sp-info-edit-field { display: flex; flex-direction: column; gap: 4px; }
.sp-info-edit-label {
  font-size: .78rem;
  font-weight: 600;
  color: var(--sp-muted);
  letter-spacing: .03em;
  text-transform: uppercase;
}
.sp-info-edit-input {
  background: var(--sp-warm);
  border: 1px solid var(--sp-border);
  border-radius: 8px;
  padding: 9px 12px;
  font-size: .9rem;
  color: var(--sp-text);
  font-family: inherit;
  transition: border-color .2s;
}
.sp-info-edit-input:focus {
  outline: none;
  border-color: var(--sp-gold);
  box-shadow: 0 0 0 2px rgba(201,169,110,.15);
}
.sp-form-error {
  font-size: .78rem;
  color: var(--sp-rust);
  margin-top: 2px;
}
.sp-about-empty {
  color: var(--sp-muted);
  font-style: italic;
}

/* Info grid */
.sp-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.sp-info-item {
  background: var(--sp-warm);
  border: 1px solid var(--sp-border);
  border-radius: 12px;
  padding: 13px 15px;
  display: flex;
  align-items: flex-start;
  gap: 11px;
}
.sp-info-item-icon {
  width: 32px; height: 32px;
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.sp-info-item-icon svg { width: 14px; height: 14px; color: #fff; }
.sp-info-label { font-size: .68rem; text-transform: uppercase; letter-spacing: .08em; color: var(--sp-muted); margin-bottom: 3px; font-weight: 600; }
.sp-info-value { font-size: .85rem; font-weight: 600; color: var(--sp-ink); }
.sp-info-value.editable { cursor: pointer; border-bottom: 1px dashed var(--sp-border); transition: border-color .2s; outline: none; }
.sp-info-value.editable:focus { border-bottom: 2px solid var(--sp-gold-light); }

/* Courses */
.sp-course-list { display: flex; flex-direction: column; gap: 11px; }
.sp-course-row {
  display: flex;
  align-items: center;
  gap: 13px;
  padding: 13px 15px;
  background: var(--sp-warm);
  border: 1px solid var(--sp-border);
  border-radius: 13px;
  transition: all .2s;
  cursor: pointer;
}
.sp-course-row:hover { border-color: var(--sp-gold-light); transform: translateX(3px); }
.sp-course-thumb {
  width: 48px; height: 48px;
  border-radius: 10px;
  overflow: hidden;
  flex-shrink: 0;
  border: 1px solid var(--sp-border);
}
.sp-course-thumb img { width: 100%; height: 100%; object-fit: cover; }
.sp-course-info { flex: 1; min-width: 0; }
.sp-course-name { font-size: .85rem; font-weight: 600; color: var(--sp-ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px; }
.sp-course-meta { font-size: .72rem; color: var(--sp-muted); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.sp-course-tutor { font-size: .72rem; color: var(--sp-gold); font-weight: 500; }
.sp-course-progress-wrap { margin-top: 6px; }
.sp-course-progress-bar { height: 4px; background: var(--sp-border); border-radius: 2px; overflow: hidden; }
.sp-course-progress-fill { height: 100%; border-radius: 2px; background: linear-gradient(90deg, var(--sp-gold), var(--sp-gold-light)); }
.sp-course-pct { font-size: .66rem; color: var(--sp-muted); margin-top: 3px; }
.sp-course-status-badge { font-size: .63rem; font-weight: 700; border-radius: 20px; padding: 3px 9px; white-space: nowrap; flex-shrink: 0; }
.sp-status-active { background: rgba(58,158,111,.12); color: var(--sp-green); border: 1px solid rgba(58,158,111,.25); }
.sp-status-completed { background: rgba(58,107,158,.12); color: var(--sp-blue); border: 1px solid rgba(58,107,158,.25); }
.sp-status-paused { background: rgba(138,127,114,.12); color: var(--sp-muted); border: 1px solid rgba(138,127,114,.25); }

/* Goals */
.sp-goal-list { display: flex; flex-direction: column; gap: 10px; }
.sp-goal-item { background: var(--sp-warm); border: 1px solid var(--sp-border); border-radius: 12px; padding: 13px 15px; }
.sp-goal-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
.sp-goal-name { font-size: .84rem; font-weight: 600; color: var(--sp-ink); display: flex; align-items: center; gap: 7px; }
.sp-goal-name svg { width: 13px; height: 13px; color: var(--sp-gold); }
.sp-goal-pct { font-size: .8rem; font-weight: 700; color: var(--sp-gold); }
.sp-goal-bar { height: 6px; background: var(--sp-border); border-radius: 3px; overflow: hidden; }
.sp-goal-fill { height: 100%; border-radius: 3px; transition: width .6s ease; }
.sp-goal-meta { font-size: .7rem; color: var(--sp-muted); margin-top: 5px; }

/* Achievements */
.sp-achievements-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.sp-ach-card {
  background: var(--sp-warm);
  border: 1.5px solid var(--sp-border);
  border-radius: 13px;
  padding: 14px;
  text-align: center;
  transition: all .25s;
  cursor: default;
}
.sp-ach-card.earned { border-color: rgba(201,137,60,.35); background: rgba(201,137,60,.05); }
.sp-ach-card.earned:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(201,137,60,.12); }
.sp-ach-card.locked { opacity: .45; filter: grayscale(.8); }
.sp-ach-icon { font-size: 1.6rem; margin-bottom: 7px; display: block; }
.sp-ach-name { font-size: .75rem; font-weight: 700; color: var(--sp-ink); margin-bottom: 2px; }
.sp-ach-desc { font-size: .66rem; color: var(--sp-muted); line-height: 1.4; }
.sp-ach-date { font-size: .62rem; color: var(--sp-gold); margin-top: 5px; font-weight: 500; }

/* Timeline */
.sp-timeline { display: flex; flex-direction: column; gap: 0; }
.sp-tl-item { display: flex; gap: 14px; padding-bottom: 18px; position: relative; }
.sp-tl-item:last-child { padding-bottom: 0; }
.sp-tl-item:not(:last-child)::before {
  content: '';
  position: absolute;
  left: 15px; top: 30px; bottom: 0;
  width: 1px;
  background: var(--sp-border);
}
.sp-tl-dot {
  width: 30px; height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  border: 2px solid var(--sp-border);
  background: var(--sp-card);
  position: relative;
  z-index: 1;
}
.sp-tl-dot svg { width: 13px; height: 13px; }
.sp-tl-dot.dot-gold  { background: rgba(201,137,60,.1);  border-color: rgba(201,137,60,.4);  }
.sp-tl-dot.dot-gold  svg { color: var(--sp-gold);  }
.sp-tl-dot.dot-green { background: rgba(58,158,111,.1); border-color: rgba(58,158,111,.4); }
.sp-tl-dot.dot-green svg { color: var(--sp-green); }
.sp-tl-dot.dot-blue  { background: rgba(58,107,158,.1);  border-color: rgba(58,107,158,.4);  }
.sp-tl-dot.dot-blue  svg { color: var(--sp-blue);  }
.sp-tl-dot.dot-rust  { background: rgba(184,75,47,.1);   border-color: rgba(184,75,47,.4);   }
.sp-tl-dot.dot-rust  svg { color: var(--sp-rust);  }
.sp-tl-body { flex: 1; padding-top: 4px; }
.sp-tl-title { font-size: .84rem; font-weight: 600; color: var(--sp-ink); margin-bottom: 2px; }
.sp-tl-sub { font-size: .76rem; color: var(--sp-muted); }
.sp-tl-time { font-size: .68rem; color: rgba(138,127,114,.6); margin-top: 2px; }

/* ══ RIGHT COLUMN CARDS ══ */

/* Profile Completion */
.sp-completion-card { background: var(--sp-ink); border-radius: 18px; overflow: hidden; animation: spFadeUp .45s .06s ease both; }
.sp-cc-body { padding: 20px; }
.sp-cc-top { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.sp-cc-ring { position: relative; width: 56px; height: 56px; flex-shrink: 0; }
.sp-cc-ring svg { width: 56px; height: 56px; transform: rotate(-90deg); }
.sp-cc-ring-bg { fill: none; stroke: rgba(255,255,255,.08); stroke-width: 5; }
.sp-cc-ring-fill {
  fill: none;
  stroke: url(#ringGrad);
  stroke-width: 5;
  stroke-linecap: round;
  stroke-dasharray: 113;
  transition: stroke-dashoffset .8s ease;
}
.sp-cc-pct { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: .9rem; color: var(--sp-gold-light); font-weight: 700; }
.sp-cc-label { font-family: 'Playfair Display', serif; font-size: .96rem; color: var(--sp-cream); margin-bottom: 3px; }
.sp-cc-sub { font-size: .74rem; color: rgba(250,246,239,.4); }
.sp-cc-steps { display: flex; flex-direction: column; gap: 8px; }
.sp-cc-step { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 10px; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07); }
.sp-cc-step.done { background: rgba(58,158,111,.1); border-color: rgba(58,158,111,.2); }
.sp-cc-step-icon { width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.sp-cc-step.done .sp-cc-step-icon { background: rgba(58,158,111,.3); }
.sp-cc-step:not(.done) .sp-cc-step-icon { background: rgba(255,255,255,.07); }
.sp-cc-step-icon svg { width: 11px; height: 11px; }
.sp-cc-step.done .sp-cc-step-icon svg { color: #7dd4ad; }
.sp-cc-step:not(.done) .sp-cc-step-icon svg { color: rgba(250,246,239,.3); }
.sp-cc-step-text { font-size: .78rem; color: rgba(250,246,239,.6); flex: 1; }
.sp-cc-step.done .sp-cc-step-text { color: rgba(250,246,239,.75); }
.sp-cc-step-action { font-size: .68rem; font-weight: 600; color: var(--sp-gold); cursor: pointer; white-space: nowrap; }

/* Quick Stats */
.sp-quick-stats { background: var(--sp-card); border: 1px solid var(--sp-border); border-radius: 16px; overflow: hidden; animation: spFadeUp .45s .10s ease both; }
.sp-qs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
.sp-qs-item { padding: 14px 16px; border-right: 1px solid var(--sp-border); border-bottom: 1px solid var(--sp-border); text-align: center; }
.sp-qs-item:nth-child(2n) { border-right: none; }
.sp-qs-item:nth-last-child(-n+2) { border-bottom: none; }
.sp-qs-num { font-family: 'Playfair Display', serif; font-size: 1.5rem; color: var(--sp-ink); line-height: 1; }
.sp-qs-label { font-size: .66rem; text-transform: uppercase; letter-spacing: .08em; color: var(--sp-muted); margin-top: 3px; }
.sp-qs-trend { font-size: .68rem; margin-top: 2px; }
.trend-up { color: var(--sp-green); }

/* Following */
.sp-following-card { background: var(--sp-card); border: 1px solid var(--sp-border); border-radius: 16px; overflow: hidden; animation: spFadeUp .45s .14s ease both; }
.sp-ft-item { display: flex; align-items: center; gap: 11px; padding: 11px 16px; border-bottom: 1px solid rgba(224,216,204,.6); transition: background .15s; cursor: pointer; }
.sp-ft-item:last-child { border-bottom: none; }
.sp-ft-item:hover { background: rgba(201,137,60,.04); }
.sp-ft-avatar {
  width: 38px; height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: .85rem;
  color: #fff;
  flex-shrink: 0;
  border: 2px solid var(--sp-border);
  position: relative;
}
.sp-ft-online { position: absolute; bottom: -1px; right: -1px; width: 10px; height: 10px; background: var(--sp-green); border-radius: 50%; border: 2px solid var(--sp-card); }
.sp-ft-body { flex: 1; }
.sp-ft-name { font-size: .82rem; font-weight: 600; color: var(--sp-ink); margin-bottom: 1px; }
.sp-ft-sub { font-size: .7rem; color: var(--sp-muted); }
.sp-ft-btn { font-size: .68rem; font-weight: 700; color: var(--sp-gold); background: rgba(201,137,60,.1); border: 1px solid rgba(201,137,60,.25); border-radius: 20px; padding: 3px 9px; cursor: pointer; white-space: nowrap; transition: all .2s; }
.sp-ft-btn:hover { background: rgba(201,137,60,.2); }

/* Upcoming Sessions */
.sp-upcoming-card { background: var(--sp-card); border: 1px solid var(--sp-border); border-radius: 16px; overflow: hidden; animation: spFadeUp .45s .18s ease both; }
.sp-session-item { display: flex; gap: 12px; padding: 12px 16px; border-bottom: 1px solid rgba(224,216,204,.6); align-items: center; }
.sp-session-date-box { width: 42px; height: 42px; border-radius: 10px; background: var(--sp-warm); border: 1px solid var(--sp-border); display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0; }
.sp-session-day { font-family: 'Playfair Display', serif; font-size: 1rem; color: var(--sp-ink); line-height: 1; }
.sp-session-month { font-size: .55rem; text-transform: uppercase; letter-spacing: .08em; color: var(--sp-muted); margin-top: 1px; }
.sp-session-body { flex: 1; }
.sp-session-name { font-size: .82rem; font-weight: 600; color: var(--sp-ink); margin-bottom: 2px; }
.sp-session-meta { font-size: .7rem; color: var(--sp-muted); }
.sp-session-time-badge { font-size: .65rem; font-weight: 600; color: var(--sp-gold); background: rgba(201,137,60,.1); border: 1px solid rgba(201,137,60,.25); border-radius: 6px; padding: 2px 7px; white-space: nowrap; flex-shrink: 0; }

/* ══ ANIMATION ══ */
@keyframes spFadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

/* ══ RESPONSIVE ══ */
@media (max-width: 1100px) { .sp-page-body { grid-template-columns: 1fr; } .sp-right-col { position: static; } }
@media (max-width: 768px) {
  .sp-hero-inner { padding: 0 20px; }
  .sp-stat-inner { padding: 0 20px; }
  .sp-hero-avatar-row { flex-wrap: wrap; }
  .sp-hero-actions-row { width: 100%; }
  .sp-page-body { padding: 16px 20px 50px; }
  .sp-info-grid { grid-template-columns: 1fr; }
  .sp-achievements-grid { grid-template-columns: repeat(2, 1fr); }
  .sp-stat-inner { flex-wrap: wrap; }
  .sp-stat-item { flex: 50%; border-bottom: 1px solid rgba(255,255,255,.07); }
}
</style>
