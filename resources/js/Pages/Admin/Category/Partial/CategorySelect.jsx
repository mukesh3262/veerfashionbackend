import axios from 'axios';
import { AsyncPaginate } from 'react-select-async-paginate';

const CategorySelect = ({ selectedCategory, handleCategoryChange }) => {
    const loadOptions = async (search, loadedOptions, { page }) => {
        try {
            const response = await axios.get(
                route('admin.categories.paginated'),
                {
                    params: {
                        search,
                        per_page: 10,
                        page: page || 1,
                    },
                },
            );

            const data = response.data;

            return {
                options: data.data.map((cat) => ({
                    value: cat.id,
                    label: cat.name,
                })),
                hasMore: data.pagination.hasMore,
                additional: {
                    page: page + 1,
                },
            };
        } catch (error) {
            return {
                options: [],
                hasMore: false,
                additional: {
                    page: page,
                },
            };
        }
    };

    return (
        <AsyncPaginate
            value={selectedCategory}
            loadOptions={loadOptions}
            onChange={handleCategoryChange}
            additional={{
                page: 1,
            }}
            placeholder="Select Category"
            debounceTimeout={300}
        />
    );
};

export default CategorySelect;
