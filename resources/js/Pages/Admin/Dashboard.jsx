import Breadcrumb from '@/Components/Admin/Breadcrumb';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard({ auth, data, success, error, uuid }) {
    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title="Dashboard" />

            <Breadcrumb breadcrumbs={[{ label: 'Dashboard' }]} />

            <div className="pt-5">
                <div className="mb-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    {/* Users */}
                    <div className="panel h-full sm:col-span-2 lg:col-span-1">
                        <div className="flex items-center justify-between dark:text-white-light">
                            <h5 className="text-lg font-semibold">Products</h5>
                        </div>

                        <div className="my-3 text-3xl font-bold text-[#e95f2b]">
                            <span className="ltr:mr-2 rtl:ml-2">
                                {data?.productsCount?.totalCount ?? 0}
                            </span>
                        </div>

                        <div className="grid gap-8 text-sm font-bold text-[#515365] sm:grid-cols-2">
                            <div>
                                <div>
                                    <div>Active Users</div>
                                    <div className="text-lg text-[#f8538d]">
                                        {data?.productsCount?.activeCount ?? 0}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <div>Inactive Users</div>
                                    <div className="text-lg text-[#f8538d]">
                                        {data?.productsCount?.inactiveCount ?? 0}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Content Pages */}
                    <div className="panel h-full sm:col-span-2 lg:col-span-1">
                        <div className="flex items-center justify-between dark:text-white-light">
                            <h5 className="text-lg font-semibold">Categories</h5>
                        </div>

                        <div className="my-3 text-3xl font-bold text-[#e95f2b]">
                            <span className="ltr:mr-2 rtl:ml-2">
                                {data?.categoriesCount?.totalCount ?? 0}
                            </span>
                        </div>

                        <div className="grid gap-8 text-sm font-bold text-[#515365] sm:grid-cols-2">
                            <div>
                                <div>
                                    <div>Main Categories</div>
                                    <div className="text-lg text-[#f8538d]">
                                        {data?.categoriesCount?.mainCategoriesCount ?? 0}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <div>Sub Categories</div>
                                    <div className="text-lg text-[#f8538d]">
                                        {data?.categoriesCount?.subCategoriesCount ?? 0}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
