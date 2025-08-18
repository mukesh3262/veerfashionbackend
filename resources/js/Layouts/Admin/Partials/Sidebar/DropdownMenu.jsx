import NavLink from '@/Components/Admin/Links/NavLink';
import { Permission } from '@/constants/Permission';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import AnimateHeight from 'react-animate-height';

export default function DropdownMenu({ auth, data, isOpen, onMenuClick }) {
    const hasAnyItemPermission =
        data.menuItems?.some(
            (item) =>
                item?.permission === Permission.IGNORE ||
                item?.permission?.some((permission) =>
                    auth?.permissions?.includes(permission),
                ),
        ) ?? false;

    if (!hasAnyItemPermission) return null;

    return (
        <li className="menu nav-item">
            <button
                type="button"
                className={`nav-link group w-full ${isOpen ? 'active' : ''}`}
                onClick={onMenuClick}
            >
                <div className="flex items-center justify-between">
                    <div className="flex items-center">
                        {data?.icon && <FontAwesomeIcon icon={data.icon} />}
                        <span className="text-black dark:text-[#506690] dark:group-hover:text-white-dark ltr:pl-3 rtl:pr-3">
                            {data?.name}
                        </span>
                    </div>
                    <div
                        className={`transform ${isOpen ? 'rotate-90' : 'rotate-0'}`}
                    >
                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M9 5L15 12L9 19"
                                stroke="currentColor"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                    </div>
                </div>
            </button>

            <AnimateHeight duration={300} height={isOpen ? 'auto' : 0}>
                <ul className="sub-menu text-gray-500">
                    {data.menuItems?.map((item, index) => (
                        <li key={`${item.name}-${index}`}>
                            {item.permission?.some((permission) =>
                                auth.permissions.includes(permission),
                            ) ? (
                                <NavLink
                                    href={route(item.route)}
                                    active={route().current(item.active)}
                                >
                                    {item.name}
                                </NavLink>
                            ) : null}
                        </li>
                    ))}
                </ul>
            </AnimateHeight>
        </li>
    );
}
