import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { Link } from '@inertiajs/react';
import CheckAbility from '../../Permissions/CheckAbility';

export default function Action({
    auth,
    show,
    edit,
    destroy,
    email_action,
    className,
}) {
    return (
        <div className={className || 'flex justify-center gap-2'}>
            {show && (
                <CheckAbility auth={auth} permission={show?.permission}>
                    <Link
                        href={show.href}
                        {...Object.keys(show).reduce(
                            (acc, key) =>
                                key !== 'href' &&
                                key !== 'icon' &&
                                key !== 'auth' &&
                                key !== 'permission'
                                    ? { ...acc, [key]: destroy[key] }
                                    : acc,
                            {},
                        )}
                    >
                        <FontAwesomeIcon
                            icon={show?.icon || 'far fa-eye'}
                            className={show?.iconClass || 'text-lg text-info'}
                        />
                    </Link>
                </CheckAbility>
            )}

            {edit && (
                <CheckAbility auth={auth} permission={edit?.permission}>
                    <button
                        type="button"
                        onClick={(e) => {
                            if (edit?.onClick) {
                                return edit.onClick(e);
                            }

                            e.preventDefault();
                            window.location.href = edit.href;
                        }}
                        {...Object.keys(edit).reduce(
                            (acc, key) =>
                                key !== 'href' &&
                                key !== 'icon' &&
                                key !== 'auth' &&
                                key !== 'permission'
                                    ? { ...acc, [key]: edit[key] }
                                    : acc,
                            {},
                        )}
                    >
                        <FontAwesomeIcon
                            icon={edit?.icon || 'far fa-pen-to-square'}
                            className={
                                edit?.iconClass || 'text-lg text-success'
                            }
                        />
                    </button>
                </CheckAbility>
            )}

            {destroy && (
                <CheckAbility auth={auth} permission={destroy?.permission}>
                    <button
                        type="button"
                        {...Object.keys(destroy).reduce(
                            (acc, key) =>
                                key !== 'href' &&
                                key !== 'icon' &&
                                key !== 'auth' &&
                                key !== 'permission'
                                    ? { ...acc, [key]: destroy[key] }
                                    : acc,
                            {},
                        )}
                    >
                        <FontAwesomeIcon
                            icon={destroy?.icon || 'far fa-trash-alt'}
                            className={
                                destroy?.iconClass || 'text-lg text-danger'
                            }
                        />
                    </button>
                </CheckAbility>
            )}

            {email_action && (
                <CheckAbility auth={auth} permission={email_action?.permission}>
                    <button
                        type="button"
                        onClick={(e) => {
                            if (email_action?.onClick) {
                                return email_action.onClick(e);
                            }

                            e.preventDefault();
                            window.location.href = email_action.href;
                        }}
                        {...Object.keys(email_action).reduce(
                            (acc, key) =>
                                key !== 'href' &&
                                key !== 'icon' &&
                                key !== 'auth' &&
                                key !== 'permission'
                                    ? { ...acc, [key]: email_action[key] }
                                    : acc,
                            {},
                        )}
                    >
                        <FontAwesomeIcon
                            icon={email_action?.icon || 'far fa-envelope'}
                            className={
                                email_action?.iconClass ||
                                'text-lg text-warning'
                            }
                        />
                    </button>
                </CheckAbility>
            )}
        </div>
    );
}
