import Breadcrumb from '@/Components/Admin/Breadcrumb';
import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import TextInput from '@/Components/Admin/Form/TextInput';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import Select from 'react-select';

export default function SmtpSetting({
    auth,
    mailers,
    mailer,
    host,
    port,
    username,
    password,
    encryption,
    from_address,
    from_name,
    selected_mailer,
    success,
    error,
    uuid,
}) {
    const { data, setData, patch, errors, processing } = useForm({
        mailer: mailer ?? '',
        host: host ?? '',
        port: port ?? '',
        username: username ?? '',
        password: password ?? '',
        encryption: encryption ?? '',
        from_address: from_address ?? '',
        from_name: from_name ?? '',
    });

    const [selectedMailer, setSelectedMailer] = useState(
        selected_mailer ?? null,
    );

    const handleMailerChange = (selectedOption) => {
        setSelectedMailer(selectedOption);
        setData('role', selectedOption?.value);
    };

    useEffect(() => {
        setSelectedMailer(selectedMailer);
        setData('mailer', selectedMailer?.value);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [selectedMailer]);

    const handleSubmit = (e) => {
        e.preventDefault();

        patch(route('admin.setting.smtp.update'));
    };

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title="SMTP List" />

            <Breadcrumb
                breadcrumbs={[
                    { to: '#', label: 'Settings' },
                    { label: 'Email Configuration' },
                ]}
            />

            <div className="pt-5">
                <div className="panel">
                    <form className="space-y-5" onSubmit={handleSubmit}>
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {/* Mailer */}
                            <div>
                                <InputLabel
                                    htmlFor="mailer"
                                    value="Mailer [MAIL_MAILER]"
                                    required
                                />

                                <Select
                                    defaultValue={selectedMailer}
                                    value={selectedMailer}
                                    options={mailers}
                                    isClearable={true}
                                    placeholder={'Select Mailer'}
                                    onChange={handleMailerChange}
                                    noOptionsMessage={() => 'No Mailers Found'}
                                />

                                <InputError
                                    message={errors.mailer}
                                    className="mt-2"
                                />
                            </div>

                            {/* Host */}
                            <div>
                                <InputLabel
                                    htmlFor="host"
                                    value="Host [MAIL_HOST]"
                                    required
                                />

                                <TextInput
                                    id="host"
                                    name="host"
                                    value={data.host}
                                    placeholder="Enter host"
                                    isFocused={true}
                                    onChange={(e) =>
                                        setData('host', e.target.value)
                                    }
                                />

                                <InputError
                                    message={errors.host}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {/* Username */}
                            <div>
                                <InputLabel
                                    htmlFor="username"
                                    value="Username [MAIL_USERNAME]"
                                    required
                                />

                                <TextInput
                                    type="email"
                                    id="username"
                                    name="username"
                                    value={data.username}
                                    placeholder="Enter username"
                                    onChange={(e) =>
                                        setData('username', e.target.value)
                                    }
                                />

                                <InputError
                                    message={errors.username}
                                    className="mt-2"
                                />
                            </div>

                            {/* Password */}
                            <div>
                                <InputLabel
                                    htmlFor="password"
                                    value="Password [MAIL_PASSWORD]"
                                    required
                                />

                                <TextInput
                                    type="password"
                                    id="password"
                                    name="password"
                                    value={data.password}
                                    placeholder="Enter password"
                                    onChange={(e) =>
                                        setData('password', e.target.value)
                                    }
                                />

                                <InputError
                                    message={errors.password}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {/* Port */}
                            <div>
                                <InputLabel
                                    htmlFor="port"
                                    value="Port [MAIL_PORT]"
                                    required
                                />

                                <TextInput
                                    id="port"
                                    name="port"
                                    value={data.port}
                                    placeholder="Enter port"
                                    onChange={(e) =>
                                        setData('port', e.target.value)
                                    }
                                />

                                <InputError
                                    message={errors.port}
                                    className="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    htmlFor="encryption"
                                    value="Encryption [MAIL_ENCRYPTION]"
                                    required
                                />

                                <TextInput
                                    id="encryption"
                                    name="encryption"
                                    value={data.encryption}
                                    placeholder="Enter encryption"
                                    onChange={(e) =>
                                        setData('encryption', e.target.value)
                                    }
                                />

                                <InputError
                                    message={errors.encryption}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {/* From Address */}
                            <div>
                                <InputLabel
                                    htmlFor="from_address"
                                    value="From Address [MAIL_FROM_ADDRESS]"
                                    required
                                />

                                <TextInput
                                    id="from_address"
                                    name="from_address"
                                    value={data.from_address}
                                    placeholder="Enter from address"
                                    onChange={(e) =>
                                        setData('from_address', e.target.value)
                                    }
                                />

                                <InputError
                                    message={errors.from_address}
                                    className="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    htmlFor="from_name"
                                    value="From Name [MAIL_FROM_NAME]"
                                    required
                                />

                                <TextInput
                                    id="from_name"
                                    name="from_name"
                                    value={data.from_name}
                                    placeholder="Enter from name"
                                    onChange={(e) =>
                                        setData('from_name', e.target.value)
                                    }
                                />

                                <InputError
                                    message={errors.from_name}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        <div className="flex items-center justify-end space-x-2">
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
