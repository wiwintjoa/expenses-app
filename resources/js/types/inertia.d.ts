import 'vue';

declare module '@inertiajs/vue3' {
  interface PageProps {
    flash?: {
      success?: string;
      error?: string;
      [key: string]: any;
    };
  }
}
