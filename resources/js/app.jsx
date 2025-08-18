import 'react-perfect-scrollbar/dist/css/styles.css';
import '../css/app.css';
import '../css/custom.css';
import './bootstrap';

import.meta.glob(['../images/**']);

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import { BrowserRouter } from 'react-router-dom';
import store from './store/index';

import { library } from '@fortawesome/fontawesome-svg-core';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { queryClient } from '@helpers/queryClient';
import { QueryClientProvider } from '@tanstack/react-query';
import { ReactQueryDevtools } from '@tanstack/react-query-devtools';
library.add(fas, far, fab);
/**
 * FontAwesome font sizes
 * 2xs, xs, sm, lg, xl, 2xl, 1x, 2x, 3x, 4x, 5x, 6x, 7x, 8x, 9x, 10x
 */

const appName = import.meta.env.VITE_APP_NAME || 'Basecode';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob('./Pages/**/*.jsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <Provider store={store}>
                <QueryClientProvider client={queryClient}>
                    {/* React Query Devtools */}
                    <ReactQueryDevtools initialIsOpen={false} />

                    {/* App */}
                    <BrowserRouter>
                        <App {...props} />
                    </BrowserRouter>
                </QueryClientProvider>
            </Provider>,
        );
    },
    progress: {
        color: '#4B5563',
    },
});
