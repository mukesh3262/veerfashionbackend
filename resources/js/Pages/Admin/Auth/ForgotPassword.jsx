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

            <div className="relative flex w-full items-center justify-center lg:w-1/2">
                <div className="max-w-[480px] p-5 md:p-10">
                    <h2 className="mb-3 text-3xl font-bold">Forgot Password</h2>

                    <p className="mb-7 w-[500px]">
                        Forgot your password? No worries! You can easily reset
                        it here.
                    </p>

                    <form className="space-y-5" onSubmit={handleSubmit}>
                        <div>
                            <InputLabel htmlFor="email" value="Email" />

                            <TextInput
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                placeholder="Enter E-Mail Address"
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

                        <PrimaryButton className="w-full" disabled={processing}>
                            Email Password Reset Link
                        </PrimaryButton>

                        <p>
                            <Link
                                href={route('admin.login')}
                                className="font-bold text-primary hover:underline"
                            >
                                Sign In Instead
                            </Link>
                        </p>
                    </form>
                </div>
            </div>
        </GuestLayout>
    );
}
