export default function CheckAbility({ auth, permission, children }) {
    if (!permission || permission === '' || !auth?.permissions.includes(permission)) return null;

    return children;
}

