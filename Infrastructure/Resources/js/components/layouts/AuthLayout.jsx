import { Helmet } from 'react-helmet-async';
import { Link } from '@inertiajs/react';
import { toAbsoluteUrl } from '@/lib/helpers';
import { Card, CardContent } from '@/components/ui/card';

export function AuthLayout({
  children,
  title = 'Vireo - Authentication',
  variant = 'simple', // 'simple', 'classic', 'branded'
  showLogo = true,
  showCard = true,
}) {
  // Simple variant - basic centered layout
  if (variant === 'simple') {
    return (
      <>
        <Helmet>
          <title>{title}</title>
        </Helmet>

        <div className="min-h-screen bg-muted flex flex-col items-center justify-center p-4">
          {showLogo && (
            <div className="mb-8">
              <Link href="/">
                <img
                  src={toAbsoluteUrl('assets/media/app/mini-logo.svg')}
                  className="h-[35px] max-w-none"
                  alt="Logo"
                />
              </Link>
            </div>
          )}

          <div className="w-full max-w-md">
            {showCard ? (
              <Card>
                <CardContent className="p-6">{children}</CardContent>
              </Card>
            ) : (
              children
            )}
          </div>
        </div>
      </>
    );
  }

  // Classic variant - with background image
  if (variant === 'classic') {
    return (
      <>
        <Helmet>
          <title>{title}</title>
        </Helmet>
        <style>
          {`
            .auth-bg-classic {
              background-image: url('${toAbsoluteUrl('assets/media/images/2600x1200/bg-10.png')}');
            }
            .dark .auth-bg-classic {
              background-image: url('${toAbsoluteUrl('assets/media/images/2600x1200/bg-10-dark.png')}');
            }
          `}
        </style>
        <div className="flex flex-col items-center justify-center grow bg-center bg-no-repeat auth-bg-classic min-h-screen">
          {showLogo && (
            <div className="mb-5">
              <Link href="/">
                <img
                  src={toAbsoluteUrl('assets/media/app/mini-logo.svg')}
                  className="h-[35px] max-w-none"
                  alt="Logo"
                />
              </Link>
            </div>
          )}

          <Card className="w-full max-w-[400px] mx-5">
            <CardContent className="p-6">{children}</CardContent>
          </Card>
        </div>
      </>
    );
  }

  // Branded variant - split screen layout
  if (variant === 'branded') {
    return (
      <>
        <Helmet>
          <title>{title}</title>
        </Helmet>
        <style>
          {`
            .auth-bg-branded {
              background-image: url('${toAbsoluteUrl('assets/media/images/2600x1600/1.png')}');
            }
            .dark .auth-bg-branded {
              background-image: url('${toAbsoluteUrl('assets/media/images/2600x1600/1-dark.png')}');
            }
          `}
        </style>
        <div className="grid lg:grid-cols-2 grow min-h-screen">
          <div className="flex justify-center items-center p-8 lg:p-10 order-2 lg:order-1">
            <Card className="w-full max-w-[400px]">
              <CardContent className="p-6">{children}</CardContent>
            </Card>
          </div>

          <div className="lg:rounded-xl lg:border lg:border-border lg:m-5 order-1 lg:order-2 bg-top xxl:bg-center xl:bg-cover bg-no-repeat auth-bg-branded">
            <div className="flex flex-col p-8 lg:p-16 gap-4">
              <Link href="/">
                <img
                  src={toAbsoluteUrl('assets/media/app/mini-logo.svg')}
                  className="h-[28px] max-w-none"
                  alt="Logo"
                />
              </Link>

              <div className="flex flex-col gap-3">
                <h3 className="text-2xl font-semibold text-mono">
                  Secure Dashboard Access
                </h3>
                <div className="text-base font-medium text-secondary-foreground">
                  A robust authentication gateway ensuring
                  <br /> secure&nbsp;
                  <span className="text-mono font-semibold">
                    efficient user access
                  </span>
                  &nbsp;to the Vireo
                  <br /> Dashboard interface.
                </div>
              </div>
            </div>
          </div>
        </div>
      </>
    );
  }

  // Fallback to simple
  return (
    <>
      <Helmet>
        <title>{title}</title>
      </Helmet>
      <div className="min-h-screen bg-muted flex items-center justify-center p-4">
        <div className="w-full max-w-md">{children}</div>
      </div>
    </>
  );
}
