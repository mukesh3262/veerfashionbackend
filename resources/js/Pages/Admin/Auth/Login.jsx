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

            {/* Heading */}
            <div className="text-center mb-6">
                <h2 className="text-3xl font-extrabold text-yellow-400 drop-shadow-md">
                    Welcome Back 👋
                </h2>
                <p className="text-gray-200 mt-2">
                    Sign in to manage your fashion admin panel
                </p>
            </div>

            {/* Form */}
            <form onSubmit={handleSubmit} className="space-y-5">
                {/* Email */}
                <div>
                    <InputLabel htmlFor="email" value="Email" className="text-white" />
                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        placeholder="Enter your email"
                        autoComplete="username"
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                        className="w-full"
                    />
                    <InputError message={errors.email} className="mt-2 text-yellow-300" />
                </div>

                {/* Password */}
                <div>
                    <InputLabel htmlFor="password" value="Password" className="text-white" />
                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        placeholder="Enter your password"
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                        className="w-full"
                    />
                    <InputError message={errors.password} className="mt-2 text-yellow-300" />
                </div>

                {/* Remember & Forgot */}
                <div className="flex items-center justify-between text-sm">
                    <label className="flex items-center space-x-2 text-gray-200">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                        />
                        <span>Remember me</span>
                    </label>

                    {canResetPassword && (
                        <Link
                            href={route('admin.password.request')}
                            className="text-yellow-400 hover:underline"
                        >
                            Forgot password?
                        </Link>
                    )}
                </div>

                {/* Sign In */}
                <PrimaryButton
                    className="w-full bg-yellow-400 text-black font-bold py-3 rounded-xl hover:bg-yellow-300 transition"
                    disabled={processing}
                >
                    Sign In
                </PrimaryButton>
            </form>
        </GuestLayout>
    );
}