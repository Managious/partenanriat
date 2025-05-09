import { useAuthStore } from '@/stores/authStore';
import router from '@/router';

export async function forceLogout() {
  const authStore = useAuthStore();

  try {
    await authStore.logout(); 
  } catch (e) {
    console.error('Forced logout failed');
  }
  console.log('Redirecting to login...');
  alert('Your session has expired. Please log in again.');

  if (router.currentRoute.value.path !== '/login') {
    router.push('/login');
  }
}
