import { useQuery, useQueryClient } from '@tanstack/react-query';
import axios from 'axios';
import { useEffect, useMemo } from 'react';

export function useSubCategories(filters) {
    const queryClient = useQueryClient();

    // Memoize query key to prevent unnecessary refetches
    const queryKey = useMemo(() => ['subCategories', filters], [filters]);

    // Fetch current page data
    const {
        isLoading,
        data: listSubCategories = {
            subCategories: [],
            pagination: {},
            filterOptions: {},
        },
        error,
    } = useQuery({
        queryKey,
        queryFn: () => getSubCategories(filters),
    });

    const pageCount = listSubCategories?.pagination?.last_page ?? 1;

    // Prefetch next/previous pages when applicable
    useEffect(() => {
        if (filters.page < pageCount) {
            queryClient.prefetchQuery({
                queryKey: [
                    'subCategories',
                    { ...filters, page: filters.page + 1 },
                ],
                queryFn: () =>
                    getSubCategories({ ...filters, page: filters.page + 1 }),
            });
        }
        if (filters.page > 1) {
            queryClient.prefetchQuery({
                queryKey: [
                    'subCategories',
                    { ...filters, page: filters.page - 1 },
                ],
                queryFn: () =>
                    getSubCategories({ ...filters, page: filters.page - 1 }),
            });
        }
    }, [filters, pageCount, queryClient]);

    return useMemo(
        () => ({ isLoading, listSubCategories, error }),
        [isLoading, listSubCategories, error],
    );
}

// fetch subCategories list from server
async function getSubCategories(filters) {
    try {
        const response = await axios.post(route('admin.subcategories.index'), {
            ...filters,
        });
        return response.data;
    } catch (err) {
        throw new Error(
            err.response?.data?.message || 'Failed to fetch subcategories',
        );
    }
}
