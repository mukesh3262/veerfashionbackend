import Breadcrumb from '@/Components/Admin/Breadcrumb';
import Action from '@/Components/Admin/CustomTable/Partials/Action';
import Switch from '@/Components/Admin/CustomTable/Partials/Switch';
import Table from '@/Components/Admin/CustomTable/Table';
import { Permission } from '@/constants/Permission';
import { getFilters, handleDataUpdate } from '@/helpers/crudHelper';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { processDate } from '@/utils/admin/dateUtils';
import { Head, router, useForm } from '@inertiajs/react';
import debounce from 'lodash/debounce';

export default function UserList({
    auth,
    users,
    pagination,
    success,
    error,
    uuid,
}) {
    const { data, setData, post, transform } = useForm({
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

        post(route('admin.users.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handleFilterChange = debounce((value, index) => {
        const column = cols[index].field;

        const filters = getFilters(data.filters, column, value);

        handleDataUpdate('filters', filters, setData, transform);

        post(route('admin.users.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handlePageChange = (page) => {
        router.post(
            route('admin.users.index'),
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
            field: 'email',
            title: 'Email',
            sort: false,
            filter: true,
            type: 'string',
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
        {
            field: 'created_at',
            title: 'Created At',
            sort: false,
            filter: true,
            type: 'date',
        },
        ...(auth?.permissions?.includes(Permission.USER_VIEW)
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

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title="Users List" />

            <Breadcrumb
                breadcrumbs={[{ to: '#', label: 'Users' }, { label: 'List' }]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Users List
                        </h5>
                    </div>

                    <Table
                        data={users.data}
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
                        <Table.Cell field="email">
                            {({ value }) => (
                                <a href={`mailto:${value}`}>{value}</a>
                            )}
                        </Table.Cell>

                        <Table.Cell field="is_active">
                            {({ value }) => (
                                <Switch
                                    auth={auth}
                                    permission={Permission.USER_EDIT}
                                    id={value.id}
                                    isActive={value.isActive}
                                    url={route(
                                        'admin.users.change-status',
                                        value.id,
                                    )}
                                    title={`Are you sure you want to {{ACTION}} this user?`}
                                />
                            )}
                        </Table.Cell>

                        <Table.Cell field="created_at">
                            {({ value }) => processDate(value)}
                        </Table.Cell>

                        <Table.Cell field="action">
                            {({ value }) => (
                                <Action
                                    auth={auth}
                                    show={{
                                        href: route(
                                            'admin.users.show',
                                            value.id,
                                        ),
                                        permission: Permission.USER_VIEW,
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
