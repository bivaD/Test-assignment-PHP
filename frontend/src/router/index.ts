import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/leaderboard',
      name: 'leaderboard',
      component: () => import('../views/MembersLeaderboard.vue')
    },
    {
      path: '/members',
      name: 'members',
      component: () => import('../views/MembersPage.vue')
    },
    {
      path: '/members/add',
      name: 'add-member',
      component: () => import('../views/AddMember.vue')
    },
    {
      path: '/members/edit/:id',
      name: 'edit-member',
      component: () => import('../views/EditMember.vue')
    },
    {
      path: '/members/:id',
      name: 'member-detail',
      component: () => import('../views/MemberDetail.vue')
    },
    {
      path: '/games',
      name: 'games',
      component: () => import('../views/GamesPage.vue')
    },
    {
      path: '/games/add',
      name: 'add-game',
      component: () => import('../views/AddGame.vue')
    },
    {
      path: '/:catchAll(.*)',
      redirect: '/'
    }
  ]
})

export default router
