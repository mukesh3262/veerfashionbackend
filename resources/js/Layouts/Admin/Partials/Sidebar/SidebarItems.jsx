import { useEffect, useState } from 'react';
import DropdownMenu from './DropdownMenu';
import Label from './Label';
import MenuLink from './MenuLink';
import sidebarData from './SidebarData';

export default function SidebarItems({ auth }) {
    const [activeMenu, setActiveMenu] = useState(null);

    const handleMenuClick = (menuName) => {
        setActiveMenu((prev) => (prev === menuName ? null : menuName));
    };

    useEffect(() => {
        const findActiveMenu = () => {
            const activeMenu = sidebarData.find((data) =>
                data.menuItems?.some((item) => route().current(item.active)),
            );
            if (activeMenu) {
                setActiveMenu(activeMenu.name);
            }
        };

        findActiveMenu();
    }, []);

    return (
        <ul className="relative space-y-0.5 p-4 py-0 font-semibold">
            {sidebarData.map((data, index) => {
                if (data?.isLabel) {
                    return <Label auth={auth} key={index} label={data} />;
                } else if (data?.menuItems) {
                    // Check if the menu should be open based on active state
                    const isOpen = activeMenu === data.name;

                    return (
                        <DropdownMenu
                            key={index}
                            auth={auth}
                            data={data}
                            isOpen={isOpen}
                            onMenuClick={() => handleMenuClick(data.name)}
                        />
                    );
                } else {
                    return <MenuLink auth={auth} key={index} data={data} />;
                }
            })}
        </ul>
    );
}
