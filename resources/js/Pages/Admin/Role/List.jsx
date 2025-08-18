import Breadcrumb from '@/Components/Admin/Breadcrumb';
import Action from '@/Components/Admin/CustomTable/Partials/Action';
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
import { useSelector } from 'react-redux';

export default function RoleList({
    auth,
    roles,
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

        post(route('admin.roles.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handleFilterChange = debounce((value, index) => {
        const column = cols[index].field;

        const filters = getFilters(data.filters, column, value);

        handleDataUpdate('filters', filters, setData, transform);

        post(route('admin.roles.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handlePageChange = (page) => {
        router.post(
            route('admin.roles.index'),
            { page: page },
            {
                preserveScroll: true,
                preserveState: true,
                replace: true,
            },
        );
    };

    const cols = [
        { field: 'id', title: '#', sort: true, filter: false },
        {
            field: 'name',
            title: 'Name',
            sort: true,
            filter: true,
            type: 'string',
        },
        {
            field: 'created_at',
            title: 'Created At',
            sort: false,
            filter: true,
            type: 'date',
        },
        ...(auth?.permissions?.includes(Permission.ROLE_EDIT) ||
        auth?.permissions?.includes(Permission.ROLE_DELETE)
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
                title: 'Are you sure you want to delete this sub admin?',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'No, Keep',
                preConfirm: async () => {
                    destroy(route('admin.roles.destroy', id));
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
            <Head title="Roles List" />

            <Breadcrumb
                breadcrumbs={[{ to: '#', label: 'Roles' }, { label: 'List' }]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Roles List
                        </h5>

                        <div className="flex items-center gap-2">
                            <CheckAbility
                                auth={auth}
                                permission={Permission.ROLE_ADD}
                            >
                                <Link
                                    href={route('admin.roles.create')}
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
                        data={roles.data}
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

                        <Table.Cell field="action">
                            {({ value }) => (
                                <Action
                                    auth={auth}
                                    edit={{
                                        href: route(
                                            'admin.roles.edit',
                                            value.id,
                                        ),
                                        permission: Permission.ROLE_EDIT,
                                    }}
                                    destroy={{
                                        href: route(
                                            'admin.roles.destroy',
                                            value.id,
                                        ),
                                        permission: Permission.ROLE_DELETE,
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
