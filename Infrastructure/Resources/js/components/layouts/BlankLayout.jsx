import { Helmet } from 'react-helmet-async';

export function BlankLayout({ children, title = 'Vireo' }) {
  return (
    <>
      <Helmet>
        <title>{title}</title>
      </Helmet>

      <div className="min-h-screen bg-background">
        {children}
      </div>
    </>
  );
}
