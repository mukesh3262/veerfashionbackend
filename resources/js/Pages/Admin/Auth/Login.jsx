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
            <div className="mb-6 text-center">
                <h2 className="text-3xl font-extrabold text-yellow-400 drop-shadow-md">
                    Welcome Back 👋
                </h2>
                <p className="mt-2 text-gray-200">
                    Sign in to manage your fashion admin panel
                </p>
            </div>

            {/* Form */}
            <form onSubmit={handleSubmit} className="space-y-5">
                {/* Email */}
                <div>
                    <InputLabel
                        htmlFor="email"
                        value="Email"
                        className="text-white"
                    />
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
                    <InputError
                        message={errors.email}
                        className="mt-2 text-yellow-300"
                    />
                </div>

                {/* Password */}
                <div>
                    <InputLabel
                        htmlFor="password"
                        value="Password"
                        className="text-white"
                    />
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
                    <InputError
                        message={errors.password}
                        className="mt-2 text-yellow-300"
                    />
                </div>

                {/* Remember & Forgot */}
                <div className="flex items-center justify-between text-sm">
                    <label className="flex items-center space-x-2 text-gray-200">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) =>
                                setData('remember', e.target.checked)
                            }
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
                    className="w-full rounded-xl bg-yellow-400 py-3 font-bold text-black transition hover:bg-yellow-300"
                    disabled={processing}
                >
                    Sign In
                </PrimaryButton>
            </form>

            {/* Animated Redirect Button */}
            <div className="mt-6 text-center">
                <a
                    href={import.meta.env.VITE_FRONTEND_URL}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-block bg-black text-yellow-400 border-2 border-yellow-400 font-semibold px-4 py-1 rounded-xl text-xs
                            hover:bg-yellow-400 hover:text-black transition duration-300
                            animate-[pulseGlow_2s_ease-in-out_infinite]"
                >
                    🌐 Go to Website
                </a>
            </div>
            
        </GuestLayout>
    );
}

