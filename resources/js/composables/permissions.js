import { usePage } from "@inertiajs/vue3";

export function usePermission() {
    const hasUser = () => usePage().props.auth.user
    const hasRole = (name) => usePage().props.auth.user?.roles.some(obj => obj.name === name)
    const hasPermission = (name) => usePage().props.auth.user?.permission.some(obj => obj.name === name)
    return { hasUser, hasRole, hasPermission };
}