import { createContext, useContext, useState } from 'react';

const LayoutContext = createContext({
  sidebarOpen: false,
  toggleSidebar: () => {},
  mobileMenuOpen: false,
  toggleMobileMenu: () => {},
});

export function LayoutProvider({ children }) {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const toggleSidebar = () => setSidebarOpen(!sidebarOpen);
  const toggleMobileMenu = () => setMobileMenuOpen(!mobileMenuOpen);

  return (
    <LayoutContext.Provider
      value={{
        sidebarOpen,
        toggleSidebar,
        mobileMenuOpen,
        toggleMobileMenu,
      }}
    >
      {children}
    </LayoutContext.Provider>
  );
}

export function useLayout() {
  const context = useContext(LayoutContext);
  if (!context) {
    throw new Error('useLayout must be used within a LayoutProvider');
  }
  return context;
}
