import Breadcrumb from '@/Components/Admin/Breadcrumb';
import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import TextInput from '@/Components/Admin/Form/TextInput';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { useRef } from 'react';

export default function Edit({ auth, success, error, uuid }) {
    const user = auth.user;
    const passwordInput = useRef();
    const currentPasswordInput = useRef();

    const { data, setData, errors, put, reset, processing } = useForm({
        email: user?.email ?? '',
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();

        put(route('admin.password.update'), {
            preserveScroll: true,
            onSuccess: () => reset(),
            onError: (errors) => {
                if (errors.password) {
                    reset('password', 'password_confirmation');
                    passwordInput.current.focus();
                }

                if (errors.current_password) {
                    reset('current_password');
                    currentPasswordInput.current.focus();
                }
            },
        });
    };
    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title="Profile" />

            <Breadcrumb
                breadcrumbs={[
                    { to: '#', label: 'My Profile' },
                    { label: 'Update Password' },
                ]}
            />

            <div className="pt-5">
                <div className="panel">
                    <h2 className="mb-3 text-lg font-medium text-gray-900 dark:text-gray-100">
                        Profile Information
                    </h2>

                    <div className="space-y-5">
                        {/* Name */}
                        <div>
                            <p>
                                Name:&nbsp;
                                <strong>{user.name}</strong>
                            </p>
                        </div>

                        {/* Email */}
                        <div>
                            <p>
                                Email:&nbsp;
                                <strong>{user.email}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div className="pt-5">
                <div className="panel">
                    <h2 className="mb-3 text-lg font-medium text-gray-900 dark:text-gray-100">
                        Update Password
                    </h2>

                    <form className="space-y-5" onSubmit={handleSubmit}>
                        {/* Current Password */}
                        <div>
                            <InputLabel
                                htmlFor="current_password"
                                value="Current Password"
                                required
                            />

                            <TextInput
                                id="current_password"
                                ref={currentPasswordInput}
                                value={data.current_password}
                                onChange={(e) =>
                                    setData('current_password', e.target.value)
                                }
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="current-password"
                            />

                            <InputError
                                message={errors.current_password}
                                className="mt-2"
                            />
                        </div>

                        {/* New Password */}
                        <div>
                            <InputLabel
                                htmlFor="password"
                                value="New Password"
                                required
                            />

                            <TextInput
                                id="password"
                                ref={passwordInput}
                                value={data.password}
                                onChange={(e) =>
                                    setData('password', e.target.value)
                                }
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                            />

                            <InputError
                                message={errors.password}
                                className="mt-2"
                            />
                        </div>

                        <div>
                            <InputLabel
                                htmlFor="password_confirmation"
                                value="Confirm Password"
                                required
                            />

                            <TextInput
                                id="password_confirmation"
                                value={data.password_confirmation}
                                onChange={(e) =>
                                    setData(
                                        'password_confirmation',
                                        e.target.value,
                                    )
                                }
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                            />

                            <InputError
                                message={errors.password_confirmation}
                                className="mt-2"
                            />
                        </div>

                        <PrimaryButton disabled={processing}>
                            Update Password
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
