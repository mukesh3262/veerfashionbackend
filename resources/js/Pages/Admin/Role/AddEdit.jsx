import Breadcrumb from '@/Components/Admin/Breadcrumb';
import LinkButton from '@/Components/Admin/Buttons/LinkButton';
import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import TextInput from '@/Components/Admin/Form/TextInput';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import Select from 'react-select';

export default function RoleAddEdit({
    auth,
    role,
    permissions,
    selectedPermissions,
    success,
    error,
    uuid,
}) {
    const { data, setData, post, patch, errors, processing } = useForm({
        id: role?.id ?? '',
        name: role?.name ?? '',
        permissions: [],
    });

    const handleSubmit = (e) => {
        e.preventDefault();

        if (role?.id) {
            patch(route('admin.roles.update', role?.id));
        } else {
            post(route('admin.roles.store'));
        }
    };

    const [selectedPermission, setSelectedPermission] =
        useState(selectedPermissions);
    const handlePermissionChange = (selectedOption) => {
        setSelectedPermission(selectedOption);
        const selectedItems = [];
        selectedOption.map((selected) => selectedItems.push(selected?.value));
        setData('permissions', selectedItems);
    };

    useEffect(() => {
        const permissionValues = selectedPermissions?.map(
            (permission) => permission.value,
        );
        setData('permissions', permissionValues);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [selectedPermissions]);

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title={`Roles ${role?.id ? 'Edit' : 'Add'}`} />

            <Breadcrumb
                breadcrumbs={[
                    {
                        to: route('admin.roles.index'),
                        label: 'Roles',
                    },
                    { label: role?.id ? 'Edit' : 'Add' },
                ]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Roles {role?.id ? 'Edit' : 'Add'}
                        </h5>
                    </div>

                    <form onSubmit={handleSubmit}>
                        <div className="mb-3">
                            <InputLabel htmlFor="name" value="Name" required />

                            <TextInput
                                id="name"
                                name="name"
                                value={data.name}
                                placeholder="Enter Role Name"
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
                                htmlFor="permissions"
                                value="Permissions"
                                required
                            />

                            <Select
                                defaultValue={selectedPermission}
                                value={selectedPermission}
                                options={permissions}
                                isClearable={true}
                                placeholder={'Select Permissions'}
                                isMulti={true}
                                onChange={handlePermissionChange}
                            />

                            <InputError
                                message={errors.permissions}
                                className="mt-2"
                            />
                        </div>

                        <div className="!mt-6 flex items-center justify-end gap-3">
                            <LinkButton href={route('admin.roles.index')}>
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
