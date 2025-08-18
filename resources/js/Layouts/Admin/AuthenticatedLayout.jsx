import { errorAlert, successAlert } from '@/helpers/sweet-alert';
import {
    toggleAnimation,
    toggleLayout,
    toggleMenu,
    toggleNavbar,
    toggleRTL,
    toggleSemidark,
    toggleSidebar,
    toggleTheme,
} from '@/store/themeConfigSlice';
import { Suspense, useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import store from '../../store/index';
import Footer from './Partials/Footer';
import Header from './Partials/Header';
import Portals from './Partials/Portals';
import Sidebar from './Partials/Sidebar';

export default function AuthenticatedLayout({
    auth,
    success,
    error,
    uuid,
    children,
}) {
    const themeConfig = useSelector((state) => state.themeConfig);
    const dispatch = useDispatch();

    const [showLoader, setShowLoader] = useState(false);
    const [showTopButton, setShowTopButton] = useState(false);

    const goToTop = () => {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    };

    const onScrollHandler = () => {
        if (
            document.body.scrollTop > 50 ||
            document.documentElement.scrollTop > 50
        ) {
            setShowTopButton(true);
        } else {
            setShowTopButton(false);
        }
    };

    useEffect(() => {
        dispatch(
            toggleTheme(localStorage.getItem('theme') || themeConfig.theme),
        );
        dispatch(toggleMenu(localStorage.getItem('menu') || themeConfig.menu));
        dispatch(
            toggleLayout(localStorage.getItem('layout') || themeConfig.layout),
        );
        dispatch(
            toggleRTL(localStorage.getItem('rtlClass') || themeConfig.rtlClass),
        );
        dispatch(
            toggleAnimation(
                localStorage.getItem('animation') || themeConfig.animation,
            ),
        );
        dispatch(
            toggleNavbar(localStorage.getItem('navbar') || themeConfig.navbar),
        );
        dispatch(
            toggleSemidark(
                localStorage.getItem('semidark') || themeConfig.semidark,
            ),
        );
    }, [
        dispatch,
        themeConfig.theme,
        themeConfig.menu,
        themeConfig.layout,
        themeConfig.rtlClass,
        themeConfig.animation,
        themeConfig.navbar,
        themeConfig.locale,
        themeConfig.semidark,
    ]);

    useEffect(() => {
        window.addEventListener('scroll', onScrollHandler);

        const screenLoader = document.getElementsByClassName('screen_loader');
        if (screenLoader?.length) {
            screenLoader[0].classList.add('animate__fadeOut');
            setTimeout(() => {
                setShowLoader(false);
            }, 200);
        }

        return () => {
            window.removeEventListener('onscroll', onScrollHandler);
        };
    }, []);

    useEffect(() => {
        if (success) {
            successAlert(success);
        }
        if (error) {
            errorAlert(error);
        }
    }, [success, error, uuid]);

    return (
        <div
            className={`${(store.getState().themeConfig.sidebar && 'toggle-sidebar') || ''} ${themeConfig.menu} ${themeConfig.layout} ${
                themeConfig.rtlClass
            } main-section relative font-nunito text-sm font-normal antialiased`}
        >
            <div className="app-bg-cover relative">
                {/* sidebar menu overlay */}
                <div
                    className={`${(!themeConfig.sidebar && 'hidden') || ''} fixed inset-0 z-50 bg-[black]/60 lg:hidden`}
                    onClick={() => dispatch(toggleSidebar())}
                ></div>
                {/* screen loader */}
                {showLoader && (
                    <div className="screen_loader animate__animated fixed inset-0 z-[60] grid place-content-center bg-[#fafafa] dark:bg-[#060818]">
                        <svg
                            width="64"
                            height="64"
                            viewBox="0 0 135 135"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="#4361ee"
                        >
                            <path d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z">
                                <animateTransform
                                    attributeName="transform"
                                    type="rotate"
                                    from="0 67 67"
                                    to="-360 67 67"
                                    dur="2.5s"
                                    repeatCount="indefinite"
                                />
                            </path>
                            <path d="M28.19 40.31c6.627 0 12-5.374 12-12 0-6.628-5.373-12-12-12-6.628 0-12 5.372-12 12 0 6.626 5.372 12 12 12zm30.72-19.825c4.686 4.687 12.284 4.687 16.97 0 4.686-4.686 4.686-12.284 0-16.97-4.686-4.687-12.284-4.687-16.97 0-4.687 4.686-4.687 12.284 0 16.97zm35.74 7.705c0 6.627 5.37 12 12 12 6.626 0 12-5.373 12-12 0-6.628-5.374-12-12-12-6.63 0-12 5.372-12 12zm19.822 30.72c-4.686 4.686-4.686 12.284 0 16.97 4.687 4.686 12.285 4.686 16.97 0 4.687-4.686 4.687-12.284 0-16.97-4.685-4.687-12.283-4.687-16.97 0zm-7.704 35.74c-6.627 0-12 5.37-12 12 0 6.626 5.373 12 12 12s12-5.374 12-12c0-6.63-5.373-12-12-12zm-30.72 19.822c-4.686-4.686-12.284-4.686-16.97 0-4.686 4.687-4.686 12.285 0 16.97 4.686 4.687 12.284 4.687 16.97 0 4.687-4.685 4.687-12.283 0-16.97zm-35.74-7.704c0-6.627-5.372-12-12-12-6.626 0-12 5.373-12 12s5.374 12 12 12c6.628 0 12-5.373 12-12zm-19.823-30.72c4.687-4.686 4.687-12.284 0-16.97-4.686-4.686-12.284-4.686-16.97 0-4.687 4.686-4.687 12.284 0 16.97 4.686 4.687 12.284 4.687 16.97 0z">
                                <animateTransform
                                    attributeName="transform"
                                    type="rotate"
                                    from="0 67 67"
                                    to="360 67 67"
                                    dur="8s"
                                    repeatCount="indefinite"
                                />
                            </path>
                        </svg>
                    </div>
                )}
                <div className="fixed bottom-6 z-50 ltr:right-6 rtl:left-6">
                    {showTopButton && (
                        <button
                            type="button"
                            className="btn btn-outline-primary animate-pulse rounded-full bg-[#fafafa] p-2 dark:bg-[#060818] dark:hover:bg-primary"
                            onClick={goToTop}
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                className="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                strokeWidth="1.5"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M8 7l4-4m0 0l4 4m-4-4v18"
                                />
                            </svg>
                        </button>
                    )}
                </div>

                {/* BEGIN APP SETTING LAUNCHER */}
                {/* <Setting /> */}
                {/* END APP SETTING LAUNCHER */}

                <div
                    className={`${themeConfig.navbar} main-container min-h-screen text-black dark:text-white-dark`}
                >
                    {/* BEGIN SIDEBAR */}
                    <Sidebar auth={auth} />
                    {/* END SIDEBAR */}

                    {/* BEGIN CONTENT AREA */}
                    <div className="main-content">
                        {/* BEGIN TOP NAVBAR */}
                        <Header />
                        {/* END TOP NAVBAR */}
                        <Suspense>
                            <div
                                className={`${themeConfig.animation} h-full min-h-[calc(100dvh-142px)] w-full px-6 pt-6`}
                            >
                                {children}
                            </div>
                        </Suspense>

                        <Portals />

                        {/* BEGIN FOOTER */}
                        <Footer />
                        {/* END FOOTER */}
                    </div>
                    {/* END CONTENT AREA */}
                </div>
            </div>
        </div>
    );
}
