import Breadcrumb from '@/Components/Admin/Breadcrumb';
import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import Table from '@/Components/Admin/CustomTable/Table';
import { getFilters, handleDataUpdate } from '@/helpers/crudHelper';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { processDate } from '@/utils/admin/dateUtils';
import { Head, router, useForm } from '@inertiajs/react';
import debounce from 'lodash/debounce';
import { useState } from 'react';

export default function Seeder({
    auth,
    files,
    pagination,
    success,
    error,
    uuid,
}) {
    const { data, setData, transform, post } = useForm({
        sort: { id: 'asc' },
        filters: [],
        columnFilter: true,
        page: 1,
        totalRecord: 0,
        offset: 10,
    });

    const handleSortChange = debounce((column) => {
        let currentSorting = Object.values(data.sort);

        let sortBy = currentSorting[0] === 'desc' ? 'asc' : 'desc';

        handleDataUpdate('sort', { [column]: sortBy }, setData, transform);

        post(route('admin.setting.seeder'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handleFilterChange = debounce((value, index) => {
        const column = cols[index].field;

        const filters = getFilters(data.filters, column, value);

        handleDataUpdate('filters', filters, setData, transform);

        post(route('admin.setting.seeder'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handlePageChange = (page) => {
        router.post(
            route('admin.setting.seeder'),
            { page: page },
            {
                preserveScroll: true,
                preserveState: true,
                replace: true,
            },
        );
    };

    const [executing, setExecuting] = useState({});

    const executeSeeder = (e, value) => {
        e.preventDefault();
        setExecuting((prev) => ({ ...prev, [value?.path]: true }));

        router.post(
            route('admin.setting.seeder.execute'),
            {
                seeder_path: value?.path,
            },
            {
                preserveScroll: true,
                preserveState: true,
                replace: true,
                onFinish: () => {
                    setExecuting((prev) => ({ ...prev, [value?.path]: false }));
                },
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
            field: 'updated_at',
            title: 'Updated At',
            sort: false,
            filter: true,
            type: 'date',
        },
        {
            field: 'action',
            title: 'Action',
            sort: false,
            filter: false,
            headerClass: 'justify-center',
        },
    ];

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title="Seeders List" />

            <Breadcrumb
                breadcrumbs={[
                    { to: '#', label: 'Settings' },
                    { label: 'Seeders Management' },
                ]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Seeders Management
                        </h5>
                    </div>
                    <Table
                        data={files.data}
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
                        <Table.Cell field="updated_at">
                            {({ value }) => processDate(value)}
                        </Table.Cell>

                        <Table.Cell field="action">
                            {({ value }) => (
                                <>
                                    <div className="flex items-center justify-center gap-2">
                                        <PrimaryButton
                                            id={value.path}
                                            disabled={executing[value?.path]}
                                            onClick={(e) => {
                                                executeSeeder(e, value);
                                            }}
                                            className="btn-sm inline-block"
                                        >
                                            Execute
                                        </PrimaryButton>
                                    </div>
                                </>
                            )}
                        </Table.Cell>
                    </Table>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
