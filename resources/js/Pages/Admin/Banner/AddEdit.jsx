import Breadcrumb from '@/Components/Admin/Breadcrumb';
import LinkButton from '@/Components/Admin/Buttons/LinkButton';
import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import ImageUploader from '@/Components/Admin/Form/ImageUploader';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import TextInput from '@/Components/Admin/Form/TextInput';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function BannerAddEdit({
    auth,
    banner,
    success,
    error,
    uuid,
}) {
    const { data, setData, post, patch, errors, processing } = useForm({
        id: banner?.id ?? '',
        title: banner?.title ?? '',
        image: '',
        image_url: banner?.image_url ?? '',
        is_active: banner?.is_active.isActive ?? 1,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        if (banner?.id) {
            post(route('admin.banners.update', banner?.id));
        } else {
            post(route('admin.banners.store'));
        }
    };

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title={`Banners ${banner?.id ? 'Edit' : 'Add'}`} />

            <Breadcrumb
                breadcrumbs={[
                    { to: route('admin.banners.index'), label: 'Banners' },
                    { label: banner?.id ? 'Edit' : 'Add' },
                ]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Banners {banner?.id ? 'Edit' : 'Add'}
                        </h5>
                    </div>

                    <form onSubmit={handleSubmit}>
                        {/* ================= Product Info ================= */}
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {/* Name */}
                            <div className="mb-3">
                                <InputLabel
                                    htmlFor="title"
                                    value="Title"
                                    required
                                />
                                <TextInput
                                    id="title"
                                    name="title"
                                    value={data.title}
                                    onChange={(e) =>
                                        setData('title', e.target.value)
                                    }
                                    placeholder="Enter banner title"
                                />
                                <InputError
                                    message={errors.title}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        {/* Banne Image */}
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div className="mb-3">
                                <InputLabel
                                    htmlFor="image"
                                    value="Banner Image"
                                    required
                                />
                                <ImageUploader
                                    id="image"
                                    prevImage={banner?.image_url}
                                    onImageChange={(file) =>
                                        setData('image', file)
                                    }
                                    containerClass="w-full aspect-[16/6] border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 flex items-center justify-center overflow-hidden"
                                    uploadImgClass="w-full h-full object-cover"
                                    prevImgClass="w-full h-full object-cover"
                                />
                                <InputError
                                    message={errors.image}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        {/* ================= Active / Inactive ================= */}
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div className="mb-3">
                                <InputLabel
                                    htmlFor="is_active"
                                    value="Status"
                                    required
                                />
                                <select
                                    id="is_active"
                                    name="is_active"
                                    value={Number(data.is_active)} // force numeric
                                    onChange={(e) =>
                                        setData(
                                            'is_active',
                                            Number(e.target.value),
                                        )
                                    }
                                    className="w-full rounded-lg border border-gray-300 p-2 text-sm"
                                >
                                    <option value={1}>Active</option>
                                    <option value={0}>Inactive</option>
                                </select>
                                <InputError
                                    message={errors.is_active}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        {/* ================= Save / Cancel ================= */}
                        <div className="!mt-6 flex items-center justify-end gap-3">
                            <LinkButton href={route('admin.products.index')}>
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
