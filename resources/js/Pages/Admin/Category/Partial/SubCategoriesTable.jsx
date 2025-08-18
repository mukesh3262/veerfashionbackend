import Action from '@/Components/Admin/CustomTable/Partials/Action';
import Switch from '@/Components/Admin/CustomTable/Partials/Switch';
import Table from '@/Components/Admin/CustomTable/Table';
import CheckAbility from '@/Components/Admin/Permissions/CheckAbility';
import { Permission } from '@/constants/Permission';
import { getFilters } from '@/helpers/crudHelper';
import { queryClient } from '@/helpers/queryClient';
import { sweetAlert } from '@/helpers/sweet-alert';
import { processDate } from '@/utils/admin/dateUtils';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { Link, useForm } from '@inertiajs/react';
import debounce from 'lodash.debounce';
import { useCallback, useMemo, useState } from 'react';
import { useSelector } from 'react-redux';
import AddEditCategoryModal from './AddEditCategoryModal';
import {
    subCategoryDelete,
    toggleSubcategoryStatus,
} from './subCategoryService';
import { useSubCategories } from './useSubCategories';

export default function SubCategoriesTable({ auth, parentCategory }) {
    const { theme } = useSelector((state) => state.themeConfig);
    const [showSubCategoryModal, setShowSubCategoryModal] = useState(false);
    const [editingSubCategory, setEditingSubCategory] = useState(null);

    // add sub category form
    const {
        data: subCategoryData,
        setData: setSubCategoryData,
        post: postSubCategory,
        errors: subCategoryErrors,
        processing: subCategoryProcessing,
        reset: resetSubCategory,
        clearErrors: clearSubCategoryErrors,
    } = useForm({
        id: '',
        parent_id: '',
        name: '',
        description: '',
        category_icon: null,
        destination: 'admin.categories.show',
    });

    // list subcategory
    const [subCategoryFilters, setSubCategoryFilters] = useState({
        sort: { created_at: 'desc' },
        filters: [{ column: 'parent_id', value: parentCategory.primary_id }],
        columnFilter: true,
        offset: 10,
        page: 1,
    });

    // fetch subcategory list hook
    const {
        isLoading: isLoadingSubCategories,
        listSubCategories: {
            subCategories,
            pagination: subCategoryPagination,
            // filterOptions: subCategoryFilterOptions,
        },
    } = useSubCategories(subCategoryFilters);

    // handle category submit
    const handleSubCategorySubmit = (e) => {
        e.preventDefault();

        if (editingSubCategory) {
            postSubCategory(
                route('admin.subcategories.update', editingSubCategory?.id),
                {
                    forceFormData: true,
                    preserveScroll: true,
                    onSuccess: () => {
                        resetSubCategory();
                        clearSubCategoryErrors();
                        setShowSubCategoryModal(false);
                        setEditingSubCategory(null);
                        queryClient.invalidateQueries(['subCategories']);
                    },
                },
            );
        } else {
            postSubCategory(route('admin.subcategories.store'), {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: () => {
                    resetSubCategory();
                    clearSubCategoryErrors();
                    setShowSubCategoryModal(false);
                    queryClient.invalidateQueries(['subCategories']);
                },
            });
        }
    };

    // handle category edit
    const handleSubCategoryEdit = (e, categoryId) => {
        const category = subCategories?.find((item) => item.id === categoryId);

        if (!category) {
            console.warn('SubCategory not found: ', categoryId);
            return;
        }

        setSubCategoryData({
            id: category.id,
            parent_id: category.parent_category?.id || '',
            name: category.name || '',
            description: category.description || '',
            category_icon: null,
        });

        setEditingSubCategory(category); // set the category that editing
        setShowSubCategoryModal(true);
    };

    const subCategoryCols = useMemo(
        () => [
            { field: 'sr_no', title: '#', sort: false, filter: false },
            {
                field: 'name',
                title: 'Name',
                sort: true,
                filter: true,
                type: 'string',
            },
            {
                field: 'description',
                title: 'Description',
                sort: true,
                filter: true,
                type: 'string',
            },
            {
                field: 'created_at',
                title: 'Created At',
                sort: true,
                filter: true,
                type: 'daterange',
            },
            {
                field: 'is_active',
                title: 'Status',
                sort: false,
                filter: true,
                type: 'select',
                value: '',
                filterOptions: [
                    { value: '', label: 'All' },
                    { value: true, label: 'Active' },
                    { value: false, label: 'Inactive' },
                ],
            },
            ...(auth?.permissions?.includes(Permission.CATEGORY_ADD) ||
            auth?.permissions?.includes(Permission.CATEGORY_VIEW) ||
            auth?.permissions?.includes(Permission.CATEGORY_DELETE)
                ? [
                      {
                          field: 'action',
                          title: 'Action',
                          sort: false,
                          filter: false,
                          headerClass: 'justify-center',
                      },
                  ]
                : []),
        ],
        [auth?.permissions],
    );

    // debounce sort change
    const debouncedSortChange = useMemo(
        () =>
            debounce((column) => {
                let currentSorting = Object.values(subCategoryFilters.sort);

                let sortBy = currentSorting[0] === 'desc' ? 'asc' : 'desc';

                const sorting = { [column]: sortBy };
                setSubCategoryFilters((prev) => ({ ...prev, sort: sorting }));
            }, 300),
        [subCategoryFilters.sort, setSubCategoryFilters], // Only re-create debounce when these dependencies change
    );

    const handleSubCategorySortChange = useCallback(
        (value, index) => {
            debouncedSortChange(value, index);
        },
        [debouncedSortChange], // Only depends on the debounced function
    );

    // debounce filter change
    const debouncedFilterChange = useMemo(
        () =>
            debounce((value, index) => {
                const column = subCategoryCols[index].field;

                const filters = getFilters(
                    subCategoryFilters.filters,
                    column,
                    value,
                );
                setSubCategoryFilters((prev) => ({ ...prev, filters }));
            }, 300),
        [subCategoryCols, subCategoryFilters.filters, setSubCategoryFilters], // Only re-create debounce when these dependencies change
    );

    const handleSubCategoryFilterChange = useCallback(
        (value, index) => {
            debouncedFilterChange(value, index);
        },
        [debouncedFilterChange], // Only depends on the debounced function
    );

    const handleSubCategoryPageChange = (page) => {
        setSubCategoryFilters((prevFilters) => ({
            ...prevFilters,
            page: page,
        }));
    };

    // Handle subcategory delete
    const handleDelete = async (id) => {
        sweetAlert(
            true,
            {
                title: 'Are you sure you want to delete this category?',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'No, Keep',
                preConfirm: async () => {
                    subCategoryDelete(id);
                },
            },
            theme,
        );
    };

    return (
        <>
            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Service SubCategories List
                        </h5>

                        <div className="flex items-center gap-2">
                            <CheckAbility
                                auth={auth}
                                permission={Permission.CATEGORY_ADD}
                            >
                                <Link
                                    onClick={(e) => {
                                        e.preventDefault();
                                        setShowSubCategoryModal(
                                            (prev) => !prev,
                                        );
                                    }}
                                    className="btn btn-primary"
                                >
                                    <FontAwesomeIcon
                                        icon="fas fa-plus"
                                        className="mr-2 text-lg text-white"
                                    />
                                    Add SubCategory
                                </Link>
                            </CheckAbility>
                        </div>
                    </div>

                    <Table
                        data={subCategories || []}
                        filters={subCategoryFilters}
                        headerData={subCategoryCols}
                        currentSort={subCategoryFilters?.sort}
                        offset={subCategoryFilters?.offset}
                        columnFilter={subCategoryFilters?.columnFilter}
                        handleSortChange={handleSubCategorySortChange}
                        handlePageChange={handleSubCategoryPageChange}
                        handleFilterChange={handleSubCategoryFilterChange}
                        pagination={subCategoryPagination}
                        isLoading={isLoadingSubCategories}
                    >
                        <Table.Cell field="created_at">
                            {({ value }) => processDate(value)}
                        </Table.Cell>

                        <Table.Cell field="is_active">
                            {({ value }) => (
                                <Switch
                                    auth={auth}
                                    permission={Permission.CATEGORY_EDIT}
                                    id={value.id}
                                    isActive={value.isActive}
                                    url={route(
                                        'admin.subcategories.change-status',
                                        value.id,
                                    )}
                                    title={`Are you sure you want to {{ACTION}} this category ?`}
                                    handlePreConfirm={toggleSubcategoryStatus}
                                />
                            )}
                        </Table.Cell>

                        <Table.Cell field="action">
                            {({ value }) => (
                                <Action
                                    auth={auth}
                                    show={{
                                        href: route(
                                            'admin.categories.show',
                                            value.id,
                                        ),
                                        permission: Permission.CATEGORY_VIEW,
                                    }}
                                    edit={{
                                        permission: Permission.CATEGORY_EDIT,
                                        onClick: (e) =>
                                            handleSubCategoryEdit(e, value.id),
                                    }}
                                    destroy={{
                                        href: route(
                                            'admin.categories.destroy',
                                            value.id,
                                        ),
                                        permission: Permission.CATEGORY_DELETE,
                                        onClick: () => handleDelete(value.id),
                                    }}
                                />
                            )}
                        </Table.Cell>
                    </Table>
                </div>
            </div>

            {/* SubCategory Modal */}
            <AddEditCategoryModal
                show={showSubCategoryModal}
                onClose={() => {
                    resetSubCategory();
                    clearSubCategoryErrors();
                    setShowSubCategoryModal(false);
                    setEditingSubCategory(null);
                }}
                data={subCategoryData}
                setData={setSubCategoryData}
                errors={subCategoryErrors}
                processing={subCategoryProcessing}
                handleSubmit={handleSubCategorySubmit}
                editingCategory={editingSubCategory}
            />
        </>
    );
}
