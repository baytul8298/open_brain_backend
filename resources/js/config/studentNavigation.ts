export interface StudentNavSection {
  label: string
  items: StudentNavItem[]
}

export interface StudentNavItem {
  name: string
  href: string
  badge?: string
  icon: string
}

export const studentNavSections: StudentNavSection[] = [
  {
    label: 'Main',
    items: [
      { name: 'Dashboard',     href: '/student/dashboard',    icon: 'dashboard' },
      { name: 'My Courses',    href: '/student/courses',      icon: 'courses',      badge: '4' },
      { name: 'Schedule',      href: '/student/schedule',     icon: 'calendar' },
      { name: 'Tutors',        href: '/student/tutors',       icon: 'tutors' },
    ],
  },
  {
    label: 'Learn',
    items: [
      { name: 'Browse Courses', href: '/student/browse',       icon: 'search' },
      { name: 'Assignments',    href: '/student/assignments',  icon: 'assignments', badge: '2' },
    ],
  },
  {
    label: 'Account',
    items: [
      { name: 'My Profile',     href: '/student/profile',       icon: 'profile' },
      { name: 'Notifications',  href: '/student/notifications', icon: 'notifications', badge: '3' },
      { name: 'Settings',       href: '/student/settings',      icon: 'settings' },
    ],
  },
]
