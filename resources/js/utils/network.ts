import os from 'os';

/**
 * Get the local IP address, either preferred, dynamically detected, or fallback.
 * @param preferredIP - An optional preferred IP address to use.
 * @param defaultFallbackIP - A fallback IP address, defaulting to 'localhost'.
 * @returns The determined IP address.
 */
export function getLocalIPAddress(
    preferredIP: string | null = null,
    defaultFallbackIP: string = 'localhost',
): string {
    const networkInterfaces = os.networkInterfaces();

    if (preferredIP) {
        return preferredIP; // Use the preferred IP if provided
    }

    // Detect the first non-internal IPv4 address
    for (const interfaceName in networkInterfaces) {
        for (const networkInterface of networkInterfaces[interfaceName] || []) {
            if (networkInterface.family === 'IPv4' && !networkInterface.internal) {
                return networkInterface.address;
            }
        }
    }

    return defaultFallbackIP;
}
