import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import Checkbox from '@/Components/Admin/Form/Checkbox';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import TextInput from '@/Components/Admin/Form/TextInput';
import GuestLayout from '@/Layouts/Admin/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ canResetPassword, success, error, uuid }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const handleSubmit = (e) => {
        e.preventDefault();

        post(route('admin.login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout success={success} error={error} uuid={uuid}>
            <Head title="Log in" />

            <div className="relative flex w-full items-center justify-center dark:text-gray-200 lg:w-1/2">
                <div className="max-w-[480px] p-5 md:p-10">
                    <h2 className="mb-3 text-3xl font-bold">Sign In</h2>

                    <p className="mb-7 w-[500px]">
                        Please enter your email and password to sign in.
                    </p>

                    <form className="space-y-5" onSubmit={handleSubmit}>
                        {/* Email */}
                        <div>
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
                                placeholder="Enter E-Mail Address"
                                autoComplete="username"
                                isFocused={true}
                                onChange={(e) =>
                                    setData('email', e.target.value)
                                }
                            />

                            <InputError
                                message={errors.email}
                                className="mt-2"
                            />
                        </div>

                        {/* Password */}
                        <div>
                            <InputLabel
                                htmlFor="password"
                                value="Password"
                                required
                            />

                            <TextInput
                                id="password"
                                type="password"
                                name="password"
                                value={data.password}
                                placeholder="Enter Password"
                                autoComplete="current-password"
                                onChange={(e) =>
                                    setData('password', e.target.value)
                                }
                            />

                            <InputError
                                message={errors.password}
                                className="mt-2"
                            />
                        </div>

                        {/* Remember Me */}
                        <div>
                            <Checkbox
                                name="remember"
                                checked={data.remember}
                                onChange={(e) =>
                                    setData('remember', e.target.checked)
                                }
                            >
                                <span className="text-white-dark">
                                    {' '}
                                    Remember me{' '}
                                </span>
                            </Checkbox>
                        </div>

                        <PrimaryButton className="w-full" disabled={processing}>
                            Sign In
                        </PrimaryButton>

                        {canResetPassword && (
                            <p>
                                <Link
                                    href={route('admin.password.request')}
                                    className="font-bold text-primary hover:underline"
                                >
                                    Forgot your password?
                                </Link>
                            </p>
                        )}
                    </form>
                </div>
            </div>
        </GuestLayout>
    );
}
