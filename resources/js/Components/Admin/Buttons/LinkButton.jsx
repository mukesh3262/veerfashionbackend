import { Link } from '@inertiajs/react';

export default function LinkButton({ className = '', children, ...props }) {
    return (
        <Link
            {...props}
            className={`rounded-md bg-gray-200 px-4 py-2 font-semibold uppercase transition duration-150 ease-in-out hover:bg-gray-300 ${className}`}
        >
            {children}
        </Link>
    );
}
