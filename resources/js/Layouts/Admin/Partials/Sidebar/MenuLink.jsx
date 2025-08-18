import NavLink from '@/Components/Admin/Links/NavLink';
import { Permission } from '@/constants/Permission';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

export default function MenuLink({ auth, data }) {
    const { permission, active, icon, name } = data;

    const hasPermission = () => {
        if (permission === Permission.IGNORE) return true;

        // if has permissions to access module
        if (Array.isArray(permission)) {
            return permission?.some((permission) =>
                auth?.permissions?.includes(permission),
            );
        }

        return false;
    };

    if (!hasPermission()) return null;

    return (
        <li className="menu nav-item">
            <NavLink
                href={route(data.route)}
                active={route().current(active)}
                className="group"
            >
                <div className="flex items-center">
                    {icon ? <FontAwesomeIcon icon={icon} /> : '-'}

                    <span className="text-black dark:text-[#506690] dark:group-hover:text-white-dark ltr:pl-3 rtl:pr-3">
                        {name}
                    </span>
                </div>
            </NavLink>
        </li>
    );
}
