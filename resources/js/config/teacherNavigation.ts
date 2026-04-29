export interface TeacherNavSection {
  label: string
  items: TeacherNavItem[]
}

export interface TeacherNavItem {
  name: string
  href: string
  badge?: string
  icon: string
}

export const teacherNavSections: TeacherNavSection[] = [
  {
    label: 'Main',
    items: [
      { name: 'Dashboard',    href: '/teacher/dashboard',         icon: 'dashboard' },
      { name: 'My Students',  href: '/teacher/students',          icon: 'students',    badge: '42' },
      { name: 'Assignments',  href: '/teacher/assignments',       icon: 'assignments', badge: '8' },
      { name: 'Schedule',     href: '/teacher/schedule',          icon: 'calendar' },
    ],
  },
  {
    label: 'Content',
    items: [
      { name: 'My Courses',    href: '/teacher/courses',          icon: 'courses' },
      { name: 'Create Course', href: '/teacher/courses/create',   icon: 'create' },
      { name: 'Analytics',     href: '/teacher/analytics',        icon: 'analytics' },
    ],
  },
  {
    label: 'Account',
    items: [
      { name: 'My Profile',    href: '/teacher/profile',          icon: 'profile' },
      { name: 'Notifications', href: '/teacher/notifications',    icon: 'notifications', badge: '5' },
    ],
  },
]
