import { errorAlert, successAlert } from '@/helpers/sweet-alert';
import { useEffect } from 'react';
import loginCover from '../../../images/auth-cover.svg';

export default function GuestLayout({ success, error, uuid, children }) {
    useEffect(() => {
        if (success) {
            successAlert(success);
        }
        if (error) {
            errorAlert(error);
        }
    }, [success, error, uuid]);

    return (
        <>
            <div className="flex min-h-screen dark:text-gray-200">
                <div className="hidden min-h-screen w-1/2 flex-col items-center justify-center bg-gradient-to-t from-[#ff1361bf] to-[#44107A] p-4 text-white dark:text-black lg:flex">
                    <div className="mx-auto mb-5 w-full">
                        <img
                            src={loginCover}
                            alt="coming_soon"
                            className="mx-auto lg:max-w-[370px] xl:max-w-[500px]"
                        />
                    </div>
                    <h3 className="mb-4 text-center text-3xl font-bold dark:text-gray-200">
                        Join the community of expert developers
                    </h3>
                    <p className="dark:text-gray-200">
                        It is easy to setup with great customer experience.
                        Start your 7-day free trial
                    </p>
                </div>

                {children}
            </div>
        </>
    );
}
