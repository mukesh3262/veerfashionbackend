import { fetchWrapper, generatePostData } from '@/helpers';
import { useAlertStore } from '@/stores'; // Assuming you use this for error handling in redux as well
import { createSlice } from '@reduxjs/toolkit';

const initialState = {
    list: [],
    offset: 15,
    currentPage: 1,
    totalRecord: 0,
    dataTableData: [],
    detail: null,
};

const userSlice = createSlice({
    name: 'user',
    initialState,
    reducers: {
        setList: (state, action) => {
            state.list = action.payload.list;
            state.totalRecord = action.payload.totalRecord;
            state.dataTableData = action.payload.dataTableData;
        },
        setDetail: (state, action) => {
            state.detail = action.payload;
        },
        setCurrentPage: (state, action) => {
            state.currentPage = action.payload;
        },
        resetUserState: () => initialState, // To reset the state if needed
    },
});

export const { setList, setDetail, setCurrentPage, resetUserState } =
    userSlice.actions;

export const listData = (cols, currentSort) => async (dispatch, getState) => {
    const appStore = getState().app; // Assuming app state is handled in Redux too
    appStore.toggleLoader(true);

    try {
        const postData = await generatePostData(
            getState().user.dataTableData.draw,
            cols,
            currentSort,
            getState().user.currentPage,
            '',
            getState().user.offset,
        );
        const response = await fetchWrapper.post('users', postData);
        dispatch(
            setList({
                list: response.data,
                totalRecord: response.recordsFiltered,
                dataTableData: response,
            }),
        );

        appStore.toggleLoader(false);
    } catch (error) {
        const alertStore = useAlertStore();
        alertStore.error(error, true);
        appStore.toggleLoader(false);
    }
};

export const changePage = (cols, currentSort, page) => async (dispatch) => {
    try {
        dispatch(setCurrentPage(page));
        await dispatch(listData(cols, currentSort));
    } catch (error) {
        const alertStore = useAlertStore();
        alertStore.error(error, true);
    }
};

export const getDetail = (id) => async (dispatch) => {
    try {
        const response = await fetchWrapper.get(`users/${id}`);
        dispatch(setDetail(response.data));
    } catch (error) {
        const alertStore = useAlertStore();
        alertStore.error(error, true);
    }
};

export const changeStatus = (id) => async () => {
    try {
        await fetchWrapper.patch(`users/${id}/status`);
        return true;
    } catch (error) {
        const alertStore = useAlertStore();
        alertStore.error(error, true);
        return false;
    }
};

export default userSlice.reducer;
