import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
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
  {
    path: "/modaltest",
    name: "ModalTest1",
    component: () => import(/* webpackChunkName: "modaltest" */ "@/views/ModalTest1.vue"),
    meta: {
      title: "Modal Test",
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
