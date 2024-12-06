import { usePage } from "@inertiajs/vue3";

export function usePermission() {
    const hasUser = () => usePage().props.auth.user
    const hasRole = (name) => usePage().props.auth.user.roles.includes(name)
    const hasPermission = (name) => usePage().props.auth.user.permissions.includes(name)
    return { hasUser, hasRole, hasPermission }
}