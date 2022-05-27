import Register from './pages/Register';
import Login from "./pages/Login";
import Main from "./pages/Main";
import Profile from "./pages/Profile";
import UserList from "./pages/UserList";
import UserCreate from "./pages/UserCreate";

const routes = [
    {
        path: '/',
        name: 'home',
        component: Main,
        meta: {
            auth: false
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
    {
        path: '/profile/:user_id',
        name: 'profile',
        component: Profile,
        meta: {
            auth: true
        }
    },
    {
        path: '/user-list',
        name: 'userList',
        component: UserList,
        meta: {
            auth: true
        }
    },
    {
        path: '/user-create',
        name: 'userCreate',
        component: UserCreate,
        meta: {
            auth: true
        }
    },
]
export default routes;
