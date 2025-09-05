import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import TextInput from '@/Components/Admin/Form/TextInput';
import GuestLayout from '@/Layouts/Admin/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function ForgotPassword({ success, error, uuid }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();

        post(route('admin.password.email'), {
            onFinish: () => reset('email'),
        });
    };

    return (
        <GuestLayout success={success} error={error} uuid={uuid}>
            <Head title="Forgot Password" />

            {/* Form Card */}
            <div className="text-center mb-6">
                <h2 className="text-3xl font-extrabold text-yellow-400 drop-shadow-md">
                    Forgot Password?
                </h2>
                <p className="text-gray-200 mt-2">
                    No worries! Enter your email and we’ll send you a reset link.
                </p>
            </div>

            <form className="space-y-5" onSubmit={handleSubmit}>
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
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                        className="w-full"
                    />
                    <InputError
                        message={errors.email}
                        className="mt-2 text-yellow-300"
                    />
                </div>

                {/* Submit */}
                <PrimaryButton
                    className="w-full bg-yellow-400 text-black font-bold py-3 rounded-xl hover:bg-yellow-300 transition"
                    disabled={processing}
                >
                    Email Password Reset Link
                </PrimaryButton>

                {/* Back to Sign In */}
                <p className="text-gray-200 text-sm mt-4">
                    Remember your password?{' '}
                    <Link
                        href={route('admin.login')}
                        className="font-semibold text-yellow-400 hover:underline"
                    >
                        Sign In Instead
                    </Link>
                </p>
            </form>
        </GuestLayout>
    );
}
