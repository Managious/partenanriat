import { useAuthStore } from '@/stores/authStore';

export function usePermissions() {
    const auth = useAuthStore();

    function hasPermission(permission) {
        return auth.permissions.includes(permission);
    }

    return { hasPermission };
}