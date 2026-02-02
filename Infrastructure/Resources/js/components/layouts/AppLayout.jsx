import { Helmet } from 'react-helmet-async';
import { LayoutProvider } from './context/layout-provider';
import { Main } from './components/main';

export function AppLayout({ children }) {
  return (
    <>
      <Helmet>
        <title>Vireo - The React Admin Template</title>
      </Helmet>

      <LayoutProvider>
        <Main>{children}</Main>
      </LayoutProvider>
    </>
  );
}
