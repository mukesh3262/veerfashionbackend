import { sweetAlert } from '@/helpers/sweet-alert';
import { useForm } from '@inertiajs/react';
import { useSelector } from 'react-redux';

export default function Switch({
    auth,
    permission,
    id,
    isActive,
    url,
    title = null,
}) {
    const { put } = useForm();

    const { theme } = useSelector((state) => state.themeConfig);

    const handleChangeStatus = (e, id, status) => {
        e.preventDefault();

        const actionText = status ? 'deactivate' : 'activate';

        const confirmButtonText = status ? 'Yes, Deactivate' : 'Yes, Activate';
        const cancelButtonText = status
            ? 'No, Keep Active'
            : 'No, Keep Inactive';

        sweetAlert(
            status,
            {
                title: title
                    ? title.replace('{{ACTION}}', actionText)
                    : `Are you sure you want to ${actionText}?`,
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText,
                preConfirm: async () => {
                    put(url);
                    return true;
                },
            },
            theme,
        );
    };

    return (
        <a
            href="#"
            className="js-active"
            onClick={(e) =>
                auth?.permissions?.includes(permission)
                    ? handleChangeStatus(e, id, isActive)
                    : undefined
            }
        >
            <span
                className={`badge rounded-full ${
                    isActive ? 'badge-outline-success' : 'badge-outline-danger'
                }`}
            >
                {isActive ? 'Active' : 'Inactive'}
            </span>
        </a>
    );
}
