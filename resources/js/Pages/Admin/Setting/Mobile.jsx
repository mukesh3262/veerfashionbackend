import Breadcrumb from '@/Components/Admin/Breadcrumb';
import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import TextInput from '@/Components/Admin/Form/TextInput';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { Head, useForm } from '@inertiajs/react';

export default function MobileSetting({
    auth,
    androidVersion,
    iosVersion,
    success,
    error,
    uuid,
}) {
    const { data, setData, patch, processing, errors } = useForm({
        android: {
            version: androidVersion?.version ?? 0,
            force_updateable: androidVersion?.force_updateable ?? false,
        },
        ios: {
            version: iosVersion?.version ?? 0,
            force_updateable: iosVersion?.force_updateable ?? false,
        },
    });

    const handleVersionChange = (e) => {
        const { name, value } = e.target;
        setData((state) => ({
            ...state,
            [name]: {
                version: value ? parseFloat(value) : '',
                force_updateable: state[name].force_updateable,
            },
        }));
    };

    const handleForceUpdateChange = (e) => {
        const { name, checked } = e.target;

        const checkboxKey = {
            android_force_update: 'android',
            ios_force_update: 'ios',
        };

        setData((state) => ({
            ...state,
            [checkboxKey[name]]: {
                version: state[checkboxKey[name]].version,
                force_updateable: Boolean(checked),
            },
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        patch(route('admin.setting.mobile-version.update'));
    };

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title="Mobile Version" />

            <Breadcrumb
                breadcrumbs={[
                    { to: '#', label: 'Settings' },
                    { label: 'Mobile Management' },
                ]}
            />

            <div className="pt-5">
                <div className="panel">
                    <form className="space-y-5" onSubmit={handleSubmit}>
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {/* Android version */}
                            <div>
                                <InputLabel
                                    htmlFor="android"
                                    value="Android Version"
                                    required
                                />

                                <div className="flex">
                                    <div className="flex items-center justify-center border border-[#e0e6ed] bg-[#eee] px-3 font-semibold dark:border-[#17263c] dark:bg-[#1b2e4b] ltr:rounded-l-md ltr:border-r-0 rtl:rounded-r-md rtl:border-l-0">
                                        <FontAwesomeIcon
                                            icon="fab fa-android"
                                            className="text-xl"
                                        />
                                    </div>

                                    <TextInput
                                        id="android"
                                        name="android"
                                        value={data.android.version}
                                        placeholder="Enter Android Version"
                                        isFocused={true}
                                        type="number"
                                        onChange={(e) => handleVersionChange(e)}
                                        className="form-input ltr:rounded-l-none rtl:rounded-r-none"
                                    />
                                </div>
                                <InputError
                                    message={errors['android.version']}
                                    className="mt-2"
                                />
                            </div>

                            {/* iOS version */}
                            <div>
                                <InputLabel
                                    htmlFor="ios"
                                    value="iOS Version"
                                    required
                                />
                                <div className="flex">
                                    <div className="flex items-center justify-center border border-[#e0e6ed] bg-[#eee] px-3 font-semibold dark:border-[#17263c] dark:bg-[#1b2e4b] ltr:rounded-l-md ltr:border-r-0 rtl:rounded-r-md rtl:border-l-0">
                                        <FontAwesomeIcon
                                            icon="fab fa-apple"
                                            className="text-xl"
                                        />
                                    </div>
                                    <TextInput
                                        id="ios"
                                        name="ios"
                                        type="number"
                                        value={data.ios.version}
                                        placeholder="Enter iOS Version"
                                        onChange={(e) => handleVersionChange(e)}
                                        className="form-input ltr:rounded-l-none rtl:rounded-r-none"
                                    />
                                </div>
                                <InputError
                                    message={errors['ios.version']}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {/* Android force update */}
                            <div>
                                <InputLabel
                                    htmlFor="android_force_update"
                                    value="Force Update"
                                />

                                <label className="relative h-6 w-12">
                                    <input
                                        type="checkbox"
                                        id="android_force_update"
                                        name="android_force_update"
                                        className="custom_switch peer absolute z-10 h-full w-full cursor-pointer opacity-0"
                                        onChange={(e) =>
                                            handleForceUpdateChange(e)
                                        }
                                        defaultChecked={
                                            data.android.force_updateable ||
                                            false
                                        }
                                    />
                                    <span
                                        htmlFor="android_force_update"
                                        className="block h-full rounded-full bg-red-600 before:absolute before:bottom-1 before:left-1 before:h-4 before:w-4 before:rounded-full before:bg-white before:transition-all before:duration-300 peer-checked:bg-primary peer-checked:before:left-7 dark:bg-dark dark:before:bg-white-dark dark:peer-checked:before:bg-white"
                                    ></span>
                                </label>
                                <InputError
                                    message={errors['android.force_updateable']}
                                    className="mt-2"
                                />
                            </div>

                            {/* iOS force update */}
                            <div>
                                <InputLabel
                                    htmlFor="ios_force_update"
                                    value="Force Update"
                                />

                                <label className="relative h-6 w-12">
                                    <input
                                        type="checkbox"
                                        id="ios_force_update"
                                        name="ios_force_update"
                                        className="custom_switch peer absolute z-10 h-full w-full cursor-pointer opacity-0"
                                        onChange={(e) =>
                                            handleForceUpdateChange(e)
                                        }
                                        defaultChecked={
                                            data.ios.force_updateable || false
                                        }
                                    />
                                    <span
                                        htmlFor="ios_force_update"
                                        className="block h-full rounded-full bg-red-600 before:absolute before:bottom-1 before:left-1 before:h-4 before:w-4 before:rounded-full before:bg-white before:transition-all before:duration-300 peer-checked:bg-primary peer-checked:before:left-7 dark:bg-dark dark:before:bg-white-dark dark:peer-checked:before:bg-white"
                                    ></span>
                                </label>
                                <InputError
                                    message={errors['ios.force_updateable']}
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
