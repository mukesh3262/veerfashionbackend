import Breadcrumb from '@/Components/Admin/Breadcrumb';
import Switch from '@/Components/Admin/CustomTable/Partials/Switch';
import FilePreviewer from '@/Components/Admin/FilePreviewer';
import { Permission } from '@/constants/Permission';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { processDate } from '@/utils/admin/dateUtils';
import { Head } from '@inertiajs/react';
import SubCategoriesTable from './Partial/SubCategoriesTable';

export default function CategoryDetail({
    auth,
    category: { data: category } = {},
    success,
    error,
    uuid,
}) {
    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title="Service Category Detail" />

            <Breadcrumb
                breadcrumbs={[
                    {
                        to: route('admin.categories.index'),
                        label: 'Service Categories',
                    },
                    category?.parent_category && {
                        to: route(
                            'admin.categories.show',
                            category.parent_category.uuid,
                        ),
                        label: category.parent_category.name,
                    },
                    { label: 'Detail' },
                ]}
            />

            <div className="pt-5">
                <div className="grid grid-cols-1 gap-5 text-center md:grid-cols-1">
                    <div className="panel">
                        <div className="mb-5 flex items-center justify-between">
                            <h5 className="text-lg font-semibold dark:text-white-light">
                                Service Category Information
                            </h5>
                        </div>

                        <div className="mb-5">
                            <div>
                                <div className="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                    <div className="flex items-center justify-between py-2">
                                        <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                            Name
                                        </h6>
                                        <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                            <p className="font-semibold">
                                                {category?.name}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                    <div className="flex items-center justify-between py-2">
                                        <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                            Description
                                        </h6>
                                        <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                            <p className="font-semibold">
                                                {category?.description}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                    <div className="flex items-center justify-between py-2">
                                        <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                            Status
                                        </h6>
                                        <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                            <Switch
                                                auth={auth}
                                                permission={
                                                    Permission.CATEGORY_EDIT
                                                }
                                                id={category?.id}
                                                isActive={
                                                    category?.is_active
                                                        ?.isActive
                                                }
                                                url={route(
                                                    'admin.categories.change-status',
                                                    category?.id,
                                                )}
                                                title={`Are you sure you want to {{ACTION}} this category ?`}
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div className="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                    <div className="flex items-center justify-between py-2">
                                        <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                            Created Date
                                        </h6>
                                        <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                            <p className="font-semibold">
                                                {processDate(
                                                    category?.created_at,
                                                )}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div className="flex items-center justify-between py-2">
                                        <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                            Icon
                                        </h6>
                                        <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                            <FilePreviewer
                                                fileUrl={category.icon_url}
                                                fileName={
                                                    category.icon || 'default'
                                                }
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {!category?.parent_category && (
                    <div className="mb-6">
                        {/* Active Bookings */}
                        <SubCategoriesTable
                            auth={auth}
                            parentCategory={category}
                        />
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
