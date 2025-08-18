import Swal from 'sweetalert2';

// Default options for the status alert
export function sweetAlert(action, customOptions = {}, theme) {
    const getCancelButtonClasses = () => {
        const browserTheme =
            window.matchMedia &&
            window.matchMedia('(prefers-color-scheme: dark)').matches
                ? 'dark'
                : 'light';
        let className = '!font-bold !py-2 !px-4 !rounded';

        if (
            theme == 'light' ||
            (theme == 'system' && browserTheme == 'light')
        ) {
            className += ' !bg-gray-300 !hover:bg-gray-400 !text-black';
        }
        if (theme == 'dark') {
            className += ' !bg-gray-800 !hover:bg-gray-700 !text-white';
        }
        return className;
    };

    const defaultOptions = {
        showCancelButton: true,
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        backdrop: `rgba(0, 0, 0, 0.7)`,
        customClass: {
            confirmButton: `!text-white !font-bold !py-2 !px-4 !rounded ${action ? '!bg-red-500 !hover:bg-red-600' : '!bg-green-500 !hover:bg-green-600'}`,
            cancelButton: getCancelButtonClasses(),
        },
        preConfirm: async () => {
            // Default preConfirm logic (does nothing by default)
            return true;
        },
    };

    // Merge the options, prioritizing customOptions over defaultOptions
    const options = { ...defaultOptions, ...customOptions };

    return Swal.fire(options);
}

export function successAlert(successObj, isShowAlert = true) {
    if (isShowAlert) {
        if (successObj?.dialog_type === 'info') {
            // informative alert
            showAlert({
                message: successObj?.message,
                type: successObj?.type || 'alert-success',
                icon: successObj?.icon || 'success',
            });
        }

        if (successObj?.dialog_type === 'confirm') {
            // confirmable alert
            Swal.fire({
                icon: successObj?.icon || 'success',
                title: successObj?.message,
                customClass: 'sweet-alerts',
            });
        }
    }
}

export function errorAlert(message, isShowAlert = true) {
    if (isShowAlert) {
        showAlert({ message, type: 'alert-danger', icon: 'error' });
    }
}

export function showAlert(alert) {
    const toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        showCloseButton: true,
    });

    toast.fire({
        icon: alert.icon,
        title: alert.message,
        padding: '10px 20px',
    });
}
