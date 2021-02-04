import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    path: "/",
    name: "Home",
    component: () => import(/* webpackChunkName: "home" */ "@/views/Home.vue"),
    meta: {
      title: "Home page",
    },
  },
  {
    path: "/purchases",
    name: "Purchases",
    component: () => import(/* webpackChunkName: "purchases" */ "@/views/Purchases.vue"),
    meta: {
      title: "Purchases page",
    },
  },
  {
    path: "/categories",
    name: "Categories",
    component: () => import(/* webpackChunkName: "categories" */ "@/views/Categories.vue"),
    meta: {
      title: "Categories page",
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.afterEach((from) => {
  document.title = from.meta.title;
});

export default router;
