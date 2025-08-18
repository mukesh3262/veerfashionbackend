import Breadcrumb from '@/Components/Admin/Breadcrumb';
import LinkButton from '@/Components/Admin/Buttons/LinkButton';
import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import CKEditor5 from '@/Components/Admin/CKEditor5/Index';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import TextInput from '@/Components/Admin/Form/TextInput';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function ContentPageAddEdit({
    auth,
    content_page,
    success,
    error,
    uuid,
}) {
    const { data, setData, post, patch, errors, processing } = useForm({
        id: content_page?.id ?? '',
        title: content_page?.title ?? '',
        slug: content_page?.slug ?? '',
        content: content_page?.content ?? '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();

        if (content_page?.id) {
            patch(route('admin.content-pages.update', content_page?.id));
        } else {
            post(route('admin.content-pages.store'));
        }
    };

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head
                title={`Content Pages ${content_page?.id ? 'Edit' : 'Add'}`}
            />

            <Breadcrumb
                breadcrumbs={[
                    {
                        to: route('admin.content-pages.index'),
                        label: 'Content Pages',
                    },
                    { label: content_page?.id ? 'Edit' : 'Add' },
                ]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Content Pages {content_page?.id ? 'Edit' : 'Add'}
                        </h5>
                    </div>

                    <form onSubmit={handleSubmit}>
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {/* Title */}
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
                                    placeholder="Enter Title"
                                    isFocused={true}
                                    onChange={(e) =>
                                        setData('title', e.target.value)
                                    }
                                />

                                <InputError
                                    message={errors.title}
                                    className="mt-2"
                                />
                            </div>

                            {/* Slug */}
                            <div className="mb-3">
                                <InputLabel
                                    htmlFor="slug"
                                    value="Slug"
                                    required
                                />

                                <TextInput
                                    id="slug"
                                    name="slug"
                                    value={data.slug}
                                    placeholder="Enter Slug"
                                    isFocused={true}
                                    onChange={(e) =>
                                        setData('slug', e.target.value)
                                    }
                                />

                                <InputError
                                    message={errors.slug}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        {/* Content */}
                        <div className="mb-3">
                            <InputLabel
                                htmlFor="content"
                                value="Content"
                                required
                            />

                            <CKEditor5
                                value={data?.content}
                                onEditorChange={(value) =>
                                    setData('content', value)
                                }
                            />

                            <InputError
                                message={errors.content}
                                className="mt-2"
                            />
                        </div>

                        <div className="!mt-6 flex items-center justify-end gap-3">
                            <LinkButton
                                href={route('admin.content-pages.index')}
                            >
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
