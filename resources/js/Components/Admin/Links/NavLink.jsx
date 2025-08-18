import { Link } from '@inertiajs/react';

export default function NavLink({
    active = false,
    className = '',
    children,
    ...props
}) {
    return (
        <Link className={(active ? 'active ' : '') + className} {...props}>
            {children}
        </Link>
    );
}
