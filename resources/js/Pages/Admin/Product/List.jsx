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
import AddEditProductModal from './Partial/AddEditProductModal';

export default function ProductList({
    auth,
    products,
    pagination,
    success,
    error,
    uuid,
}) {
    console.log('product', products);
    const { theme } = useSelector((state) => state.themeConfig);
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

    const handleSortChange = debounce((column) => {
        let currentSorting = Object.values(data.sort);

        let sortBy = currentSorting[0] === 'desc' ? 'asc' : 'desc';

        handleDataUpdate('sort', { [column]: sortBy }, setData, transform);

        post(route('admin.products.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handleFilterChange = debounce((value, index) => {
        const column = cols[index].field;

        const filters = getFilters(data.filters, column, value);

        handleDataUpdate('filters', filters, setData, transform);

        post(route('admin.products.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handlePageChange = (page) => {
        handleDataUpdate('page', page, setData, transform);

        post(route('admin.products.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };

    const cols = [
        { field: 'sr_no', title: '#', sort: false, filter: false },
        {
            field: 'code',
            title: 'Code',
            sort: true,
            filter: true,
            type: 'string',
        },
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
            field: 'base_price',
            title: 'Base Price',
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
        ...(auth?.permissions?.includes(Permission.PRODUCT_ADD) ||
        auth?.permissions?.includes(Permission.PRODUCT_VIEW) ||
        auth?.permissions?.includes(Permission.PRODUCT_DELETE)
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
                title: 'Are you sure you want to delete this product?',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'No, Keep',
                preConfirm: async () => {
                    destroy(route('admin.products.destroy', id));
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
            <Head title="Products List" />

            <Breadcrumb
                breadcrumbs={[
                    { to: '#', label: 'Products' },
                    { label: 'List' },
                ]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                        Products List
                        </h5>

                        <div className="flex items-center gap-2">
                            <CheckAbility
                                auth={auth}
                                permission={Permission.PRODUCT_ADD}
                            >
                                <Link
                                    href={route('admin.products.create')}
                                    className="btn btn-primary"
                                >
                                    <FontAwesomeIcon
                                        icon="fas fa-plus"
                                        className="mr-2 text-lg text-white"
                                    />
                                    Add New
                                </Link>
                            </CheckAbility>
                           
                        </div>
                    </div>

                    <Table
                        data={products?.data}
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
                                    permission={Permission.PRODUCT_EDIT}
                                    id={value.id}
                                    isActive={value.isActive}
                                    url={route(
                                        'admin.products.change-status',
                                        value.id,
                                    )}
                                    title={`Are you sure you want to {{ACTION}} this product ?`}
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
                                                'admin.products.show',
                                                value.id,
                                            ),
                                            permission:
                                                Permission.PRODUCT_VIEW,
                                        }}
                                        edit={{
                                            href: route(
                                                'admin.products.edit',
                                                value.id,
                                            ),
                                            permission: Permission.PRODUCT_EDIT,
                                        }}
                                        destroy={{
                                            href: route(
                                                'admin.products.destroy',
                                                value.id,
                                            ),
                                            permission:
                                                Permission.PRODUCT_DELETE,
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

        </AuthenticatedLayout>
    );
}
