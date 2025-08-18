<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('l5-swagger.documentations.' . $documentation . '.api.title') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}">
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-32x32.png') }}"
        sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-16x16.png') }}"
        sizes="16x16" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            background: #fafafa;
        }
    </style>
    <style>
        body#dark-mode,
        #dark-mode .scheme-container {
            background: #1b1b1b;
        }

        #dark-mode .scheme-container,
        #dark-mode .opblock .opblock-section-header {
            box-shadow: 0 1px 2px 0 rgba(255, 255, 255, 0.15);
        }

        #dark-mode .operation-filter-input,
        #dark-mode .dialog-ux .modal-ux,
        #dark-mode input[type=email],
        #dark-mode input[type=file],
        #dark-mode input[type=password],
        #dark-mode input[type=search],
        #dark-mode input[type=text],
        #dark-mode textarea {
            background: #343434;
            color: #e7e7e7;
        }

        #dark-mode .title,
        #dark-mode li,
        #dark-mode p,
        #dark-mode table,
        #dark-mode label,
        #dark-mode .opblock-tag,
        #dark-mode .opblock .opblock-summary-operation-id,
        #dark-mode .opblock .opblock-summary-path,
        #dark-mode .opblock .opblock-summary-path__deprecated,
        #dark-mode h1,
        #dark-mode h2,
        #dark-mode h3,
        #dark-mode h4,
        #dark-mode h5,
        #dark-mode .btn,
        #dark-mode .tab li,
        #dark-mode .parameter__name,
        #dark-mode .parameter__type,
        #dark-mode .prop-format,
        #dark-mode .loading-container .loading:after {
            color: #e7e7e7;
        }

        #dark-mode .opblock-description-wrapper p,
        #dark-mode .opblock-external-docs-wrapper p,
        #dark-mode .opblock-title_normal p,
        #dark-mode .response-col_status,
        #dark-mode table thead tr td,
        #dark-mode table thead tr th,
        #dark-mode .response-col_links,
        #dark-mode .swagger-ui {
            color: wheat;
        }

        #dark-mode .parameter__extension,
        #dark-mode .parameter__in,
        #dark-mode .model-title {
            color: #949494;
        }

        #dark-mode table thead tr td,
        #dark-mode table thead tr th {
            border-color: rgba(120, 120, 120, .2);
        }

        #dark-mode .opblock .opblock-section-header {
            background: transparent;
        }

        #dark-mode .opblock.opblock-post {
            background: rgba(73, 204, 144, .25);
        }

        #dark-mode .opblock.opblock-get {
            background: rgba(97, 175, 254, .25);
        }

        #dark-mode .opblock.opblock-put {
            background: rgba(252, 161, 48, .25);
        }

        #dark-mode .opblock.opblock-delete {
            background: rgba(249, 62, 62, .25);
        }

        #dark-mode .loading-container .loading:before {
            border-color: rgba(255, 255, 255, 10%);
            border-top-color: rgba(255, 255, 255, .6);
        }

        #dark-mode svg:not(:root) {
            fill: #e7e7e7;
        }

        #theme-switcher-button {
            background-color: #fff;
            border: none;
            padding: 6px;
            border-radius: 50px;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        #theme-switcher-button span {
            font-size: 24px;
            color: #f39c12;
        }

        #theme-switcher-button.dark-theme span {
            color: #f1c40f;
        }

        @media (max-width: 600px) {
            #theme-switcher {
                top: 10px;
                right: 10px;
            }

            #theme-switcher-button {
                padding: 8px 12px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body @if (config('l5-swagger.defaults.ui.display.dark_mode')) id="dark-mode" @endif>
    <div id="swagger-ui"></div>

    <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"></script>
    <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}"></script>
    <script>
        window.onload = function() {
            // Build a system
            const ui = SwaggerUIBundle({
                dom_id: '#swagger-ui',
                url: "{!! $urlToDocs !!}",
                operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
                configUrl: {!! isset($configUrl) ? '"' . $configUrl . '"' : 'null' !!},
                validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
                oauth2RedirectUrl: "{{ route('l5-swagger.' . $documentation . '.oauth2_callback', [], $useAbsolutePath) }}",

                requestInterceptor: function(request) {
                    request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    return request;
                },

                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],

                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],

                layout: "StandaloneLayout",
                docExpansion: "{!! config('l5-swagger.defaults.ui.display.doc_expansion', 'none') !!}",
                deepLinking: true,
                filter: {!! config('l5-swagger.defaults.ui.display.filter') ? 'true' : 'false' !!},
                persistAuthorization: "{!! config('l5-swagger.defaults.ui.authorization.persist_authorization') ? 'true' : 'false' !!}",

            })

            window.ui = ui

            @if (in_array('oauth2', array_column(config('l5-swagger.defaults.securityDefinitions.securitySchemes'), 'type')))
                ui.initOAuth({
                    usePkceWithAuthorizationCodeGrant: "{!! (bool) config('l5-swagger.defaults.ui.authorization.oauth2.use_pkce_with_authorization_code_grant') !!}"
                })
            @endif
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new MutationObserver(function(mutationsList, observer) {
                // Look for the element once the DOM has been modified
                const ele = document.querySelector('.topbar > .wrapper > .topbar-wrapper');
                if (ele) {
                    // Insert the theme switcher button
                    ele.insertAdjacentHTML('beforeend', `
                        <div id="theme-switcher">
                            <button type="button" id="theme-switcher-button" class="theme-switcher-button">
                                <span>&#x1F31E;</span>
                            </button>
                        </div>
                    `);

                    // Now that the button is added, add the event listener
                    const themeSwitcherButton = document.getElementById('theme-switcher-button');
                    if (themeSwitcherButton) {
                        themeSwitcherButton.addEventListener('click', () => {
                            toggleTheme();
                        });

                        // Set initial theme based on system preference
                        applyThemeBasedOnSystem();
                    }

                    observer.disconnect();
                }
            });

            // Configure the observer to watch for DOM changes
            observer.observe(document.body, {
                childList: true, // Watch for added or removed child elements
                subtree: true // Watch for changes within the entire document
            });
        });

        // Function to toggle between light and dark themes
        function toggleTheme() {
            const currentTheme = document.body.getAttribute('id');
            const newTheme = currentTheme === 'dark-mode' ? 'light-mode' : 'dark-mode';
            document.body.setAttribute('id', newTheme);

            // Update the button icon and style based on the new theme
            updateButtonStyle(newTheme);
        }

        // Apply the theme based on system preference
        function applyThemeBasedOnSystem() {
            const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = isDarkMode ? 'dark-mode' : 'light-mode';
            document.body.setAttribute('id', theme);

            // Set the button style and icon
            updateButtonStyle(theme);
        }

        // Update button style and icon based on the current theme
        function updateButtonStyle(theme) {
            const themeSwitcherButton = document.getElementById('theme-switcher-button');
            const sunIcon = '&#x1F31E;';
            const moonIcon = '&#x1F319;';
            const newIcon = theme === 'dark-mode' ? moonIcon : sunIcon;

            themeSwitcherButton.querySelector('span').innerHTML = newIcon;

            if (theme === 'dark-mode') {
                themeSwitcherButton.style.backgroundColor = '#333';
                themeSwitcherButton.style.color = '#fff';
            } else {
                themeSwitcherButton.style.backgroundColor = '#fff';
                themeSwitcherButton.style.color = '#000';
            }
        }

        // Listen for system theme changes and update the theme accordingly
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            const newTheme = e.matches ? 'dark-mode' : 'light-mode';
            document.body.setAttribute('id', newTheme);
            updateButtonStyle(newTheme);
        });
    </script>
</body>

</html>
