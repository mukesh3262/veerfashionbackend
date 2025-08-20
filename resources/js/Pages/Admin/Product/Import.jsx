import Breadcrumb from '@/Components/Admin/Breadcrumb';
import LinkButton from '@/Components/Admin/Buttons/LinkButton';
import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Import({ auth, success, error, uuid }) {
    const { data, setData, post, processing, errors } = useForm({
        file: null,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('admin.products.import.store'));
    };

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title="Import Products" />

            <Breadcrumb
                breadcrumbs={[
                    { to: route('admin.products.index'), label: 'Products' },
                    { label: 'Import' },
                ]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                        Import Products
                    </h5>

                    <form onSubmit={handleSubmit} encType="multipart/form-data">
                        <div className="mb-3">
                            <InputLabel
                                htmlFor="file"
                                value="Upload File"
                                required
                            />

                            <input
                                id="file"
                                type="file"
                                name="file"
                                className="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                onChange={(e) =>
                                    setData('file', e.target.files[0])
                                }
                            />

                            <InputError
                                message={errors.file}
                                className="mt-2"
                            />
                        </div>

                        <div className="!mt-6 flex items-center justify-end gap-3">
                            <LinkButton href={route('admin.products.index')}>
                                Cancel
                            </LinkButton>

                            <PrimaryButton disabled={processing}>
                                Import
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
