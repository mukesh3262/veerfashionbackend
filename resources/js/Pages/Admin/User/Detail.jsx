import Breadcrumb from '@/Components/Admin/Breadcrumb';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { processDate } from '@/utils/admin/dateUtils';
import { formatPhoneToIntl } from '@/utils/admin/phoneUtils';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { Head } from '@inertiajs/react';
import userImage from '../../../../images/user-profile.png';

export default function UserDetail({
    auth,
    user,
    tokens,
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
            <Head title="User Detail" />

            <Breadcrumb
                breadcrumbs={[
                    {
                        to: route('admin.users.index'),
                        label: 'Users',
                    },
                    { label: 'Detail' },
                ]}
            />

            <div className="pt-5">
                <div className="grid grid-cols-1 gap-5 text-center md:grid-cols-1">
                    <div className="panel">
                        <div className="mb-5 flex items-center justify-between">
                            <h5 className="text-lg font-semibold dark:text-white-light">
                                User Information
                            </h5>
                        </div>

                        <div className="mb-5">
                            <div className="flex flex-col items-center justify-center">
                                {}
                                <img
                                    src={user?.data?.profile_photo ?? userImage}
                                    className="mb-5 h-24 w-24 rounded-full object-cover"
                                />
                                <p className="text-xl font-semibold text-primary">
                                    {user?.data?.name}
                                </p>
                            </div>

                            <div>
                                <div className="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                    <div className="flex items-center justify-between py-2">
                                        <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                            Username
                                        </h6>
                                        <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                            <p className="font-semibold">
                                                {user?.data?.username}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                    <div className="flex items-center justify-between py-2">
                                        <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                            Email
                                        </h6>
                                        <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                            <p className="font-semibold">
                                                {user?.data?.email ? (
                                                    <a
                                                        href="`mailto:${user?.email}`"
                                                        className="text-primary hover:underline"
                                                    >
                                                        {user?.data?.email}
                                                    </a>
                                                ) : (
                                                    '-'
                                                )}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                    <div className="flex items-center justify-between py-2">
                                        <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                            Locale
                                        </h6>
                                        <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                            <p className="font-semibold">
                                                {user?.data?.locale}
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
                                            <p className="font-semibold">
                                                {user?.data?.is_active?.isActive
                                                    ? 'Active'
                                                    : 'Inactive'}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div className="flex items-center justify-between py-2">
                                        <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                            Registered At
                                        </h6>
                                        <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                            <p className="font-semibold">
                                                {processDate(
                                                    user?.data?.created_at,
                                                )}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="panel">
                        <div className="mb-5 flex items-center justify-between">
                            <h5 className="text-lg font-semibold dark:text-white-light">
                                Contact & Verification Details
                            </h5>
                        </div>
                        <div>
                            <div className="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                <div className="flex items-center justify-between py-2">
                                    <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                        Mobile
                                    </h6>
                                    <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                        <p className="font-semibold">
                                            {formatPhoneToIntl(
                                                user?.data?.isd_code +
                                                    user?.data?.mobile,
                                            )}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div className="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                <div className="flex items-center justify-between py-2">
                                    <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                        Email Verified At
                                    </h6>
                                    <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                        <p className="font-semibold">
                                            {processDate(
                                                user?.data?.email_verified_at,
                                            )}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div className="flex items-center justify-between py-2">
                                    <h6 className="font-semibold text-[#515365] dark:text-white-dark">
                                        Mobile Verified At
                                    </h6>
                                    <div className="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                        <p className="font-semibold">
                                            {processDate(
                                                user?.data?.mobile_verified_at,
                                            )}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="panel">
                        <div className="mb-5">
                            <h5 className="text-lg font-semibold dark:text-white-light">
                                Login Details
                            </h5>
                        </div>

                        <div className="mb-5">
                            <div className="table-responsive font-semibold text-[#515365] dark:text-white-light">
                                <table className="whitespace-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Device Type</th>
                                            <th>Device Name</th>
                                            <th>Last Used At</th>
                                            <th>Expired At</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody className="dark:text-white-dark">
                                        {tokens?.data?.map((data, i) => (
                                            <tr key={i}>
                                                <td>{i + 1}</td>
                                                <td>
                                                    <FontAwesomeIcon
                                                        icon={
                                                            {
                                                                1: 'fab fa-apple',
                                                                2: 'fab fa-android',
                                                            }[
                                                                data.device_type
                                                            ] ||
                                                            'fas fa-earth-asia'
                                                        }
                                                    />
                                                </td>

                                                <td>
                                                    {data.device_name || 'N/A'}
                                                </td>
                                                <td>
                                                    {data.last_used_at
                                                        ? processDate(
                                                              data?.last_used_at,
                                                          )
                                                        : 'N/A'}
                                                </td>
                                                <td>
                                                    {data.expired_at || 'N/A'}
                                                </td>
                                                <td>
                                                    <span className="badge bg-success">
                                                        Active
                                                    </span>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
