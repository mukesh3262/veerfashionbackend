export function getFilters(filters, column, value) {
    const localFilters = filters;

    if (localFilters.length === 0) {
        localFilters.push({ column, value });
    } else {
        const filterIndex = localFilters.findIndex(
            (filter) => filter.column === column,
        );

        if (filterIndex !== -1) {
            localFilters[filterIndex] = { column, value };
        } else {
            localFilters.push({ column, value });
        }
    }

    return localFilters;
}

export function handleDataUpdate(key, value, setData, transform) {
    setData((data) => ({
        ...data,
        [key]: value,
    }));

    transform((data) => ({
        ...data,
        [key]: value,
    }));
}
