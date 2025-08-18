import { Link } from '@inertiajs/react';

export default function Breadcrumb({ breadcrumbs = [{ label: 'Dashboard' }] }) {
    return (
        <ul className="flex">
            {breadcrumbs.map(
                (breadcrumb, index) =>
                    breadcrumb && (
                        <li key={index} className="flex items-center">
                            {breadcrumb.to === '#' ? (
                                <a
                                    href="#"
                                    onClick={(e) => e.preventDefault()}
                                    className="text-primary hover:underline"
                                >
                                    {breadcrumb.label}
                                </a>
                            ) : breadcrumb.to ? (
                                <Link
                                    href={breadcrumb.to}
                                    className="text-primary hover:underline"
                                >
                                    {breadcrumb.label}
                                </Link>
                            ) : (
                                <span>{breadcrumb.label}</span>
                            )}

                            {/* Separator */}
                            {index < breadcrumbs.length - 1 && (
                                <span className="before:mx-2 before:content-['/']"></span>
                            )}
                        </li>
                    ),
            )}
        </ul>
    );
}
