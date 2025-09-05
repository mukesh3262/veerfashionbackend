import { errorAlert, successAlert } from '@/helpers/sweet-alert';
import { useEffect } from 'react';
import bgImage from '../../../images/fashion-bg.jpg'; // replace with your background image

export default function GuestLayout({ success, error, uuid, children }) {
    useEffect(() => {
        if (success) successAlert(success);
        if (error) errorAlert(error);
    }, [success, error, uuid]);

    return (
        <div
            className="min-h-screen flex items-center justify-center relative bg-black"
            style={{
                backgroundImage: `url(${bgImage})`,
                backgroundSize: 'cover',
                backgroundPosition: 'center',
            }}
        >
            {/* Overlay for readability */}
            <div className="absolute inset-0 bg-black/60"></div>

            {/* Centered Transparent Auth Card */}
            <div className="relative z-10 w-full max-w-md p-8 rounded-2xl bg-white/10 backdrop-blur-lg shadow-xl border border-white/20">
                {children}
            </div>
        </div>
    );
}
