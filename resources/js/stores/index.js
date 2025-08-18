import { configureStore } from '@reduxjs/toolkit';
import appReducer from '../redux/admin/appSlice'; // Assuming appSlice is used to manage app-level states like loader
import userReducer from '../redux/admin/userSlice';

const store = configureStore({
    reducer: {
        user: userReducer,
        app: appReducer,
    },
});

export default store;
