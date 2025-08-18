import { queryClient } from '@/helpers/queryClient';
import { errorAlert, successAlert } from '@/helpers/sweet-alert';
import axios from 'axios';

// Toggle subcategory status
export const toggleSubcategoryStatus = async (
    id,
    invalidateKeys = ['subCategories'],
) => {
    try {
        const response = await axios.put(
            route('admin.subcategories.change-status', id),
        );

        if (response.status === 200 && response.data?.success) {
            successAlert(response.data.message);

            // React Query: Invalidate cache after success
            queryClient.invalidateQueries({ queryKey: invalidateKeys });

            return response.data;
        } else {
            const msg = response.data?.message || 'Unexpected error';
            errorAlert(msg);
            throw new Error(msg);
        }
    } catch (error) {
        const msg =
            error?.response?.data?.message ||
            error.message ||
            'Request failed.';
        errorAlert(msg);
        throw error;
    }
};

// Handle subcategory delete
export const subCategoryDelete = async (
    id,
    invalidateKeys = ['subCategories'],
) => {
    try {
        const response = await axios.delete(
            route('admin.subcategories.destroy', id),
        );

        if (response.status === 200 && response.data?.success) {
            successAlert(response.data.message);

            // React Query: Invalidate cache after success
            queryClient.invalidateQueries({ queryKey: invalidateKeys });

            return response.data;
        } else {
            const msg = response.data?.message || 'Unexpected error';
            errorAlert(msg);
            throw new Error(msg);
        }
    } catch (error) {
        const msg =
            error?.response?.data?.message ||
            error.message ||
            'Request failed.';
        errorAlert(msg);
        throw error;
    }
};
