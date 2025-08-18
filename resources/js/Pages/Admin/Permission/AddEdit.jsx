import Breadcrumb from '@/Components/Admin/Breadcrumb';
import LinkButton from '@/Components/Admin/Buttons/LinkButton';
import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import TextInput from '@/Components/Admin/Form/TextInput';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function PermissionAddEdit({
    auth,
    permission,
    success,
    error,
    uuid,
}) {
    const { data, setData, post, patch, errors, processing } = useForm({
        id: permission?.id ?? '',
        name: permission?.name ?? '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();

        if (permission?.id) {
            patch(route('admin.permissions.update', permission?.id));
        } else {
            post(route('admin.permissions.store'));
        }
    };

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title={`Permissions ${permission?.id ? 'Edit' : 'Add'}`} />

            <Breadcrumb
                breadcrumbs={[
                    {
                        to: route('admin.permissions.index'),
                        label: 'Permissions',
                    },
                    { label: permission?.id ? 'Edit' : 'Add' },
                ]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Permissions {permission?.id ? 'Edit' : 'Add'}
                        </h5>
                    </div>

                    <form onSubmit={handleSubmit}>
                        <div>
                            <InputLabel htmlFor="name" value="Name" required />

                            <TextInput
                                id="name"
                                name="name"
                                value={data.name}
                                placeholder="Enter Permission Name"
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

                        <div className="!mt-6 flex items-center justify-end gap-3">
                            <LinkButton href={route('admin.permissions.index')}>
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
