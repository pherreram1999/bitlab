import { inject } from 'vue';
import { AxiosInstance } from 'axios';

export function useAxios(): AxiosInstance {
    const axios = inject<AxiosInstance>('axios');

    if (!axios) {
        throw new Error('Axios instance not found. Please ensure that AxiosInterceptorLayout is wrapping your component.');
    }

    return axios;
}
