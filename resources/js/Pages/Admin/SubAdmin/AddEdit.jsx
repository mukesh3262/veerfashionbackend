import Breadcrumb from '@/Components/Admin/Breadcrumb';
import LinkButton from '@/Components/Admin/Buttons/LinkButton';
import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import TextInput from '@/Components/Admin/Form/TextInput';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import Select from 'react-select';

export default function SubAdminAddEdit({
    auth,
    admin,
    roles,
    assignedRoles,
    success,
    error,
    uuid,
}) {
    const { data, setData, post, patch, errors, processing } = useForm({
        id: admin?.id ?? '',
        name: admin?.name ?? '',
        email: admin?.email ?? '',
        role: assignedRoles[0]?.value ?? '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();

        if (admin?.id) {
            patch(route('admin.admins.update', admin?.id));
        } else {
            post(route('admin.admins.store'));
        }
    };

    const [selectedRole, setSelectedRole] = useState(assignedRoles ?? null);

    const handleRoleChange = (selectedOption) => {
        setData('role', selectedOption?.value);
        setSelectedRole(selectedOption);
    };

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title={`Sub Admin ${admin?.id ? 'Edit' : 'Add'}`} />

            <Breadcrumb
                breadcrumbs={[
                    {
                        to: route('admin.admins.index'),
                        label: 'Sub Admins',
                    },
                    { label: admin?.id ? 'Edit' : 'Add' },
                ]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Sub Admins {admin?.id ? 'Edit' : 'Add'}
                        </h5>
                    </div>

                    <form onSubmit={handleSubmit}>
                        <div className="mb-3">
                            <InputLabel htmlFor="name" value="Name" required />

                            <TextInput
                                id="name"
                                name="name"
                                value={data.name}
                                placeholder="Enter Name"
                                isFocused={true}
                                onChange={(e) =>
                                    setData('name', e.target.value)
                                }
                            />

                            <InputError
                                message={errors.name}
                                className="mt-2"
                            />
                        </div>

                        <div className="mb-3">
                            <InputLabel
                                htmlFor="email"
                                value="Email"
                                required
                            />

                            <TextInput
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                placeholder="Enter Email"
                                isFocused={false}
                                onChange={(e) =>
                                    setData('email', e.target.value)
                                }
                            />

                            <InputError
                                message={errors.email}
                                className="mt-2"
                            />
                        </div>

                        <div className="mb-3">
                            <InputLabel htmlFor="role" value="Role" required />

                            <Select
                                defaultValue={selectedRole}
                                value={selectedRole}
                                options={roles}
                                isClearable={true}
                                placeholder={'Select Role'}
                                onChange={handleRoleChange}
                                noOptionsMessage={() => 'No Role Found'}
                            />

                            <InputError
                                message={errors.role}
                                className="mt-2"
                            />
                        </div>

                        <div className="!mt-6 flex items-center justify-end gap-3">
                            <LinkButton href={route('admin.admins.index')}>
                                Cancel
                            </LinkButton>

                            <PrimaryButton disabled={processing}>
                                Save
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
