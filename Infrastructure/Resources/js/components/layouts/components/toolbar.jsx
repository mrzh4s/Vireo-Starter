import { Fragment } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { MENU_SIDEBAR_COMPACT } from '@/config/app.config';
import { cn } from '@/lib/utils';
import { useMenu } from '@/hooks/use-menu';

function Toolbar({ children }) {
  return (
    <div className="flex items-center justify-between flex-wrap gap-3 pb-5">
      {children}
    </div>
  );
}

function ToolbarActions({ children }) {
  return (
    <div className="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      {children}
    </div>
  );
}

function ToolbarBreadcrumbs() {
  const { url } = usePage();
  const { getBreadcrumb, isActive } = useMenu(url);
  const items = getBreadcrumb(MENU_SIDEBAR_COMPACT);

  if (items.length === 0) {
    return null;
  }

  return (
    <div className="flex items-center gap-1 text-sm">
      {items.map((item, index) => {
        const isLast = index === items.length - 1;
        const active = item.path ? isActive(item.path) : false;

        return (
          <Fragment key={index}>
            {item.path ? (
              <Link
                href={item.path}
                className={cn(
                  'flex items-center gap-1',
                  active
                    ? 'text-mono'
                    : 'text-secondary-foreground hover:text-primary',
                )}
              >
                {item.title}
              </Link>
            ) : (
              <span
                className={cn(
                  isLast ? 'text-mono' : 'text-secondary-foreground',
                )}
              >
                {item.title}
              </span>
            )}
            {!isLast && <span className="text-muted-foreground">/</span>}
          </Fragment>
        );
      })}
    </div>
  );
}

const ToolbarHeading = ({ title = '' }) => {
  const { url } = usePage();
  const { getCurrentItem } = useMenu(url);
  const item = getCurrentItem(MENU_SIDEBAR_COMPACT);

  return (
    <div className="flex flex-col md:flex-row md:items-center flex-wrap gap-1 lg:gap-5">
      <h1 className="font-medium text-lg text-mono">{title || item?.title}</h1>
      <ToolbarBreadcrumbs />
    </div>
  );
};

export { Toolbar, ToolbarActions, ToolbarBreadcrumbs, ToolbarHeading };
