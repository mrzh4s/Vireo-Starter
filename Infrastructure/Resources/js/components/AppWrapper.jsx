import { FlashMessages } from './FlashMessages';

export function AppWrapper({ children }) {
  return (
    <>
      <FlashMessages />
      {children}
    </>
  );
}
