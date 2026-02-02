import './styles/globals.css';
import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { ThemeProvider } from 'next-themes';
import { HelmetProvider } from 'react-helmet-async';
import { Toaster } from '@/components/ui/sonner';

createInertiaApp({
  resolve: async (name) => {
    const pages = import.meta.glob('./views/**/*.jsx');

    if (!pages[`./views/${name}.jsx`]) {
      throw new Error(`Page not found: ${name}`);
    }

    const page = await pages[`./views/${name}.jsx`]();
    const PageComponent = page.default;

    // Handle layout wrapping
    const Layout = PageComponent.layout;
    if (Layout) {
      return (props) => (
        <Layout>
          <PageComponent {...props} />
        </Layout>
      );
    }

    return PageComponent;
  },
  setup({ el, App, props }) {
    createRoot(el).render(
      <StrictMode>
        <HelmetProvider>
          <ThemeProvider
            attribute="class"
            defaultTheme="light"
            storageKey="vite-theme"
            enableSystem
            disableTransitionOnChange
            enableColorScheme
          >
            <Toaster />
            <App {...props} />
          </ThemeProvider>
        </HelmetProvider>
      </StrictMode>
    );
  },
  progress: {
    color: '#4B5563',
    showSpinner: true,
  },
});
