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
import { Head, Link, router, useForm } from '@inertiajs/react';
import debounce from 'lodash/debounce';
import { useState } from 'react';
import { useSelector } from 'react-redux';

export default function BannerList({
    auth,
    banners,
    pagination,
    success,
    error,
    uuid,
}) {
    const { theme } = useSelector((state) => state.themeConfig);

    const {
        data,
        setData,
        post,
        transform,
        delete: destroy,
    } = useForm({
        sort: { id: 'asc' },
        filters: [],
        columnFilter: true,
        currentPage: 1,
        totalRecord: 0,
        offset: 10,
    });

    const handleSortChange = debounce((column) => {
        let currentSorting = Object.values(data.sort);

        let sortBy = currentSorting[0] === 'desc' ? 'asc' : 'desc';

        handleDataUpdate('sort', { [column]: sortBy }, setData, transform);

        post(route('admin.banners.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handleFilterChange = debounce((value, index) => {
        const column = cols[index].field;

        const filters = getFilters(data.filters, column, value);

        handleDataUpdate('filters', filters, setData, transform);

        post(route('admin.banners.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handlePageChange = (page) => {
        router.post(
            route('admin.banners.index'),
            { page: page },
            {
                preserveScroll: true,
                preserveState: true,
                replace: true,
            },
        );
    };

    const cols = [
        { field: 'sr_no', title: 'Sr No', sort: false, filter: false },
        {
            field: 'title',
            title: 'Title',
            sort: true,
            filter: true,
            type: 'string',
        },

        {
            field: 'image_url',
            title: 'Image',
            sort: false,
            filter: false,
            type: 'string',
        },

        {
            field: 'created_at',
            title: 'Created At',
            sort: false,
            filter: true,
            type: 'date',
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
        ...(auth?.permissions?.includes(Permission.BANNER_EDIT) ||
        auth?.permissions?.includes(Permission.BANNER_VIEW) ||
        auth?.permissions?.includes(Permission.BANNER_DELETE)
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
                title: 'Are you sure you want to delete this banner?',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'No, Keep',
                preConfirm: async () => {
                    destroy(route('admin.banners.destroy', id));
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
            <Head title="Banners List" />

            <Breadcrumb
                breadcrumbs={[{ to: '#', label: 'Banners' }, { label: 'List' }]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Banners List
                        </h5>

                        <div className="flex items-center gap-2">
                            <CheckAbility
                                auth={auth}
                                permission={Permission.BANNER_ADD}
                            >
                                <Link
                                    href={route('admin.banners.create')}
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
                        data={banners?.data}
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
                        <Table.Cell field="image_url">
                            {({ value }) => {
                                // To show image without preview
                                // <img
                                //     src={value}
                                //     alt="banner"
                                //     className="h-20 w-40 object-cover"
                                // />
                                const [open, setOpen] = useState(false);

                                return (
                                    <>
                                        <img
                                            src={value}
                                            alt="banner"
                                            className="h-20 w-40 cursor-pointer rounded-md object-cover shadow"
                                            onClick={() => setOpen(true)}
                                        />

                                        {open && (
                                            <div
                                                className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70"
                                                onClick={() => setOpen(false)}
                                            >
                                                <img
                                                    src={value}
                                                    alt="banner preview"
                                                    className="max-h-[90%] max-w-[90%] rounded-lg shadow-lg"
                                                />
                                            </div>
                                        )}
                                    </>
                                );
                            }}
                        </Table.Cell>
                        <Table.Cell field="created_at">
                            {({ value }) => processDate(value)}
                        </Table.Cell>

                        <Table.Cell field="is_active">
                            {({ value }) => (
                                <Switch
                                    auth={auth}
                                    permission={Permission.BANNER_EDIT}
                                    id={value.id}
                                    isActive={value.isActive}
                                    url={route(
                                        'admin.banners.change-status',
                                        value.id,
                                    )}
                                    title={`Are you sure you want to {{ACTION}} this banner?`}
                                />
                            )}
                        </Table.Cell>

                        <Table.Cell field="action">
                            {({ value }) => (
                                <Action
                                    auth={auth}
                                    edit={{
                                        href: route(
                                            'admin.banners.edit',
                                            value.id,
                                        ),
                                        permission: Permission.BANNER_EDIT,
                                    }}
                                    destroy={{
                                        href: route(
                                            'admin.banners.destroy',
                                            value.id,
                                        ),
                                        permission: Permission.BANNER_DELETE,
                                        onClick: () => handleDelete(value.id),
                                    }}
                                />
                            )}
                        </Table.Cell>
                    </Table>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
