import Breadcrumb from '@/Components/Admin/Breadcrumb';
import Action from '@/Components/Admin/CustomTable/Partials/Action';
import Switch from '@/Components/Admin/CustomTable/Partials/Switch';
import Table from '@/Components/Admin/CustomTable/Table';
import CheckAbility from '@/Components/Admin/Permissions/CheckAbility';
import { Permission } from '@/constants/Permission';
import { getFilters, handleDataUpdate } from '@/helpers/crudHelper';
import { sweetAlert } from '@/helpers/sweet-alert';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { processDate } from '@/utils/admin/dateUtils';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { Head, Link, useForm } from '@inertiajs/react';
import debounce from 'lodash/debounce';
import { useState } from 'react';
import { useSelector } from 'react-redux';
import AddEditCategoryModal from './Partial/AddEditCategoryModal';

export default function CategoryList({
    auth,
    categories,
    pagination,
    success,
    error,
    uuid,
}) {
    const { theme } = useSelector((state) => state.themeConfig);
    const [showCategoryModal, setShowCategoryModal] = useState(false);
    const [showSubCategoryModal, setShowSubCategoryModal] = useState(false);
    const [editingCategory, setEditingCategory] = useState(null);

    // add category form
    const {
        data: categoryData,
        setData: setCategoryData,
        post: postCategory,
        errors: categoryErrors,
        processing: categoryProcessing,
        reset: resetCategory,
        clearErrors: clearCategoryErrors,
    } = useForm({
        id: '',
        name: '',
        description: '',
        category_icon: null,
    });

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
        destination: 'admin.categories.index',
    });

    // list category form
    const {
        data,
        setData,
        post,
        transform,
        delete: destroy,
    } = useForm({
        sort: { created_at: 'desc' },
        filters: [],
        columnFilter: true,
        currentPage: 1,
        totalRecord: 0,
        offset: 10,
        page: 1,
    });

    // handle category submit
    const handleCategorySubmit = (e) => {
        e.preventDefault();

        if (editingCategory) {
            postCategory(
                route('admin.categories.update', editingCategory?.id),
                {
                    forceFormData: true,
                    preserveScroll: true,
                    onSuccess: () => {
                        resetCategory();
                        clearCategoryErrors();
                        setShowCategoryModal(false);
                        setEditingCategory(null);
                    },
                },
            );
        } else {
            postCategory(route('admin.categories.store'), {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: () => {
                    resetCategory();
                    clearCategoryErrors();
                    setShowCategoryModal(false);
                },
            });
        }
    };

    // handle category edit
    const handleCategoryEdit = (e, categoryId) => {
        const category = categories?.data?.find(
            (item) => item.id === categoryId,
        );

        if (!category) {
            console.warn('Category not found: ', categoryId);
            return;
        }

        setCategoryData({
            id: category.id,
            name: category.name || '',
            description: category.description || '',
            category_icon: null,
        });
        setEditingCategory(category); // set the category that editing
        setShowCategoryModal(true);
    };

    // handle sub category submit
    const handleSubCategorySubmit = (e) => {
        e.preventDefault();

        postSubCategory(route('admin.subcategories.store'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                resetSubCategory();
                clearSubCategoryErrors();
                setShowSubCategoryModal(false);
            },
        });
    };

    const handleSortChange = debounce((column) => {
        let currentSorting = Object.values(data.sort);

        let sortBy = currentSorting[0] === 'desc' ? 'asc' : 'desc';

        handleDataUpdate('sort', { [column]: sortBy }, setData, transform);

        post(route('admin.categories.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handleFilterChange = debounce((value, index) => {
        const column = cols[index].field;

        const filters = getFilters(data.filters, column, value);

        handleDataUpdate('filters', filters, setData, transform);

        post(route('admin.categories.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handlePageChange = (page) => {
        handleDataUpdate('page', page, setData, transform);

        post(route('admin.categories.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };

    const cols = [
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
    ];

    const handleDelete = async (id) => {
        sweetAlert(
            true,
            {
                title: 'Are you sure you want to delete this category?',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'No, Keep',
                preConfirm: async () => {
                    destroy(route('admin.categories.destroy', id));
                },
            },
            theme,
        );
    };

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title="Categories List" />

            <Breadcrumb
                breadcrumbs={[
                    { to: '#', label: 'Categories' },
                    { label: 'List' },
                ]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Categories List
                        </h5>

                        <div className="flex items-center gap-2">
                            <CheckAbility
                                auth={auth}
                                permission={Permission.CATEGORY_ADD}
                            >
                                <Link
                                    onClick={(e) => {
                                        e.preventDefault();
                                        setShowCategoryModal((prev) => !prev);
                                    }}
                                    className="btn btn-primary"
                                >
                                    <FontAwesomeIcon
                                        icon="fas fa-plus"
                                        className="mr-2 text-lg text-white"
                                    />
                                    Add Category
                                </Link>
                            </CheckAbility>
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
                        data={categories?.data}
                        filters={data}
                        headerData={cols}
                        currentPage={data.currentPage}
                        totalRecord={data.totalRecord}
                        currentSort={data.sort}
                        offset={data.offset}
                        columnFilter={data.columnFilter}
                        handleSortChange={handleSortChange}
                        handlePageChange={handlePageChange}
                        handleFilterChange={handleFilterChange}
                        pagination={pagination}
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
                                        'admin.categories.change-status',
                                        value.id,
                                    )}
                                    title={`Are you sure you want to {{ACTION}} this category ?`}
                                />
                            )}
                        </Table.Cell>

                        <Table.Cell field="action">
                            {({ value }) => (
                                <>
                                    <Action
                                        auth={auth}
                                        show={{
                                            href: route(
                                                'admin.categories.show',
                                                value.id,
                                            ),
                                            permission:
                                                Permission.CATEGORY_VIEW,
                                        }}
                                        edit={{
                                            permission:
                                                Permission.CATEGORY_EDIT,
                                            onClick: (e) =>
                                                handleCategoryEdit(e, value.id),
                                        }}
                                        destroy={{
                                            href: route(
                                                'admin.categories.destroy',
                                                value.id,
                                            ),
                                            permission:
                                                Permission.CATEGORY_DELETE,
                                            onClick: () =>
                                                handleDelete(value.id),
                                        }}
                                    />
                                </>
                            )}
                        </Table.Cell>
                    </Table>
                </div>
            </div>

            {/* Category Modal */}
            <AddEditCategoryModal
                show={showCategoryModal}
                onClose={() => {
                    resetCategory();
                    clearCategoryErrors();
                    setShowCategoryModal(false);
                    setEditingCategory(null);
                }}
                data={categoryData}
                setData={setCategoryData}
                errors={categoryErrors}
                processing={categoryProcessing}
                handleSubmit={handleCategorySubmit}
                editingCategory={editingCategory}
            />

            {/* SubCategory Modal */}
            <AddEditCategoryModal
                show={showSubCategoryModal}
                onClose={() => {
                    resetSubCategory();
                    clearSubCategoryErrors();
                    setShowSubCategoryModal(false);
                }}
                data={subCategoryData}
                setData={setSubCategoryData}
                errors={subCategoryErrors}
                processing={subCategoryProcessing}
                handleSubmit={handleSubCategorySubmit}
            />
        </AuthenticatedLayout>
    );
}
